<?php

namespace App\Http\Controllers;

use App\Http\Resources\RepaymentResource;
use App\Http\Resources\RepaymentsResource;
use App\Loan;
use App\Repayment;
use Illuminate\Http\Request;
use Carbon\Carbon;
class RepaymentController extends Controller
{


    public function __construct()
    {

        $this->middleware('auth:api')->only(['create']);
        $this->middleware('auth:apiemployee')->only(['index','store','show','update','destroy']);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return  new RepaymentsResource(Repayment::paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Loan $loan
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request,Loan $loan)
    {

        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric',
            'remakrs' => 'nullable',

        ]);
        if ($validator->fails()) {

            return response()->json(['error' => $validator->errors()], 401);

        }

        $Totalpaidammount =  $loan->repaymentSum();
        // check if currently authenticated user is the owner of the Loan
        if ($request->user()->id !== $loan->user_id) {
            return response()->json(['error' => 'You can only make Repayment for your own Loans.'], 403);
        }

        // check if the Loan Was Makred by An Agent as Paid
        if ($loan->status ==="paid") {
            return response()->json(['error' => 'You Lown was Already Totaly Paid.'], 403);
        }

        // check if the Total Repayment of Current Loan was Already Paid
        if ($Totalpaidammount >= $loan->amount ) {
            return response()->json(['error' => 'You Lown was Already Totaly Paid.'], 403);
        }


//        dd($Totalpaidammount);
        $repayment = Repayment::create([
            'user_id' => $request->user()->id,
            'loan_id' => $loan->id,
            'payment_date' => Carbon::now(),
            'amount' => $request->amount,
            'remakrs' => $request->remakrs,

        ]);

        return new RepaymentResource($repayment);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return response()->json(['error' =>'Method Not Allowed'], 404);
    }

    /**
     * Display the specified resource.
     *
     * @param Repayment $repayment
     * @return RepaymentResource
     */
    public function show(Repayment $repayment)
    {
        return new RepaymentResource($repayment);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return response()->json(['error' =>'Method Not Allowed'], 404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Repayment $repayment
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Repayment $repayment)
    {
        $repayment->delete();

        return response()->json(['sucess' =>'The Repayment was Deleted'], 204);
    }
}
