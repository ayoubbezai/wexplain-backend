<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'phone_number',
        'second_number',
        'parent_number',
        'student_image_url',
        'date_of_birth',
        'address',
        'year_of_study',
        'gender'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'phone_number' => 'encrypted',
        'second_number' => 'encrypted',
        'parent_number' => 'encrypted',
        'address' => 'encrypted',
        'year_of_study' => 'encrypted',
    ];

    /**
     * Get the user that owns the student.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
