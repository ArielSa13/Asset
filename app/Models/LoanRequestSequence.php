<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanRequestSequence extends Model
{
    protected $fillable = [
        'year',
        'month',
        'last_number',
    ];
}
