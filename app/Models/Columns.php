<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Columns extends Model
{
    use HasFactory;

    protected $table = 'columns';

    public $timestamps = false;

    protected $fillable = [
        'parent_id',
        'name',
        'description',
        'path',
        'cover',
        'external_link',
        'level',
        'show',
        'order',
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
