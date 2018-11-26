<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RepaymentResource extends JsonResource
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
            'Loan' => $this->loan,
            'payment_date' => (string) $this->payment_date,
            'amount' => (string) $this->amount,
            'remakrs' => $this->remakrs,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
            'links'         => [
                'self' => route('repayments.show', ['repayment' => $this->id]),
            ],

        ];
    }
}
