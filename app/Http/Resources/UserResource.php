<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'email' => $this->email,
            'address' => $this->address,
            'account_number' => $this->account_number,
            'balance' => $this->balance,
            'comments' => $this->comments,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
            'links'         => [
                'self' => route('users.show', ['user' => $this->id]),
            ],

        ];
    }
}
