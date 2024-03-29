<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 *
 */
class City extends Model
{
    use HasFactory;

    /**
     * @return BelongsTo
     */
    public function state()
    {
        return $this->belongsTo(State::class, 'state_id', 'id');
    }
}
