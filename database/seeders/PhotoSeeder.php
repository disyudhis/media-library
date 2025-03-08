<?php

namespace Database\Seeders;

use App\Models\Photo;
use Illuminate\Database\Seeder;
use Laravolt\Avatar\Avatar;

class PhotoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(Avatar $faker)
    {
        for ($i = 0; $i < 10; $i++) {
            $photo = new Photo();
            $photo->path = $faker->create('Joko Widodo')->toGravatar();
            $photo->description = fake()->text(20);
            $photo->save();
        }
    }
}