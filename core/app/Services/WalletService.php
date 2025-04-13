<?php

namespace App\Services;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class WalletService
{


    public function getWalletBalance()
    {
        if (Auth::check()) {
            $user = Auth::user();

            $walletBalance = $user->wallet_balance;
        } else {
            $walletBalance = 0;
        }

        return  $walletBalance;
    }


    public function addFunds(User $user, $amount, $note = '')
    {
        DB::transaction(function () use ($user, $amount, $note) {
            $user->wallet_balance += $amount;
            $user->save();

            Wallet::create([
                'user_id' => $user->id,
                'amount' => $amount,
                'transaction_type' => 'credit',
                'note' => $note,
            ]);
        });
    }



    public function withdrawFunds(User $user,$order, $amount, $note = '')
    {
        DB::transaction(function () use ($user, $order,$amount, $note) {

            if ($user->wallet_balance < $amount) {
                throw new \Exception('Insufficient balance');
            }



            Wallet::create([

                'order_id' => $order->id,
                'user_id'=>$order->user_id,
                'amount' => $amount,
                'transaction_type' => 'debit',
                'payment_method_id'=>4,
                'w_type'=>'order placed',
                'note' => $note,
                'status'=>1,
            ]);

            $user->wallet_balance -= $amount;

            $user->save();
        });
    }




    public function getTransactionHistory(User $user)
    {
        return Wallet::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
