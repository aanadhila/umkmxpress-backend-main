<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Province;
use App\Models\Subdistrict;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

class LocationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $province_data = Province::pluck('name')->toArray();
        $provinces = Http::withHeaders(['key' => config('ads.rajaongkir_api_key')])->get('https://pro.rajaongkir.com/api/province')->object()->rajaongkir->results;
        foreach ($provinces as $province) {
            if (in_array($province->province, $province_data)) {
                continue;
            }
            $new_province = Province::create(['name' => $province->province]);
            $cities = Http::withHeaders(['key' => config('ads.rajaongkir_api_key')])->get('https://pro.rajaongkir.com/api/city', ['province' => $province->province_id])->object()->rajaongkir->results;
            foreach ($cities as $city) {
                $city_name = $city->type . ' ' . $city->city_name;
                $new_city = City::create([
                    'province_id'   => $new_province->id,
                    'name'          => $city_name,
                    'postal_code'   => $city->postal_code,
                ]);
                $subdistricts = Http::withHeaders(['key' => config('ads.rajaongkir_api_key')])->get('https://pro.rajaongkir.com/api/subdistrict', ['city' => $city->city_id])->object()->rajaongkir->results;
                foreach ($subdistricts as $subdistrict) {
                    Subdistrict::create([
                        'city_id'   => $new_city->id,
                        'name'      => $subdistrict->subdistrict_name,
                    ]);
                }
            }
        }
    }
}
