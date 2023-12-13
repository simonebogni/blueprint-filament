<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\LineItem;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\LineItemController
 */
final class LineItemControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_displays_view(): void
    {
        $lineItems = LineItem::factory()->count(3)->create();

        $response = $this->get(route('line-item.index'));

        $response->assertOk();
        $response->assertViewIs('lineItem.index');
        $response->assertViewHas('lineItems');
    }


    #[Test]
    public function create_displays_view(): void
    {
        $response = $this->get(route('line-item.create'));

        $response->assertOk();
        $response->assertViewIs('lineItem.create');
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\LineItemController::class,
            'store',
            \App\Http\Requests\LineItemStoreRequest::class
        );
    }

    #[Test]
    public function store_saves_and_redirects(): void
    {
        $productCode = $this->faker->word();
        $quantity = $this->faker->numberBetween(-10000, 10000);
        $pricePerUnit = $this->faker->randomFloat(/** decimal_attributes **/);
        $order = Order::factory()->create();

        $response = $this->post(route('line-item.store'), [
            'productCode' => $productCode,
            'quantity' => $quantity,
            'pricePerUnit' => $pricePerUnit,
            'order_id' => $order->id,
        ]);

        $lineItems = LineItem::query()
            ->where('productCode', $productCode)
            ->where('quantity', $quantity)
            ->where('pricePerUnit', $pricePerUnit)
            ->where('order_id', $order->id)
            ->get();
        $this->assertCount(1, $lineItems);
        $lineItem = $lineItems->first();

        $response->assertRedirect(route('line-item.index'));
        $response->assertSessionHas('lineItem.id', $lineItem->id);
    }


    #[Test]
    public function show_displays_view(): void
    {
        $lineItem = LineItem::factory()->create();

        $response = $this->get(route('line-item.show', $lineItem));

        $response->assertOk();
        $response->assertViewIs('lineItem.show');
        $response->assertViewHas('lineItem');
    }


    #[Test]
    public function edit_displays_view(): void
    {
        $lineItem = LineItem::factory()->create();

        $response = $this->get(route('line-item.edit', $lineItem));

        $response->assertOk();
        $response->assertViewIs('lineItem.edit');
        $response->assertViewHas('lineItem');
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\LineItemController::class,
            'update',
            \App\Http\Requests\LineItemUpdateRequest::class
        );
    }

    #[Test]
    public function update_redirects(): void
    {
        $lineItem = LineItem::factory()->create();
        $productCode = $this->faker->word();
        $quantity = $this->faker->numberBetween(-10000, 10000);
        $pricePerUnit = $this->faker->randomFloat(/** decimal_attributes **/);
        $order = Order::factory()->create();

        $response = $this->put(route('line-item.update', $lineItem), [
            'productCode' => $productCode,
            'quantity' => $quantity,
            'pricePerUnit' => $pricePerUnit,
            'order_id' => $order->id,
        ]);

        $lineItem->refresh();

        $response->assertRedirect(route('line-item.index'));
        $response->assertSessionHas('lineItem.id', $lineItem->id);

        $this->assertEquals($productCode, $lineItem->productCode);
        $this->assertEquals($quantity, $lineItem->quantity);
        $this->assertEquals($pricePerUnit, $lineItem->pricePerUnit);
        $this->assertEquals($order->id, $lineItem->order_id);
    }


    #[Test]
    public function destroy_deletes_and_redirects(): void
    {
        $lineItem = LineItem::factory()->create();

        $response = $this->delete(route('line-item.destroy', $lineItem));

        $response->assertRedirect(route('line-item.index'));

        $this->assertModelMissing($lineItem);
    }
}
