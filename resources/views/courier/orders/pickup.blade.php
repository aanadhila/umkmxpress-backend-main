@extends('layouts.app')
@section('title', 'Pesanan yang perlu dipickup')
@section('page-title')
    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
        <h1 class="page-heading d-flex text-dark fw-bold flex-column justify-content-center my-0">
            Pesanan yang perlu dipickup
        </h1>
    </div>
@endsection
@section('content')
    @forelse ($assignments as $assignment)
        @php
            $shipment = $assignment->shipment;
        @endphp
        <div class="card shadow-lg mb-3">
            <div class="card-header">
                <div class="card-title">Pesanan {{ $shipment->airway_bill }}</div>
                <div class="card-toolbar">
                    {!! $shipment->status != 5 ? config('data.shipment_status')[$shipment->status]['badge'] : config('data.payment_status')[$shipment->payment->status]['badge'] !!}
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-xl-6">
                        <div class="card shadow-xs mb-3">
                            @if ($shipment->status <= 3)
                                <div class="card-header">
                                    <div class="card-title">Informasi Alamat Pickup</div>
                                    <div class="card-toolbar">
                                        <a class="btn btn-primary" href="https://www.google.com/maps/dir/?api=1&destination={{ $shipment->sender->latitude }},{{ $shipment->sender->longitude }}&travelmode=driving" target="_blank">
                                            Buka Google Maps
                                        </a>
                                    </div>
                                </div>
                            @else
                                <div class="card-header">
                                    <div class="card-title">Informasi Alamat Pengantaran</div>
                                    <div class="card-toolbar">
                                        <a class="btn btn-primary" href="https://www.google.com/maps/dir/?api=1&destination={{ $shipment->recipient->latitude }},{{ $shipment->recipient->longitude }}&travelmode=driving" target="_blank">
                                            Buka Google Maps
                                        </a>
                                    </div>
                                </div>
                            @endif
                            <div class="card-body">
                                @if ($shipment->status <= 3)
                                    <a href="https://wa.me/{{ $shipment->sender->phonenumber }}" class="text-gray-900 text-hover-primary">
                                        <div class="fs-1 fw-bold">
                                            {{ $shipment->sender->name }}
                                            <a href="https://wa.me/{{ $shipment->sender->phonenumber }}" target="_blank">
                                                <span class="svg-icon svg-icon-2x svg-icon-success me-2">
                                                    <!-- WhatsApp Icon SVG -->
                                                </span>
                                            </a>
                                        </div>
                                        <div class="fs-3">{{ $shipment->sender->full_address }}, {{ $shipment->sender->phonenumber }}</div>
                                    </a>
                                    <div class="map-container rounded mt-3" style="height:250px;" data-lat="{{ $shipment->sender->latitude }}" data-lng="{{ $shipment->sender->longitude }}"></div>
                                @else
                                    <a href="https://wa.me/{{ $shipment->recipient->phonenumber }}" class="text-gray-900 text-hover-primary">
                                        <div class="fs-1 fw-bold">
                                            {{ $shipment->recipient->name }}
                                            <a href="https://wa.me/{{ $shipment->recipient->phonenumber }}" target="_blank">
                                                <span class="svg-icon svg-icon-2x svg-icon-success me-2">
                                                    <!-- WhatsApp Icon SVG -->
                                                </span>
                                            </a>
                                        </div>
                                        <div class="fs-3">{{ $shipment->recipient->full_address }}, {{ $shipment->recipient->phonenumber }}</div>
                                    </a>
                                    <div class="map-container rounded mt-3" style="height:250px;" data-lat="{{ $shipment->recipient->latitude }}" data-lng="{{ $shipment->recipient->longitude }}"></div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="card shadow-xs mb-3">
                            <div class="card-header">
                                <div class="card-title">Informasi Barang dan Tarif</div>
                            </div>
                            <div class="card-body">
                                <div class="fs-2 mb-3">
                                    @foreach ($shipment->items as $item)
                                        {{ $item->name }} x {{ $item->amount }}
                                    @endforeach
                                </div>
                                <div class="separator separator-dashed border-gray-500 mb-3"></div>
                                <div class="fs-2">
                                    Tarif
                                    @if ($shipment->cluster)
                                        <span class="fw-bold">{{ $shipment->cost_type == 'next_day' ? 'Same Day - Rp ' . number_format($shipment->cluster->next_day_cost, 0, ',', '.') : 'Instant Courier - Rp ' . number_format($shipment->cluster->instant_courier_cost, 0, ',', '.') }}</span>
                                    @elseif ($shipment->special_cost)
                                        <span class="fw-bold">{{ $shipment->special_cost->origin->name }} - {{ $shipment->special_cost->destination->name }}</span>
                                    @elseif ($shipment->expedition)
                                        <span class="fw-bold">{{ $shipment->distance }} Km</span>
                                    @endif
                                </div>
                                <div class="fs-2">
                                    <span class="fw-bold">Tarif Total Rp {{ number_format($shipment->total_price, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="card shadow-xs mb-3">
                            @if ($shipment->status <= 3)
                                <div class="card-header">
                                    <div class="card-title">Informasi Alamat Pengantaran</div>
                                </div>
                                <div class="card-body">
                                    <div class="fs-1 fw-bold">{{ $shipment->recipient->name }}
                                        <a href="https://wa.me/{{ $shipment->recipient->phonenumber }}" target="_blank">
                                            <span class="svg-icon svg-icon-2x svg-icon-success me-2">
                                                <!-- WhatsApp Icon SVG -->
                                            </span>
                                        </a>
                                    </div>
                                    <div class="fs-3">{{ $shipment->recipient->full_address }}, {{ $shipment->recipient->phonenumber }}</div>
                                </div>
                            @else
                                <div class="card-header">
                                    <div class="card-title">Informasi Alamat Pickup</div>
                                </div>
                                <div class="card-body">
                                    <a href="https://wa.me/{{ $shipment->sender->phonenumber }}" class="text-gray-900 text-hover-primary">
                                        <div class="fs-1 fw-bold">
                                            {{ $shipment->sender->name }}
                                            <a href="https://wa.me/{{ $shipment->sender->phonenumber }}" target="_blank">
                                                <span class="svg-icon svg-icon-2x svg-icon-success me-2">
                                                    <!-- WhatsApp Icon SVG -->
                                                </span>
                                            </a>
                                        </div>
                                        <div class="fs-3">{{ $shipment->sender->full_address }}, {{ $shipment->sender->phonenumber }}</div>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="float-end">
                    @if ($shipment->status == 1)
                        <form action="{{ route('testing.orders.pickup') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="shipment_id" value="{{ $shipment->id }}">
                            <button type="submit" class="btn btn-primary">Pickup Pesanan {{ $shipment->airway_bill }}</button>
                        </form>
                    @elseif ($shipment->status == 2)
                        <button class="btn btn-primary" id="pickupShipment" data-id="{{ $shipment->id }}" data-airway-bill="{{ $shipment->airway_bill }}" data-bs-toggle="modal" data-bs-target="#pickupModal">Foto pesanan</button>
                    @elseif ($shipment->status == 3)
                        <form action="{{ route('testing.orders.pickup') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="shipment_id" value="{{ $shipment->id }}">
                            <button type="submit" class="btn btn-primary">Antarkan pesanan {{ $shipment->airway_bill }}</button>
                        </form>
                    @elseif ($shipment->status == 4)
                        <button class="btn btn-primary" id="deliveredShipment" data-id="{{ $shipment->id }}" data-airway-bill="{{ $shipment->airway_bill }}" data-bs-toggle="modal" data-bs-target="#deliveredModal">Selesaikan pesanan</button>
                    @elseif ($shipment->status == 5 && $shipment->payment->status == 1)
                        <form action="{{ route('testing.orders.pickup') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="shipment_id" value="{{ $shipment->id }}">
                            <button type="submit" class="btn btn-primary">Selesaikan Pembayaran {{ $shipment->airway_bill }}</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="row">
            <div class="col">
                <div class="card shadow-xs">
                    <div class="card-body">
                        <div class="text-center fs-3 fw-bold">
                            Belum ada pesanan!
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforelse

    <!-- Modal for Pickup -->
    <div class="modal fade" tabindex="-1" id="pickupModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('testing.orders.pickup') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h3 class="modal-title">Pickup Pesanan <span class="airway_bill"></span></h3>
                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                            <span class="svg-icon svg-icon-1"></span>
                        </div>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="shipment_id" class="shipment_id">
                        <div class="form-group">
                            <label for="pickup_photo" class="required form-label">Foto Pickup Barang</label>
                            <div class="d-flex justify-content-center">
                                <div class="image-input image-input-outline text-center" data-kt-image-input="true" style="background-image: url({{ asset('assets/media/svg/avatars/blank.svg') }})">
                                    <div class="image-input image-input-outline image-input-empty" data-kt-image-input="true" style="background-image: url({{ asset('assets/media/svg/avatars/blank.svg') }})">
                                        <div class="image-input-wrapper w-450px h-450px" style="background-image: none;"></div>
                                        <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" data-kt-initialized="1">
                                            <i class="bi bi-pencil-fill fs-7"></i>
                                            <input type="file" name="pickup_photo" accept=".png, .jpg, .jpeg" required>
                                            <input type="hidden" name="pickup_photo_remove" value="1">
                                        </label>
                                        <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" data-kt-initialized="1">
                                            <i class="bi bi-x fs-2"></i>
                                        </span>
                                        <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" data-kt-initialized="1">
                                            <i class="bi bi-x fs-2"></i>
                                        </span>
                                    </div>
                                    <div class="form-text">Max. 5MB</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batalkan</button>
                        <button type="submit" class="btn btn-primary">Pickup</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal for Delivered -->
    <div class="modal fade" tabindex="-1" id="deliveredModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('testing.orders.pickup') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h3 class="modal-title">Selesaikan Pesanan <span class="airway_bill"></span></h3>
                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                            <span class="svg-icon svg-icon-1"></span>
                        </div>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="shipment_id" class="shipment_id">
                        <div class="form-group">
                            <label for="delivered_photo" class="required form-label">Foto Pengiriman Barang</label>
                            <div class="d-flex justify-content-center">
                                <div class="image-input image-input-outline text-center" data-kt-image-input="true" style="background-image: url({{ asset('assets/media/svg/avatars/blank.svg') }})">
                                    <div class="image-input image-input-outline image-input-empty" data-kt-image-input="true" style="background-image: url({{ asset('assets/media/svg/avatars/blank.svg') }})">
                                        <div class="image-input-wrapper w-450px h-450px" style="background-image: none;"></div>
                                        <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" data-kt-initialized="1">
                                            <i class="bi bi-pencil-fill fs-7"></i>
                                            <input type="file" name="delivered_photo" accept=".png, .jpg, .jpeg" required>
                                            <input type="hidden" name="delivered_photo_remove" value="1">
                                        </label>
                                        <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" data-kt-initialized="1">
                                            <i class="bi bi-x fs-2"></i>
                                        </span>
                                        <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" data-kt-initialized="1">
                                            <i class="bi bi-x fs-2"></i>
                                        </span>
                                    </div>
                                    <div class="form-text">Max. 5MB</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batalkan</button>
                        <button type="submit" class="btn btn-primary">Selesaikan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        function initMap() {
            var customIcon = {
                url: "{{ asset('assets/img/driver-icon.png') }}",
                scaledSize: new google.maps.Size(32, 32),
                origin: new google.maps.Point(0, 0),
                anchor: new google.maps.Point(16, 16)
            };

            navigator.geolocation.getCurrentPosition(function(position) {
                const origin = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };

                $('.map-container').each(function() {
                    const lat = parseFloat($(this).data('lat'));
                    const lng = parseFloat($(this).data('lng'));

                    const destination = {
                        lat: lat,
                        lng: lng
                    };

                    const map = new google.maps.Map($(this)[0], {
                        center: origin,
                        zoom: 12
                    });

                    const currentLocationMarker = new google.maps.Marker({
                        position: origin,
                        map: map,
                        title: 'Lokasi Saat Ini',
                        icon: customIcon
                    });

                    const directionsService = new google.maps.DirectionsService();
                    const directionsDisplay = new google.maps.DirectionsRenderer();

                    directionsDisplay.setMap(map);

                    const request = {
                        origin: origin,
                        destination: destination,
                        travelMode: 'DRIVING'
                    };

                    directionsService.route(request, function(result, status) {
                        if (status === 'OK') {
                            directionsDisplay.setDirections(result);
                        }
                    });
                });
            }, function(error) {
                console.error(error);
            });
        }

        $(document).on('click', '#pickupShipment, #deliveredShipment', function() {
            $('.airway_bill').text($(this).data('airway-bill'));
            $('.shipment_id').val($(this).data('id'));
        });

        @if (Session::has('success'))
            toastr.success("{{ session('success') }}", 'Success');
        @endif

        @if (Session::has('error'))
            toastr.error("{{ session('error') }}", 'Error');
        @endif

        @if (Session::has('errors'))
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: "@foreach ($errors->all() as $error){{ $error }}@endforeach",
            })
        @endif

        @if (Session::has('info'))
            toastr.info("{{ session('info') }}", 'Info');
        @endif

        @if (Session::has('warning'))
            toastr.warning("{{ session('warning') }}", 'Warning');
        @endif
    </script>
@endpush

@include('components.gmaps', ['function' => 'initMap'])
