<?php

namespace App\Http\Controllers;

use App\Models\Sender;
use App\Models\Subdistrict;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class SenderController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $senders = Sender::get();
            return DataTables::of($senders)
                ->addColumn('full_address', function ($item) {
                    return $item->full_address;
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
                                        <a class="menu-link px-3" id="editSender" data-bs-toggle="modal" data-bs-target="#editSenderModal" data-id="' . $item->id . '">Edit</a>
                                    </div>
                                    <div class="menu-item px-3">
                                        <a href="" class="menu-link px-3" id="deleteConfirm" data-id="' . $item->id . '" data-name="' . $item->name . '">Hapus</a>
                                    </div>
                                </div>
                            </div>';
                })
                ->rawColumns(['actions'])
                ->addIndexColumn()
                ->make();
        }
        return view('admin.senders.index');
    }

    public function create(Request $request)
    {
        if (request()->ajax()) {
            if ($request->geocoding) {
                $subdistrict = Subdistrict::find($request->subdistrict_id);
                return $subdistrict->name . ', ' . $subdistrict->city->name . ', ' . $subdistrict->city->province->name;
            }
            $users = User::role('User')->select('id', 'name AS text')
                ->where([
                    ['name', 'like', '%' . $request->input('search', '') . '%']
                ])->get()->toArray();
            return response()->json(['results' => $users]);
        }
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $user = User::find($request->user_id);
            if (!$user) {
                $request->validate([
                    'phonenumber'   => 'required',
                    'name'          => 'required',
                    'address'       => 'required',
                    'latitude'      => 'required',
                    'longitude'     => 'required',
                ]);
            }
            Sender::create([
                'user_id'       => $request->user_id ?? null,
                'name'          => $request->name ? $request->name : $user->name,
                'phonenumber'   => $this->parsingPhonenumber($request->phonenumber ? $request->phonenumber : $user->phonenumber),
                'address'       => $request->address,
                'subdistrict_id' => $request->subdistrict_id,
                'postal_code'   => $request->postal_code ?? Subdistrict::find($request->subdistrict_id)->city->postal_code,
                'note'          => $request->note ?? '-',
                'latitude'      => $request->latitude,
                'longitude'     => $request->longitude,
            ]);
            DB::commit();
            return response()->json(['message' => 'Data berhasil disimpan!'], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    public function show(Sender $sender)
    {
        //
    }

    public function edit($id)
    {
        try {
            $recipient = Sender::with('subdistrict', 'subdistrict.city', 'subdistrict.city.province')->find($id);
            return response()->json($recipient, 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $sender = Sender::find($id);
            $sender->update([
                'name'          => $request->name ?? $sender->name,
                'phonenumber'   => $this->parsingPhonenumber($request->phonenumber ?? $sender->phonenumber),
                'address'       => $request->address ?? $sender->address,
                'subdistrict_id' => $request->subdistrict_id ?? $sender->subdistrict_id,
                'postal_code'   => $request->postal_code ?? Subdistrict::find($request->subdistrict_id)->city->postal_code,
                'note'          => $request->note ?? $sender->note,
                'default'       => $request->default ? 1 : 0,
                'latitude'      => $request->latitude,
                'longitude'     => $request->longitude,
            ]);
            DB::commit();
            return response()->json(['message' => 'Data pengirim berhasil diupdate!'], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $sender = Sender::findOrFail($id);
            if ($sender->shipments()->count() > 0) return response()->json(['message' => 'Data pengirim ini memiliki data pemesanan!'], 500);
            $sender->delete();
            DB::commit();
            return response()->json(['message' => 'Data pengirim berhasil dihapus!'], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }
}
