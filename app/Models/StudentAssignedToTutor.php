<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAssignedToTutor extends Model
{
    use HasFactory;
    public $timestamps = false;


     /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'student_id',
        'teacher_id',
    ];
}
