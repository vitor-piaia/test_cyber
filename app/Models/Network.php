<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Network extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'networks';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'name',
        'description',
        'cidr',
        'location',
        'status',
    ];

    public function accesses(): HasMany
    {
        return $this->hasMany(DeviceNetworkAccess::class, 'network_id', 'id');
    }
}
