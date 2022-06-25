<?php

require_once('../config/database.php');
require_once('../vendor/autoload.php');

// use the factory to create a Faker\Generator instance
$faker = Faker\Factory::create();

for($i = 0; $i < 150; $i++)
{
    $q = $db->prepare("INSERT INTO users(
        name, pseudo, email, password, active, created_at, city, country, sex, available_for_hiring, bio)
        VALUES (:name, :pseudo, :email, :password, :active, :created_at, :city, :country, :sex, :available_for_hiring, :bio)");

    $q->execute([
        "name"                  => $faker->unique()->name,
        "pseudo"                => $faker->unique()->userName,
        "email"                 => $faker->unique()->email,
        "password"              => password_hash("123456", PASSWORD_BCRYPT),
        "active"                => 1,
        "created_at"            => $faker->date.' '.$faker->time,
        "city"                  => $faker->city,
        "country"               => $faker->country,
        "sex"                   => $faker->randomElement(['H', 'F']),
        "available_for_hiring"  => $faker->randomElement([0, 1]),
        "bio"                   => $faker->text()
    ]);

    $id = $db->lastInsertId();

    $q = $db->prepare("INSERT INTO friends(user_id1, user_id2, status)
                        VALUES(?, ?, ?)");

    $q->execute([$id, $id, '2']);

}

echo "Users added !!!";