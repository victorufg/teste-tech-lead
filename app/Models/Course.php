<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'type', 'max_students', 'enrollment_deadline'];

    public function students()
    {
        return $this->belongsToMany(Student::class)->withTimestamps();
    }
}
