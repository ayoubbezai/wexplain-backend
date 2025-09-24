<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TeacherResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                  => $this->id,
            'first_name'          => $this->user->first_name,
            'last_name'           => $this->user->last_name,
            'email'               => $this->user->email,
            'phone_number'        => $this->phone_number,
            'second_phone_number' => $this->second_phone_number,
            'gender'              => $this->gender,
            'primary_subject'     => $this->primary_subject,
            'teaching_level'      => $this->teaching_level,
            'years_of_experience' => $this->years_of_experience,
        ];
    }
}
