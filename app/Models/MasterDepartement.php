<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterDepartement extends Model
{
    protected $table = 'master_department'; // Tambahkan ini!

    protected $fillable = [
        'name',
        'active',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    // Relasi ke tasks
    public function tasks()
    {
        return $this->hasMany(Task::class, 'created_by');
    }
}
