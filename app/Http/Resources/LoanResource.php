<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LoanResource extends JsonResource
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
            'Borrower' => $this->user,
            'Agent' => $this->employee,
            'description' => $this->description,
            'amount' => (string) $this->amount,
            'amount_topay' => (string) $this->amount_topay,
            'term' => $this->term,
            'frequency' => $this->frequency,
            'start_date' => (string) $this->start_date,
            'released_date' => (string) $this->released_date,
            'status' => $this->status,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
            'links'         => [
                'self' => route('loans.show', ['loan' => $this->id]),
            ],

        ];
    }
}
