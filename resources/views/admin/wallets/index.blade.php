@extends('layouts.app')
@section('title', 'Wallets')
@section('page-title')
    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
        <h1 class="page-heading d-flex text-dark fw-bold flex-column justify-content-center my-0">
            Wallet Management
        </h1>
    </div>
@endsection
@section('content')
    <div class="card card-docs flex-row-fluid mb-2">
        <div class="card-header d-flex justify-content-between">
            <div class="card-title align-items-start flex-column">
                <span class="svg-icon svg-icon-1 position-absolute ms-6">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                        <path
                            d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                            fill="currentColor" />
                    </svg>
                </span>
                <input type="search" name="search" class="form-control form-control-solid ps-15 w-lg-250px w-150px" id="search" placeholder="Cari.." />
            </div>
            <div class="card-toolbar">
                <div class="d-flex flex-stack gap-3">
                    <select class="form-select w-150px" id="statusFilter" data-control="select2" data-placeholder="Semua Status" data-allow-clear="true">
                        <option></option>
                        @foreach (config('data.payment_status') as $key => $value)
                            <option value="{{ $key }}">{{ $value['label'] }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="button" class="btn btn-primary ms-3" data-bs-toggle="modal" data-bs-target="#addTransactionModal">
                    + Transaksi
                </button>
            </div>
        </div>
        <div class="card-body pt-0">
            <table id="wallets-table" class="table align-middle table-row-dashed fs-6 gy-5">
                <thead>
                    <tr class="fw-semibold fs-6 text-muted">
                        <th>No</th>
                        <th>Kurir</th>
                        <th>Tipe</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                        @role('Super Admin|Admin')
                            <th class="text-center">Actions</th>
                        @endrole
                    </tr>
                </thead>
                <tbody class="fw-semibold text-gray-600">
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" id="addTransactionModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="addTransactionForm" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h3 class="modal-title w-100 text-center">Tambah Transaksi</h3>
                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                            <span class="svg-icon svg-icon-1"><i class="bi bi-x-lg"></i></span>
                        </div>
                    </div>
                    <div class="modal-body">
                        @role('Super Admin|Admin')
                            <div class="mb-5">
                                <label for="courier_id" class="required form-label">Kurir</label>
                                <select name="courier_id" id="courier_id" class="form-select" data-control="select2">
                                </select>
                            </div>
                        @endrole
                        <div class="mb-5">
                            <label for="type" class="required form-label">Jenis Transaksi</label>
                            <select name="type" id="type" class="form-select" data-control="select2" data-hide-search="true" data-placeholder="Pilih jenis transaksi">
                                <option value=""></option>
                                @role('Super Admin|Admin')
                                    <option value="1">{{ config('data.transaction_type.1') }}</option>
                                @endrole
                                @role('Kurir')
                                    <option value="1">{{ config('data.transaction_type.1') }}</option>
                                    <option value="2">{{ config('data.transaction_type.2') }}</option>
                                @endrole
                            </select>
                        </div>
                        <div class="mb-5">
                            <label for="amount" class="required form-label">Jumlah</label>
                            <div class="input-group">
                                <span class="input-group-text" id="Rp">Rp</span>
                                <input type="number" name="amount" id="amount" class="form-control" placeholder="0" aria-label="price" aria-describedby="Rp">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-light" data-bs-dismiss="modal" id="btn_cancel_add_transaction">Batal</button>
                        <button type="submit" class="btn btn-primary me-10" id="btn_add_transaction">
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
    <div class="modal fade" tabindex="-1" id="updateStatusTransactionModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="updateStatusTransactionForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="transaction_id">
                    <div class="modal-header">
                        <h3 class="modal-title w-100 text-center">Update Status Kurir</h3>
                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                            <span class="svg-icon svg-icon-1"><i class="bi bi-x-lg"></i></span>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="mb-5">
                            <label for="vehicle_type" class="form-label">Informasi Penarikan</label>
                        </div>
                        <div class="mb-5">
                            <label for="vehicle_type" class="form-label">Nama Kurir: </label>
                            <div id="courierName"></div>
                        </div>
                        <div class="mb-5">
                            <label for="vehicle_type" class="form-label">Jumlah Penarikan: </label>
                            <div id="withdrawalAmount"></div>
                        </div>
                        <div class="mb-5">
                            <label for="vehicle_type" class="form-label">Nomor Rekening: </label>
                            <div id="accountNumber"></div>
                        </div>
                        <div class="mb-5">
                            <label for="vehicle_type" class="form-label">Rekening Atas Nama: </label>
                            <div id="accountName"></div>
                        </div>
                        <div class="mb-5">
                            <label for="status" class="required form-label">Status</label>
                            <select name="status" class="form-control form-select" id="status" data-control="select2" data-placeholder="Pilih status" data-hide-search="true">
                                <option value=""></option>
                                @foreach (config('data.payment_status') as $key => $status)
                                    <option value="{{ $key }}">{{ $status['label'] }}</option>
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
@endsection
@push('js')
    <script>
        var visible = [{}];
        @role('Kurir')
            visible = [{
                targets: [1, 5],
                visible: false
            }]
        @endrole
        var datatable = $('#wallets-table').DataTable({
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
                    data: 'courier',
                    name: 'courier',
                    orderable: true,
                    searchable: true,
                    width: '5',
                },
                {
                    data: 'type',
                    name: 'type',
                    orderable: false,
                    searchable: false,
                    width: '10'
                },
                {
                    data: 'amount',
                    name: 'amount',
                    orderable: true,
                    searchable: true,
                    width: '15'
                },
                {
                    data: 'status',
                    name: 'status',
                    orderable: true,
                    searchable: true,
                    width: '15'
                },
                {
                    data: 'actions',
                    name: 'actions',
                    orderable: false,
                    searchable: false,
                    width: '10',
                    className: 'text-center'
                },
            ],
            order: [
                [0, "asc"]
            ],
            columnDefs: visible
        });

        $('#search').on('keyup', function() {
            datatable.search(this.value).draw();
        });

        $(document).ready(function() {
            $('#courier_id').select2({
                ajax: {
                    url: "{{ route('dropdown.couriers') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        var query = {
                            search: params.term,
                            add: 0,
                        }
                        return query;
                    },
                },
                placeholder: 'Pilih Kurir',
                dropdownParent: $("#addTransactionModal  > .modal-dialog > .modal-content"),
                width: '100%',
            });
        });

        $('#addTransactionForm').submit(function(e) {
            e.preventDefault();
            $(this).find("button[type='submit']").prop('disabled', true);
            $(this).find("button[type='submit']").attr('data-kt-indicator', 'on');
            $.ajax({
                url: "{{ route('wallets.store') }}",
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
                    $('#addTransactionModal').modal('toggle');
                    datatable.ajax.reload();
                }
            });
            $(this).find("button[type='submit']").prop('disabled', false);
            $(this).find("button[type='submit']").attr('data-kt-indicator', 'off');
        })

        $(document).on('click', '#updateStatusTransaction', function() {
            $('#walletIdStatus').val('');
            $('#badge').empty();
            $('#status').find('option').each(function() {
                $(this).attr("disabled", false);
            });
            var id = $(this).data('id');
            var url = "{{ route('wallets.show', ':id') }}";
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
                    $('#transaction_id').val(data.id);
                    $('#courierName').text(data.wallet.courier.user.name);
                    $('#withdrawalAmount').text(data.amount);
                    $('#accountNumber').text(data.account_number);
                    $('#accountName').text(data.account_name);
                    $('#status').find('option').each(function() {
                        var optionValue = $(this).val();
                        if (optionValue <= data.status) {
                            $(this).attr("disabled", true);
                        }
                    });
                }
            });
        });

        $('#updateStatusTransactionForm').submit(function(e) {
            e.preventDefault();
            var id = $('#transaction_id').val();
            var url = "{{ route('wallets.update', ':id') }}";
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
                    $('#updateStatusTransactionModal').modal('toggle');
                    datatable.ajax.reload();
                }
            });
            $(this).find("button[type='submit']").prop('disabled', false);
            $(this).find("button[type='submit']").attr('data-kt-indicator', 'off');
        });
    </script>
@endpush
