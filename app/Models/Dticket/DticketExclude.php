<?php

namespace App\Models\Dticket;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DticketExclude extends Model
{
    use HasFactory;

    protected $casts = [
        'exclude_starts_at' => 'date',
        'exclude_ends_at' => 'date',
    ];
}
