<div class="modal fade" tabindex="-1" id="addSenderModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="addSenderForm">
                @csrf
                <div class="modal-header">
                    <h3 class="modal-title w-100 text-center">Tambah Sender</h3>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                        aria-label="Close">
                        <span class="svg-icon svg-icon-1"><i class="bi bi-x-lg"></i></span>
                    </div>
                </div>

                <div class="modal-body">
                    <div class="mb-5">
                        <label for="user_id" class="form-label">User</label>
                        <select name="user_id" class="form-select form-select-solid" data-control="select2"
                            data-placeholder="Pilih User" data-allow-clear="true" id="senderUser_id">
                            <option value=""></option>
                            @if (request()->input('user'))
                                <option value="{{ $user->id }}" selected>{{ $user->name }}</option>
                            @endif
                        </select>
                    </div>
                    <div class="mb-5">
                        <label for="name" class="required form-label optional">Nama</label>
                        <input type="text" name="name" id="name" class="form-control"
                            placeholder="Nama Sender" required />
                    </div>
                    <div class="mb-5">
                        <label for="phonenumber" class="required form-label optional">No. Whatsapp</label>
                        <div class="input-group">
                            <span class="input-group-text" id="+62">+62</span>
                            <input type="text" name="phonenumber" id="phonenumber" class="form-control"
                                placeholder="8xxx..." aria-label="phonenumber" aria-describedby="+62" required />
                        </div>
                    </div>
                    <div class="mb-5">
                        <label for="address" class="required form-label">Alamat Lengkap</label>
                        <textarea name="address" id="address" class="form-control" cols="30" rows="5"
                            placeholder="Nama Jalan, Nomor Rumah/Gedung, RT/RW" required></textarea>
                    </div>
                    <div class="mb-5">
                        <label for="location" class="required form-label">Lokasi</label>
                        <div class="mb-3">
                            <select name="province_id" class="form-select" id="senderProvince_id"
                                data-control="select2"></select>
                        </div>
                        <div class="mb-3">
                            <select name="city_id" class="form-select" id="senderCity_id"
                                data-control="select2"></select>
                        </div>
                        <div class="mb-3">
                            <select name="subdistrict_id" class="form-select" id="senderSubdistrict_id"
                                data-control="select2"></select>
                        </div>
                    </div>
                    <div class="mb-5">
                        <label for="postal_code" class="form-label">Kode Pos</label>
                        <input type="text" name="postal_code" id="postal_code" class="form-control"
                            placeholder="Kode Pos (Opsional)" />
                    </div>
                    <div class="mb-5">
                        <label for="note" class="form-label">Catatan</label>
                        <textarea name="note" id="note" class="form-control" cols="30" rows="5"
                            placeholder="Petunjuk, patokan, dll. (Opsional)"></textarea>
                    </div>
                    {{-- <div class="mb-5">
                        <label for="status" class="form-label">Atur sebagai alamat utama</label>
                        <div class="form-check form-check-solid form-switch form-check-custom fv-row">
                            <input class="form-check-input w-45px h-30px" type="checkbox" id="default" name="default">
                            <label class="form-check-label" for="default"></label>
                        </div>
                    </div> --}}
                    <div class="mb-5">
                        <label class="form-label">Lokasi</label>
                        <div class="mb-3 text-center">
                            <a class="btn btn-primary" id="senderSearchAddress">Cari lokasi</a>
                        </div>
                        <div id="map" class="rounded-1" style="height: 300px;"></div>
                        <input type="hidden" name="latitude" id="senderLatitude">
                        <input type="hidden" name="longitude" id="senderLongitude">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="reset" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary me-10" id="btn_add_sender">
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
<div class="modal fade" tabindex="-1" id="editSenderModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editSenderForm">
                @csrf
                @method('PUT')
                <input type="hidden" id="senderId">
                <div class="modal-header">
                    <h3 class="modal-title w-100 text-center">Edit Sender</h3>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                        aria-label="Close">
                        <span class="svg-icon svg-icon-1"><i class="bi bi-x-lg"></i></span>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="mb-5">
                        <label for="user_id" class="required form-label">User</label>
                        <select name="user_id" id="editUser_id" class="form-select form-select-solid"
                            data-control="select2" data-placeholder="Pilih User" data-enable="false">
                            <option value=""></option>
                            @if (request()->input('user'))
                                <option value="{{ $user->id }}" selected>{{ $user->name }}</option>
                            @endif
                        </select>
                    </div>
                    <div class="mb-5">
                        <label for="name" class="required form-label optional">Nama</label>
                        <input type="text" name="name" id="editName" class="form-control"
                            placeholder="Nama Sender" required />
                    </div>
                    <div class="mb-5">
                        <label for="phonenumber" class="required form-label optional">No. Whatsapp</label>
                        <div class="input-group">
                            <span class="input-group-text" id="+62">+62</span>
                            <input type="text" name="phonenumber" id="editPhonenumber" class="form-control"
                                placeholder="8xxx..." aria-label="phonenumber" aria-describedby="+62" required />
                        </div>
                    </div>
                    <div class="mb-5">
                        <label for="address" class="required form-label">Alamat Lengkap</label>
                        <textarea name="address" id="editAddress" class="form-control" cols="30" rows="5"
                            placeholder="Nama Jalan, Nomor Rumah/Gedung, RT/RW" required></textarea>
                    </div>
                    <div class="mb-5">
                        <label for="location" class="required form-label">Lokasi</label>
                        <div class="mb-3">
                            <select name="province_id" id="editSenderProvince_id" class="form-select"
                                data-control="select2"></select>
                        </div>
                        <div class="mb-3">
                            <select name="city_id" id="editSenderCity_id" class="form-select"
                                data-control="select2"></select>
                        </div>
                        <div class="mb-3">
                            <select name="subdistrict_id" id="editSenderSubdistrict_id" class="form-select"
                                data-control="select2"></select>
                        </div>
                    </div>
                    <div class="mb-5">
                        <label for="postal_code" class="form-label">Kode Pos</label>
                        <input type="text" name="postal_code" id="editPostal_code" class="form-control"
                            placeholder="Kode Pos (Opsional)" />
                    </div>
                    <div class="mb-5">
                        <label for="note" class="form-label">Catatan</label>
                        <textarea name="note" id="editNote" class="form-control" cols="30" rows="5"
                            placeholder="Petunjuk, patokan, dll. (Opsional)"></textarea>
                    </div>
                    {{-- <div class="mb-5">
                        <label for="status" class="form-label">Atur sebagai alamat utama</label>
                        <div class="form-check form-check-solid form-switch form-check-custom fv-row">
                            <input class="form-check-input w-45px h-30px" type="checkbox" id="editDefault" name="default">
                            <label class="form-check-label" for="default"></label>
                        </div>
                    </div> --}}
                    <div class="mb-5">
                        <label class="form-label">Lokasi</label>
                        <div class="mb-3 text-center">
                            <a class="btn btn-primary" id="editSenderSearchAddress">Cari lokasi</a>
                        </div>
                        <div id="editMap" class="rounded-1" style="height: 300px;"></div>
                        <input type="hidden" name="latitude" id="editSenderLatitude">
                        <input type="hidden" name="longitude" id="editSenderLongitude">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="reset" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary me-10" id="btn_edit_sender">
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
@push('js')
    <script>
        var sendermap, sendermarker, editMap, editMarker;

        function initSenderMap() {
            sendermap = new google.maps.Map(document.getElementById("map"), {
                zoom: 12,
                center: {
                    lat: -7.968376,
                    lng: 112.632354,
                },
                streetViewControl: false,
            });

            sendermarker = new google.maps.Marker({
                position: new google.maps.LatLng(-7.968376, 112.632354),
                map: sendermap,
                draggable: true,
            });

            google.maps.event.addListener(sendermarker, 'dragend', function() {
                $('#senderLatitude').val(sendermarker.getPosition().lat());
                $('#senderLongitude').val(sendermarker.getPosition().lng());
            });

            editMap = new google.maps.Map(document.getElementById("editMap"), {
                zoom: 18,
                center: {
                    lat: -7.968376,
                    lng: 112.632354,
                },
                streetViewControl: false,
            });

            editMarker = new google.maps.Marker({
                position: new google.maps.LatLng(-7.968376, 112.632354),
                map: editMap,
                draggable: true,
            });

            google.maps.event.addListener(editMarker, 'dragend', function() {
                $('#editSenderLatitude').val(editMarker.getPosition().lat());
                $('#editSenderLongitude').val(editMarker.getPosition().lng());
            });
        }

        function senderGeocodeAddress() {
            var senderGeocoder = new google.maps.Geocoder();
            if ($('#senderSubdistrict_id').val() == null || $('#address').val() == '') {
                return toastr.error('Silahkan lengkapi alamat terlebih dahulu!');
            }

            var zone = $('#senderSubdistrict_id').select2('data')[0].text + ", " + $('#senderCity_id').select2('data')[0]
                .text + ", " + $('#senderProvince_id').select2('data')[0].text;
            var address = $('#address').val() + ", " + zone;


            senderGeocoder.geocode({
                address: address
            }, function(results, status) {
                if (status === google.maps.GeocoderStatus.OK) {
                    var location = results[0].geometry.location;
                    sendermap.setZoom(18);
                    sendermap.setCenter(new google.maps.LatLng(location.lat(), location.lng()));
                    sendermarker.setPosition(new google.maps.LatLng(location.lat(), location.lng()));
                    $('#senderLatitude').val(location.lat());
                    $('#senderLongitude').val(location.lng());
                } else {
                    toastr.error(status, 'Error');
                }
            });
        }

        function editSenderGeocodeAddress() {
            var senderGeocoder = new google.maps.Geocoder();
            if ($('#editSenderSubdistrict_id').val() == null || $('#editAddress').val() == '') {
                return toastr.error('Silahkan lengkapi alamat terlebih dahulu!');
            }
            var zone = $('#editSenderSubdistrict_id').select2('data')[0].text + ", " + $('#editSenderCity_id').select2(
                'data')[0].text + ", " + $('#editSenderProvince_id').select2('data')[0].text;
            var address = $('#editAddress').val() + ", " + zone;

            senderGeocoder.geocode({
                address: address
            }, function(results, status) {
                if (status === google.maps.GeocoderStatus.OK) {
                    var location = results[0].geometry.location;
                    editMap.setZoom(18);
                    editMap.setCenter(new google.maps.LatLng(location.lat(), location.lng()));
                    editMarker.setPosition(new google.maps.LatLng(location.lat(), location.lng()));
                    $('#editSenderLatitude').val(location.lat());
                    $('#editSenderLongitude').val(location.lng());
                } else {
                    toastr.error(status, 'Error');
                }
            });
        }

        $('#senderSubdistrict_id').on('change', function() {
            senderGeocodeAddress();
        });
        $('#senderSearchAddress').on('click', function() {
            senderGeocodeAddress();
        });
        $('#editSenderSubdistrict_id').on('change', function() {
            editSenderGeocodeAddress();
        });
        $('#editSenderSearchAddress').on('click', function() {
            editSenderGeocodeAddress();
        });
    </script>
    @if (!request()->input('user'))
        <script>
            $(document).ready(function() {
                $('#senderUser_id').select2({
                    ajax: {
                        url: "{{ route('senders.create') }}",
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            var query = {
                                search: params.term,
                                add: true,
                            }
                            return query;
                        },
                        placeholder: 'Pilih User',
                        dropddownParent: $("#addSenderModal > .modal-dialog > .modal-content"),
                        width: '100%',
                    }
                });
                $('#editUser_id').select2({
                    ajax: {
                        url: "{{ route('senders.create') }}",
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            var query = {
                                search: params.term,
                                add: false,
                            }
                            return query;
                        },
                        placeholder: 'Pilih User',
                        dropddownParent: $("#editSenderModal > .modal-dialog > .modal-content"),
                        width: '100%',
                    }
                });
            });
        </script>
    @endif
    <script>
        $('#senderUser_id').on('change', function() {
            if ($(this).val() != '') {
                $('.optional').removeClass('required');
                $('#name').removeAttr('required');
                $('#phonenumber').removeAttr('required');
            } else {
                $('.optional').addClass('required');
                $('#name').attr('required', true);
                $('#phonenumber').attr('required', true);
            }
        });

        $('#btnAddSender').on('click', function() {
            $('#senderProvince_id').select2({
                ajax: {
                    url: "{{ route('dropdown.provinces') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        var query = {
                            search: params.term,
                        }
                        return query;
                    },
                },
                placeholder: 'Pilih Provinsi',
                dropdownParent: $("#addSenderModal > .modal-dialog > .modal-content"),
                width: '100%'
            });
            $('#senderCity_id').select2({
                placeholder: 'Pilih provinsi terlebih dahulu!',
                dropdownParent: $("#addSenderModal > .modal-dialog > .modal-content"),
                width: '100%',
            });
            $('#senderSubdistrict_id').select2({
                placeholder: 'Pilih kota terlebih dahulu!',
                dropdownParent: $("#addSenderModal > .modal-dialog > .modal-content"),
                width: '100%',
            });
        });

        $('#senderProvince_id').on('change', function() {
            var province_id = $(this).val();
            $('#senderCity_id').select2({
                ajax: {
                    url: "{{ route('dropdown.cities') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        var query = {
                            search: params.term,
                            province_id: province_id,
                        }
                        return query;
                    },
                },
                placeholder: 'Pilih Kota',
                dropdownParent: $("#addSenderModal > .modal-dialog > .modal-content"),
                width: '100%',
            });
        });

        $('#senderCity_id').on('change', function() {
            var city_id = $(this).val();
            $('#senderSubdistrict_id').select2({
                ajax: {
                    url: "{{ route('dropdown.subdistricts') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        var query = {
                            search: params.term,
                            city_id: city_id,
                        }
                        return query;
                    },
                },
                placeholder: 'Pilih Kecamatan',
                dropdownParent: $("#addSenderModal > .modal-dialog > .modal-content"),
                width: '100%',
            });
        });

        $('#addSenderForm').submit(function(e) {
            e.preventDefault();
            $('#btn_add_sender').prop('disabled', true);
            $('#btn_add_sender').attr("data-kt-indicator", "on");
            $.ajax({
                url: "{{ route('senders.store') }}",
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
                    $('#addSenderModal').modal('toggle');
                    $('#addSenderForm').trigger('reset');
                    $('#senders-table').DataTable().ajax.reload();
                }
            });
            $('#btn_add_sender').prop('disabled', false);
            $('#btn_add_sender').removeAttr("data-kt-indicator");
        });

        $(document).on('click', '#editSender', function() {
            $('#editSenderProvince_id').select2({
                ajax: {
                    url: "{{ route('dropdown.provinces') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        var query = {
                            search: params.term,
                        }
                        return query;
                    },
                },
                placeholder: 'Pilih Provinsi',
                dropdownParent: $("#editSenderModal > .modal-dialog > .modal-content"),
                width: '100%'
            });
            $('#editSenderCity_id').select2({
                placeholder: 'Pilih provinsi terlebih dahulu!',
                dropdownParent: $("#editSenderModal > .modal-dialog > .modal-content"),
                width: '100%',
            });
            $('#editSenderSubdistrict_id').select2({
                placeholder: 'Pilih kota terlebih dahulu!',
                dropdownParent: $("#editSenderModal > .modal-dialog > .modal-content"),
                width: '100%',
            });

            var id = $(this).data('id');
            var url = "{{ route('senders.edit', ':id') }}";
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
                    $('#senderId').val(data.id);
                    $('#editName').val(data.name);
                    $('#editPhonenumber').val(data.phonenumber.substring(2));
                    $('#editAddress').val(data.address);
                    $('#editSenderProvince_id').append($("<option selected></option>").val(data
                            .subdistrict.city.province.id).text(data.subdistrict.city.province
                        .name)).trigger('change');
                    $('#editSenderCity_id').append($("<option selected></option>").val(data.subdistrict
                        .city.id).text(data.subdistrict.city.name)).trigger('change');
                    $('#editSenderSubdistrict_id').append($("<option selected></option>").val(data
                        .subdistrict_id).text(data.subdistrict.name));
                    $('#editPostal_code').val(data.postal_code);
                    $('#editNote').val(data.note);
                    $('#editSenderLatitude').val(data.latitude);
                    $('#editSenderLongitude').val(data.longitude);
                    editMap.setZoom(18);
                    editMap.setCenter(new google.maps.LatLng(data.latitude, data.longitude));
                    editMarker.setPosition(new google.maps.LatLng(data.latitude, data.longitude));
                }
            });
        });

        $('#editSenderProvince_id').on('change', function() {
            var province_id = $(this).val();
            $('#editSenderCity_id').select2({
                ajax: {
                    url: "{{ route('dropdown.cities') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        var query = {
                            search: params.term,
                            province_id: province_id,
                        }
                        return query;
                    },
                },
                placeholder: 'Pilih Kota',
                dropdownParent: $("#editSenderModal > .modal-dialog > .modal-content"),
                width: '100%',
            });
        });

        $('#editSenderCity_id').on('change', function() {
            var city_id = $(this).val();
            $('#editSenderSubdistrict_id').select2({
                ajax: {
                    url: "{{ route('dropdown.subdistricts') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        var query = {
                            search: params.term,
                            city_id: city_id,
                        }
                        return query;
                    },
                },
                placeholder: 'Pilih Kecamatan',
                dropdownParent: $("#editSenderModal > .modal-dialog > .modal-content"),
                width: '100%',
            });
        });

        $('#editSenderForm').submit(function(e) {
            e.preventDefault();
            var id = $('#senderId').val();
            var url = "{{ route('senders.update', ':id') }}";
            $('#btn_edit_sender').prop('disabled', true);
            $('#btn_edit_sender').attr("data-kt-indicator", "on");
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
                    $('#editSenderModal').modal('toggle');
                    $('#senders-table').DataTable().ajax.reload();
                }
            });
            $('#btn_edit_sender').prop('disabled', false);
            $('#btn_edit_sender').removeAttr("data-kt-indicator");
        });
    </script>
@endpush