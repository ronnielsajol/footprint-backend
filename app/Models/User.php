<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
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

    // Relationships
    public function events()
    {
        return $this->hasMany(Event::class, 'created_by');
    }

    public function vips()
    {
        return $this->hasMany(Vip::class, 'created_by');
    }

    public function ascDirectives()
    {
        return $this->hasMany(AscDirective::class, 'created_by');
    }

    public function ascParticipations()
    {
        return $this->hasMany(AscParticipation::class, 'created_by');
    }

    // Helper methods
    public function isSuperadmin(): bool
    {
        return $this->role === 'superadmin';
    }

    public function isPolAdmin(): bool
    {
        return $this->role === 'pol_admin';
    }
}
