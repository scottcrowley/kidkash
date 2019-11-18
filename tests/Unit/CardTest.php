<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CardTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_access_details_about_its_owner()
    {
        $owner = create('App\User');
        $card = create('App\Card', ['owner_id' => $owner->id]);

        $this->assertEquals($owner->name, $card->owner->name);
    }

    /** @test */
    public function it_can_access_details_about_its_vendor()
    {
        $vendor = create('App\Vendor');
        $card = create('App\Card', ['vendor_id' => $vendor->id]);

        $this->assertEquals($vendor->name, $card->vendor->name);
    }
}
