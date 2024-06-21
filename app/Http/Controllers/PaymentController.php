<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;


class PaymentController extends Controller
{
    public function process(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'user_id' => 'required|integer',
        ]);

        $response = Http::withHeaders([
            'X-Mock-Status' => $request->header('X-Mock-Status'),
        ])->get(route('mock.handle'));

        $transactionId = Str::uuid();

        $transaction = new Transaction();
        $transaction->transaction_id = $transactionId;
        $transaction->user_id = $request->user_id;
        $transaction->amount = $request->amount;
        $transaction->status = $response->status() == 200 ? 'accepted' : 'failed';
        $transaction->save();

        return response()->json([
            'transaction_id' => $transactionId,
            'status' => $transaction->status,
        ])->header('Cache-Control', 'no-store');
    }
}
