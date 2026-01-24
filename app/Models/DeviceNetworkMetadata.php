<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeviceNetworkMetadata extends Model
{
    use HasFactory;

    protected $table = 'device_network_metadata';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'device_network_access_id',
        'isp',
        'domains',
        'hostnames',
        'geolocation',
        'ports',
        'last_shodan_scan_at',
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

    public function deviceNetworkAccess(): BelongsTo
    {
        return $this->belongsTo(DeviceNetworkAccess::class, 'device_network_access_id', 'id');
    }
}
