<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Event::fake();
        Queue::fake();
    }

    public function test_user_can_create_order_successfully()
    {
        $product = $this->createProductWithStock(50);

        $orderData = [
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 2,
                    'item_size' => 'M'
                ]
            ]
        ];

        $response = $this->actingAsUser()
                        ->postJson('/api/createOrder', $orderData);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'payload' => [
                        'id',
                        'user_id',
                        'status',
                        'total_price',
                        'items' => [
                            '*' => [
                                'id',
                                'product_id',
                                'quantity',
                                'price',
                                'item_size'
                            ]
                        ]
                    ],
                    'status'
                ]);

        $this->assertDatabaseHas('orders', [
            'user_id' => $this->user->id,
            'status' => 'Pending'
        ]);

        $this->assertDatabaseHas('order_items', [
            'product_id' => $product->id,
            'quantity' => 2,
            'item_size' => 'M'
        ]);
    }

    public function test_user_cannot_create_order_with_insufficient_stock()
    {
        $product = $this->createProductWithStock(1);

        $orderData = [
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 5,
                    'item_size' => 'M'
                ]
            ]
        ];

        $response = $this->actingAsUser()
                        ->postJson('/api/createOrder', $orderData);

        $response->assertStatus(500);
    }

    public function test_user_cannot_create_order_without_items()
    {
        $orderData = [
            'items' => []
        ];

        $response = $this->actingAsUser()
                        ->postJson('/api/createOrder', $orderData);

        $response->assertStatus(500);
    }

    public function test_user_cannot_create_order_with_invalid_product()
    {
        $orderData = [
            'items' => [
                [
                    'product_id' => 99999,
                    'quantity' => 1,
                    'item_size' => 'M'
                ]
            ]
        ];

        $response = $this->actingAsUser()
                        ->postJson('/api/createOrder', $orderData);

        $response->assertStatus(422);
    }

    public function test_user_can_view_their_orders()
    {
        $order1 = $this->createOrderWithItems($this->user);
        $order2 = $this->createOrderWithItems($this->user);

        // Create order for different user
        $otherUser = User::factory()->create();
        $otherOrder = $this->createOrderWithItems($otherUser);

        $response = $this->actingAsUser()
                        ->getJson('/api/myOrders');

        $response->assertStatus(201)
                ->assertJsonCount(2, 'payload');

        $orderIds = collect($response->json('payload'))->pluck('id')->toArray();
        $this->assertContains($order1->id, $orderIds);
        $this->assertContains($order2->id, $orderIds);
        $this->assertNotContains($otherOrder->id, $orderIds);
    }

    public function test_admin_can_view_all_orders()
    {
        $order1 = $this->createOrderWithItems($this->user);
        $order2 = $this->createOrderWithItems($this->admin);

        $response = $this->actingAsAdmin()
                        ->getJson('/api/orders');

        $response->assertStatus(201)
                ->assertJsonCount(2, 'payload.data');
    }

    public function test_admin_can_view_specific_order()
    {
        $order = $this->createOrderWithItems($this->user);

        $response = $this->actingAsAdmin()
                        ->getJson("/api/order/{$order->id}");

        $response->assertStatus(200)
                ->assertJson([
                    'payload' => [
                        'id' => $order->id,
                        'user_id' => $this->user->id
                    ]
                ]);
    }

    public function test_admin_can_update_order_status()
    {
        $order = $this->createOrderWithItems($this->user, ['status' => 'Pending']);

        $response = $this->actingAsAdmin()
                        ->postJson("/api/order/{$order->id}/status", [
                            'status' => 'Paid'
                        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'Paid'
        ]);

        $this->assertDatabaseHas('audit_logs', [
            'order_id' => $order->id,
            'user_id' => $this->admin->id,
            'from_status' => 'Pending',
            'to_status' => 'Paid'
        ]);
    }

    public function test_admin_cannot_update_order_status_with_invalid_status()
    {
        $order = $this->createOrderWithItems($this->user);

        $response = $this->actingAsAdmin()
                        ->postJson("/api/order/{$order->id}/status", [
                            'status' => 'InvalidStatus'
                        ]);

        $response->assertStatus(422);
    }

    public function test_regular_user_cannot_update_order_status()
    {
        $order = $this->createOrderWithItems($this->user);

        $response = $this->actingAsUser()
                        ->postJson("/api/order/{$order->id}/status", [
                            'status' => 'Paid'
                        ]);

        $response->assertStatus(422);
    }

    public function test_user_can_delete_their_own_order()
    {
        $order = $this->createOrderWithItems($this->user);

        $response = $this->actingAsUser()
                        ->deleteJson("/api/order/{$order->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('orders', ['id' => $order->id]);
        $this->assertDatabaseMissing('order_items', ['order_id' => $order->id]);
    }

    public function test_user_cannot_delete_other_users_order()
    {
        $otherUser = User::factory()->create();
        $order = $this->createOrderWithItems($otherUser);

        $response = $this->actingAsUser()
                        ->deleteJson("/api/order/{$order->id}");

        $response->assertStatus(404);
    }

    public function test_admin_can_view_todays_revenue()
    {
        // Create orders for today
        $order1 = $this->createOrderWithItems($this->user, ['total_price' => 100]);
        $order2 = $this->createOrderWithItems($this->admin, ['total_price' => 200]);

        $response = $this->actingAsAdmin()
                        ->getJson('/api/orders/revenue/today');

        $response->assertStatus(200);

        // Note: This test might need adjustment based on how OrderPerHour is calculated
        $this->assertIsNumeric($response->json('payload'));
    }

    public function test_unauthenticated_user_cannot_access_order_endpoints()
    {
        $order = $this->createOrderWithItems($this->user);

        $this->postJson('/api/createOrder')->assertStatus(401);
        $this->getJson('/api/myOrders')->assertStatus(401);
        $this->getJson("/api/order/{$order->id}")->assertStatus(401);
        $this->postJson("/api/order/{$order->id}/status")->assertStatus(401);
        $this->deleteJson("/api/order/{$order->id}")->assertStatus(401);
    }

    public function test_order_creation_dispatches_events()
    {
        $product = $this->createProductWithStock(50);

        $orderData = [
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 2,
                    'item_size' => 'M'
                ]
            ]
        ];

        $this->actingAsUser()
            ->postJson('/api/createOrder', $orderData);

        Event::assertDispatched(\App\Events\OrderPlaced::class);
    }

    public function test_order_status_update_to_shipped_dispatches_event()
    {
        $order = $this->createOrderWithItems($this->user, ['status' => 'Packed']);

        $this->actingAsAdmin()
            ->postJson("/api/order/{$order->id}/status", [
                'status' => 'Shipped'
            ]);

        Event::assertDispatched(\App\Events\OrderStatusShipped::class);
    }
}
