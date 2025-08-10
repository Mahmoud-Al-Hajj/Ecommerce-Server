<?php


namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class OrderTest extends TestCase{
            use RefreshDatabase;

    public function test_user_can_see_order(){
        $user = User::factory()->create(['role'=>'admin']);
        $token =JWTAuth::fromUser($user);
        $user->token = $token;
        $response = $this->getJson('/api/orders', ['Authorization' => 'Bearer ' . $token]);
        $response->assertStatus(201);
    }

}
