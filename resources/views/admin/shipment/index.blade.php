@extends('layouts.app')
@section('title', 'Shipments')
@section('page-title')
    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
        <h1 class="page-heading d-flex text-dark fw-bold flex-column justify-content-center my-0">
            Shipment Management
        </h1>
    </div>
@endsection
@section('content')
    <div class="card card-docs flex-row-fluid mb-2">
        <div class="card-header d-flex justify-content-between">
            <div class="card-title align-items-start flex-column">
                <span class="svg-icon svg-icon-1 position-absolute ms-6">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1"
                            transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                        <path
                            d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                            fill="currentColor" />
                    </svg>
                </span>
                <input type="search" name="search" class="form-control form-control-solid ps-15 w-lg-250px w-150px"
                    id="search" placeholder="Cari.." />
            </div>
            <div class="card-toolbar">
                <div class="d-flex flex-stack gap-3">
                    <select class="form-select w-200px" id="statusFilter" data-control="select2"
                        data-placeholder="Semua Status" data-allow-clear="true">
                        <option></option>
                        @foreach (config('data.shipment_status') as $key => $value)
                            <option value="{{ $key }}">{{ $value['label'] }}</option>
                        @endforeach
                    </select>
                    <a class="btn btn-primary" href="{{ route('shipments.create') }}">+Shipment</a>
                </div>
            </div>
        </div>
        <div class="card-body pt-0">
            <table id="shipments-table" class="table align-middle table-row-dashed fs-6 gy-5">
                <thead>
                    <tr class="fw-semibold fs-6 text-muted">
                        <th>No</th>
                        <th>No. Resi</th>
                        <th>Platform</th>
                        <th>Pengirim</th>
                        <th>Penerima</th>
                        <th>Jarak/Cluster/Tarif Khusus</th>
                        <th>Tangal Pengiriman</th>
                        <th>Waktu Pengiriman</th>
                        <th>Biaya Kirim</th>
                        <th>Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="fw-semibold text-gray-600">
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" id="updateStatusShipmentModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="updateStatusShipmentForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="shipmentIdStatus" name="id">
                    <div class="modal-header">
                        <h3 class="modal-title w-100 text-center">Update Status Pengiriman</h3>
                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                            aria-label="Close">
                            <span class="svg-icon svg-icon-1"><i class="bi bi-x-lg"></i></span>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="mb-5">
                            <label for="shipmentAirwayBill" class="form-label">Nomor Resi</label>
                            <div id="shipmentAirwayBill"></div>
                        </div>
                        <div class="mb-5">
                            <label for="shipmentStatus" class="form-label">Status saat ini</label>
                            <div id="shipmentStatus"></div>
                        </div>
                        <div class="mb-5">
                            <label for="status" class="required form-label">Status</label>
                            <select name="status" class="form-control form-select" id="editStatus" data-control="select2"
                                data-placeholder="Pilih status" data-hide-search="true">
                                <option value=""></option>
                                @foreach (config('data.shipment_status') as $key => $value)
                                    <option value="{{ $key }}">{{ $value['label'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary me-10" id="btn_edit_courier">
                            <span class="indicator-label">
                                Simpan
                            </span>
                            <span class="indicator-progress">
                                Tunggu <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" id="shipmentItemsModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title w-100 text-center">Barang Pesanan "<span id="airway_bill"></span>"</h3>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                        aria-label="Close">
                        <span class="svg-icon svg-icon-1"><i class="bi bi-x-lg"></i></span>
                    </div>
                </div>
                <div class="modal-body">
                    @include('admin.shipment.items')
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        @if (Session::has('success'))
            toastr.success("{{ session('success') }}", 'Success');
        @endif
        var datatable = $('#shipments-table').DataTable({
            processing: true,
            serverSide: true,
            ordering: true,
            stateSave: false,
            ajax: {
                url: '{!! url()->current() !!}',
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: true,
                    searchable: false,
                    width: '5'
                },
                {
                    data: 'airway_bill',
                    name: 'airway_bill',
                    orderable: true,
                    searchable: true,
                    width: '5',
                },
                {
                    data: 'platform',
                    name: 'platform',
                    orderable: false,
                    searchable: false,
                    width: '10'
                },
                {
                    data: 'pengirim',
                    name: 'pengirim',
                    orderable: true,
                    searchable: true,
                    width: '15'
                },
                {
                    data: 'penerima',
                    name: 'penerima',
                    orderable: true,
                    searchable: true,
                    width: '15'
                },
                {
                    data: 'distance',
                    name: 'distance',
                    orderable: true,
                    searchable: true,
                    width: '5'
                },
                {
                    data: 'created_at',
                    name: 'created_at',
                    orderable: true,
                    searchable: true,
                    width: '5',
                    render: function(data) {
                        return moment(data).format('YYYY-MM-DD');
                    }
                },
                {
                    data: 'shipping_time',
                    name: 'shipping_time',
                    orderable: true,
                    searchable: true,
                    width: '5'
                },
                {
                    data: 'total_price',
                    name: 'total_price',
                    orderable: true,
                    searchable: true,
                    width: '5'
                },
                {
                    data: 'badge',
                    name: 'badge',
                    orderable: false,
                    searchable: false,
                    width: '5'
                },
                {
                    data: 'actions',
                    name: 'actions',
                    orderable: false,
                    searchable: false,
                    width: '5',
                    className: 'text-center'
                },
            ],
            order: [
                [0, "asc"]
            ]
        });

        $('#search').on('keyup', function() {
            datatable.search(this.value).draw();
        });

        $('#statusFilter').on('change', function() {
            datatable.ajax.url('{!! url()->current() !!}?status=' + $(this).val()).load();
            datatable.ajax.reload();
        });

        $(document).on('click', '#updateStatusShipment', function() {
            $('#shipmentIdStatus').val('');
            $('#shipmentAirwayBill').empty();
            $('#shipmentStatus').empty();
            $('#editStatus').find('option').each(function() {
                $(this).attr("disabled", false);
            });
            var id = $(this).data('id');
            var url = "{{ route('shipments.show', ':id') }}";
            $.ajax({
                url: url.replace(':id', id),
                type: "GET",
                dataType: "JSON",
                processData: false,
                contentType: false,
                cache: false,
                error: function(data) {
                    toastr.error(data.responseJSON.message, 'Error');
                },
                success: function(data) {
                    $('#shipmentIdStatus').val(data.id);
                    $('#shipmentAirwayBill').text(data.airway_bill);
                    $('#shipmentStatus').append(data.badge);
                    $('#editStatus').find('option').each(function() {
                        var optionValue = $(this).val();
                        if (optionValue <= data.status) {
                            $(this).attr("disabled", true);
                        }
                    });
                }
            });
        });

        $('#updateStatusShipmentForm').submit(function(e) {
            e.preventDefault();
            var id = $('#shipmentIdStatus').val();
            var url = "{{ route('shipments.update', ':id') }}";
            $(this).find("button[type='submit']").prop('disabled', true);
            $(this).find("button[type='submit']").attr('data-kt-indicator', 'on');
            $.ajax({
                url: url.replace(':id', id),
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: "JSON",
                processData: false,
                contentType: false,
                cache: false,
                data: new FormData(this),
                error: function(data) {
                    toastr.error(data.responseJSON.message, 'Error');
                },
                success: function(data) {
                    toastr.success(data.message, 'Sukses');
                    $('#updateStatusShipmentModal').modal('toggle');
                    datatable.ajax.reload();
                }
            });
            $(this).find("button[type='submit']").prop('disabled', false);
            $(this).find("button[type='submit']").attr('data-kt-indicator', 'off');
        });
    </script>
@endpush
