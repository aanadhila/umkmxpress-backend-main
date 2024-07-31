@extends('layouts.app')
@section('title', 'Expeditions')
@section('page-title')
    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
        <h1 class="page-heading d-flex text-dark fw-bold flex-column justify-content-center my-0">
            Expedition Management
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
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addExpeditionModal">
                    + Ekspedisi
                </button>
                <div class="modal fade" tabindex="-1" id="addExpeditionModal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form id="addExpeditionForm" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-header">
                                    <h3 class="modal-title w-100 text-center">Tambah Ekspedisi</h3>
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
                                        <label for="name" class="required form-label">Nama Ekspedisi</label>
                                        <input type="text" name="name" id="name" class="form-control" placeholder="Nama Ekspedisi" required />
                                    </div>
                                    <div class="mb-5">
                                        <label for="description" class="form-label">Deskripsi</label>
                                        <input type="text" name="description" id="description" class="form-control" placeholder="Deskripsi" />
                                    </div>
                                    <div class="mb-5">
                                        <label for="type" class="form-label required">Jenis Harga</label>
                                        <div class="d-flex align-items-center mt-2">
                                            <label for="description" class="form-label"></label>
                                            <label class="form-check form-check-inline form-check-solid me-5">
                                                <input class="form-check-input" name="price_type" type="radio" value="price_km" required>
                                                <span class="fw-semibold ps-2 fs-6">Harga per Km</span>
                                            </label>
                                            <label class="form-check form-check-inline form-check-solid">
                                                <input class="form-check-input" name="price_type" type="radio" value="price" required>
                                                <span class="fw-semibold ps-2 fs-6">Harga</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="mb-5" id="priceWrapper">
                                        <label for="price" class="required form-label">Harga</label>
                                        <div class="input-group">
                                            <span class="input-group-text" id="Rp">Rp</span>
                                            <input type="number" name="price" id="price" class="form-control" placeholder="0" aria-label="price" aria-describedby="Rp">
                                        </div>
                                    </div>
                                    <div class="mb-5">
                                        <div class="row">
                                            <div class="col-lg-6 fv-row fv-plugins-icon-container">
                                                <label for="started_at" class="required form-label">Started At</label>
                                                <input type="text" name="started_at" id="started_at" class="form-control" placeholder="Started At">
                                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                            </div>
                                            <div class="col-lg-6 fv-row fv-plugins-icon-container">
                                                <label for="ended_at" class="required form-label">Ended At</label>
                                                <input type="text" name="ended_at" id="ended_at" class="form-control" placeholder="Ended At">
                                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-5">
                                        <label for="status" class="form-label required">Status</label>
                                        <div class="form-check form-check-solid form-switch form-check-custom fv-row">
                                            <input class="form-check-input w-45px h-30px" type="checkbox" id="status" name="status" checked>
                                            <label class="form-check-label" for="status"></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="reset" class="btn btn-light" data-bs-dismiss="modal" id="btn_cancel_add_expedition">Batal</button>
                                    <button type="submit" class="btn btn-primary me-10" id="btn_add_expedition">
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
                <div class="modal fade" tabindex="-1" id="editExpeditionModal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form id="editExpeditionForm" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <input type="hidden" id="expeditionId">
                                <div class="modal-header">
                                    <h3 class="modal-title w-100 text-center">Edit Ekspedisi</h3>
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
                                        <label for="name" class="required form-label">Nama Ekspedisi</label>
                                        <input type="text" name="name" id="editName" class="form-control" placeholder="Nama Ekspedisi" required />
                                    </div>
                                    <div class="mb-5">
                                        <label for="description" class="form-label">Deskripsi</label>
                                        <input type="text" name="description" id="editDescription" class="form-control" placeholder="Deskripsi" />
                                    </div>
                                    <div class="mb-5">
                                        <label for="type" class="form-label required">Jenis Harga</label>
                                        <div class="d-flex align-items-center mt-2">
                                            <label for="description" class="form-label"></label>
                                            <label class="form-check form-check-inline form-check-solid me-5">
                                                <input class="form-check-input" name="price_type" id="editPrice_km" type="radio" value="price_km" required>
                                                <span class="fw-semibold ps-2 fs-6">Harga per Km</span>
                                            </label>
                                            <label class="form-check form-check-inline form-check-solid">
                                                <input class="form-check-input" name="price_type" id="editPrice" type="radio" value="price" required>
                                                <span class="fw-semibold ps-2 fs-6">Harga</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="mb-5" id="editPriceWrapper">
                                        <label for="price" class="required form-label">Harga</label>
                                        <div class="input-group">
                                            <span class="input-group-text" id="Rp">Rp</span>
                                            <input type="number" name="price" id="editPriceInput" class="form-control" placeholder="0" aria-label="price" aria-describedby="Rp">
                                        </div>
                                    </div>
                                    <div class="mb-5">
                                        <div class="row">
                                            <div class="col-lg-6 fv-row fv-plugins-icon-container">
                                                <label for="started_at" class="required form-label">Started At</label>
                                                <input type="text" name="started_at" id="editStarted_at" class="form-control" placeholder="Started At">
                                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                            </div>
                                            <div class="col-lg-6 fv-row fv-plugins-icon-container">
                                                <label for="ended_at" class="required form-label">Ended At</label>
                                                <input type="text" name="ended_at" id="editEnded_at" class="form-control" placeholder="Ended At">
                                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-5">
                                        <label for="status" class="form-label required">Status</label>
                                        <div class="form-check form-check-solid form-switch form-check-custom fv-row">
                                            <input class="form-check-input w-45px h-30px" type="checkbox" id="editStatus" name="status">
                                            <label class="form-check-label" for="status"></label>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="reset" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary me-10" id="btn_edit_expedition">
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
            <table id="expeditions-table" class="table align-middle table-row-dashed fs-6 gy-5">
                <thead>
                    <tr class="fw-semibold fs-6 text-muted">
                        <th>No</th>
                        <th>Icon</th>
                        <th>Nama Ekspedisi</th>
                        <th>Periode</th>
                        <th>Harga</th>
                        <th>Harga / Km</th>
                        <th>Status</th>
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
        var datatable = $('#expeditions-table').DataTable({
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
                    data: 'period',
                    name: 'period',
                    orderable: true,
                    searchable: true,
                    width: '10'
                },
                {
                    data: 'price',
                    name: 'price',
                    orderable: true,
                    searchable: true,
                    width: '10'
                },
                {
                    data: 'price_km',
                    name: 'price_km',
                    orderable: true,
                    searchable: true,
                    width: '10'
                },
                {
                    data: 'status',
                    name: 'status',
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

        $('#status').on('change', function() {
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
                dropdownParent: $("#addExpeditionModal"),
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
                dropdownParent: $("#editExpeditionModal"),
                width: '100%',
            });
        });

        $("#ended_at, #started_at").flatpickr({
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            time_24hr: true,
            defaultHour: 0,
        });

        $('input[type=radio][name=price_type]').change(function() {
            $('#priceWrapper').show();
            $('#price').attr('required', true);
        });

        $('#btn_cancel_add_expedition').on('click', function() {
            $('#priceWrapper').hide();
            $('#price').removeAttr('required');
        });

        $('#addExpeditionForm').submit(function(e) {
            e.preventDefault();
            $('#btn_add_expedition').prop('disabled', true);
            $('#btn_add_expedition').attr("data-kt-indicator", "on");
            $.ajax({
                url: "{{ route('expeditions.store') }}",
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
                    $('#addExpeditionModal').modal('toggle');
                    $('#addExpeditionForm').trigger('reset');
                    $('#expeditions-table').DataTable().ajax.reload();
                }
            });
            $('#btn_add_expedition').prop('disabled', false);
            $('#btn_add_expedition').removeAttr("data-kt-indicator");
        });

        $(document).on('click', '#editExpedition', function() {
            var id = $(this).data('id');
            var url = "{{ route('expeditions.edit', ':id') }}";
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
                    $('#expeditionId').val(data.id);
                    $('#editIcon').css('background-image', 'url(' + data.icon + ')');
                    $('#editName').val(data.name);
                    $('#editDescription').val(data.description);
                    if (data.price_km) {
                        $('#editPrice_km').attr('checked', true);
                        $('#editPriceInput').val(data.price_km);
                    } else {
                        $('#editPrice').attr('checked', true);
                        $('#editPriceInput').val(data.price);
                    }
                    $('#editStarted_at').val(data.started_at);
                    $('#editEnded_at').val(data.ended_at);
                    if (data.status) {
                        $('#editStatus').attr('checked', true);
                    } else {
                        $('#editStatus').removeAttr('checked');
                    }
                    var started_at = data.started_at;
                    started_at = started_at.match(/^(\d+)[ :,](\d+)[ :,](\d+)$/);
                    $("#editStarted_at").flatpickr({
                        enableTime: true,
                        noCalendar: true,
                        dateFormat: "H:i",
                        time_24hr: true,
                        defaultHour: parseInt(started_at[1]),
                        defaultMinute: parseInt(started_at[2]),
                    });

                    var ended_at = data.ended_at;
                    ended_at = ended_at.match(/^(\d+)[ :,](\d+)[ :,](\d+)$/);
                    $("#editEnded_at").flatpickr({
                        enableTime: true,
                        noCalendar: true,
                        dateFormat: "H:i",
                        time_24hr: true,
                        defaultHour: parseInt(ended_at[1]),
                        defaultMinute: parseInt(ended_at[2]),
                    });

                }
            });
        });

        $('#editExpeditionForm').submit(function(e) {
            e.preventDefault();
            var id = $('#expeditionId').val();
            var url = "{{ route('expeditions.update', ':id') }}";
            $('#btn_edit_expedition').prop('disabled', true);
            $('#btn_edit_expedition').attr("data-kt-indicator", "on");
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
                    $('#editExpeditionModal').modal('toggle');
                    $('#expeditions-table').DataTable().ajax.reload();
                }
            });
            $('#btn_edit_expedition').prop('disabled', false);
            $('#btn_edit_expedition').removeAttr("data-kt-indicator");
        });

        $(document).on("click", "#deleteConfirm", function(e) {
            e.preventDefault();
            var name = $(this).data('name');
            var text = 'Apakah anda yakin ingin menghapus ekspedisi ":name"?';
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
                    var route = "{{ route('expeditions.destroy', ':id') }}";
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
                            $('#expeditions-table').DataTable().ajax.reload();
                        },
                        error: function(data) {
                            toastr.error(data.responseJSON.message, 'Error');
                        }
                    });
                }
            });
        });

        $(document).on('change', '#changeStatus', function () {
            if ($(this).is(":checked")) {
                var checked = 1;
            } else {
                var checked = 0;
            }
            var url = "{{ route('expeditions.update.status') }}";
            $.ajax({
                type: 'PUT',
                url: url,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    id: $(this).data('id'),
                    status: checked,
                },
                success: function(data) {
                    toastr.success(data.message, 'Success');
                    $('#expeditions-table').DataTable().ajax.reload();
                },
                error: function(data) {
                    toastr.error(data.responseJSON.message, 'Error');
                }
            });
        });
    </script>
@endpush
