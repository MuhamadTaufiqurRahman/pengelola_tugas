<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory;

    // Priority constants
    const PRIORITY_LOW = 1;
    const PRIORITY_MEDIUM = 2;
    const PRIORITY_HIGH = 3;
    const PRIORITY_URGENT = 4;

     // Priority options for forms
    public static $priorityOptions = [
        self::PRIORITY_LOW => 'Low',
        self::PRIORITY_MEDIUM => 'Medium',
        self::PRIORITY_HIGH => 'High',
        self::PRIORITY_URGENT => 'Urgent',
    ];

    // Priority colors
    public static $priorityColors = [
        self::PRIORITY_LOW => 'blue',
        self::PRIORITY_MEDIUM => 'yellow',
        self::PRIORITY_HIGH => 'orange',
        self::PRIORITY_URGENT => 'red',
    ];

    protected $fillable = [
        'title',
        'description',
        'status',
        'priority',
        'start_date',
        'due_date',
        'created_by'
    ];

    protected $casts = [
        'start_date' => 'date',
        'due_date' => 'date',
        'priority' => 'integer',
    ];

    // Relasi ke departemen
    public function departement()
    {
        return $this->belongsTo(MasterDepartement::class, 'created_by','id','name');
    }

    // Relasi ke users (assignees)
    public function assignees()
    {
        return $this->belongsToMany(User::class, 'task_user')
                    ->withTimestamps();
    }

    // Accessor untuk departemen name
    public function getDepartementNameAttribute()
    {
        return $this->departement->name ?? '-';
    }

    public function getPriorityTextAttribute()
    {
        return match($this->priority) {
            1 => 'Low',
            2 => 'Medium',
            3 => 'High',
            4 => 'Urgent',
            default => 'Unknown',
        };
    }

    public function getPriorityBadgeClassAttribute()
    {
        return match($this->priority) {
            1 => 'bg-blue-100 text-blue-800',      // Low - Blue
            2 => 'bg-yellow-100 text-yellow-800',  // Medium - Yellow
            3 => 'bg-orange-100 text-orange-800',  // High - Orange
            4 => 'bg-red-100 text-red-800',        // Urgent - Red
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getPriorityColorAttribute()
    {
        return match($this->priority) {
            1 => 'blue',
            2 => 'yellow',
            3 => 'orange',
            4 => 'red',
            default => 'gray',
        };
    }

     public function getPriorityIconAttribute()
    {
        return match($this->priority) {
            1 => 'bi-flag',
            2 => 'bi-flag-fill',
            3 => 'bi-exclamation-circle',
            4 => 'bi-exclamation-triangle',
            default => 'bi-flag',
        };
    }
}
