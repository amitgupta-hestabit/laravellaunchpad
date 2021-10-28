<?php

namespace App\Models;
use Laravel\Passport\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
//use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'user_type',
        'status',
        'password',
        'address',
        'profile_picture',
        'current_school',
        'previous_school',
        'experience',
        'expertise_in_subjects',
        'parent_details',
        'assigned_teacher',
        'status',
        'is_approved'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'parent_details' => 'array',
    ];

    public function expertiseInSubjects()
    {
        return $this->belongsToMany(Subject::class);
    }
}
