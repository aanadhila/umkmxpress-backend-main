<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PaymentController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $payment = Payment::when(request()->status, function ($query) {
                return $query->where('status', request()->status);
            })->get();
            return DataTables::of($payment)
            ->addColumn('pengirim', function ($item) {
                if ($item->shipment && $item->shipment->user) {
                    return '
                        <p>
                            <b>' . $item->shipment->user->name . '</b>
                            <br>
                            ' . $item->shipment->user->phonenumber . '
                            <br>
                            ' . $item->shipment->sender->full_address . '
                        </p>
                        ';
                } else if ($item->shipment && $item->shipment->sender) {
                    return '
                        <p>
                            <b>' . $item->shipment->sender->name . '</b>
                            <br>
                            ' . $item->shipment->sender->phonenumber . '
                            <br>
                            ' . $item->shipment->sender->full_address . '
                        </p>
                        ';
                } else {
                    return '-';
                }
            })
            ->addColumn('method', function ($item) {
                return $item->method->name;
            })
            ->editColumn('total_payment', function ($item) {
                return  'Rp ' . number_format(intval($item->total_payment), 0, ',', '.');
;
            })
            ->addColumn('status', function ($item) {
                if ($item->method->verification == 0 && $item->shipment && $item->shipment->status < 5) {
                    return config('data.shipment_status')[$item->shipment->status]['badge'];
                } else {
                    return config('data.payment_status')[$item->status]['badge'];
                }
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
                                    <a class="menu-link px-3" id="updateStatusPayment" data-bs-toggle="modal" data-bs-target="#updateStatusPaymentModal" data-id="' . $item->id . '">Update Status</a>
                                </div>
                            </div>
                        </div>';
            })
            ->addIndexColumn()
            ->rawColumns(['pengirim', 'total_payment', 'status', 'actions'])
            ->make();
        }
        return view('admin.payments.index');
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Payment $payment)
    {
        //
    }

    public function edit(Payment $payment)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy(Payment $payment)
    {
        //
    }
}
