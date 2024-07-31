@extends('layouts.app')
@section('title', 'Users')
@section('page-title')
    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
        <h1 class="page-heading d-flex text-dark fw-bold flex-column justify-content-center my-0">
            User Management
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
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                    + User
                </button>
                <div class="modal fade" tabindex="-1" id="addUserModal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form id="addUserForm">
                                @csrf
                                <div class="modal-header">
                                    <h3 class="modal-title w-100 text-center">Tambah User</h3>
                                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                                        <span class="svg-icon svg-icon-1"><i class="bi bi-x-lg"></i></span>
                                    </div>
                                </div>

                                <div class="modal-body">
                                    <div class="mb-5">
                                        <label for="name" class="required form-label">Nama</label>
                                        <input type="text" name="name" id="name" class="form-control" placeholder="Nama User" required />
                                    </div>
                                    <div class="mb-5">
                                        <label for="email" class="required form-label">Email</label>
                                        <input type="email" name="email" id="email" class="form-control" placeholder="Email User" required />
                                    </div>
                                    <div class="mb-5">
                                        <label for="phonenumber" class="required form-label">No. Whatsapp</label>
                                        <div class="input-group">
                                            <span class="input-group-text" id="+62">+62</span>
                                            <input type="text" name="phonenumber" id="phonenumber" class="form-control" placeholder="8xxx..." aria-label="phonenumber" aria-describedby="+62" required />
                                        </div>
                                    </div>
                                    <div class="mb-5">
                                        <label for="password" class="required form-label">Password</label>
                                        <input type="password" name="password" id="password" class="form-control" placeholder="Password" required />
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="reset" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary me-10" id="btn_add_user">
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
                <div class="modal fade" tabindex="-1" id="editUserModal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form id="editUserForm">
                                @csrf
                                @method('PUT')
                                <input type="hidden" id="userId">
                                <div class="modal-header">
                                    <h3 class="modal-title w-100 text-center">Edit User</h3>
                                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                                        <span class="svg-icon svg-icon-1"><i class="bi bi-x-lg"></i></span>
                                    </div>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-5">
                                        <label for="name" class="required form-label">Nama</label>
                                        <input type="text" name="name" id="editName" class="form-control" placeholder="Nama User" required />
                                    </div>
                                    <div class="mb-5">
                                        <label for="email" class="required form-label">Email</label>
                                        <input type="email" name="email" id="editEmail" class="form-control" placeholder="Email User" required />
                                    </div>
                                    <div class="mb-5">
                                        <label for="phonenumber" class="required form-label">No. Whatsapp</label>
                                        <div class="input-group">
                                            <span class="input-group-text" id="+62">+62</span>
                                            <input type="text" name="phonenumber" id="editPhonenumber" class="form-control" placeholder="8xxx..." aria-label="phonenumber" aria-describedby="+62" required />
                                        </div>
                                    </div>
                                    <div class="mb-5">
                                        <label for="otp_daily" class="required form-label">OTP Daily</label>
                                        <input type="number" name="otp_daily" id="editOtpDaily" class="form-control" placeholder="OTP Daily" required />
                                    </div>
                                    <div class="mb-5">
                                        <label for="password" class="required form-label">Password</label>
                                        <input type="password" name="password" id="editPassword" class="form-control" placeholder="Password" />
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="reset" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary me-10" id="btn_edit_user">
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
            <table id="users-table" class="table align-middle table-row-dashed fs-6 gy-5">
                <thead>
                    <tr class="fw-semibold fs-6 text-muted">
                        <th>No</th>
                        <th>Profil</th>
                        <th>Nama User</th>
                        <th>No. Whatsapp</th>
                        <th>E-Mail</th>
                        <th>OTP</th>
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
        var datatable = $('#users-table').DataTable({
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
                    data: 'photo',
                    name: 'photo',
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
                    data: 'phonenumber',
                    name: 'phonenumber',
                    orderable: true,
                    searchable: true,
                    width: '10'
                },
                {
                    data: 'email',
                    name: 'email',
                    orderable: true,
                    searchable: true,
                    width: '10'
                },
                {
                    data: 'otp',
                    name: 'otp',
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

        $('#addUserForm').submit(function(e) {
            e.preventDefault();
            $('#btn_add_user').prop('disabled', true);
            $('#btn_add_user').attr("data-kt-indicator", "on");
            $.ajax({
                url: "{{ route('users.store') }}",
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
                    $('#addUserModal').modal('toggle');
                    $('#addUserForm').trigger('reset');
                    $('#users-table').DataTable().ajax.reload();
                }
            });
            $('#btn_add_user').prop('disabled', false);
            $('#btn_add_user').removeAttr("data-kt-indicator");
        });

        $(document).on('click', '#editUser', function() {
            var id = $(this).data('id');
            var url = "{{ route('users.edit', ':id') }}";
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
                    $('#userId').val(data.id);
                    $('#editName').val(data.name);
                    $('#editEmail').val(data.email);
                    $('#editOtpDaily').val(data.otp_daily);
                    $('#editPhonenumber').val(data.phonenumber.substring(2));
                }
            });
        });

        $('#editUserForm').submit(function(e) {
            e.preventDefault();
            var id = $('#userId').val();
            var url = "{{ route('users.update', ':id') }}";
            $('#btn_edit_user').prop('disabled', true);
            $('#btn_edit_user').attr("data-kt-indicator", "on");
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
                    $('#editUserModal').modal('toggle');
                    $('#users-table').DataTable().ajax.reload();
                }
            });
            $('#btn_edit_user').prop('disabled', false);
            $('#btn_edit_user').removeAttr("data-kt-indicator");
        });

        $(document).on("click", "#deleteConfirm", function(e) {
            e.preventDefault();
            var name = $(this).data('name');
            var text = 'Apakah anda yakin ingin menghapus user ":name"?'
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
                    var route = "{{ route('users.destroy', ':id') }}";
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
                            $('#users-table').DataTable().ajax.reload();
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
