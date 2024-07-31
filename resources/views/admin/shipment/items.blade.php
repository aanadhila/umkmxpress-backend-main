<table id="items-table" class="table align-middle table-row-dashed fs-6 gy-5">
    <thead>
        <tr class="fw-semibold text-muted">
            <th>No</th>
            <th>Nama Barang</th>
            <th>Jumlah Barang</th>
            <th>Berat Barang</th>
            <th>Berat Total</th>
        </tr>
    </thead>
    <tbody class="fw-semibold text-gray-600" style="">
    </tbody>
</table>
@push('js')
    @if (Route::is('shipments.index'))
        <script>
            var itemsDatatable;
            $(document).on('click', '#showItems', function() {
                if (itemsDatatable instanceof $.fn.dataTable.Api) {
                    itemsDatatable.destroy();
                }
                $('#airway_bill').text($(this).data('airway_bill'));
                itemsDatatable = $('#items-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ordering: true,
                    stateSave: false,
                    ajax: {
                        url: "{{ route('items.index') }}",
                        data: {
                            shipment_id: $(this).data('shipment_id'),
                        },
                    },
                    columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: true,
                        searchable: false,
                        width: '5'
                    }, {
                        data: 'name',
                        name: 'name',
                        orderable: true,
                        searchable: false,
                        width: '5'
                    }, {
                        data: 'weight',
                        name: 'weight',
                        orderable: true,
                        searchable: false,
                        width: '5'
                    }, {
                        data: 'amount',
                        name: 'amount',
                        orderable: true,
                        searchable: false,
                        width: '5'
                    }, {
                        data: 'total_weight',
                        name: 'total_weight',
                        orderable: true,
                        searchable: false,
                        width: '5'
                    }],
                    order: [
                        [0, "asc"]
                    ]
                });
            })
        </script>
    @elseif (Route::is('shipments.show'))
        <script>
            var itemsDatatable = $('#items-table').DataTable({
                processing: true,
                serverSide: true,
                ordering: true,
                stateSave: false,
                ajax: {
                    url: "{{ route('items.index') }}",
                    data: {
                        shipment_id: "{{ $shipment->id }}",
                    },
                },
                columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: true,
                    searchable: false,
                    width: '5'
                }, {
                    data: 'name',
                    name: 'name',
                    orderable: true,
                    searchable: false,
                    width: '5'
                }, {
                    data: 'amount',
                    name: 'amount',
                    orderable: true,
                    searchable: false,
                    width: '5'
                }, {
                    data: 'weight',
                    name: 'weight',
                    orderable: true,
                    searchable: false,
                    width: '5'
                }, {
                    data: 'total_weight',
                    name: 'total_weight',
                    orderable: true,
                    searchable: false,
                    width: '5'
                }],
                order: [
                    [0, "asc"]
                ]
            });
        </script>
    @endif
@endpush
