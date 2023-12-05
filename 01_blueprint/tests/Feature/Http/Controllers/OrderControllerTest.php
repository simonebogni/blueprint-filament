<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\OrderController
 */
final class OrderControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_displays_view(): void
    {
        $orders = Order::factory()->count(3)->create();

        $response = $this->get(route('order.index'));

        $response->assertOk();
        $response->assertViewIs('order.index');
        $response->assertViewHas('orders');
    }


    #[Test]
    public function create_displays_view(): void
    {
        $response = $this->get(route('order.create'));

        $response->assertOk();
        $response->assertViewIs('order.create');
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\OrderController::class,
            'store',
            \App\Http\Requests\OrderStoreRequest::class
        );
    }

    #[Test]
    public function store_saves_and_redirects(): void
    {
        $orderNumber = $this->faker->numberBetween(-10000, 10000);
        $deliveryAddress = $this->faker->word();
        $customer = Customer::factory()->create();

        $response = $this->post(route('order.store'), [
            'orderNumber' => $orderNumber,
            'deliveryAddress' => $deliveryAddress,
            'customer_id' => $customer->id,
        ]);

        $orders = Order::query()
            ->where('orderNumber', $orderNumber)
            ->where('deliveryAddress', $deliveryAddress)
            ->where('customer_id', $customer->id)
            ->get();
        $this->assertCount(1, $orders);
        $order = $orders->first();

        $response->assertRedirect(route('order.index'));
        $response->assertSessionHas('order.id', $order->id);
    }


    #[Test]
    public function show_displays_view(): void
    {
        $order = Order::factory()->create();

        $response = $this->get(route('order.show', $order));

        $response->assertOk();
        $response->assertViewIs('order.show');
        $response->assertViewHas('order');
    }


    #[Test]
    public function edit_displays_view(): void
    {
        $order = Order::factory()->create();

        $response = $this->get(route('order.edit', $order));

        $response->assertOk();
        $response->assertViewIs('order.edit');
        $response->assertViewHas('order');
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\OrderController::class,
            'update',
            \App\Http\Requests\OrderUpdateRequest::class
        );
    }

    #[Test]
    public function update_redirects(): void
    {
        $order = Order::factory()->create();
        $orderNumber = $this->faker->numberBetween(-10000, 10000);
        $deliveryAddress = $this->faker->word();
        $customer = Customer::factory()->create();

        $response = $this->put(route('order.update', $order), [
            'orderNumber' => $orderNumber,
            'deliveryAddress' => $deliveryAddress,
            'customer_id' => $customer->id,
        ]);

        $order->refresh();

        $response->assertRedirect(route('order.index'));
        $response->assertSessionHas('order.id', $order->id);

        $this->assertEquals($orderNumber, $order->orderNumber);
        $this->assertEquals($deliveryAddress, $order->deliveryAddress);
        $this->assertEquals($customer->id, $order->customer_id);
    }


    #[Test]
    public function destroy_deletes_and_redirects(): void
    {
        $order = Order::factory()->create();

        $response = $this->delete(route('order.destroy', $order));

        $response->assertRedirect(route('order.index'));

        $this->assertModelMissing($order);
    }
}
