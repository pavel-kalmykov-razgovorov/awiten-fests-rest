<?php

use Illuminate\Database\Seeder;

class PhotosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $festivals = DB::table('festivals')->pluck('id');

        DB::table('photos')->delete();
        DB::table('photos')->insert(
            [
                ['name' => 'Entrada recinto', 'permalink' => 'entrada-recinto', 'festival_id' => $festivals[0]],
                ['name' => 'Escenario 1', 'permalink' => 'escenario-1', 'festival_id' => $festivals[1]]
            ]
        );
    }
}
