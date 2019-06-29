<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        dd(config('app'));
        $transactions = Transaction::orderBy('date', 'ASC');

        if($request->get('from')){
            $transactions->where('date', '>=', new Carbon($request->get('from')));
        }

        if($request->get('to')){
            $transactions->where('date', '<=', new Carbon($request->get('to')));
        }

        if($request->get('name')){
            $transactions->where('name', 'like', '%'.$request->get('name').'%');
        }

        if($request->get('type')){
            $transactions->where('type', $request->get('type'));
        }

        // dd($transactions->toSql());
        $transactions = $transactions->get();
        return view('transaction.index', compact('transactions', 'request'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    private function validateRequest(Request $request){
        $this->validate($request, [
            'date' => 'bail|required|date',
            'name' => 'bail|required',
            'description' => 'bail|required',
            'amount' => 'bail|required|numeric',
            'type' => 'bail|required|in:1,2',
        ], [],[
            'name' => 'account name',
            'type' => 'transaction type',
        ]); 
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validateRequest($request);
        $transaction = new Transaction;
        $transaction->fill($request->all());
        $transaction->save();
        return back()->with('success', 'Record Saved successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function edit(Transaction $transaction)
    {
        $transactions = Transaction::orderBy('date', 'ASC')->get();
        return view('transaction.index', compact('transactions', 'transaction'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaction $transaction)
    {
        $this->validateRequest($request);
        $transaction->fill($request->all());
        $transaction->save();
        return back()->with('success', 'Record Saved successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        $transaction->delete();
        return back()->with('success', 'Record Deleted successfully');
    }
}
