@extends('layouts.app')
@section('title', 'Payment Methods')
@section('page-title')
    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
        <h1 class="page-heading d-flex text-dark fw-bold flex-column justify-content-center my-0">
            Payment Method Management
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
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMethodModal">
                    + Method
                </button>
                <div class="modal fade" tabindex="-1" id="addMethodModal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form id="addMethodForm" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-header">
                                    <h3 class="modal-title w-100 text-center">Tambah Metode</h3>
                                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                                        <span class="svg-icon svg-icon-1"><i class="bi bi-x-lg"></i></span>
                                    </div>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-5 d-flex justify-content-center">
                                        <div class="image-input image-input-outline" data-kt-image-input="true" style="background-image: url({{ asset('assets/media/svg/avatars/blank.svg') }})">
                                            <div class="image-input image-input-outline image-input-empty" data-kt-image-input="true" style="background-image: url({{ asset('assets/media/svg/avatars/blank.svg') }})">
                                                <div class="image-input-wrapper w-100px h-100px" style="background-image: none;"></div>
                                                <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" data-kt-initialized="1">
                                                    <i class="bi bi-pencil-fill fs-7"></i>
                                                    <input type="file" name="icon" accept=".png, .jpg, .jpeg">
                                                    <input type="hidden" name="icon_remove" value="1">
                                                </label>
                                                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" data-kt-initialized="1">
                                                    <i class="bi bi-x fs-2"></i>
                                                </span>
                                                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" data-kt-initialized="1">
                                                    <i class="bi bi-x fs-2"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-5">
                                        <label for="name" class="required form-label">Nama Metode / Bank</label>
                                        <input type="text" name="name" id="name" class="form-control" placeholder="Nama Metode / Bank" required />
                                    </div>
                                    <div class="mb-5">
                                        <label for="type" class="form-label required">Verifikasi</label>
                                        <div class="d-flex align-items-center mt-2">
                                            <label for="description" class="form-label"></label>
                                            <label class="form-check form-check-inline form-check-solid me-5">
                                                <input class="form-check-input" name="verification" type="radio" value="0" required checked>
                                                <span class="fw-semibold ps-2 fs-6">Manual</span>
                                            </label>
                                            <label class="form-check form-check-inline form-check-solid">
                                                <input class="form-check-input" name="verification" type="radio" value="1" required>
                                                <span class="fw-semibold ps-2 fs-6">Otomatis</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="mb-5">
                                        <label for="name" class="required form-label">Biaya</label>
                                        <input type="text" name="cost" id="cost" class="form-control" placeholder="Biaya" required />
                                    </div>
                                    <div class="mb-5">
                                        <label for="name" class="form-label">Nomor Rekening</label>
                                        <input type="text" name="account_number" id="account_number" class="form-control" placeholder="Nomor rekening" />
                                    </div>
                                    <div class="mb-5">
                                        <label for="name" class="form-label">Nama Pemegang Rekening</label>
                                        <input type="text" name="account_name" id="account_name" class="form-control" placeholder="Nama pemegang rekening" />
                                    </div>
                                    <div class="mb-5">
                                        <label for="available" class="form-label required">Available</label>
                                        <div class="form-check form-check-solid form-switch form-check-custom fv-row">
                                            <input class="form-check-input w-45px h-30px" type="checkbox" id="available" name="available" checked value="1">
                                            <label class="form-check-label" for="available"></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="reset" class="btn btn-light" data-bs-dismiss="modal" id="btn_cancel_add_method">Batal</button>
                                    <button type="submit" class="btn btn-primary me-10" id="btn_add_method">
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
                <div class="modal fade" tabindex="-1" id="editMethodModal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form id="editMethodForm" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <input type="hidden" id="methodId">
                                <div class="modal-header">
                                    <h3 class="modal-title w-100 text-center">Edit Metode</h3>
                                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                                        <span class="svg-icon svg-icon-1"><i class="bi bi-x-lg"></i></span>
                                    </div>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-5 d-flex justify-content-center">
                                        <div class="image-input image-input-outline" data-kt-image-input="true" style="background-image: url('assets/media/svg/avatars/blank.svg')">
                                            <div class="image-input-wrapper w-125px h-125px" id="editIcon"></div>
                                            <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" data-kt-initialized="1">
                                                <i class="bi bi-pencil-fill fs-7"></i>
                                                <input type="file" name="icon" accept=".png, .jpg, .jpeg">
                                                <input type="hidden" name="icon_remove">
                                            </label>
                                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" data-kt-initialized="1">
                                                <i class="bi bi-x fs-2"></i>
                                            </span>
                                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" data-kt-initialized="1">
                                                <i class="bi bi-x fs-2"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="mb-5">
                                        <label for="name" class="required form-label">Nama Metode / Bank</label>
                                        <input type="text" name="name" id="editName" class="form-control" placeholder="Nama Metode / Bank" required />
                                    </div>
                                    <div class="mb-5">
                                        <label for="type" class="form-label required">Verifikasi</label>
                                        <div class="d-flex align-items-center mt-2">
                                            <label for="description" class="form-label"></label>
                                            <label class="form-check form-check-inline form-check-solid me-5">
                                                <input class="form-check-input" name="verification" id="editManual" type="radio" value="0" required>
                                                <span class="fw-semibold ps-2 fs-6">Manual</span>
                                            </label>
                                            <label class="form-check form-check-inline form-check-solid">
                                                <input class="form-check-input" name="verification" id="editAuto" type="radio" value="1" required>
                                                <span class="fw-semibold ps-2 fs-6">Otomatis</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="mb-5">
                                        <label for="name" class="required form-label">Biaya</label>
                                        <input type="text" name="cost" id="editCost" class="form-control" placeholder="Biaya" required />
                                    </div>
                                    <div class="mb-5">
                                        <label for="name" class="form-label">Nomor Rekening</label>
                                        <input type="text" name="account_number" id="editAccount_number" class="form-control" placeholder="Nomor rekening" />
                                    </div>
                                    <div class="mb-5">
                                        <label for="name" class="form-label">Nama Pemegang Rekening</label>
                                        <input type="text" name="account_name" id="editAccount_name" class="form-control" placeholder="Nama pemegang rekening" />
                                    </div>
                                    <div class="mb-5">
                                        <label for="available" class="form-label required">Available</label>
                                        <div class="form-check form-check-solid form-switch form-check-custom fv-row">
                                            <input class="form-check-input w-45px h-30px" type="checkbox" id="editAvailable" name="available" checked>
                                            <label class="form-check-label" for="available"></label>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="reset" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary me-10" id="btn_edit_method">
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
            </div>
        </div>
        <div class="card-body pt-0">
            <table id="methods-table" class="table align-middle table-row-dashed fs-6 gy-5">
                <thead>
                    <tr class="fw-semibold fs-6 text-muted">
                        <th>No</th>
                        <th>Icon</th>
                        <th>Nama Metode / Bank</th>
                        <th>Verifikasi</th>
                        <th>Biaya</th>
                        <th>Nama pemegang rekening</th>
                        <th>Nomor rekening</th>
                        <th>Tersedia</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="fw-semibold text-gray-600">
                </tbody>
            </table>
        </div>
    </div>

@endsection
@push('js')
    <script>
        var datatable = $('#methods-table').DataTable({
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
                    searchable: true,
                    width: '5'
                },
                {
                    data: 'icon',
                    name: 'icon',
                    orderable: false,
                    searchable: false,
                    width: '10'
                },
                {
                    data: 'name',
                    name: 'name',
                    orderable: true,
                    searchable: true,
                    width: '15'
                },
                {
                    data: 'verification',
                    name: 'verification',
                    orderable: true,
                    searchable: true,
                    width: '5'
                },
                {
                    data: 'cost',
                    name: 'cost',
                    orderable: false,
                    searchable: false,
                    width: '10'
                },
                {
                    data: 'account_name',
                    name: 'account_name',
                    orderable: true,
                    searchable: true,
                    width: '10'
                },
                {
                    data: 'account_number',
                    name: 'account_number',
                    orderable: true,
                    searchable: true,
                    width: '10'
                },
                {
                    data: 'available',
                    name: 'available',
                    orderable: true,
                    searchable: true,
                    width: '10'
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

        $('#available').on('change', function() {
            if ($(this).is(':checked')) {
                $(this).val(1);
            } else {
                $(this).val(0);
            }
        })

        $(document).ready(function() {
            $('#priceWrapper').hide();
            $('#user_id').select2({
                ajax: {
                    url: "{{ route('dropdown.users') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        var query = {
                            search: params.term,
                            add: 1,
                        }
                        return query;
                    },
                },
                placeholder: 'Pilih User',
                dropdownParent: $("#addMethodModal"),
                width: '100%'
            });
            $('#editUser_id').select2({
                ajax: {
                    url: "{{ route('dropdown.users') }}",
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
                placeholder: 'Pilih User',
                dropdownParent: $("#editMethodModal"),
                width: '100%',
            });
        });

        $('#addMethodForm').submit(function(e) {
            e.preventDefault();
            $('#btn_add_method').prop('disabled', true);
            $('#btn_add_method').attr("data-kt-indicator", "on");
            $.ajax({
                url: "{{ route('methods.store') }}",
                type: "POST",
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
                    $('#addMethodModal').modal('toggle');
                    $('#addMethodForm').trigger('reset');
                    $('#methods-table').DataTable().ajax.reload();
                }
            });
            $('#btn_add_method').prop('disabled', false);
            $('#btn_add_method').removeAttr("data-kt-indicator");
        });

        $(document).on('click', '#editMethod', function() {
            var id = $(this).data('id');
            var url = "{{ route('methods.edit', ':id') }}";
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
                    $('#methodId').val(data.id);
                    $('#editIcon').css('background-image', 'url(' + data.icon + ')');
                    $('#editName').val(data.name);
                    if (data.verification) {
                        $('#editManual').attr('checked', true);
                    } else {
                        $('#editAuto').attr('checked', true);
                    }
                    $('#editCost').val(data.cost);
                    if (data.available) {
                        $('#editAvailable').attr('checked', true);
                    } else {
                        $('#editAvailable').removeAttr('checked');
                    }
                    $('#editAccount_number').val(data.account_number);
                    $('#editAccount_name').val(data.account_name);
                }
            });
        });

        $('#editMethodForm').submit(function(e) {
            e.preventDefault();
            var id = $('#methodId').val();
            var url = "{{ route('methods.update', ':id') }}";
            $('#btn_edit_method').prop('disabled', true);
            $('#btn_edit_method').attr("data-kt-indicator", "on");
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
                    $('#editMethodModal').modal('toggle');
                    $('#methods-table').DataTable().ajax.reload();
                }
            });
            $('#btn_edit_method').prop('disabled', false);
            $('#btn_edit_method').removeAttr("data-kt-indicator");
        });

        $(document).on("click", "#deleteConfirm", function(e) {
            e.preventDefault();
            var name = $(this).data('name');
            var text = 'Apakah anda yakin ingin menghapus metode ":name"?';
            Swal.fire({
                customClass: {
                    confirmButton: 'bg-danger',
                },
                title: 'Apakah anda yakin?',
                text: text.replace(':name', name),
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Delete'
            }).then((result) => {
                if (result.isConfirmed) {
                    e.preventDefault();
                    var id = $(this).data("id");
                    var route = "{{ route('methods.destroy', ':id') }}";
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
                            $('#methods-table').DataTable().ajax.reload();
                        },
                        error: function(data) {
                            toastr.error(data.responseJSON.message, 'Error');
                        }
                    });
                }
            });
        });

        $(document).on('change', '#changeAvailable', function() {
            if ($(this).is(":checked")) {
                var checked = 1;
            } else {
                var checked = 0;
            }
            var url = "{{ route('methods.update.available') }}";
            $.ajax({
                type: 'PUT',
                url: url,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    id: $(this).data('id'),
                    available: checked,
                },
                success: function(data) {
                    toastr.success(data.message, 'Success');
                    $('#methods-table').DataTable().ajax.reload();
                },
                error: function(data) {
                    toastr.error(data.responseJSON.message, 'Error');
                }
            });
        });
    </script>
@endpush
