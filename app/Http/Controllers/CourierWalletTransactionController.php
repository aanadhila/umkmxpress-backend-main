<?php

namespace App\Http\Controllers;

use App\Models\Courier;
use App\Models\CourierWalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class CourierWalletTransactionController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            if (auth()->user()->hasRole('Kurir')) {
                $courierWalletTransaction = CourierWalletTransaction::where('courier_wallet_id', auth()->user()->courier->wallet->id)->orderBy('created_at', 'desc')->get();
            } else {
                $courierWalletTransaction = CourierWalletTransaction::whereIn('type', [1, 2])->orderBy('created_at', 'desc')->get();
            }

            return DataTables::of($courierWalletTransaction)
                ->addColumn('courier', function ($item) {
                    return $item->wallet->courier->user->name . ' - ' . $item->wallet->courier->phonenumber;
                })
                ->editColumn('type', function ($item) {
                    return config('data.transaction_type')[$item->type];
                })
                ->editColumn('amount', function ($item) {
                    return 'Rp ' . number_format($item->amount, 0, ',', '.');
                })
                ->editColumn('status', function ($item) {
                    return config('data.payment_status')[$item->status]['badge'];
                })
                ->addColumn('actions', function ($item) {
                    return '<div class="dropdown">
                            <button class="btn btn-light btn-active-light-primary btn-sm" type="button" id="menu-' . $item->id . '" data-bs-toggle="dropdown" aria-expanded="false">
                                Actions
                                <span class="svg-icon svg-icon-5 m-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="currentColor" />
                                    </svg>
                                </span>
                            </button>
                            <div class="dropdown-menu menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-50px py-4" aria-labelledby="menu-' . $item->id . '">
                                <div class="menu-item px-3">
                                    <a class="menu-link px-3" id="updateStatusTransaction" data-bs-toggle="modal" data-bs-target="#updateStatusTransactionModal" data-id="' . $item->id . '">Update Status</a>
                                </div>
                            </div>
                        </div>';
                })
                ->rawColumns(['status', 'actions'])
                ->addIndexColumn()
                ->make();
        }

        return view('admin.wallets.index');
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            if (auth()->user()->hasRole('Super Admin|Admin')) {
                if (!in_array($request->type, [1])) return response()->json(['message' => 'Tidak dapat menambahkan transaksi dengan tipe tersebut!'], 500);
                $request->validate([
                    'courier_id'    => 'required',
                ]);

                $courier = Courier::findOrFail($request->courier_id);
            } else if (auth()->user()) {
                if (!in_array($request->type, [1, 2])) return response()->json(['message' => 'Tidak dapat menambahkan transaksi dengan tipe tersebut!'], 500);
                $courier = auth()->user()->courier;
                if ($request->type == 2) {
                    if ($courier->wallet->balance <= $request->amount) return response()->json(['message' => 'Saldo anda tidak mencukupi!'], 500);
                }
            }

            $request->validate([
                'type'          => 'required',
                'amount'        => 'required',
            ]);

            CourierWalletTransaction::create([
                'courier_wallet_id' => $courier->wallet->id,
                'type'      => $request->type,
                'amount'    => $request->amount,
                'status'    => 1,
                'note'      => config('data.transaction_type')[1] . ' saldo kurir ' . $courier->user->name,
            ]);

            DB::commit();
            return response()->json(['message' => 'Transaksi berhasil ditambahkan!'], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $courierWalletTransaction = CourierWalletTransaction::with('wallet.courier.user')->findOrFail($id);
            return response()->json($courierWalletTransaction, 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    public function edit(CourierWalletTransaction $courierWalletTransaction)
    {
        //
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        if (!auth()->user()->hasRole('Super Admin|Admin')) return response()->json(['message' => 'Anda bukan Admin!'], 500);
        try {
            if ($request->status) {
                $courierWalletTransaction = CourierWalletTransaction::findOrFail($id);
                $courierWalletTransaction->update([
                    'status'    => $request->status,
                ]);
                if ($request->status == 2) {
                    if ($courierWalletTransaction->type == 1) {
                        $courierWalletTransaction->wallet()->update([
                            'balance'    => $courierWalletTransaction->wallet->balance + $courierWalletTransaction->amount,
                        ]);
                    } else if ($courierWalletTransaction->type == 2) {
                        $courierWalletTransaction->wallet()->update([
                            'balance'    => $courierWalletTransaction->wallet->balance - $courierWalletTransaction->amount,
                        ]);
                    }
                    DB::commit();
                    return response()->json(['message' => 'Status berhasil diupdate!'], 200);
                }
                if ($request->status == 3) {
                    if ($courierWalletTransaction->type == 1) {
                        $courierWalletTransaction->wallet()->update([
                            'balance'    => $courierWalletTransaction->wallet->balance - $courierWalletTransaction->amount,
                        ]);
                    } else if ($courierWalletTransaction->type == 2) {
                        $courierWalletTransaction->wallet()->update([
                            'balance'    => $courierWalletTransaction->wallet->balance + $courierWalletTransaction->amount,
                        ]);
                    }
                    DB::commit();
                    return response()->json(['message' => 'Transaksi berhasil dibatalkan'], 200);
                }
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CourierWalletTransaction  $courierWalletTransaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(CourierWalletTransaction $courierWalletTransaction)
    {
        //
    }
}
