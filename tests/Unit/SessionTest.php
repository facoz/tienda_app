<?php

namespace Tests\Unit;

// use PHPUnit\Framework\TestCase;

use App\Models\Order;
use Tests\TestCase;
class SessionTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    /**@test */
    public function test_create_user_data()
    {
        // $this->withoutExceptionHandling();
        // $a = Order::factory()->create();
        $response  = $this->post('orders/create',[
            'customer_name' => 'Test Name2',
            'customer_email' => 'TestEmail@hotmail.com',
            'customer_phone' => '55556678',
        ]);
        $id = Order::latest('id')->first()->id;
        $response->assertRedirect("orders/view/$id");
    }

    public function test_view_order_all()
    {
        $response  = $this->get('orders/all');
        $response->assertOk();
    }

    public function test_create_session()
    {
        $id = Order::latest('id')->first()->id;
        $response = $this->put("orders/process/$id");
        $response->assertStatus(200);
    }
}
