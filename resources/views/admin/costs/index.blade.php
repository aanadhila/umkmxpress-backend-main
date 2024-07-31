@extends('layouts.app')
@section('title', 'Costs')
@section('page-title')
    {{-- <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
        <h1 class="page-heading d-flex text-dark fw-bold flex-column justify-content-center my-0">
            Tarif
        </h1>
    </div> --}}
@endsection
@section('content')
    {{-- <div class="card card-docs flex-row-fluid mb-5">
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
                <input type="search" name="search" class="form-control form-control-solid ps-15 w-lg-250px w-150px" id="search1" placeholder="Cari.." />
            </div>
            <div class="card-toolbar">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCostModal">
                    +Tarif
                </button>
            </div>
        </div>
        <div class="card-body pt-0">
            <table id="costs-table" class="table align-middle table-row-dashed fs-6 gy-5">
                <thead>
                    <tr class="fw-semibold text-muted">
                        <th style="text-align:center;">No</th>
                        <th style="text-align:center;">Jenis Tarif</th>
                        <th style="text-align:center;">Tarif</th>
                        <th style="text-align:center;">Actions</th>
                    </tr>
                </thead>
                <tbody class="fw-semibold text-gray-600" style="text-align:center;">
                </tbody>
            </table>
        </div>
    </div> --}}
    <div class="app-toolbar py-3 py-lg-6">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-dark fw-bold flex-column justify-content-center my-0">
                Tarif Khusus
            </h1>
        </div>
    </div>
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
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSpecialCostModal">
                    +Tarif Khusus
                </button>
            </div>
        </div>
        <div class="card-body pt-0">
            <table id="special-costs-table" class="table align-middle table-row-dashed fs-6 gy-5">
                <thead>
                    <tr class="fw-semibold fs-6 text-muted">
                        <th class="text-start">No</th>
                        <th class="text-start">Kecamatan Asal</th>
                        <th class="text-start">Kecamatan Tujuan</th>
                        <th class="text-start">Tarif</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody class="fw-semibold text-gray-600" class="text-start">
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" id="addCostModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="addCostForm" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h3 class="modal-title w-100 text-center">Tambah Tarif</h3>
                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                            <span class="svg-icon svg-icon-1"><i class="bi bi-x-lg"></i></span>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="mb-5">
                            <label for="name" class="required form-label">Jenis Tarif</label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Masukkan nama jenis tarif" required />
                        </div>
                        <div class="mb-5">
                            <label for="cost" class="required form-label">Tarif</label>
                            <div class="input-group">
                                <span class="input-group-text" id="Rp">Rp</span>
                                <input type="text" name="cost" id="cost" class="form-control" placeholder="Masukkan tarif" aria-label="cost" aria-describedby="Rp" required />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary me-10" id="btn_add_courier">
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
    <div class="modal fade" tabindex="-1" id="editCostModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editCostForm" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="costId">
                    <div class="modal-header">
                        <h3 class="modal-title w-100 text-center">Edit Tarif</h3>
                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                            <span class="svg-icon svg-icon-1"><i class="bi bi-x-lg"></i></span>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="mb-5">
                            <label for="name" class="required form-label">Jenis Tarif</label>
                            <input type="text" name="name" id="editCostName" class="form-control" placeholder="Masukkan nama jenis tarif" required />
                        </div>
                        <div class="mb-5">
                            <label for="cost" class="required form-label">Tarif</label>
                            <div class="input-group">
                                <span class="input-group-text" id="Rp">Rp</span>
                                <input type="text" name="cost" id="editCostCost" class="form-control" placeholder="Masukkan tarif" aria-label="cost" aria-describedby="Rp" required />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary me-10" id="btn_add_courier">
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
    <div class="modal fade" tabindex="-1" id="addSpecialCostModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="addSpecialCostForm" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h3 class="modal-title w-100 text-center">Tambah Tarif Khusus</h3>
                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                            <span class="svg-icon svg-icon-1"><i class="bi bi-x-lg"></i></span>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="mb-5">
                            <label for="origin_subdistrict_id" class="required form-label">Kecamatan Asal</label>
                            <select type="text" name="origin_subdistrict_id" id="origin_subdistrict_id" class="form-control form-select subdistrict_id_special" data-control="select2" data-placeholder="Pilih kecamatan asal" required>
                                <option value=""></option>
                            </select>
                        </div>
                        <div class="mb-5">
                            <label for="destination_subdistrict_id" class="required form-label">Kecamatan Tujuan</label>
                            <select type="text" name="destination_subdistrict_id" id="destination_subdistrict_id" class="form-control form-select subdistrict_id_special" data-control="select2" data-placeholder="Pilih kecamatan tujuan" required>
                                <option value=""></option>
                            </select>
                        </div>
                        <div class="mb-5">
                            <label for="cost" class="required form-label">Tarif</label>
                            <div class="input-group">
                                <span class="input-group-text" id="Rp">Rp</span>
                                <input type="text" name="cost" id="cost" class="form-control" placeholder="Masukkan tarif" aria-label="cost" aria-describedby="Rp" required />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary me-10" id="btn_add_courier">
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
    <div class="modal fade" tabindex="-1" id="editSpecialCostModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editSpecialCostForm" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="specialCostId">
                    <div class="modal-header">
                        <h3 class="modal-title w-100 text-center">Edit Tarif Khusus</h3>
                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                            <span class="svg-icon svg-icon-1"><i class="bi bi-x-lg"></i></span>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="mb-5">
                            <label for="editOrigin_subdistrict_id" class="required form-label">Kecamatan Asal</label>
                            <select type="text" name="origin_subdistrict_id" id="editOrigin_subdistrict_id" class="form-control form-select editsubdistrict_id_special" data-control="select2" data-placeholder="Pilih kecamatan asal" required>
                                <option value=""></option>
                            </select>
                        </div>
                        <div class="mb-5">
                            <label for="editDestination_subdistrict_id" class="required form-label">Kecamatan Tujuan</label>
                            <select type="text" name="destination_subdistrict_id" id="editDestination_subdistrict_id" class="form-control form-select editsubdistrict_id_special" data-control="select2" data-placeholder="Pilih kecamatan tujuan" required>
                                <option value=""></option>
                            </select>
                        </div>
                        <div class="mb-5">
                            <label for="editSpecialCostCost" class="required form-label">Tarif</label>
                            <div class="input-group">
                                <span class="input-group-text" id="Rp">Rp</span>
                                <input type="text" name="cost" id="editSpecialCostCost" class="form-control" placeholder="Masukkan tarif" aria-label="cost" aria-describedby="Rp" required />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary me-10" id="btn_add_courier">
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
        var im = new Inputmask({
            alias: 'numeric',
            groupSeparator: '.',
            radixPoint: ','
        });

        var datatable1 = $('#costs-table').DataTable({
            processing: true,
            serverSide: true,
            ordering: true,
            stateSave: false,
            ajax: {
                url: '{!! url()->current() !!}',
                data: {
                    table: 'cost',
                },
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: true,
                    searchable: true,
                    width: '5'
                },
                {
                    data: 'name',
                    name: 'name',
                    orderable: true,
                    searchable: true,
                    width: '10'
                },
                {
                    data: 'cost',
                    name: 'cost',
                    orderable: true,
                    searchable: true,
                    width: '15'
                },
                {
                    data: 'actions',
                    name: 'actions',
                    orderable: false,
                    searchable: false,
                    width: '10'
                },
            ],
            order: [
                [0, "asc"]
            ]
        });

        var datatable2 = $('#special-costs-table').DataTable({
            processing: true,
            serverSide: true,
            ordering: true,
            stateSave: false,
            ajax: {
                url: "{{ route('specials.index') }}",
                data: {
                    table: 'special-cost',
                },
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: true,
                    searchable: true,
                    width: '5'
                },
                {
                    data: 'origin',
                    name: 'origin',
                    orderable: true,
                    searchable: true,
                    width: '10'
                },
                {
                    data: 'destination',
                    name: 'destination',
                    orderable: true,
                    searchable: true,
                    width: '10'
                },
                {
                    data: 'cost',
                    name: 'cost',
                    orderable: true,
                    searchable: true,
                    width: '15'
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

        $('#search1').on('keyup', function() {
            datatable1.search(this.value).draw();
        });

        $('#search2').on('keyup', function() {
            datatable2.search(this.value).draw();
        });

        $(function() {
            im.mask('#cost');
            $('.subdistrict_id').select2({
                ajax: {
                    url: "{{ route('dropdown.subdistricts') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        var query = {
                            search: params.term,
                            city_name: params.term
                        }
                        return query;
                    },
                },
                // placeholder: 'Pilih Kecamatan Asal',
                dropdownParent: $("#addSpecialCostModal"),
                width: '100%'
            });
            $('.subdistrict_id_special').select2({
                ajax: {
                    url: "{{ route('dropdown.subdistricts') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        var query = {
                            search: params.term,
                            city_name: params.term,
                            with_city_name: true,
                        }
                        return query;
                    },
                },
                // placeholder: 'Pilih Kecamatan Asal',
                dropdownParent: $("#addSpecialCostModal"),
                width: '100%'
            });
        });

        $('#addCostForm').on('submit', function(e) {
            e.preventDefault();
            $(this).find("button[type='submit']").prop('disabled', true);
            $(this).find("button[type='submit']").attr('data-kt-indicator', 'on');
            $.ajax({
                url: "{{ route('costs.store') }}",
                type: 'POST',
                dataType: 'JSON',
                processData: false,
                contentType: false,
                cache: false,
                data: new FormData(this),
                error: function(data) {
                    toastr.error(data.responseJSON.message, 'Error');
                },
                success: function(data) {
                    toastr.success(data.message, 'Sukses');
                    $('#addCostModal').modal('toggle');
                    $('#addCostForm').trigger('reset');
                    datatable1.ajax.reload();
                }
            });
            $(this).find("button[type='submit']").prop('disabled', false);
            $(this).find("button[type='submit']").attr('data-kt-indicator', 'off');
        });

        $(document).on('click', '#editCost', function () {
            var id = $(this).data('id');
            var url = "{{ route('costs.edit', ':id') }}";
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
                    $('#costId').val(id);
                    $('#editCostName').val(data.name);
                    $('#editCostCost').val(data.cost);
                    im.mask('#editCostCost');
                }
            })
        });

        $('#editCostForm').submit(function (e) {
            e.preventDefault();
            $(this).find("button[type='submit']").prop('disabled', true);
            $(this).find("button[type='submit']").attr('data-kt-indicator', 'on');
            var id = $('#costId').val();
            var url = "{{ route('costs.update', ':id') }}";
            $.ajax({
                url: url.replace(':id', id),
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'JSON',
                processData: false,
                contentType: false,
                cache: false,
                data: new FormData(this),
                error: function(data) {
                    toastr.error(data.responseJSON.message, 'Error');
                },
                success: function(data) {
                    toastr.success(data.message, 'Sukses');
                    $('#editCostModal').modal('toggle');
                    $('#editCostForm').trigger('reset');
                    datatable1.ajax.reload();
                }
            });
            $(this).find("button[type='submit']").prop('disabled', false);
            $(this).find("button[type='submit']").attr('data-kt-indicator', 'off');
        });

        $('#addSpecialCostForm').on('submit', function(e) {
            e.preventDefault();
            $(this).find("button[type='submit']").prop('disabled', true);
            $(this).find("button[type='submit']").attr('data-kt-indicator', 'on');
            $.ajax({
                url: "{{ route('specials.store') }}",
                type: 'POST',
                dataType: 'JSON',
                processData: false,
                contentType: false,
                cache: false,
                data: new FormData(this),
                error: function(data) {
                    toastr.error(data.responseJSON.message, 'Error');
                },
                success: function(data) {
                    toastr.success(data.message, 'Sukses');
                    $('#addSpecialCostModal').modal('toggle');
                    $('#addSpecialCostForm').trigger('reset');
                    datatable2.ajax.reload();
                }
            });
            $(this).find("button[type='submit']").prop('disabled', false);
            $(this).find("button[type='submit']").attr('data-kt-indicator', 'off');
        });

        $(document).on('click', '#editSpecialCost', function () {
            var id = $(this).data('id');
            var url = "{{ route('specials.edit', ':id') }}";
            $('.editsubdistrict_id').select2({
                ajax: {
                    url: "{{ route('dropdown.subdistricts') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        var query = {
                            search: params.term,
                            city_name: params.term
                        }
                        return query;
                    },
                },
                dropdownParent: $("#editSpecialCostModal"),
                width: '100%'
            });
            $('.editsubdistrict_id_special').select2({
                ajax: {
                    url: "{{ route('dropdown.subdistricts') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        var query = {
                            search: params.term,
                            city_name: params.term,
                            with_city_name: true,
                        }
                        return query;
                    },
                },
                dropdownParent: $("#editSpecialCostModal"),
                width: '100%'
            });
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
                    $('#specialCostId').val(id);
                    $('#editOrigin_subdistrict_id').append($("<option selected></option>").val(data.origin_subdistrict_id).text(data.origin.name)).trigger('change');
                    $('#editDestination_subdistrict_id').append($("<option selected></option>").val(data.destination_subdistrict_id).text(data.destination.name)).trigger('change');
                    $('#editSpecialCostCost').val(data.cost);
                    im.mask('#editSpecialCostCost');
                }
            });
        });

        $('#editSpecialCostForm').on('submit', function(e) {
            e.preventDefault();
            $(this).find("button[type='submit']").prop('disabled', true);
            $(this).find("button[type='submit']").attr('data-kt-indicator', 'on');
            var id = $('#specialCostId').val();
            var url = "{{ route('specials.update', ':id') }}";
            $.ajax({
                url: url.replace(':id', id),
                type: 'POST',
                dataType: 'JSON',
                processData: false,
                contentType: false,
                cache: false,
                data: new FormData(this),
                error: function(data) {
                    toastr.error(data.responseJSON.message, 'Error');
                },
                success: function(data) {
                    toastr.success(data.message, 'Sukses');
                    $('#editSpecialCostModal').modal('toggle');
                    $('#editSpecialCostForm').trigger('reset');
                    datatable2.ajax.reload();
                }
            });
            $(this).find("button[type='submit']").prop('disabled', false);
            $(this).find("button[type='submit']").attr('data-kt-indicator', 'off');
        });

        $(document).on("click", "#deleteCostConfirm", function(e) {
            e.preventDefault();
            Swal.fire({
                customClass: {
                    confirmButton: 'bg-danger',
                },
                title: 'Apakah anda yakin?',
                text: "Apakah anda yakin ingin menghapus data ini?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Delete'
            }).then((result) => {
                if (result.isConfirmed) {
                    e.preventDefault();
                    var id = $(this).data("id");
                    var route = "{{ route('costs.destroy', ':id') }}";
                    $.ajax({
                        url: route.replace(':id', id),
                        type: 'DELETE',
                        data: {
                            _token: $("meta[name='csrf-token']").attr("content"),
                        },
                        success: function(response) {
                            Swal.fire({
                                title: 'Success',
                                text: response.message,
                                icon: 'success',
                                confirmButtonText: 'OK',
                                timer: 1000,
                                timerProgressBar: true,
                            })
                            datatable1.ajax.reload();
                        },
                        error: function(data) {
                            toastr.error(data.responseJSON.message, 'Error');
                        }
                    });
                }
            });
        });

        $(document).on("click", "#deleteSpecialCostConfirm", function(e) {
            e.preventDefault();
            Swal.fire({
                customClass: {
                    confirmButton: 'bg-danger',
                },
                title: 'Apakah anda yakin?',
                text: "Apakah anda yakin ingin menghapus data ini?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Delete'
            }).then((result) => {
                if (result.isConfirmed) {
                    e.preventDefault();
                    var id = $(this).data("id");
                    var route = "{{ route('specials.destroy', ':id') }}";
                    $.ajax({
                        url: route.replace(':id', id),
                        type: 'DELETE',
                        data: {
                            _token: $("meta[name='csrf-token']").attr("content"),
                        },
                        success: function(response) {
                            Swal.fire({
                                title: 'Success',
                                text: response.message,
                                icon: 'success',
                                confirmButtonText: 'OK',
                                timer: 1000,
                                timerProgressBar: true,
                            })
                            datatable2.ajax.reload();
                        },
                        error: function(data) {
                            toastr.error(data.responseJSON.message, 'Error');
                        }
                    });
                }
            });
        });
    </script>
@endpush
