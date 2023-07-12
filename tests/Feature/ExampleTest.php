<?php

use function Pest\Faker\faker;

uses(\Illuminate\Foundation\Testing\WithFaker::class);

it('is an example', function () {
    $name = faker()->firstName;

    expect($name)->not->toBeEmpty()->toBeString();
});
