<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\CustomerController
 */
final class CustomerControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_displays_view(): void
    {
        $customers = Customer::factory()->count(3)->create();

        $response = $this->get(route('customer.index'));

        $response->assertOk();
        $response->assertViewIs('customer.index');
        $response->assertViewHas('customers');
    }


    #[Test]
    public function create_displays_view(): void
    {
        $response = $this->get(route('customer.create'));

        $response->assertOk();
        $response->assertViewIs('customer.create');
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\CustomerController::class,
            'store',
            \App\Http\Requests\CustomerStoreRequest::class
        );
    }

    #[Test]
    public function store_saves_and_redirects(): void
    {
        $name = $this->faker->name();
        $surname = $this->faker->word();
        $email = $this->faker->safeEmail();

        $response = $this->post(route('customer.store'), [
            'name' => $name,
            'surname' => $surname,
            'email' => $email,
        ]);

        $customers = Customer::query()
            ->where('name', $name)
            ->where('surname', $surname)
            ->where('email', $email)
            ->get();
        $this->assertCount(1, $customers);
        $customer = $customers->first();

        $response->assertRedirect(route('customer.index'));
        $response->assertSessionHas('customer.id', $customer->id);
    }


    #[Test]
    public function show_displays_view(): void
    {
        $customer = Customer::factory()->create();

        $response = $this->get(route('customer.show', $customer));

        $response->assertOk();
        $response->assertViewIs('customer.show');
        $response->assertViewHas('customer');
    }


    #[Test]
    public function edit_displays_view(): void
    {
        $customer = Customer::factory()->create();

        $response = $this->get(route('customer.edit', $customer));

        $response->assertOk();
        $response->assertViewIs('customer.edit');
        $response->assertViewHas('customer');
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\CustomerController::class,
            'update',
            \App\Http\Requests\CustomerUpdateRequest::class
        );
    }

    #[Test]
    public function update_redirects(): void
    {
        $customer = Customer::factory()->create();
        $name = $this->faker->name();
        $surname = $this->faker->word();
        $email = $this->faker->safeEmail();

        $response = $this->put(route('customer.update', $customer), [
            'name' => $name,
            'surname' => $surname,
            'email' => $email,
        ]);

        $customer->refresh();

        $response->assertRedirect(route('customer.index'));
        $response->assertSessionHas('customer.id', $customer->id);

        $this->assertEquals($name, $customer->name);
        $this->assertEquals($surname, $customer->surname);
        $this->assertEquals($email, $customer->email);
    }


    #[Test]
    public function destroy_deletes_and_redirects(): void
    {
        $customer = Customer::factory()->create();

        $response = $this->delete(route('customer.destroy', $customer));

        $response->assertRedirect(route('customer.index'));

        $this->assertSoftDeleted($customer);
    }
}
