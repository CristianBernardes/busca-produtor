<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 *
 */
class State extends Model
{
    use HasFactory;

    /**
     * @return HasMany
     */
    public function cities()
    {
        return $this->hasMany(City::class, 'state_id', 'id');
    }
}
