<?php

namespace Tests\Unit;

use App\Artist;
use App\Festival;
use App\Genre;
use App\User;
use Tests\TestCase;

class AssociationsTest extends TestCase
{
    static private $promoter;
    static private $manager;

    public static function setUpBeforeClass()
    {
        self::$promoter = User::create([
            'name' => 'Test Promoter',
            'username' => 'test_promoter',
            'email' => 'promoter@test.com',
            'password' => '1234',
            'role' => 'promoter'
        ]);
        self::$manager = User::create([
            'name' => 'Test Manager',
            'username' => 'test_manager',
            'email' => 'manager@test.com',
            'password' => '1234',
            'role' => 'manager'
        ]);
    }

    public static function tearDownAfterClass()
    {
        self::$promoter->delete();
        self::$manager->delete();
    }

    /**
     * Checks the association Artist-Festival
     *
     * @return void
     */
    public function testAssociationArtistFestival()
    {
        $artist = new Artist();
        $artist->name = 'Suicidal Tendencies';
        $artist->permalink = str_slug($artist->name);
        $artist->manager_id = self::$manager->id;
        $artist->save();

        $festival = new Festival();
        $festival->name = 'Download Festival';
        $festival->permalink = str_slug($festival->name);
        $festival->promoter_id = self::$promoter->id;
        $festival->save();

        $festival->artists()->attach($artist->id);

        $this->assertEquals($artist->festivals[0]->name, 'Download Festival');
        $this->assertEquals($festival->artists[0]->name, 'Suicidal Tendencies');

        $festival->artists()->detach($artist->id);
        $festival->delete();
        $artist->delete();
    }

    /**
     * Checks the association Artist-Genre
     *
     * @return void
     */
    public function testAssociationArtistGenre()
    {
        $artist = new Artist();
        $artist->name = 'Suicidal Tendencies';
        $artist->permalink = str_slug($artist->name);
        $artist->manager_id = self::$manager->id;
        $artist->save();

        $genre = new Genre();
        $genre->name = 'Rock';
        $genre->save();

        $genre->artists()->attach($artist->id);

        $this->assertEquals($artist->genres[0]->name, 'Rock');
        $this->assertEquals($genre->artists[0]->name, 'Suicidal Tendencies');

        $genre->artists()->detach($artist->id);
        $genre->delete();
        $artist->delete();
    }

    /**
     * Checks the association Festival-Genre
     *
     * @return void
     */
    public function testAssociationFestivalGenre()
    {
        $festival = new Festival();
        $festival->name = 'Download Festival';
        $festival->permalink = str_slug($festival->name);
        $festival->promoter_id = self::$promoter->id;
        $festival->save();

        $genre = new Genre();
        $genre->name = 'Rock';
        $genre->save();

        $genre->festivals()->attach($festival->id);

        $this->assertEquals($festival->genres[0]->name, 'Rock');
        $this->assertEquals($genre->festivals[0]->name, 'Download Festival');

        $genre->artists()->detach($festival->id);
        $genre->delete();
        $festival->delete();
    }
}
