<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Book;
use Faker\Generator as Faker;

$factory->define(Book::class, function (Faker $faker) {
    return [
        'name' => ucwords($faker->words($nbWords = 5, $variableNbWords = true), " "),
        'isbn' => function () use ($faker) {
            $isbn = substr_replace($faker->isbn13(), '-', 3, 0);
            return $isbn;
        },
        'authors' => function () use ($faker) {
            $authors = array(
                $faker->name,
                $faker->name
            );
            return serialize($authors);
        },
        'country' => $faker->country,
        'number_of_pages' => $faker->numberBetween($min = 150, $max = 1000),
        'publisher' => $faker->company,
        'release_date' => $faker->date($format = 'Y-m-d', $max = 'now')
    ];
});
