<?php

namespace App\Http\Controllers;

use App\Models\Cluster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Yajra\DataTables\Facades\DataTables;

class ClusterController extends Controller
{
    public function index()
    {
        try {
            // Mengirim permintaan GET ke server dan mendapatkan response
            $response = Http::get('http://127.0.0.1:5000/clusters');

            if($response->successful()){
               $json = $response->json();
               return view('admin.clusters.index', compact('json'));
            }
        } catch (\Exception $e) {
            // Menangani kesalahan jika terjadi exception
            echo "An error occurred: " . $e->getMessage();
        }

        // if (request()->ajax()) {
        //     $clusters = Cluster::get();
        //     return DataTables::of($clusters)
        //         ->editColumn('next_day_cost', function ($item) {
        //             return 'Rp ' . number_format(intval($item->next_day_cost), 0, ',', '.');
        //         })
        //         ->editColumn('instant_courier_cost', function ($item) {
        //             return 'Rp ' . number_format(intval($item->instant_courier_cost), 0, ',', '.');
        //         })
        //         ->addColumn('coverages', function ($item) {
        //             return $item->coverages()->orderBy('subdistrict_id', 'asc')->with('subdistrict')->get()->pluck('subdistrict.name')->implode(', ');
        //         })
        //         ->addColumn('actions', function ($item) {
        //             $data = [];
        //             $coverages = $item->coverages()->with('subdistrict.city.province')->get();
        //             foreach ($coverages as $coverage) {
        //                 $data[] = 'Kecamatan ' . $coverage->subdistrict->name . ', ' . $coverage->subdistrict->city->name . ', Provinsi ' . $coverage->subdistrict->city->province->name;
        //             }
        //             $dataJson = json_encode($data);
        //             return '<div class="dropdown">
        //                         <button class="btn btn-light btn-active-light-primary btn-sm" type="button" id="menu-' . $item->id . '" data-bs-toggle="dropdown" aria-expanded="false">
        //                             Actions
        //                             <span class="svg-icon svg-icon-5 m-0">
        //                                 <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
        //                                     <path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="currentColor" />
        //                                 </svg>
        //                             </span>
        //                         </button>
        //                         <div class="dropdown-menu menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-50px py-4" aria-labelledby="menu-' . $item->id . '">
        //                             <div class="menu-item px-3">
        //                                 <a class="menu-link px-3" id="editCluster" data-bs-toggle="modal" data-bs-target="#editClusterModal" data-id="' . $item->id . '">Edit</a>
        //                             </div>
        //                             <div class="menu-item px-3">
        //                                 <a class="menu-link px-3" id="showCoverages" data-bs-toggle="modal" data-bs-target="#clusterCoverageModal" data-id="' . $item->id . '" data-name="' . $item->name . '">Cakupan</a>
        //                             </div>
        //                             <div class="menu-item px-3">
        //                                 <a class="menu-link px-3" id="showCoveragesMap" data-bs-toggle="modal" data-bs-target="#clusterMapModal" data-id="' . $item->id . '" data-name="' . $item->name . '" data-coverages="' . htmlspecialchars($dataJson, ENT_QUOTES, 'UTF-8') . '">Peta</a>
        //                             </div>
        //                             <div class="menu-item px-3">
        //                                 <a href="" class="menu-link px-3" id="deleteClusterConfirm" data-id="' . $item->id . '" data-name="' . $item->name . '">Hapus</a>
        //                             </div>
        //                         </div>
        //                     </div>';
        //         })
        //         ->rawColumns(['actions'])
        //         ->addIndexColumn()
        //         ->make();
        // }

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
                'name' => 'required',
                'subdistrict_id' => 'required',
                'next_day_cost' => 'required',
                'instant_courier_cost' => 'required'
            ]);

            $cluster = Cluster::create([
                'name'  => $request->name,
                'next_day_cost' => str_replace('.', '', $request->next_day_cost),
                'instant_courier_cost'  => str_replace('.', '', $request->instant_courier_cost),
            ]);

            foreach ($request->input('subdistrict_id', []) as $subdistrict) {
                $cluster->coverages()->create([
                    'subdistrict_id'    => $subdistrict
                ]);
            }

            DB::commit();
            return response()->json(['message' => 'Data berhasil disimpan!'], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    public function show(Cluster $cluster)
    {
        //
    }

    public function edit($id)
    {
        try {
            $cost = Cluster::find($id);
            return response()->json($cost, 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $cluster = Cluster::find($id);

            $cluster->update([
                'name'  => $request->name ?? $cluster->name,
                'next_day_cost' => str_replace('.', '', $request->next_day_cost ?? $cluster->next_day_cost),
                'instant_courier_cost'  => str_replace('.', '', $request->instant_courier_cost ?? $cluster->instant_courier_cost),
            ]);

            DB::commit();
            return response()->json(['message' => 'Data berhasil disimpan!'], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $cluster = Cluster::find($id);
            if ($cluster->couriers()->count() > 0) return response()->json(['message' => 'Cluster ini memiliki driver aktif!'], 500);
            foreach ($cluster->coverages as $coverages) {
                $coverages->delete();
            }
            $cluster->delete();
            DB::commit();
            return response()->json(['message' => 'Data berhasil dihapus'], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }
}
