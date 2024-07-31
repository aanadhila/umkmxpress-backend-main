<?php

namespace App\Http\Controllers;

use App\Models\Courier;
use App\Models\CourierWallet;
use App\Models\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDO;
use Yajra\DataTables\Facades\DataTables;

class CourierController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $couriers = Courier::when(request()->status, function ($query) {
                return $query->where('status', request()->status);
            })->get();
            return DataTables::of($couriers)
                ->editColumn('photo', function ($item) {
                    return '<img class="w-100px" src="' . $item->pict . '"/>';
                })
                ->addColumn('name', function ($item) {
                    return $item->user->name;
                })
                ->addColumn('cluster', function ($item) {
                    if ($item->cluster) {
                        return '
                            <p>
                                <b>' . $item->cluster->name . '</b>
                                <br>
                                ' . $item->cluster->coverages()->orderBy('subdistrict_id', 'asc')->with('subdistrict')->get()->pluck('subdistrict.name')->implode(', ') . '
                            </p>
                            ';
                    } else {
                        return 'Belum ada cluster';
                    }
                })
                ->addColumn('balance', function ($item) {
                    if ($item->wallet) {
                        return 'Rp ' . number_format($item->wallet->balance, 0, ',', '.');
                    } else {
                        return 'Kurir ini belum terverifikasi';
                    }
                })
                ->editColumn('courier_specialist', function($item){
                    return $item->courier_specialist;
                })
                ->addColumn('badge', function ($item) {
                    return config('data.courier_status')[$item->status]['badge'];
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
                                        <a class="menu-link px-3" id="updateStatusCourier" data-bs-toggle="modal" data-bs-target="#updateStatusCourierModal" data-id="' . $item->id . '">Update Status</a>
                                    </div>
                                    <div class="menu-item px-3">
                                        <a class="menu-link px-3" id="editCourier" data-bs-toggle="modal" data-bs-target="#editCourierModal" data-id="' . $item->id . '">Edit Data</a>
                                    </div>
                                    <div class="menu-item px-3">
                                        <a class="menu-link px-3" id="editCluster" data-bs-toggle="modal" data-bs-target="#editClusterModal" data-id="' . $item->id . '">Edit Cluster</a>
                                    </div>
                                    <div class="menu-item px-3">
                                        <a href="" class="menu-link px-3" id="deleteConfirm" data-id="' . $item->id . '" data-name="' . $item->user->name . '">Hapus</a>
                                    </div>
                                </div>
                            </div>';
                })
                ->rawColumns(['photo', 'badge', 'cluster', 'actions'])
                ->addIndexColumn()
                ->make();
        }
        return view('admin.couriers.index');
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'photo' => 'required|mimes:png,jpg|max:2048',
                'nik'   => 'required|unique:couriers,nik',
                'ktp'   => 'required|mimes:png,jpg|max:2048',
                'nosim' => 'required|unique:couriers,nosim',
                'sim'   => 'required|mimes:png,jpg|max:2048',
                'nopol' => 'required|unique:couriers,nopol',
                'platno'  => 'required|mimes:png,jpg|max:2048',
                'courier_specialist' => 'required'
            ]);

            $photo      = $request->file('photo');
            $photo_name = time() . '_photo_' . $photo->getClientOriginalExtension();
            $photo_path = $photo->storeAs('couriers', $photo_name, 'public');

            $ktp      = $request->file('ktp');
            $ktp_name = time() . '_ktp_' . $ktp->getClientOriginalExtension();
            $ktp_path = $ktp->storeAs('couriers', $ktp_name, 'public');

            $sim      = $request->file('sim');
            $sim_name = time() . '_sim_' . $sim->getClientOriginalExtension();
            $sim_path = $sim->storeAs('couriers', $sim_name, 'public');

            $platno      = $request->file('platno');
            $platno_name = time() . '_platno_' . $platno->getClientOriginalExtension();
            $platno_path = $platno->storeAs('couriers', $platno_name, 'public');

            $user = User::find($request->user_id);
            $user->courier()->create([
                'user_id'   => $request->user_id,
                'nik'       => $request->nik,
                'nopol'     => $request->nopol,
                'phonenumber' => $this->parsingPhonenumber($request->phonenumber ?? $user->phonenumber),
                'photo'     => $photo_path,
                'ktp'       => $ktp_path,
                'nosim'     => $request->nosim,
                'sim'       => $sim_path,
                'platno'      => $platno_path,
                'vehicle_type' => $request->vehicle_type,
                'courier_specialist' => $request->courier_specialist,
                'status'    => 1,
                'status_updated_at' => new DateTime(),
            ]);
            $user->assignRole('Kurir');
            DB::commit();
            return response()->json(['message' => 'Kurir berhasil disimpan'], 200);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    public function show(Courier $courier)
    {
        //
    }

    public function edit($id)
    {
        try {
            $courier = Courier::with('user', 'cluster')->findOrFail($id);
            $courier->photo = asset('storage/' . $courier->photo);
            $courier->ktp = asset('storage/' . $courier->ktp);
            $courier->sim = asset('storage/' . $courier->sim);
            $courier->platno = asset('storage/' . $courier->platno);
            $courier->badge = config('data.courier_status')[$courier->status]['badge'];
            $data = [];
            foreach (config('data.courier_status') as $key => $value) {
                if ($key == $courier->status) continue;
                $data[] = [
                    'id'    => $key,
                    'text'  => $value['label'],
                ];
            }
            $courier->results = $data;
            return response()->json($courier, 200);
        } catch (\Throwable $th) {
            return response()->json(['message', $th->getMessage()]);
        }
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $courier = Courier::findOrFail($id);

            $photo      = $request->file('photo');
            if ($photo) {
                $photo_name = time() . '_photo_' . $photo->getClientOriginalExtension();
                $photo_path = $photo->storeAs('couriers', $photo_name, 'public');
            }

            $ktp      = $request->file('ktp');
            if ($ktp) {
                $ktp_name = time() . '_ktp_' . $ktp->getClientOriginalExtension();
                $ktp_path = $ktp->storeAs('couriers', $ktp_name, 'public');
            }

            $sim      = $request->file('sim');
            if ($sim) {
                $sim_name = time() . '_sim_' . $sim->getClientOriginalExtension();
                $sim_path = $sim->storeAs('couriers', $sim_name, 'public');
            }

            $platno      = $request->file('platno');
            if ($platno) {
                $platno_name = time() . '_platno_' . $platno->getClientOriginalExtension();
                $platno_path = $platno->storeAs('couriers', $platno_name, 'public');
            }

            $courier->update([
                'user_id'       => $request->user_id ?? $courier->user_id,
                'phonenumber'   => $this->parsingPhonenumber($request->phonenumber ?? $courier->phonenumber),
                'nik'           => $request->nik ?? $courier->nik,
                'nopol'         => $request->nopol ?? $courier->nopol,
                'photo'         => $photo_path ?? $courier->photo,
                'ktp'           => $ktp_path ?? $courier->ktp,
                'nosim'         => $request->nosim ?? $courier->nosim,
                'sim'           => $sim_path ?? $courier->sim,
                'platno'          => $platno_path ?? $courier->platno,
                'vehicle_type'  => $request->vehicle_type ?? $courier->vehicle_type,
                'courier_specialist' => $request->courier_specialist
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
            Courier::findOrFail($id)->delete();
            DB::commit();
            return response()->json(['message' => 'Data berhasil dihapus'], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    public function updateStatus(Request $request) {
        DB::beginTransaction();
        try {
            $request->validate([
                'status'    => 'required'
            ], [
                'status.required'   => 'Silahkan pilih status!',
            ]);
            $courier = Courier::find($request->id);
            $courier->status = $request->status;
            $courier->status_updated_at = now();
            $courier->user->assignRole('Kurir');
            $courier->save();

            if ($courier->status == 2) {
                $courier->wallet()->create([
                    'balance'   => 0,
                ]);
            }
            DB::commit();
            return response()->json(['message' => 'Status kurir berhasil diperbarui'], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    public function updateCluster(Request $request) {
        DB::beginTransaction();
        try {
            $request->validate([
                'new_cluster'    => 'required'
            ], [
                'new_cluster.required'   => 'Silahkan pilih status!',
            ]);
            $courier = Courier::find($request->id);
            $courier->new_cluster = $request->new_cluster;
            $courier->save();
            DB::commit();
            return response()->json(['message' => 'Cluster kurir berhasil diperbarui'], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }
}
