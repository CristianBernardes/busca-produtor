<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producer extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'producer_name',
        'city',
        'state',
        'latitude',
        'longitude',
        'coordinates',
        'whatsapp_phone',
        'volume_in_liters',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'coordinates',
        'created_at',
        'updated_at'
    ];

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
}
