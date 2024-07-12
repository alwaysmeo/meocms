<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Organizes extends Model
{
    use HasFactory;

    protected $table = 'organizes';

    protected $fillable = [
        'name',
        'description',
        'status'
    ];
}
