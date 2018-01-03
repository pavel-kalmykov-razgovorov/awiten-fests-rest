<?php

use Illuminate\Database\Seeder;

class PostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $festivals = DB::table('festivals')->pluck('id');

        DB::table('posts')->delete();
        DB::table('posts')->insert(
            [
                ['title' => 'Nuevos artistas incorporados', 'lead' => 'Más de 10 artisas nuevos, conocidos internacionalmente', 
                'body' => 'Ver tibi contribuat sua munera florea grata 
                            Et tibi grata comis nutet aestiva voluptas 
                            Reddat et autumnus Bacchi tibi munera semper 
                            Ac leve hiberni tempus tellure dicetur', 
                'permalink' => 'nuevos-artistas-incorporados', 'festival_id' => $festivals[0]],
                ['title' => 'Cantarán en Latín', 'lead' => 'El latín como lengua en nuestro festival', 
                'body' => 'Scripta eius labentibus saeculis fons fuerunt unde fluxit inspiratio attinens ad sacerdotalem spiritualitatem, quapropter ille fautor haberi potest mystici motus apud presbyteros saeculares.', 
                'permalink' => 'cantaran-en-latin', 'festival_id' => $festivals[0]],
            ]
        );
    }
}
