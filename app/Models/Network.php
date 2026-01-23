<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Network extends Model
{
    use HasFactory;

    protected $table = 'networks';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'name',
        'description',
        'network_range_start',
        'network_range_end',
        'location',
        'status',
    ];
}
