<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(FestivalsTableSeeder::class);
        $this->call(ArtistsTableSeeder::class);
        $this->call(GenresTableSeeder::class);
        $this->call(ArtistFestivalTableSeeder::class);
        $this->call(ArtistGenreTableSeeder::class);
        $this->call(FestivalGenreTableSeeder::class);
        $this->call(PhotosTableSeeder::class);
    }
}
