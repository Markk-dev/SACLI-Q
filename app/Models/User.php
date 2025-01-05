<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{    
    use HasApiTokens, HasFactory, Notifiable;
    /** @use HasFactory<\Database\Factories\UserFactory> */


    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    public $timestamps = true;
    protected $fillable = [
        'name',
        'account_id',
        'access_type',
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
            'password' => 'hashed',
        ];
    }

    public function Ticket()
    {
        return $this->hasMany(Ticket::class, 'handled_by');
    }

    
    public function Windows()
    {
        return $this->belongsToMany(Window::class, 'window_access', 'user_id', 'window_id');
    }
}
