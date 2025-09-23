<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $fillable = [
        'user_id',
        'gender',
        'nationality',
        'phone_number',
        'second_phone_number',
        'date_of_birth',
        'teacher_image_url',
        'id_card_image_url',
        'address',
        'ccp_number',
        'ccp_key',
        'ccp_account_name',
        'card_number',
        'card_expiry',
        'card_cvv',
        'card_holder_name',
        'cv_url',
        'primary_subject',
        'other_subjects',
        'teaching_level',
        'years_of_experience',
        'credit',
    ];

    protected $casts = [
        // Dates
        'date_of_birth' => 'date',
        'card_expiry'   => 'date',

        // Encrypt sensitive fields
        'teacher_image_url'   => 'encrypted',
        'id_card_image_url'   => 'encrypted',
        'address'             => 'encrypted',
        'ccp_number'          => 'encrypted',
        'ccp_key'             => 'encrypted',
        'ccp_account_name'    => 'encrypted',
        'card_number'         => 'encrypted',
        'card_cvv'            => 'encrypted',
        'card_holder_name'    => 'encrypted',
        'cv_url'              => 'encrypted',

        // Financial
        'credit' => 'decimal:2',
    ];

    /**
     * Link to the User model
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
