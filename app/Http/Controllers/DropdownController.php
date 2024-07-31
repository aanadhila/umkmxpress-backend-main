<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Cluster;
use App\Models\ClusterCoverage;
use App\Models\Courier;
use App\Models\Expedition;
use App\Models\PaymentMethod;
use App\Models\Province;
use App\Models\Recipient;
use App\Models\Sender;
use App\Models\Subdistrict;
use App\Models\User;
use Illuminate\Http\Request;

class DropdownController extends Controller
{
    public function getUsers(Request $request)
    {
        $users = User::role('User')->select('id', 'name AS text')
            ->when($request->add, function ($query) {
                $query->doesntHave('courier');
            })->where([
                ['name', 'like', '%' . $request->input('search', '') . '%']
            ])->get()->toArray();
        return response()->json(['results' => $users]);
    }

    public function getCouriers(Request $request)
    {
        $couriers = Courier::with('user')->accepted()
            ->whereRelation('user', 'name', 'like', '%' . $request->input('search', '') . '%')
            ->where([
                ['phonenumber', 'like', '%' . $request->input('search', '') . '%']
            ])->get();
        $data = [];
        foreach ($couriers as $courier) {
            $data[] = [
                'id'    => $courier->id,
                'text'  => $courier->user->name . ' - ' . $courier->phonenumber,
            ];
        }
        return response()->json(['results' => $data]);
    }

    public function getProvinces(Request $request)
    {
        $provinces = Province::select('id', 'name AS text')
            ->where([
                ['name', 'like', '%' . $request->input('search', '') . '%']
            ])->get()->toArray();
        return response()->json(['results' => $provinces]);
    }

    public function getCities(Request $request)
    {
        $cities = City::select('id', 'name AS text', 'province_id')
            ->where([
                ['name', 'like', '%' . $request->input('search', '') . '%'],
            ])
            ->when($request->province_id, function ($query) use ($request) {
                return $query->where('province_id', $request->province_id);
            })
            ->get()->toArray();
        return response()->json(['results' => $cities]);
    }

    public function getSubdistricts(Request $request)
    {
        $subdistricts = Subdistrict::select('id', 'name AS text', 'city_id')
            ->where([
                ['name', 'like', '%' . $request->input('search', '') . '%'],
            ])
            ->when($request->city_name, function ($query) use ($request) {
                return $query->orWhereRelation('city', 'name', 'like', '%' . $request->input('city_name', '') . '%');
            })
            ->when($request->city_id, function ($query) use ($request) {
                return $query->where('city_id', $request->city_id);
            })
            // ->when($request->cluster, function ($query) {
            //     $coverages = ClusterCoverage::get();
            //     return $query->whereNotIn('id', $coverages->pluck('subdistrict_id'));
            // })
            ->when($request->with_city_name, function ($query) {
                return $query->with('city');
            })
            ->get()->toArray();

            if ($request->with_city_name) {
                foreach ($subdistricts as $key => $subdistrict) {
                    $subdistricts[$key]['text'] = $subdistrict['text'] . ', ' . $subdistrict['city']['name'];
                }
            }
        return response()->json(['results' => $subdistricts]);
    }

    public function getSenders(Request $request)
    {
        $senders = Sender::where([
            ['phonenumber', 'like', '%' . $request->input('search', '') . '%']
        ])->get();
        $data = [];
        foreach ($senders as $sender) {
            $data[] = [
                'id'    => $sender->id,
                'text'  => $sender->name . ', ' . $sender->short_address,
            ];
        }
        return response()->json(['results' => $data]);
    }

    public function getRecipients(Request $request)
    {
        $recipients = Recipient::where([
            ['phonenumber', 'like', '%' . $request->input('search', '') . '%']
        ])->orderBy('default', 'desc')->get();
        $data = [];
        foreach ($recipients as $recipient) {
            $data[] = [
                'id'    => $recipient->id,
                'text'  => $recipient->name . ', ' . $recipient->short_address,
            ];
        }
        return response()->json(['results' => $data]);
    }

    public function getExpeditions(Request $request)
    {
        $expeditions = Expedition::available()
            ->where([
                ['name', 'like', '%' . $request->input('search', '') . '%'],
            ])->get();
        $data = [];
        foreach ($expeditions as $expedition) {
            $data[] = [
                'id'    => $expedition->id,
                'text'  => $expedition->name . ' - ' . 'Rp ' . number_format(intval($expedition->price ?? $expedition->price_km), 0, ',', '.'),
            ];
        }
        return response()->json(['results' => $data]);
    }

    public function getPaymentMethods(Request $request)
    {
        $paymentMethods = PaymentMethod::available()
            ->where([
                ['name', 'like', '%' . $request->input('search', '') . '%'],
            ])->get();
        $data = [];
        foreach ($paymentMethods as $method) {
            $data[] = [
                'id'    => $method->id,
                'text'  => $method->name . ' - ' . 'Rp ' . number_format($method->cost, 0, ',', '.'),
            ];
        }
        return response()->json(['results' => $data]);
    }

    public function getClusters(Request $request)
    {
        $clusters = Cluster::select('id', 'name AS text')
            ->where([
                ['name', 'like', '%' . $request->input('search', '') . '%'],
            ])->get()->toArray();
        return response()->json(['results' => $clusters]);
    }
}
