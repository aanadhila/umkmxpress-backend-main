@extends('layouts.app')
@section('title', 'API List')
@section('page-title')
    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
        <h1 class="page-heading d-flex text-dark fw-bold flex-column justify-content-center my-0">
            Daftar API
        </h1>
    </div>
@endsection
@section('content')
    <div class="card card-docs flex-row-fluid mb-5">
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
            </div>
        </div>
        <div class="card-body pt-0">
            <table id="api-table" class="table align-middle table-row-dashed fs-6 gy-5">
                <thead>
                    <tr class="fw-semibold text-muted">
                        <th>No</th>
                        <th>Collection</th>
                        <th>Name</th>
                        <th>URL</th>
                        <th>Method</th>
                        <th>Auth</th>
                        <th>Body</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody class="fw-semibold text-gray-600" style="">
                </tbody>
            </table>
        </div>
    </div>
@endsection
@push('js')
    <script>
        var datatable = $('#api-table').DataTable({
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
                    width: '2%'
                },
                {
                    data: 'collection',
                    name: 'collection',
                    orderable: true,
                    searchable: true,
                    width: '5%'
                },
                {
                    data: 'name',
                    name: 'name',
                    orderable: true,
                    searchable: true,
                    width: '5%'
                },
                {
                    data: 'url',
                    name: 'url',
                    orderable: true,
                    searchable: true,
                    width: '5%'
                },
                {
                    data: 'method',
                    name: 'method',
                    orderable: true,
                    searchable: true,
                    width: '5%'
                },
                {
                    data: 'auth',
                    name: 'auth',
                    orderable: false,
                    searchable: false,
                    width: '10%',
                },
                {
                    data: 'body',
                    name: 'body',
                    orderable: false,
                    searchable: false,
                    width: '15%'
                },
                {
                    data: 'description',
                    name: 'description',
                    orderable: true,
                    searchable: true,
                    width: '10%'
                }
            ],
            order: [
                [0, "asc"]
            ]
        });

        $('#search').on('keyup', function() {
            datatable.search(this.value).draw();
        });
    </script>
@endpush
