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

        $vendor = makeRaw('App\Vendor', ['name' => '']);

        $this->post(route('vendors.store'), $vendor)
            ->assertSessionHasErrors('name');
    }

    /** @test */
    public function it_requires_a_name_when_updating_existing()
    {
        $this->signIn();

        $vendor = create('App\Vendor');

        $vendor->name = '';

        $this->patch(route('vendors.update', $vendor->id), $vendor->toArray())
            ->assertSessionHasErrors('name');
    }

    /** @test */
    public function it_requires_a_unique_name_when_adding_new()
    {
        $this->signIn();

        create('App\Vendor', ['name' => 'New Vendor']);

        $vendor = makeRaw('App\Vendor', ['name' => 'New Vendor']);

        $this->post(route('vendors.store'), $vendor)
            ->assertSessionHasErrors('name');
    }

    /** @test */
    public function it_requires_a_unique_name_when_updating_existing()
    {
        $this->signIn();

        create('App\Vendor', ['name' => 'New Vendor']);

        $vendor = createRaw('App\Vendor');
        $vendor['name'] = 'New Vendor';

        $this->patch(route('vendors.update', $vendor['id']), $vendor)
            ->assertSessionHasErrors('name');
    }

    /** @test */
    public function it_requires_a_url_when_adding_new()
    {
        $this->signIn();

        $vendor = makeRaw('App\Vendor', ['url' => '']);

        $this->post(route('vendors.store'), $vendor)
            ->assertSessionHasErrors('url');
    }

    /** @test */
    public function it_requires_a_url_when_updating_existing()
    {
        $this->signIn();

        $vendor = create('App\Vendor');

        $vendor->url = '';

        $this->patch(route('vendors.update', $vendor->id), $vendor->toArray())
            ->assertSessionHasErrors('url');
    }
}
