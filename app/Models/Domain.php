<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'client',
        'site_url',
        'service',
        'host',
        'invoice',
        'google_drive',
        'start_date',
        'end_date',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'invoice' => 'datetime',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];
}
