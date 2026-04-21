<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceReport extends Model
{
    //
    public $table = 'service_records';
    public $timestamps = true;
    protected $fillable = [
        'machine_id',
        'service_type',
        'other_service_type',
        'identification_verification',
        'root_cause_findings',
        'action_taken',
        'equipment_status',
        'recommendations',
        'parts_replaced',
        'approved_by',
        'medtech_signature',
        'service_engineer',
        'service_engineer_department',
        'service_date',
        'service_images',
        'before_images',
        'after_images',
        'calibration_images',
        'completed_by_user_id',
        'completion_notes',
        'created_at'
    ];
}
