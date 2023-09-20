<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;;

class ConferenceResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->title,
            'agenda' => $this->agenda,
            'date' => $this->date,
            "status" => $this->status,
            "attachments" => $this->attachment->map(function($e, $i){
                return [
                    "category" => $this->category,
                    "files" => [
                        "name" => $this->file_name,
                        "details" => $this->details,
                        "storage_location" => $this->storage_location,
                        "path" => $this->path
                    ]
                ];
            })
        ];
    }
}
