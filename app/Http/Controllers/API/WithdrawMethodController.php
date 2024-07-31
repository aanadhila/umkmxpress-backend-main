<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\WithdrawMethod;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\API\ApiController;
use App\Http\Resources\WithdrawMethodResource;
use Illuminate\Support\Facades\Storage;

class WithdrawMethodController extends ApiController
{
    public function index()
    {
        try {
            $methods = WithdrawMethod::all();
            return $this->respondSuccess('success', WithdrawMethodResource::collection($methods));
        } catch (\Throwable $th) {
            return $this->respondInternalError($th->getMessage());
        }
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate(
                [
                    'name' => 'required',
                    'method_image' => 'required|mimes:png,jpg|max:10240',
                ],
                [
                    'name.required' => 'Nama metode tidak boleh kosong!',
                    'method_image.required' => 'Gambar metode tidak boleh kosong!',
                ]
            );
            $bank      = $request->file('method_image');
            $bank_name = time() . '_bank_' . Str::lower($request->name) . '_' . $bank->getClientOriginalExtension();
            $bank_path = $bank->storeAs('withdraws', $bank_name, 'public');
            WithdrawMethod::create([
                'name' => $request->name,
                'method_image' => $bank_path,
            ]);
            DB::commit();
            return $this->respondSuccess('Withdraw Method berhasil disimpan!');
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->respondInternalError($th->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $method = WithdrawMethod::find($id);
            if ($method == null) return $this->respondNotFound('Metode tidak ditemukan');
            return $this->respondSuccess('success', new WithdrawMethodResource($method));
        } catch (\Throwable $th) {
            return $this->respondInternalError($th->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $withdraw_method = WithdrawMethod::find($id);
            $request->validate(
                [
                    'name' => 'required',
                    'method_image' => 'required|mimes:png,jpg|max:10240',
                ],
                [
                    'name.required' => 'Nama metode tidak boleh kosong!',
                    'method_image.required' => 'Gambar metode tidak boleh kosong!',
                ]
            );
            $bank = $request->file('method_image');
            $old_image = $withdraw_method->method_image;
            Storage::disk('public')->delete($old_image);
            $bank_name = time() . '_bank_' . Str::lower($request->name) . '_' . $bank->getClientOriginalExtension();
            $bank_path = $bank->storeAs('withdraws', $bank_name, 'public');
            $withdraw_method->update(
                [
                    'name' => $request->name ?? $withdraw_method->name,
                    'method_image' => $bank_path ?? $withdraw_method->method_image,
                ]
            );
            DB::commit();
            return $this->respondSuccess('Withdraw Method berhasil diupdate!');
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->respondInternalError($th->getMessage());
        }
    }
}
