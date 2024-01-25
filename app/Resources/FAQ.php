<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FAQ extends JsonResource
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
            'question' => $this->question,
            'answer' => $this->answer,
        ];
    }
}