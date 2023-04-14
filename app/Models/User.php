<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'client_id',
        'name',
        'email',
        'password',
        'city',
        'state',
        'latitude',
        'longitude',
        'first_access'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Determine if the user is an administrator.
     *
     * @return bool
     */
    public function getIsAdminAttribute(): bool
    {
        if ($this->client_id === null) {

            return true;
        }

        return false;
    }

    /**
     * Get the attribute instance for the password.
     *
     * @return Attribute
     */
    protected function password(): Attribute
    {
        return Attribute::make(
            set: static fn ($value) => Hash::make($value)
        );
    }

    /**
     * Get the attribute instance for the city.
     *
     * @return Attribute
     */
    protected function city(): Attribute
    {
        return Attribute::make(
            set: static fn ($value) => ucwords($value)
        );
    }

    /**
     * Get the attribute instance for the state.
     *
     * @return Attribute
     */
    protected function state(): Attribute
    {
        return Attribute::make(
            set: static fn ($value) => strtoupper($value)
        );
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * @param self $user
     *
     * @return array
     */
    public static function getPermissionsUser(self $user): array
    {
        return $user->isAdmin ? [
            'users' => [
                'GET' => true,
                'GET/ID' => true,
                'POST' => true,
                'PUT' => true,
                'DELETE' => true
            ],
            'clients' => [
                'GET' => true,
                'GET/ID' => true,
                'POST' => true,
                'PUT' => true,
                'DELETE' => true
            ],
            'producers' => [
                'GET' => true,
                'GET/ID' => true,
                'POST' => true,
                'PUT' => true,
                'DELETE' => true
            ],
        ] : [
            'users' => [
                'GET' => false,
                'GET/ID' => false,
                'POST' => false,
                'PUT' => false,
                'DELETE' => false
            ],
            'clients' => [
                'GET' => false,
                'GET/ID' => false,
                'POST' => false,
                'PUT' => false,
                'DELETE' => false
            ],
            'producers' => [
                'GET' => true,
                'GET/ID' => true,
                'POST' => false,
                'PUT' => false,
                'DELETE' => false
            ],
        ];
    }
}
