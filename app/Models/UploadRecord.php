<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UploadRecord extends Model
{
    use HasFactory;

    protected $table = 'upload_record';

    protected $fillable = [
        'user_ulid',
        'url',
        'file_type',
        'origin_name',
        'suffix',
        'status',
        'type',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime:Y-m-d H:i:s',
            'updated_at' => 'datetime:Y-m-d H:i:s',
            'deleted_at' => 'datetime:Y-m-d H:i:s',
        ];
    }
}
