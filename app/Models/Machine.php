<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Machine extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'model',
        'serial_number',
        'description',
        'installation_date',
        'status',
        'client_location',
        'region',
        'city',
        'last_service_date',
        'next_service_date',
        'service_interval_days',
        'image_path'
    ];

    protected $casts = [
        'installation_date'   => 'date',
        'last_service_date'   => 'date',
        'next_service_date'   => 'date',
        'service_interval_days' => 'integer',
    ];

    // Relationship with Service Records
//    public function serviceRecords()
//     {
//         return $this->hasMany(ServiceRecord::class)->orderBy('service_date', 'desc');
//     }

//     public function latestServiceRecord()
//     {
//         return $this->hasOne(ServiceRecord::class)->latest('service_date');
//     }

//     public function recentServiceRecords($limit = 5)
//     {
//         return $this->hasMany(ServiceRecord::class)
//                    ->orderBy('service_date', 'desc')
//                    ->limit($limit);
//     }

    // Add database indexes for better performance
    public function getIndexes()
    {
        return [
            'status',
            'client_location', 
            'region',
            'next_service_date',
            ['status', 'next_service_date'],
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($machine) {
            if (
                !$machine->next_service_date &&
                $machine->last_service_date &&
                $machine->service_interval_days
            ) {
                $machine->next_service_date = Carbon::parse($machine->last_service_date)
                    ->addDays((int) $machine->service_interval_days);
            }
        });

        static::updating(function ($machine) {
            if (
                $machine->isDirty(['last_service_date', 'service_interval_days']) &&
                $machine->last_service_date &&
                $machine->service_interval_days
            ) {
                $machine->next_service_date = Carbon::parse($machine->last_service_date)
                    ->addDays((int) $machine->service_interval_days);
            }
        });
    }

    // Optimized scopes with proper indexing
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeRegion($query, $region)
    {
        return $query->where('region', $region);
    }

    public function scopeLocation($query, $location)
    {
        return $query->where('client_location', $location);
    }

    public function scopeDueForService($query, $days = 30)
    {
        return $query->where('next_service_date', '<=', now()->addDays($days))
                    ->where('next_service_date', '>=', now());
    }

    public function scopeOverdue($query)
    {
        return $query->where('next_service_date', '<', now());
    }

    public function scopeOperational($query)
    {
        return $query->where('status', 'Operational');
    }

    // Efficient status checkers
    public function isDueForService()
    {
        return $this->next_service_date && $this->next_service_date->isPast();
    }

    public function isDueSoon($days = 7)
    {
        if (!$this->next_service_date) return false;
        return $this->next_service_date->diffInDays(now(), false) <= $days && 
               $this->next_service_date->isFuture();
    }

    // Cached attribute accessors
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'Operational' => 'green',
            'Maintenance' => 'yellow', 
            'Standby' => 'blue',
            'Not Operational' => 'red',
            default => 'gray'
        };
    }

    public function getPmsStatusColorAttribute()
    {
        if ($this->isDueForService()) {
            return 'red';
        } elseif ($this->isDueSoon(7)) {
            return 'yellow';
        }
        return 'green';
    }

    public function getServiceStatusAttribute()
    {
        if ($this->isDueForService()) {
            return 'Overdue';
        } elseif ($this->isDueSoon(7)) {
            return 'Due Soon';
        } elseif ($this->isDueSoon(30)) {
            return 'Upcoming';
        }
        return 'Current';
    }

    // Efficient service record management
    public function markAsServiced($details = null, $servicedBy = null)
    {
        $this->update([
            'last_service_date' => now(),
            'next_service_date' => now()->addDays($this->service_interval_days),
            'status' => 'Operational'
        ]);

        // Log service if needed
        if ($details) {
            \Log::info("Machine {$this->serial_number} serviced", [
                'machine_id' => $this->id,
                'details' => $details,
                'serviced_by' => $servicedBy ?? auth()->user()?->name ?? 'System'
            ]);
        }

        return $this;
    }

    // Get service statistics for this machine
    public function getServiceStatsAttribute()
    {
        return [
            'total_services' => $this->serviceRecords()->count(),
            'last_service_days_ago' => $this->last_service_date ? $this->last_service_date->diffInDays(now()) : null,
            'next_service_in_days' => $this->next_service_date ? now()->diffInDays($this->next_service_date, false) : null,
            'average_service_interval' => $this->serviceRecords()
                ->selectRaw('AVG(DATEDIFF(service_date, LAG(service_date) OVER (ORDER BY service_date))) as avg_interval')
                ->value('avg_interval'),
        ];
    }

    // Static method for bulk operations
    public static function getServiceStatistics()
    {
        return [
            'total' => static::count(),
            'operational' => static::where('status', 'Operational')->count(),
            'maintenance' => static::where('status', 'Maintenance')->count(),
            'overdue' => static::where('next_service_date', '<', now())->count(),
            'due_soon' => static::whereBetween('next_service_date', [now(), now()->addDays(7)])->count(),
        ];
    }
}