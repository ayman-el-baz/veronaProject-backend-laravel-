<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory; 
use App\Models\clients;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
  {
    $faker = Factory::create('fr_FR');
    for ($i = 0; $i < 20; $i++) {
      clients::create([
        'nom' => $faker->firstName,
        'prenom' => $faker->lastName,
        'email' => $faker->email,
        'password' => 'password',
        'telephone' => $faker->e164PhoneNumber,
        'sexe' => $faker->randomElement(['male', 'female']),
        'nationalite' => $faker->country,
        'photo' => 'https://via.placeholder.com/360x360.png',
        'role' => 'user',
        'adresse' => $faker->address,
        'commentaire' => 'qualite',
        'ville' => $faker->city,
      ]);
    }
  }
}
