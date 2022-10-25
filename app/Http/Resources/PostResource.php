<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    // define properties
    public $status;
    public $message;

    public function __construct($resource, $status, $message)
    {
        // Ensure you call the parent constructor
        parent::__construct($resource);

        // set properties
        $this->status = $status;
        $this->message = $message;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return[
            'success' => $this->status,
            'message' => $this->message,
            'data' => $this->resource,
        ];
    }
}
