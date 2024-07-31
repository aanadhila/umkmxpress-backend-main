<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $users = User::role('User')->select('id', 'name', 'phonenumber', 'email', 'otp')->get();
            return DataTables::of($users)
                ->addColumn('photo', function ($item) {
                    return '<img src="' . $item->photo . '"/>';
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
                                <div class="dropdown-menu menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-175px py-4" aria-labelledby="menu-' . $item->id . '">
                                    <div class="menu-item px-3">
                                        <a class="menu-link px-3" id="editUser" data-bs-toggle="modal" data-bs-target="#editUserModal" data-id="' . $item->id . '">Edit</a>
                                    </div>
                                    <div class="menu-item px-3">
                                        <a href="' . route('recipients.index', ['user' => $item->id]) . '" class="menu-link px-3">Lihat alamat penerima</a>
                                    </div>
                                    <div class="menu-item px-3">
                                        <a href="" class="menu-link px-3" id="deleteConfirm" data-id="' . $item->id . '" data-name="' . $item->name . '">Hapus</a>
                                    </div>
                                </div>
                            </div>';
                })
                ->rawColumns(['photo', 'actions'])
                ->addIndexColumn()
                ->make();
        }
        return view('admin.users.index');
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->merge(['phonenumber' => $this->parsingPhonenumber($request->phonenumber)]);
            $request->validate([
                'name'          => 'required',
                'email'         => 'required|unique:users,email',
                'phonenumber'   => 'required|unique:users,phonenumber',
                'password'      => 'required',
            ]);

            User::create([
                'name'          => $request->name,
                'email'         => $request->email,
                'phonenumber'   => $request->phonenumber,
                'password'      => bcrypt($request->password),
            ])->assignRole('User');

            DB::commit();
            return response()->json(['message' => 'User berhasil ditambahkan'], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        try {
            $user = User::findOrFail($id);
            return response()->json($user, 200);
        } catch (\Throwable $th) {
            return response()->json(['message', $th->getMessage()]);
        }
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $user = User::findOrFail($id);
            $request->merge(['phonenumber' => $this->parsingPhonenumber($request->phonenumber)]);
            $request->validate([
                'email'         => 'unique:users,email,' . $user->id,
                'phonenumber'   => 'unique:users,phonenumber,' . $user->id,
            ]);
            $user->update([
                'name'          => $request->name ?? $user->name,
                'phonenumber'   => $request->phonenumber ?? $user->phonenumber,
                'email'         => $request->email ?? $user->email,
                'otp_daily'     => $request->otp_daily ?? $user->otp_daily,
                'password'      => $request->password ? bcrypt($request->password) : $user->password,
            ]);

            DB::commit();
            return response()->json(['message' => 'Data berhasil diupdate'], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $user = User::findOrFail($id);
            if ($user->courier) return response()->json(['message' => 'User ini memiliki data kurir!'], 500);
            if ($user->shipments()->count() > 0) return response()->json(['message' => 'User ini memiliki data pesanan!'], 500);
            $user->delete();
            DB::commit();
            return response()->json(['message' => 'Data berhasil dihapus'], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }
}
