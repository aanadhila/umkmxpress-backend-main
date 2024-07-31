<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class PaymentMethodController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $paymentMethod = PaymentMethod::get();
            return DataTables::of($paymentMethod)
                ->editColumn('available', function ($item) {
                    $checked = $item->available ? 'checked' : '';
                    return '<div class="form-check form-check-solid form-switch form-check-custom fv-row">
                                <input class="form-check-input w-45px h-30px" type="checkbox" id="changeAvailable" data-id="' . $item->id . '" ' . $checked . '>
                                <label class="form-check-label" for="available"></label>
                            </div>';
                })
                ->editColumn('verification', function ($item) {
                    return $item->verification ? 'Otomatis' : 'Manual';
                })
                ->editColumn('icon', function ($item) {
                    return '<img alt="' . $item->name . ' icon" src="' . $item->pict . '" class="w-75px" />';;
                })
                ->editColumn('cost', function ($item) {
                    return 'Rp ' . number_format(intval($item->cost), 0, ',', '.');
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
                                        <a class="menu-link px-3" id="editMethod" data-bs-toggle="modal" data-bs-target="#editMethodModal" data-id="' . $item->id . '">Edit</a>
                                    </div>
                                    <div class="menu-item px-3">
                                        <a href="" class="menu-link px-3" id="deleteConfirm" data-id="' . $item->id . '" data-name="' . $item->name . '">Hapus</a>
                                    </div>
                                </div>
                            </div>';
                })
                ->rawColumns(['icon', 'available', 'actions'])
                ->addIndexColumn()
                ->make();
        }
        return view('admin.paymentMethods.index');
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            if (!file_exists(storage_path('app/public/icons/expeditions/'))) {
                mkdir(storage_path('app/public/icons/expeditions'), 775, true);
                chmod(storage_path('app/public/icons/expeditions'), 0775);
            }

            if ($request->file('icon')) {
                // Logo Square
                $icon       = $request->file('icon');
                $ext        = $icon->getClientOriginalExtension();
                $fileName   = basename($icon->getClientOriginalName(), '.' . $ext);
                $name       = time() . '_' . Str::slug($fileName) . '.png';
                $icon_path  = 'icons/expeditions/' . $name;
                $path   = storage_path('app/public/icons/expeditions/') . $name;
                $canvas = Image::canvas(100, 100);
                $image  = Image::make($icon)->resize(100, 100, function ($constraint) {
                    $constraint->aspectRatio();
                })->orientate();
                $canvas->insert($image, 'center');
                $canvas->save($path);
            }
            PaymentMethod::create([
                'name'          => $request->name,
                'verification'  => $request->verification,
                'cost'          => $request->cost,
                'available'     => $request->available,
                'account_number' => $request->account_number,
                'account_name'  => $request->account_name,
                'icon'          => $icon_path,
            ]);
            DB::commit();
            return response()->json(['message' => 'Data berhasil disimpan!'], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    public function show(PaymentMethod $paymentMethod)
    {
        //
    }

    public function edit($id)
    {
        try {
            $paymentMethod = PaymentMethod::findOrFail($id);
            $paymentMethod->icon = $paymentMethod->pict;
            return response()->json($paymentMethod, 200);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500);
        }
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $paymentMethod = PaymentMethod::find($id);
            if ($request->file('icon')) {
                Storage::disk('public')->delete($paymentMethod->icon);
                // Logo Square
                $icon       = $request->file('icon');
                $ext        = $icon->getClientOriginalExtension();
                $fileName   = basename($icon->getClientOriginalName(), '.' . $ext);
                $name       = time() . '_' . Str::slug($fileName) . '.png';
                $icon_path  = 'icons/expeditions/' . $name;
                $path   = storage_path('app/public/icons/expeditions/') . $name;
                $canvas = Image::canvas(100, 100);
                $image  = Image::make($icon)->resize(100, 100, function ($constraint) {
                    $constraint->aspectRatio();
                })->orientate();
                $canvas->insert($image, 'center');
                $canvas->save($path);
            }
            $paymentMethod->update([
                'name'          => $request->name ?? $paymentMethod->name,
                'verification'  => $request->verification ?? $paymentMethod->verification,
                'cost'          => $request->cost ?? $paymentMethod->cost,
                'available'     => $request->available == 'on' ? 1 : 0,
                'account_number' => $request->account_number ?? $paymentMethod->account_number,
                'account_name'  => $request->account_name ?? $paymentMethod->account_name,
                'icon'          => $request->file('icon') ? $icon_path : $paymentMethod->icon,
            ]);
            DB::commit();
            return response()->json(['message' => 'Data berhasil diupdate!'], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $paymentMethod = PaymentMethod::find($id);
            if ($paymentMethod->payments()->count() > 0) return response()->json(['message' => 'Metode pembayaran ini memiliki data pembayaran!'], 500);
            Storage::disk('public')->delete($paymentMethod->icon);
            $paymentMethod->delete();
            DB::commit();
            return response()->json(['message' => 'Data berhasil dihapus'], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    public function changeAvailable(Request $request)
    {
        DB::beginTransaction();
        try {
            PaymentMethod::findOrFail($request->id)->update([
                'available' => $request->available ? 1 : 0,
            ]);
            DB::commit();
            return response()->json(['message' => 'Payment method berhasil diperbarui'], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }
}
