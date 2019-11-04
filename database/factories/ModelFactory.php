<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use App\Vendor;
use App\Transaction;
use Illuminate\Support\Str;
use Faker\Generator as Faker;
use Illuminate\Http\UploadedFile;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token' => Str::random(10),
        'avatar_path' => '',
        'is_kid' => false
    ];
});

$factory->state(User::class, 'kid', [
    'is_kid' => true
]);

$factory->state(User::class, 'withAvatar', function () {
    $file = UploadedFile::fake()->image('avatar.jpg');

    $file->store('avatars', 'public');

    return [
        'avatar_path' => 'avatars/'.$file->hashName(),
    ];
});

$factory->define(Vendor::class, function (Faker $faker) {
    return [
        'name' => $faker->company,
        'url' => $faker->url,
    ];
});

$factory->define(Transaction::class, function (Faker $faker) {
    return [
        'kid_id' => factory('App\User')->states('kid'),
        'vendor_id' => factory('App\Vendor'),
        'amount' => 20,
        'description' => $faker->text(),
    ];
});
