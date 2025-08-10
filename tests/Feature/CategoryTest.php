<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryTest extends TestCase{
            use RefreshDatabase;
    public function test_can_list_categories(){
        Category::factory()->count(3)->create();

        $response = $this->getJson('/api/categories', ['Accept' => 'application/json']);
        $response->assertStatus(200);
    }
}
