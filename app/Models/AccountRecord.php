<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountRecord extends Model
{
    use HasFactory;

    protected $table = 'account_record';

    protected $fillable = [
        'user_ulid',
        'control_user_ulid',
        'type',
        'description',
        'ip',
        'longitude',
        'altitude',
        'nation',
        'province',
        'city',
        'district',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'deleted_at' => 'datetime:Y-m-d H:i:s',
    ];
}
