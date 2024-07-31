@extends('layouts.app')
@section('title', 'Recipients')
@section('page-title')
    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
        <h1 class="page-heading d-flex text-dark fw-bold flex-column justify-content-center my-0">
            Daftar Penerima User {{ $user ? '"' . $user->name . '"' : '' }}
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
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRecipientModal" id="btnAddRecipient">
                    + Recipient
                </button>
                @include('admin.recipients.modals')
            </div>
        </div>
        <div class="card-body pt-0">
            <table id="recipients-table" class="table align-middle table-row-dashed fs-6 gy-5">
                <thead>
                    <tr class="fw-semibold fs-6 text-muted">
                        <th>No</th>
                        <th>Nama</th>
                        <th>No. Whatsapp</th>
                        <th>Alamat Lengkap</th>
                        <th>Catatan</th>
                        <th>Alamat Utama</th>
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
        var datatable = $('#recipients-table').DataTable({
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
                    data: 'full_address',
                    name: 'full_address',
                    orderable: true,
                    searchable: true,
                    width: '10'
                },
                {
                    data: 'note',
                    name: 'note',
                    orderable: true,
                    searchable: true,
                    width: '10'
                },
                {
                    data: 'default',
                    name: 'default',
                    orderable: true,
                    searchable: true,
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

        $(document).on("click", "#deleteConfirm", function(e) {
            e.preventDefault();
            var name = $(this).data('name');
            var text = 'Apakah anda yakin ingin menghapus recipient ":name"?'
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
                    var route = "{{ route('recipients.destroy', ':id') }}";
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
                            $('#recipients-table').DataTable().ajax.reload();
                        },
                        error: function(data) {
                            toastr.error(data.responseJSON.message, 'Error');
                        }
                    });
                }
            });
        });

        $(document).on('change', '#changeDefault', function () {
            if ($(this).is(":checked")) {
                var checked = 1;
            } else {
                var checked = 0;
            }
            var url = "{{ route('recipients.update.default') }}";
            $.ajax({
                type: 'PUT',
                url: url,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    id: $(this).data('id'),
                    user_id: $(this).data('user-id'),
                    default: checked,
                },
                success: function(data) {
                    toastr.success(data.message, 'Success');
                    $('#recipients-table').DataTable().ajax.reload();
                },
                error: function(data) {
                    toastr.error(data.responseJSON.message, 'Error');
                }
            });
        });
    </script>
    @include('components.gmaps', ['function' => 'initRecipientMap'])
@endpush
