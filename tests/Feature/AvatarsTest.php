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
    public function a_user_must_be_authenticated_to_add_an_avatar()
    {
        $user = create('App\User');

        $this->json('POST', route('api.users.avatar.add', $user->slug), [])
            ->assertStatus(401);
    }

    /** @test */
    public function an_authenticated_user_may_not_add_an_avatar_to_another_user()
    {
        $user = create('App\User');

        $this->signIn();

        $this->json('POST', route('api.users.avatar.add', $user->slug), [])
            ->assertStatus(403);
    }

    /** @test */
    public function an_authenticated_authorized_parent_may_add_an_avatar_to_a_users_profile()
    {
        $this->signIn();
        config(['kidkash.parents' => [auth()->user()->email]]);

        $user = create('App\User');

        Storage::fake('public');

        $this->json('POST', route('api.users.avatar.add', $user->slug), [
            'avatar' => $file = UploadedFile::fake()->image('avatar.jpg')
        ]);

        $this->assertEquals(asset('avatars/'.$file->hashName()), asset($user->fresh()->avatar_path));

        Storage::disk('public')->assertExists('avatars/'.$file->hashName());
    }

    /** @test */
    public function an_authenticated_user_may_add_an_avatar_to_their_own_profile()
    {
        $this->signIn($user = create('App\User'));

        Storage::fake('public');

        $this->json('POST', route('api.users.avatar.add', $user->slug), [
            'avatar' => $file = UploadedFile::fake()->image('avatar.jpg')
        ]);

        $this->assertEquals(asset('avatars/'.$file->hashName()), asset($user->fresh()->avatar_path));

        Storage::disk('public')->assertExists('avatars/'.$file->hashName());
    }

    /** @test */
    public function a_valid_avatar_must_be_provided()
    {
        $this->signIn();
        config(['kidkash.parents' => [auth()->user()->email]]);

        $user = create('App\User');

        $this->json('POST', route('api.users.avatar.add', $user->slug), [
            'avatar' => 'not-an-image'
        ])->assertStatus(422);
    }

    /** @test */
    public function a_user_must_be_an_authenticated_to_delete_an_avatar()
    {
        $user = create('App\User');

        $this->json('DELETE', route('api.users.avatar.delete', $user->slug))
            ->assertStatus(401);
    }

    /** @test */
    public function an_authenticated_user_may_not_delete_an_avatar_from_another_user()
    {
        $user = create('App\User');

        $this->signIn();

        $this->json('DELETE', route('api.users.avatar.delete', $user->slug))
            ->assertStatus(403);
    }

    /** @test */
    public function an_authenticated_authorized_parent_may_delete_an_avatar_from_another_users_profile()
    {
        Storage::fake('public');

        $this->signIn();
        config(['kidkash.parents' => [auth()->user()->email]]);

        $user = factory('App\User')->states('withAvatar')->create();

        Storage::disk('public')->assertExists($user->avatar_path);

        $this->json('DELETE', route('api.users.avatar.delete', $user->slug))
            ->assertStatus(204);

        Storage::disk('public')->assertMissing($user->avatar_path);

        $this->assertEmpty($user->fresh()->avatar_path);
    }

    /** @test */
    public function an_authenticated_user_may_delete_their_own_avatar()
    {
        Storage::fake('public');

        $this->signIn(
            $user = factory('App\User')->states('withAvatar')->create()
        );

        Storage::disk('public')->assertExists($user->avatar_path);

        $this->json('DELETE', route('api.users.avatar.delete', $user->slug))
            ->assertStatus(204);

        Storage::disk('public')->assertMissing($user->avatar_path);

        $this->assertEmpty($user->fresh()->avatar_path);
    }
}
