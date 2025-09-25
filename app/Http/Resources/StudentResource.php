<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                  => $this->id,
            'first_name'          => $this->user->first_name,
            'last_name'           => $this->user->last_name,
            'email'               => $this->user->email,
            'phone_number'        => $this->phone_number,
            'second_number'       => $this->second__number,
            'parent_number'       => $this->parent_number,
            'student_image_url'   => $this->student_image,
            'gender'              => $this->gender,
            'date_of_birth'       => $this->date_of_birth,
            'address'             => $this->address,
            'year_of_study'       => $this->year_of_study,
            'student_image_url'   => $this->files['image'] ?? null,
        ];
    }
}
