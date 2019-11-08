<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VendorTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_requires_a_name_when_adding_new()
    {
        $this->signIn();
        config(['kidkash.parents' => [auth()->user()->email]]);

        $vendor = makeRaw('App\Vendor', ['name' => '']);

        $this->post(route('vendors.store'), $vendor)
            ->assertSessionHasErrors('name');
    }

    /** @test */
    public function it_requires_a_name_when_updating_existing()
    {
        $this->signIn();
        config(['kidkash.parents' => [auth()->user()->email]]);

        $vendor = create('App\Vendor');

        $this->patch(route('vendors.update', $vendor->slug), ['name' => ''])
            ->assertSessionHasErrors('name');
    }

    /** @test */
    public function it_requires_a_unique_name_when_adding_new()
    {
        $this->signIn();
        config(['kidkash.parents' => [auth()->user()->email]]);

        create('App\Vendor', ['name' => 'New Vendor']);

        $vendor = makeRaw('App\Vendor', ['name' => 'New Vendor']);

        $this->post(route('vendors.store'), $vendor)
            ->assertSessionHasErrors('name');
    }

    /** @test */
    public function it_requires_a_unique_name_when_updating_existing()
    {
        $this->signIn();
        config(['kidkash.parents' => [auth()->user()->email]]);

        create('App\Vendor', ['name' => 'New Vendor']);

        $vendor = createRaw('App\Vendor');
        $vendor['name'] = 'New Vendor';

        $this->patch(route('vendors.update', $vendor['slug']), $vendor)
            ->assertSessionHasErrors('name');
    }

    /** @test */
    public function it_can_access_all_of_its_transactions()
    {
        $vendor = create('App\Vendor');
        create('App\Transaction', ['vendor_id' => $vendor->id], 2);

        $this->assertCount(2, $vendor->transactions);
    }

    /** @test */
    public function it_can_access_all_kids_with_related_transactions()
    {
        $vendor = create('App\Vendor');
        create('App\Transaction', ['vendor_id' => $vendor->id], 4);

        $this->assertCount(4, $vendor->kids);

        $this->assertInstanceOf('App\User', $vendor->kids[0]);
    }
}
