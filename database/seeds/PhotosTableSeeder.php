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
        $medusa = DB::table('festivals')->where('name', 'Medusa Sunbeach Festival')->first()->id;
        $arenal = DB::table('festivals')->where('name', 'Arenal Sound')->first()->id;
        $dreambeach = DB::table('festivals')->where('name', 'Dreambeach Festival')->first()->id;
        $awakenings = DB::table('festivals')->where('name', 'Awakenings')->first()->id;
        $sstory = DB::table('festivals')->where('name', 'A Summer Story')->first()->id;
        $aquasella = DB::table('festivals')->where('name', 'Aquasella')->first()->id;
        $wan = DB::table('festivals')->where('name', 'Wan Festival')->first()->id;
        $tomorrow = DB::table('festivals')->where('name', 'Tomorrowland')->first()->id;
        $umf = DB::table('festivals')->where('name', 'Ultra Music Festival')->first()->id;
        $jaco = DB::table('festivals')->where('name', 'The Jaco Festival')->first()->id;

        DB::table('photos')->delete();
        DB::table('photos')->insert(
            [
                ['name' => 'Aquasella 1', 'festival_id' => $aquasella, 'filename' => 'aquasella1.jpg'],
                ['name' => 'Aquasella 2', 'festival_id' => $aquasella, 'filename' => 'aquasella2.jpg'],
                ['name' => 'Aquasella 3', 'festival_id' => $aquasella, 'filename' => 'aquasella3.jpg'],
                ['name' => 'Arenal Sound 1', 'festival_id' => $arenal, 'filename' => 'arenal-sound1.jpg'],
                ['name' => 'Arenal Sound 2', 'festival_id' => $arenal, 'filename' => 'arenal-sound2.jpg'],
                ['name' => 'A Summer-story 1', 'festival_id' => $sstory, 'filename' => 'a-summer-story1.jpg'],
                ['name' => 'A Summer-story 2', 'festival_id' => $sstory, 'filename' => 'a-summer-story2.jpg'],
                ['name' => 'Awakenings 1', 'festival_id' => $awakenings, 'filename' => 'awakenings1.jpg'],
                ['name' => 'Awakenings 2', 'festival_id' => $awakenings, 'filename' => 'awakenings2.jpg'],
                ['name' => 'Awakenings 3', 'festival_id' => $awakenings, 'filename' => 'awakenings3.jpg'],
                ['name' => 'Awakenings 4', 'festival_id' => $awakenings, 'filename' => 'awakenings4.jpg'],
                ['name' => 'Awakenings 5', 'festival_id' => $awakenings, 'filename' => 'awakenings5.jpg'],
                ['name' => 'Dreambeach 1', 'festival_id' => $dreambeach, 'filename' => 'dreambeach1.jpg'],
                ['name' => 'Dreambeach 2', 'festival_id' => $dreambeach, 'filename' => 'dreambeach2.jpg'],
                ['name' => 'Creamfields 1', 'festival_id' => $jaco, 'filename' => 'creamfields1.jpg'],
                ['name' => 'Creamfields 2', 'festival_id' => $jaco, 'filename' => 'creamfields2.jpg'],
                ['name' => 'Creamfields 3', 'festival_id' => $jaco, 'filename' => 'creamfields3.jpg'],
                ['name' => 'Creamfields 4', 'festival_id' => $jaco, 'filename' => 'creamfields4.jpg'],
                ['name' => 'Medusa 1', 'festival_id' => $medusa, 'filename' => 'medusa1.jpg'],
                ['name' => 'Medusa 2', 'festival_id' => $medusa, 'filename' => 'medusa2.jpg'],
                ['name' => 'Medusa 3', 'festival_id' => $medusa, 'filename' => 'medusa3.jpg'],
                ['name' => 'Medusa 4', 'festival_id' => $medusa, 'filename' => 'medusa4.jpg'],
                ['name' => 'Medusa Raul Barcia', 'festival_id' => $medusa, 'filename' => 'medusa-raul-barcia.jpg'],
                ['name' => 'Tomorrowland 1', 'festival_id' => $tomorrow, 'filename' => 'tomorrowland1.jpg'],
                ['name' => 'Tomorrowland 2', 'festival_id' => $tomorrow, 'filename' => 'tomorrowland2.jpg'],
                ['name' => 'Tomorrowland 3', 'festival_id' => $tomorrow, 'filename' => 'tomorrowland3.jpg'],
                ['name' => 'Tomorrowland 4', 'festival_id' => $tomorrow, 'filename' => 'tomorrowland4.jpg'],
                ['name' => 'Tomorrowland 5', 'festival_id' => $tomorrow, 'filename' => 'tomorrowland5.jpg'],
                ['name' => 'Tomorrowland 6', 'festival_id' => $tomorrow, 'filename' => 'tomorrowland6.jpg'],
                ['name' => 'UMF 1', 'festival_id' => $umf, 'filename' => 'UMF1.jpg'],
                ['name' => 'UMF 2', 'festival_id' => $umf, 'filename' => 'UMF2.jpg'],
                ['name' => 'UMF 3', 'festival_id' => $umf, 'filename' => 'UMF3.jpg'],
                ['name' => 'UMF 4', 'festival_id' => $umf, 'filename' => 'UMF4.jpg'],
                ['name' => 'Wan 1', 'festival_id' => $wan, 'filename' => 'wan1.jpg'],
                ['name' => 'Wan 2', 'festival_id' => $wan, 'filename' => 'wan2.jpeg'],
                ['name' => 'Wan 3', 'festival_id' => $wan, 'filename' => 'wan3.png'],
            ]
        );
    }
}