<?php

namespace App\Http\Controllers;

use App\Http\Resources\LoanResource;
use App\Http\Resources\LoansResource;
use App\Loan;
use App\User;
use Illuminate\Http\Request;
use Auth;
use Validator;
class LoanController extends Controller
{


    /**
     * ! Securing the API endpoints
     */

    public function __construct()
    {
        $this->middleware('auth:api')->except(['index','store','show','update','destroy']);

        $this->middleware('auth:apiemployee')->only(['index','store','show','update','destroy']);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        $myloans = \Auth::guard('api')->user()->loans;
//        dd($myloans);
     //   return response()->json(['error' => 'Strange !!!.'], 403);
       return new LoansResource($myloans);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return  new LoansResource(Loan::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'user_id' => 'exists:users,id',
            'description' => 'nullable',
            'amount' => 'required|numeric',
            'interest_rate' => 'required|numeric',
            'term' => 'required|numeric',
            'frequency' => 'required|in:Month,Year',
            'start_date' => 'required|date',
            'released_date' => 'required|date',
            'status' => 'required|in:pending,approved,on going,paid',
        ]);
        if ($validator->fails()) {

            return response()->json(['error' => $validator->errors()], 401);

        }

        //***********************************************************
        //              INTEREST * ((1 + INTEREST) ^ TOTALPAYMENTS)
        // PMT = LOAN * -------------------------------------------
        //                  ((1 + INTEREST) ^ TOTALPAYMENTS) - 1
        //***********************************************************
        $loanAmount = (float) $request->amount;
        $totalPayments = (int) $request->term;
        $interest = (float) $request->interest_rate;


        switch ($request->frequency)
        {
            case "Month": // 12 months in 1yr
                $int = $interest/1200;
                $int1 = 1+$int;
                $r1 = pow($int1, $totalPayments);

                $pmt = $loanAmount*($int*$r1)/($r1-1);
                $amounttopay = round($pmt*100)/100;

                break;
            case "Year": // 1yr in 1yr
                $int    = $interest / 100;    // convert to a percentage
                $value1 = $int * pow((1 + $int), $totalPayments);
                $value2 = pow((1 + $int), $totalPayments) - 1;
                $pmt    = $loanAmount * ($value1 / $value2);
                // $accuracy specifies the number of decimal places required in the result
                $amounttopay    = number_format($pmt, 2, ".", "");
                break;

        }


        $loan = Loan::create([
            'user_id' => $request->user_id,
            'employee_id' => \Auth::guard('apiemployee')->user()->id,
            'description' => $request->description,

            'amount' => $request->amount,
            'amount_topay' => $amounttopay*$request->term,
            'interest_rate' => $request->interest_rate,
            'term' => $request->term,
            'frequency' => $request->frequency,
            'start_date' => $request->start_date,
            'released_date' => $request->released_date,
            'status' => $request->status,
        ]);
        /**
         * TODO:Create Scheduled Loans
         */


        return new LoanResource($loan);
    }

    /**
     * Display the specified resource.
     *
     * @param Loan $loan
     * @return LoanResource
     */
    public function show(Loan $loan)
    {
       return new LoanResource($loan);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Loan $loan)
    {
        $validator = Validator::make($request->all(), [

            'status' => 'required|in:pending,approved,on going,paid',
        ]);
        if ($validator->fails()) {

            return response()->json(['error' => $validator->errors()], 401);

        }

        $loan->update($request->only(['status']));

        return new LoanResource($loan);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Loan $loan
     * @return \Illuminate\Http\Response
     * @throws \Exception
     * @throws \Exception
     */
    public function destroy(Loan $loan)
    {
        $loan->delete();

        return response()->json(['sucess' =>'The Loan was Deleted'], 204);
    }
}
