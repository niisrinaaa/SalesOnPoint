<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Models\User;
class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Transactions = Transaction::all();
        return view('transaction', compact('Transactions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    //     $users = User::all();
    //     return view('create', [
    //         'type' => 'transaction',
    //         'user' => $users
    //     ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $request->validate([
        //     'user_id' => 'required|exists:users,id',
        //     'datetime' => 'required|date',
        //     'total' => 'required|numeric',
        //     'pay_total' => 'required|numeric',
             
        // ]);

        // Transaction::create($request->all());

        // return redirect()->route('transaction.index')->with('success', 'Transaction created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        // $transaction->delete();
        // return redirect()->route('transaction.index')->with('success', 'Transaction deleted successfully.');
    }
}
