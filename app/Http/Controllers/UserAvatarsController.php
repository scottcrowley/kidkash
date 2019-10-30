<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class UserAvatarsController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $user)
    {
        $data = request()->validate([
            'avatar' => ['required', 'image']
        ]);

        Storage::disk('public')->delete($user->avatar_path);

        // Image::make(request()->file('avatar'))->resize(600)->save(request()->file('avatar'));

        $user->update([
            'avatar_path' => request()->file('avatar')->store('avatars', 'public')
        ]);

        return response()->json(['avatar_path' => $user->avatar_path]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        Storage::disk('public')->delete($user->avatar_path);

        $user->update([
            'avatar_path' => null
        ]);

        return response([], 204);
    }
}
