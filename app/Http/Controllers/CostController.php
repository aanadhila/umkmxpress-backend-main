<?php

namespace App\Http\Controllers;

use App\Models\Cost;
use App\Models\SpecialCost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class CostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {
            $costs = Cost::get();
            return DataTables::of($costs)
                ->editColumn('cost', function ($item) {
                    return 'Rp ' . number_format(intval($item->cost), 0, ',', '.');
                })
                ->addColumn('actions', function ($item) {
                    return '<div class="dropdown text-end">
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
                                <a class="menu-link px-3" id="editCost" data-bs-toggle="modal" data-bs-target="#editCostModal" data-id="' . $item->id . '">Edit</a>
                            </div>
                            <div class="menu-item px-3">
                                <a href="" class="menu-link px-3" id="deleteCostConfirm" data-id="' . $item->id . '" data-name="' . $item->name . '">Hapus</a>
                            </div>
                        </div>
                    </div>';
                })
                ->rawColumns(['actions'])
                ->addIndexColumn()
                ->make();
        }
        return view('admin.costs.index');
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
                'name'  => 'required',
                'cost'  => 'required',
            ]);

            Cost::create([
                'name'  => $request->name,
                'cost'  => str_replace('.', '', $request->cost),
            ]);

            DB::commit();
            return response()->json(['message' => 'Data berhasil disimpan!'], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    public function show(Cost $cost)
    {
        //
    }

    public function edit($id)
    {
        try {
            $cost = Cost::find($id);
            return response()->json($cost, 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $cost = Cost::findOrFail($id);
            $cost->update([
                'name'  => $request->name ?? $cost->name,
                'cost'  => str_replace('.', '', $request->cost ?? $cost->cost)
            ]);
            DB::commit();
            return response()->json(['message' => 'Data berhasil diupdate']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            Cost::findOrFail($id)->delete();
            DB::commit();
            return response()->json(['message' => 'Data berhasil dihapus'], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }
}
