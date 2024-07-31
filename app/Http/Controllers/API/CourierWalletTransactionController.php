<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\CourierWalletResource;
use App\Http\Resources\CourierWalletTransactionResource;
use App\Models\CourierWalletTransaction;
use App\Models\WithdrawAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CourierWalletTransactionController extends ApiController
{
    public function index()
    {
        try {
            $user = auth()->user();
            $courier = $user->courier;
            $wallet = $courier->wallet;
            return $this->respondSuccess('success', new CourierWalletResource($wallet));
        } catch (\Throwable $th) {
            return $this->respondInternalError($th->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $user = auth()->user();
            $wallet = $user->courier->wallet->id;
            $transaction = CourierWalletTransaction::where('courier_wallet_id', $wallet)->where('id', $id)->first();
            if (!$transaction) return $this->respondNotFound('Transaksi tidak ditemukan!');
            return $this->respondSuccess('success', new CourierWalletTransactionResource($transaction));
        } catch (\Throwable $th) {
            return $this->respondInternalError($th->getMessage());
        }
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            if (!in_array($request->type, [1, 2])) return $this->respondForbidden('Tidak dapat menambahkan transaksi dengan tipe tersebut!');
            $courier = auth()->user()->courier;
            if ($request->type == 2) {
                if ($courier->wallet->balance < $request->amount) return $this->respondForbidden('Saldo anda tidak mencukupi!');
            }
            $request->validate([
                'type'                              => 'required',
                'amount'                            => 'required',
                'name'                              => 'required_if:type,2',
                'withdraw_method_id'                => 'required_if:type,2',
                'account_number'                    => 'required_if:type,2',
            ]);

            $wallet = CourierWalletTransaction::create([
                'courier_wallet_id' => $courier->wallet->id,
                'type'      => $request->type,
                'amount'    => $request->amount,
                'status'    => 1,
                'note'      => config('data.transaction_type')[1] . ' saldo kurir ' . $courier->user->name,
            ]);

            if ($request->type == 2) {
                WithdrawAccount::create([
                    'name'                              => $request->name,
                    'withdraw_method_id'                => $request->withdraw_method_id,
                    'account_number'                    => $request->account_number,
                    'courier_wallet_transaction_id'     => $wallet->id,
                ]);
            }


            DB::commit();
            return $this->respondSuccess('success');
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->respondInternalError($th->getMessage());
        }
    }
}
