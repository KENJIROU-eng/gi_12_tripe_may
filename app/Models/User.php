<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    const ADMIN_ROLE_ID = 1;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function itineraries() {
        return $this->hasMany(Itinerary::class, 'created_by')->latest();
    }

    public function groups() {
        return $this->belongsToMany(Group::class, 'group_members', 'user_id', 'group_id');
    }


    public function group(){
        return $this->hasMany(Group::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'follows', 'following_id', 'follower_id');
    }

    public function followings()
    {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'following_id');
    }

    public function private_group(){
        return $this->hasMany(Group::class, 'user_id');
    }

    # Returns TRUE if the Auth user is already following the user
    public function isFollowed()
    {
        return $this->followers()->where('follower_id', Auth::user()->id)->exists();
    }

    public function belongings()
    {
        return $this->belongsToMany(Belonging::class)
                    ->withPivot('is_checked')
                    ->withTimestamps();
    }

}
