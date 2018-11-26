<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{



    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'employee_id', 'description', 'amount', 'amount_topay','interest_rate', 'term', 'frequency', 'start_date', 'released_date', 'status'
    ];




    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function repayments()
    {
        return $this->hasMany(Repayment::class);
    }


    //Model
    public function repaymentSum()
    {
        return $this->repayments()->sum('amount');
    }

}
