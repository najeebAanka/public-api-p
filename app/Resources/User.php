<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'f_name' => $this->f_name,
            'l_name' =>$this->l_name,
            'phone' => $this->phone,
            'email' => $this->email,
            'user_type' => $this->user_type,
            'location' => $this->location
        ];
    }
}