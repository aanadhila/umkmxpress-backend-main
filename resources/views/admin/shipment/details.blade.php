@extends('layouts.app')
@section('title', 'Detail Shipment')
@section('page-title')
    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
        <h1 class="page-heading d-flex text-dark fw-bold flex-column justify-content-center my-0">
            Detail Shipment
        </h1>
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-6 mb-5">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        Informasi Pengirim
                    </div>
                </div>
                <div class="card-body">
                    <div class="row px-xl-5">
                        <div class="col">
                            <label for="sender_id" class=" form-label">Data Pengirim</label>
                            <p>{{ $shipment->sender->name }}, {{ $shipment->sender->full_address }}, {{ $shipment->sender->phonenumber }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 mb-5">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        Informasi Penerima
                    </div>
                </div>
                <div class="card-body">
                    <div class="row px-xl-5">
                        <div class="col">
                            <label for="recipient_data" class=" form-label">Data Penerima</label>
                            <p>{{ $shipment->recipient->name }}, {{ $shipment->recipient->full_address }}, {{ $shipment->recipient->phonenumber }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-5">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        Informasi Pengiriman
                    </div>
                    <div class="card-toolbar">
                        {!! config('data.shipment_status')[$shipment->status]['badge'] !!}
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col mb-5">
                            <div class="fs-2 fw-bold mb-2 text-gray-700">Informasi Kurir</div>
                            @if ($shipment->courier)
                                <div class="d-flex">
                                    <div class="me-7">
                                        <img src="{{ $shipment->courier->pict }}" alt="image" class="rounded" style="height: 150px; width: auto;">
                                    </div>
                                    <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                                        <div class="d-flex flex-column">
                                            <div class="d-flex align-items-center mb-2">
                                                <span class="text-gray-900 fs-2 fw-bold">{{ $shipment->courier->user->name }}</span>
                                            </div>
                                            <div class="d-flex flex-wrap">
                                                <a href="https://wa.me/{{ $shipment->courier->phonenumber }}">
                                                    <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                                        <div class="d-flex align-items-center">
                                                            <span class="svg-icon svg-icon-3 svg-icon-success me-2">
                                                                <?xml version="1.0" ?><svg id="Layer_1" style="enable-background:new 0 0 1000 1000;" version="1.1" viewBox="0 0 1000 1000" xml:space="preserve" xmlns="http://www.w3.org/2000/svg"
                                                                    xmlns:xlink="http://www.w3.org/1999/xlink">
                                                                    <style type="text/css">
                                                                        .st0 {
                                                                            fill: #25D366;
                                                                        }

                                                                        .st1 {
                                                                            fill-rule: evenodd;
                                                                            clip-rule: evenodd;
                                                                            fill: #FFFFFF;
                                                                        }
                                                                    </style>
                                                                    <title />
                                                                    <g>
                                                                        <path class="st0" d="M500,1000L500,1000C223.9,1000,0,776.1,0,500v0C0,223.9,223.9,0,500,0h0c276.1,0,500,223.9,500,500v0   C1000,776.1,776.1,1000,500,1000z" />
                                                                        <g>
                                                                            <g id="WA_Logo">
                                                                                <g>
                                                                                    <path class="st1"
                                                                                        d="M733.9,267.2c-62-62.1-144.6-96.3-232.5-96.4c-181.1,0-328.6,147.4-328.6,328.6      c0,57.9,15.1,114.5,43.9,164.3L170.1,834l174.2-45.7c48,26.2,102,40,157,40h0.1c0,0,0,0,0,0c181.1,0,328.5-147.4,328.6-328.6      C830.1,411.9,796,329.3,733.9,267.2z M501.5,772.8h-0.1c-49,0-97.1-13.2-139-38.1l-10-5.9L249,755.9l27.6-100.8l-6.5-10.3      c-27.3-43.5-41.8-93.7-41.8-145.4c0.1-150.6,122.6-273.1,273.3-273.1c73,0,141.5,28.5,193.1,80.1c51.6,51.6,80,120.3,79.9,193.2      C774.6,650.3,652,772.8,501.5,772.8z M651.3,568.2c-8.2-4.1-48.6-24-56.1-26.7c-7.5-2.7-13-4.1-18.5,4.1      c-5.5,8.2-21.2,26.7-26,32.2c-4.8,5.5-9.6,6.2-17.8,2.1c-8.2-4.1-34.7-12.8-66-40.8c-24.4-21.8-40.9-48.7-45.7-56.9      c-4.8-8.2-0.5-12.7,3.6-16.8c3.7-3.7,8.2-9.6,12.3-14.4c4.1-4.8,5.5-8.2,8.2-13.7c2.7-5.5,1.4-10.3-0.7-14.4      c-2.1-4.1-18.5-44.5-25.3-61c-6.7-16-13.4-13.8-18.5-14.1c-4.8-0.2-10.3-0.3-15.7-0.3c-5.5,0-14.4,2.1-21.9,10.3      c-7.5,8.2-28.7,28.1-28.7,68.5c0,40.4,29.4,79.5,33.5,84.9c4.1,5.5,57.9,88.4,140.3,124c19.6,8.5,34.9,13.5,46.8,17.3      c19.7,6.3,37.6,5.4,51.7,3.3c15.8-2.4,48.6-19.9,55.4-39c6.8-19.2,6.8-35.6,4.8-39C665,574.4,659.5,572.4,651.3,568.2z" />
                                                                                </g>
                                                                            </g>
                                                                        </g>
                                                                    </g>
                                                                </svg>
                                                            </span>
                                                            <div class="fs-2 fw-bold counted text-gray-900 text-hover-primary me-0">+{{ $shipment->courier->phonenumber }}</div>
                                                        </div>
                                                        <div class="fw-semibold fs-6 text-gray-400">WhatsApp</div>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                Belum ada kurir
                            @endif
                        </div>
                        <div class="col">
                            <div class="fs-2 fw-bold mb-2 text-gray-700">Informasi Wilayah</div>
                            <div class="text-gray-900 mb-2 fs-5">
                                @if ($shipment->cluster)
                                    Cluster <span class="fw-bold">{{ $shipment->cluster->name }}</span>
                                @elseif ($shipment->special_cost)
                                    Tarif Khusus <span class="fw-bold">{{ $shipment->special_cost->origin->name }} - {{ $shipment->special_cost->destination->name }}</span>
                                @elseif ($shipment->expedition)
                                    Jarak <span class="fw-bold">{{ $shipment->distance }} Km</span>
                                @endif
                            </div>
                            <div class="text-gray-900 mb-2 fs-5">
                                Tarif
                                @if ($shipment->cluster)
                                    <span
                                        class="fw-bold">{{ $shipment->cost_type == 'next_day' ? 'Same Day - Rp ' . number_format($shipment->cluster->next_day_cost, 0, ',', '.') : 'Instant Courier - Rp ' . number_format($shipment->cluster->instant_courier_cost, 0, ',', '.') }}</span>
                                @elseif ($shipment->special_cost)
                                    <span class="fw-bold">{{ $shipment->special_cost->origin->name }} - {{ $shipment->special_cost->destination->name }} - {{ number_format($shipment->special_cost->cost, 0, ',', '.') }}</span>
                                @elseif ($shipment->expedition)
                                    <span class="fw-bold">{{ $shipment->expedition->name }} - Rp {{ number_format($shipment->total_price, 0, ',', '.') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-5">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        Informasi Paket
                    </div>
                </div>
                <div class="card-body">
                    @include('admin.shipment.items')
                </div>
                <div class="card-footer">
                    <div class="float-end">
                        <a href="{{ route('shipments.index') }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-5">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="card-header">
                        <div class="card-title">
                            Tracker
                        </div>
                    </div>
                    @forelse ($shipment->trackers()->latest()->get() as $trackers)
                        <div class="row {{ $loop->first ? 'mb-5 pt-5' : ($loop->last ? 'mt-5 pb-5' : 'my-5') }} px-10">
                            <div class="col-xl-2 mb-3 mb-xl-0">
                                {!! config('data.shipment_status')[$trackers->status]['badge'] !!}
                            </div>
                            <div class="col-xl-7 mb-3 mb-xl-0">
                                {{ $trackers->note }}
                            </div>
                            <div class="col-xl-2 mb-3 mb-xl-0">
                                {{ $trackers->created_at->format('d F Y H:i') }}
                            </div>
                            <div class="col-xl-1 mb-3 mb-xl-0">
                                @if ($trackers->status == 3)
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#pickup">
                                        <span class="svg-icon-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20">
                                                <path fill="currentColor" d="M16 2H7.979C6.88 2 6 2.88 6 3.98V12c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 10H8V4h8v8zM4 10H2v6c0 1.1.9 2 2 2h6v-2H4v-6z" />
                                            </svg>
                                        </span>
                                    </a>
                                    <div class="modal fade" tabindex="-1" id="pickup">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h3 class="modal-title">Foto Pickup</h3>
                                                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                                                        <span class="svg-icon svg-icon-1"></span>
                                                    </div>
                                                </div>

                                                <div class="modal-body" style="height: 500px">
                                                    <div class="bgi-no-repeat bgi-position-center bgi-size-cover card-rounded min-h-400px min-h-sm-100 h-100"
                                                        style="background-size: 100% 100%; background-image: url('{{ $shipment->pickup_pict }}')">
                                                    </div>
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if ($trackers->status == 5)
                                <a href="#" data-bs-toggle="modal" data-bs-target="#delivery">
                                    <span class="svg-icon-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20">
                                            <path fill="currentColor" d="M16 2H7.979C6.88 2 6 2.88 6 3.98V12c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 10H8V4h8v8zM4 10H2v6c0 1.1.9 2 2 2h6v-2H4v-6z" />
                                        </svg>
                                    </span>
                                </a>
                                <div class="modal fade" tabindex="-1" id="delivery">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h3 class="modal-title">Foto Delivered</h3>
                                                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                                                    <span class="svg-icon svg-icon-1"></span>
                                                </div>
                                            </div>

                                            <div class="modal-body" style="height: 500px">
                                                <div class="bgi-no-repeat bgi-position-center bgi-size-cover card-rounded min-h-400px min-h-sm-100 h-100"
                                                    style="background-size: 100% 100%; background-image: url('{{ $shipment->delivered_pict }}')">
                                                </div>
                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        @if (!$loop->last)
                            <div class="separator"></div>
                        @endif
                    @empty
                        <div class="justify-center">
                            Belum ada pengiriman
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Peta</div>
                </div>
                <div class="card-body">
                    <div class="rounded">
                        <div id="map" style="height: 1000px"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        function initMap() {
            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 10,
                center: {
                    lat: -7.797068,
                    lng: 110.370529
                }
            });

            var directionsService = new google.maps.DirectionsService();
            var directionsDisplay = new google.maps.DirectionsRenderer({
                map: map
            });

            var request = {
                origin: "{{ $shipment->sender->latitude }},{{ $shipment->sender->longitude }}",
                destination: "{{ $shipment->recipient->latitude }},{{ $shipment->recipient->longitude }}",
                travelMode: 'DRIVING' // Mode perjalanan
            };

            directionsService.route(request, function(response, status) {
                if (status === 'OK') {
                    directionsDisplay.setDirections(response);
                } else {
                    window.alert('Gagal memuat rute. Error: ' + status);
                }
            });
        }
    </script>
@endpush
@include('components.gmaps', ['function' => 'initMap'])
