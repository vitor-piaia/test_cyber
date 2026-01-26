<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Device extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'devices';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'name',
        'description',
        'mac',
        'device_type',
        'os',
        'status',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
    ];

    public function accesses(): HasMany
    {
        return $this->hasMany(DeviceNetworkAccess::class, 'device_id', 'id');
    }
}
