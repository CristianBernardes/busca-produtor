<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 *
 */
class PasswordResetToken extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'password_reset_tokens';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'token'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'created_at',
    ];

    /**
     * @return bool|void|null
     */
    public function delete()
    {
        DB::delete('delete from password_reset_tokens where email = ?', [$this->email]);
    }
}
