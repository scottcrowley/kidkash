<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AvatarsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_must_be_authenticated_to_add_an_avatar_to_a_kids_profile()
    {
        $kid = createStates('App\User', 'kid');

        $this->json('POST', route('api.users.avatar.add', $kid->id), [])
            ->assertStatus(401);
    }

    /** @test */
    public function an_authenticated_kid_must_be_authorized_to_add_an_avatar_to_a_user()
    {
        $kid = createStates('App\User', 'kid');

        $this->signIn(createStates('App\User', 'kid'));

        $this->json('POST', route('api.users.avatar.add', $kid->id), [])
            ->assertStatus(403);
    }

    /** @test */
    public function an_authenticated_parent_may_add_an_avatar_to_a_kids_profile()
    {
        $this->signIn();
        config(['kidkash.parents' => [auth()->user()->email]]);

        $kid = createStates('App\User', 'kid');

        Storage::fake('public');

        $this->json('POST', route('api.users.avatar.add', $kid->id), [
            'avatar' => $file = UploadedFile::fake()->image('avatar.jpg')
        ]);

        $this->assertEquals(asset('avatars/'.$file->hashName()), asset($kid->fresh()->avatar_path));

        Storage::disk('public')->assertExists('avatars/'.$file->hashName());
    }

    /** @test */
    public function an_authorized_kid_may_add_an_avatar_to_their_own_profile()
    {
        $this->signIn($kid = createStates('App\User', 'kid'));

        Storage::fake('public');

        $this->json('POST', route('api.users.avatar.add', $kid->id), [
            'avatar' => $file = UploadedFile::fake()->image('avatar.jpg')
        ]);

        $this->assertEquals(asset('avatars/'.$file->hashName()), asset($kid->fresh()->avatar_path));

        Storage::disk('public')->assertExists('avatars/'.$file->hashName());
    }

    /** @test */
    public function a_valid_avatar_must_be_provided()
    {
        $this->signIn();
        config(['kidkash.parents' => [auth()->user()->email]]);

        $kid = createStates('App\User', 'kid');

        $this->json('POST', route('api.users.avatar.add', $kid->id), [
            'avatar' => 'not-an-image'
        ])->assertStatus(422);
    }

    /** @test */
    public function a_user_must_be_an_authenticated_to_delete_a_kids_avatar()
    {
        $kid = createStates('App\User', 'kid');

        $this->json('DELETE', route('api.users.avatar.delete', $kid->id))
            ->assertStatus(401);
    }

    /** @test */
    public function an_authenticated_kid_must_be_authorized_to_delete_an_avatar_from_another_kid()
    {
        $kid = createStates('App\User', 'kid');

        $this->signIn(createStates('App\User', 'kid'));

        $this->json('DELETE', route('api.users.avatar.delete', $kid->id))
            ->assertStatus(403);
    }

    /** @test */
    public function an_authenticated_parent_may_delete_an_avatar_from_a_kids_profile()
    {
        Storage::fake('public');

        $this->signIn();
        config(['kidkash.parents' => [auth()->user()->email]]);

        $kid = factory('App\User')->states('kid', 'withAvatar')->create();

        Storage::disk('public')->assertExists($kid->avatar_path);

        $this->json('DELETE', route('api.users.avatar.delete', $kid->id))
            ->assertStatus(204);

        Storage::disk('public')->assertMissing($kid->avatar_path);

        $this->assertEmpty($kid->fresh()->avatar_path);
    }

    /** @test */
    public function an_authenticated_authorized_kid_may_delete_their_own_avatar()
    {
        Storage::fake('public');

        $this->signIn(
            $kid = factory('App\User')->states('kid', 'withAvatar')->create()
        );

        Storage::disk('public')->assertExists($kid->avatar_path);

        $this->json('DELETE', route('api.users.avatar.delete', $kid->id))
            ->assertStatus(204);

        Storage::disk('public')->assertMissing($kid->avatar_path);

        $this->assertEmpty($kid->fresh()->avatar_path);
    }
}
