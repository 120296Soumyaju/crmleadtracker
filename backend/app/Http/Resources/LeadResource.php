<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeadResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'company' => $this->company,
            'source' => $this->source,
            'status' => $this->status,
            'assigned_to' => $this->assigned_to,
            'assigned_user' => new UserResource($this->whenLoaded('assignedUser')),
            'follow_up_date' => $this->follow_up_date?->format('Y-m-d'),
            'notes' => $this->notes,
            'customer_id' => $this->customer_id,
            'customer' => new CustomerResource($this->whenLoaded('customer')),
            'is_converted' => ! is_null($this->customer_id),
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}
