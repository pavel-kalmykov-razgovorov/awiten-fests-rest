<?php
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App {
    /**
     * App\Artist
     *
     * @property int $id
     * @property string $permalink
     * @property \Carbon\Carbon|null $created_at
     * @property \Carbon\Carbon|null $updated_at
     * @property string $name
     * @property string|null $country
     * @property string|null $soundcloud
     * @property string|null $website
     * @property string|null $pathProfile
     * @property string|null $pathHeader
     * @property int $manager_id
     * @property-read \Illuminate\Database\Eloquent\Collection|\App\Festival[] $festivals
     * @property-read \Illuminate\Database\Eloquent\Collection|\App\Genre[] $genres
     * @property-read \App\User $manager
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Artist whereCountry($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Artist whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Artist whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Artist whereManagerId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Artist whereName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Artist wherePathHeader($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Artist wherePathProfile($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Artist wherePermalink($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Artist whereSoundcloud($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Artist whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Artist whereWebsite($value)
     */
    class Artist extends \Eloquent
    {
    }
}

namespace App {
    /**
     * App\Photo
     *
     * @property int $id
     * @property string $name
     * @property string $permalink
     * @property int $festival_id
     * @property \Carbon\Carbon|null $created_at
     * @property \Carbon\Carbon|null $updated_at
     * @property-read \App\Festival $festival
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Photo whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Photo whereFestivalId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Photo whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Photo whereName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Photo wherePermalink($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Photo whereUpdatedAt($value)
     */
    class Photo extends \Eloquent
    {
    }
}

namespace App {
    /**
     * App\Genre
     *
     * @property int $id
     * @property string $permalink
     * @property \Carbon\Carbon|null $created_at
     * @property \Carbon\Carbon|null $updated_at
     * @property string $name
     * @property-read \Illuminate\Database\Eloquent\Collection|\App\Artist[] $artists
     * @property-read \Illuminate\Database\Eloquent\Collection|\App\Festival[] $festivals
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Genre whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Genre whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Genre whereName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Genre wherePermalink($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Genre whereUpdatedAt($value)
     */
    class Genre extends \Eloquent
    {
    }
}

namespace App {
    /**
     * App\Post
     *
     * @property int $id
     * @property string $title
     * @property string $lead
     * @property string $body
     * @property string $permalink
     * @property int $festival_id
     * @property \Carbon\Carbon|null $created_at
     * @property \Carbon\Carbon|null $updated_at
     * @property-read \App\Festival $festival
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereBody($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereFestivalId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereLead($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Post wherePermalink($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereTitle($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereUpdatedAt($value)
     */
    class Post extends \Eloquent
    {
    }
}

namespace App {
    /**
     * App\Festival
     *
     * @property int $id
     * @property string $permalink
     * @property \Carbon\Carbon|null $created_at
     * @property \Carbon\Carbon|null $updated_at
     * @property string $name
     * @property string|null $date
     * @property string|null $province
     * @property string|null $location
     * @property string|null $pathLogo
     * @property string|null $pathCartel
     * @property int $promoter_id
     * @property-read \Illuminate\Database\Eloquent\Collection|\App\Artist[] $artists
     * @property-read \Illuminate\Database\Eloquent\Collection|\App\Genre[] $genres
     * @property-read \Illuminate\Database\Eloquent\Collection|\App\Photo[] $photos
     * @property-read \Illuminate\Database\Eloquent\Collection|\App\Post[] $posts
     * @property-read \App\User $promoter
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Festival whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Festival whereDate($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Festival whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Festival whereLocation($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Festival whereName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Festival wherePathCartel($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Festival wherePathLogo($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Festival wherePermalink($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Festival wherePromoterId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Festival whereProvince($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\Festival whereUpdatedAt($value)
     */
    class Festival extends \Eloquent
    {
    }
}

namespace App {
    /**
     * App\User
     *
     * @property int $id
     * @property string $name
     * @property string $username
     * @property string $email
     * @property string $password
     * @property string|null $role
     * @property int $confirmed
     * @property int $locked
     * @property string|null $token
     * @property string|null $remember_token
     * @property \Carbon\Carbon|null $created_at
     * @property \Carbon\Carbon|null $updated_at
     * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
     * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereConfirmed($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmail($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereLocked($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePassword($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRememberToken($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRole($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereToken($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUsername($value)
     */
    class User extends \Eloquent
    {
    }
}

