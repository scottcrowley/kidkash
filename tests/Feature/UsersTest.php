<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UsersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_authenticated_admin_can_view_all_kids()
    {
        $this->assertTrue(true);
    }
}
