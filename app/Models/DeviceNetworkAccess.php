<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeviceNetworkAccess extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'device_network_access';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'ip',
        'device_id',
        'network_id',
        'accessed_at',
        'disconnected_at',
        'created_at',
        'updated_at',
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

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class, 'device_id', 'id');
    }

    public function network(): BelongsTo
    {
        return $this->belongsTo(Network::class, 'network_id', 'id');
    }

    public function metadata(): HasOne
    {
        return $this->HasOne(DeviceNetworkMetadata::class, 'device_network_access_id', 'id');
    }
}
