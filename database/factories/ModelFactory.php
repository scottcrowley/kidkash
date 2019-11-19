<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Card;
use App\User;
use App\Vendor;
use App\Transaction;
use App\CardTransaction;
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
    $name = $faker->name;
    return [
        'name' => $name,
        'slug' => Str::slug($name),
        'email' => $faker->unique()->safeEmail,
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token' => Str::random(10),
        'avatar_path' => '',
    ];
});

$factory->state(User::class, 'withAvatar', function () {
    $file = UploadedFile::fake()->image('avatar.jpg');

    $file->store('avatars', 'public');

    return [
        'avatar_path' => 'avatars/'.$file->hashName(),
    ];
});

$factory->define(Vendor::class, function (Faker $faker) {
    $name = $faker->company;
    return [
        'name' => $name,
        'slug' => Str::slug($name),
        'url' => $faker->url,
    ];
});

$factory->define(Transaction::class, function (Faker $faker) {
    return [
        'owner_id' => factory('App\User'),
        'vendor_id' => factory('App\Vendor'),
        'amount' => 20,
        'description' => $faker->text(),
    ];
});

$factory->define(Card::class, function (Faker $faker) {
    return [
        'owner_id' => factory('App\User'),
        'vendor_id' => factory('App\Vendor'),
        'number' => $faker->unique()->text(20),
        'pin' => $faker->text(5),
    ];
});

$factory->define(CardTransaction::class, function (Faker $faker) {
    $card = factory('App\Card')->create();
    return [
        'card_id' => $card->id,
        'transaction_id' => factory('App\Transaction')->create(['owner_id' => $card->owner_id, 'vendor_id' => $card->vendor_id]),
    ];
});
