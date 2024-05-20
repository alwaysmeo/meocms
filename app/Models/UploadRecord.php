<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UploadRecord extends Model
{
    use HasFactory;

    protected $table = 'upload_record';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'url',
        'type',
        'origin_name',
        'suffix',
        'status',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
