<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permissions extends Model
{
    use HasFactory;

    protected $table = 'permissions';

    public $timestamps = false;

    protected $fillable = [
        'parent_id',
        'code',
        'name',
        'description',
        'icon',
        'path',
        'level',
        'show',
        'order',
        'type',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime:Y-m-d H:i:s',
            'updated_at' => 'datetime:Y-m-d H:i:s',
            'deleted_at' => 'datetime:Y-m-d H:i:s',
        ];
    }

    public function getShowAttribute($value): bool
    {
        return [0 => false, 1 => true][$value];
    }
}
