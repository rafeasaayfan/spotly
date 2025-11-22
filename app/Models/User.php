<?php

namespace App\Models;

use App\Enums\Spotly\UserStatus;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'status',
    ];

    protected $casts = [
        'status' => UserStatus::class,
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

    /**
     * Get the website types created by the user.
     */
    public function websiteTypes()
    {
        return $this->hasMany(WebsiteType::class, 'created_by');
    }

    /**
     * Get the website of the user.
     */
    public function websites()
    {
        return $this->hasMany(Website::class, 'owner_id');
    }

    /**
     * Get the websites that reviewed by the user.
     */
    public function reviewedWebsites()
    {
        return $this->hasMany(Website::class, 'approved_or_denied_by');
    }
}
