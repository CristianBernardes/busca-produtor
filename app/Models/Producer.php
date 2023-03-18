<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

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
        'created_at',
        'updated_at',
    ];

    /**
     *
     * @return Attribute
     */
    protected function latitude(): Attribute
    {
        return Attribute::make(
            set: static fn ($value) => commaToPeriod($value)
        );
    }

    /**
     *
     * @return Attribute
     */
    protected function longitude(): Attribute
    {
        return Attribute::make(
            set: static fn ($value) => commaToPeriod($value)
        );
    }
}
