<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Repayment extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'loan_id', 'payment_date', 'amount', 'remakrs'
    ];



    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }



}
