<div class="modal fade" tabindex="-1" id="addRecipientModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="addRecipientForm">
                @csrf
                <div class="modal-header">
                    <h3 class="modal-title w-100 text-center">Tambah Recipient</h3>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <span class="svg-icon svg-icon-1"><i class="bi bi-x-lg"></i></span>
                    </div>
                </div>

                <div class="modal-body">
                    <div class="mb-5">
                        <label for="recipientUser_id" class="form-label">User</label>
                        <select name="user_id" class="form-select form-select-solid" data-control="select2" data-placeholder="Pilih User" data-enable="false" id="recipientUser_id">
                            <option value=""></option>
                            @if (request()->input('user'))
                                <option value="{{ $user->id }}" selected>{{ $user->name }}</option>
                            @endif
                        </select>
                    </div>
                    <div class="mb-5">
                        <label for="name" class="{{ request()->input('user') ? '' : 'required' }} form-label optional">Nama</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Nama Recipient" {{ request()->input('user') ? '' : 'required' }} />
                    </div>
                    <div class="mb-5">
                        <label for="phonenumber" class="{{ request()->input('user') ? '' : 'required' }} form-label optional">No. Whatsapp</label>
                        <div class="input-group">
                            <span class="input-group-text" id="+62">+62</span>
                            <input type="text" name="phonenumber" id="phonenumber" class="form-control" placeholder="8xxx..." aria-label="phonenumber" aria-describedby="+62" {{ request()->input('user') ? '' : 'required' }} />
                        </div>
                    </div>
                    <div class="mb-5">
                        <label for="address" class="required form-label">Alamat Lengkap</label>
                        <textarea name="address" id="recipientAddress" class="form-control" cols="30" rows="5" placeholder="Nama Jalan, Nomor Rumah/Gedung, RT/RW" required></textarea>
                    </div>
                    <div class="mb-5">
                        <label for="location" class="required form-label">Lokasi</label>
                        <div class="mb-3">
                            <select name="province_id" class="form-select province-select" id="recipientProvince_id" data-control="select2"></select>
                        </div>
                        <div class="mb-3">
                            <select name="city_id" class="form-select city-select" id="recipientCity_id" data-control="select2"></select>
                        </div>
                        <div class="mb-3">
                            <select name="subdistrict_id" class="form-select subdistrict-select" id="recipientSubdistrict_id" data-control="select2"></select>
                        </div>
                    </div>
                    <div class="mb-5">
                        <label for="postal_code" class="form-label">Kode Pos</label>
                        <input type="text" name="postal_code" class="form-control" placeholder="Kode Pos (Opsional)" />
                    </div>
                    <div class="mb-5">
                        <label for="note" class="form-label">Catatan</label>
                        <textarea name="note" class="form-control" cols="30" rows="5" placeholder="Petunjuk, patokan, dll. (Opsional)"></textarea>
                    </div>
                    <div class="mb-5">
                        <label for="status" class="form-label">Atur sebagai alamat utama</label>
                        <div class="form-check form-check-solid form-switch form-check-custom fv-row">
                            <input class="form-check-input w-45px h-30px" type="checkbox" id="default" name="default">
                            <label class="form-check-label" for="default"></label>
                        </div>
                    </div>
                    <div class="mb-5">
                        <label class="form-label">Lokasi</label>
                        <div class="mb-3 text-center">
                            <a class="btn btn-primary" id="recipientSearchAddress">Cari lokasi</a>
                        </div>
                        <div id="recipientMap" class="rounded-1" style="height: 300px;"></div>
                        <input type="hidden" name="latitude" id="recipientLatitude">
                        <input type="hidden" name="longitude" id="recipientLongitude">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="reset" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary me-10" id="btn_add_recipient">
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
<div class="modal fade" tabindex="-1" id="editRecipientModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editRecipientForm">
                @csrf
                @method('PUT')
                <input type="hidden" id="recipientId">
                <div class="modal-header">
                    <h3 class="modal-title w-100 text-center">Edit Recipient</h3>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <span class="svg-icon svg-icon-1"><i class="bi bi-x-lg"></i></span>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="mb-5">
                        <label for="editRecipientUser_id" class="required form-label">User</label>
                        <select name="user_id" class="form-select form-select-solid" data-control="select2" data-placeholder="Pilih User" data-enable="false" id="editRecipientUser_id">
                            <option value=""></option>
                            @if (request()->input('user'))
                                <option value="{{ $user->id }}" selected>{{ $user->name }}</option>
                            @endif
                        </select>
                    </div>
                    <div class="mb-5">
                        <label for="name" class="required form-label">Nama</label>
                        <input type="text" name="name" id="editName" class="form-control" placeholder="Nama Recipient" required />
                    </div>
                    <div class="mb-5">
                        <label for="phonenumber" class="required form-label">No. Whatsapp</label>
                        <div class="input-group">
                            <span class="input-group-text" id="+62">+62</span>
                            <input type="text" name="phonenumber" id="editPhonenumber" class="form-control" placeholder="8xxx..." aria-label="phonenumber" aria-describedby="+62" required />
                        </div>
                    </div>
                    <div class="mb-5">
                        <label for="address" class="required form-label">Alamat Lengkap</label>
                        <textarea name="address" id="editRecipentAddress" class="form-control" cols="30" rows="5" placeholder="Nama Jalan, Nomor Rumah/Gedung, RT/RW" required></textarea>
                    </div>
                    <div class="mb-5">
                        <label for="location" class="required form-label">Lokasi</label>
                        <div class="mb-3">
                            <select name="province_id" id="editRecipientProvince_id" class="form-select" data-control="select2"></select>
                        </div>
                        <div class="mb-3">
                            <select name="city_id" id="editRecipientCity_id" class="form-select" data-control="select2"></select>
                        </div>
                        <div class="mb-3">
                            <select name="subdistrict_id" id="editRecipientSubdistrict_id" class="form-select" data-control="select2"></select>
                        </div>
                    </div>
                    <div class="mb-5">
                        <label for="postal_code" class="form-label">Kode Pos</label>
                        <input type="text" name="postal_code" id="editPostal_code" class="form-control" placeholder="Kode Pos (Opsional)" />
                    </div>
                    <div class="mb-5">
                        <label for="note" class="form-label">Catatan</label>
                        <textarea name="note" id="editNote" class="form-control" cols="30" rows="5" placeholder="Petunjuk, patokan, dll. (Opsional)"></textarea>
                    </div>
                    <div class="mb-5">
                        <label for="status" class="form-label">Atur sebagai alamat utama</label>
                        <div class="form-check form-check-solid form-switch form-check-custom fv-row">
                            <input class="form-check-input w-45px h-30px" type="checkbox" id="editDefault" name="default">
                            <label class="form-check-label" for="default"></label>
                        </div>
                    </div>
                    <div class="mb-5">
                        <label class="form-label">Lokasi</label>
                        <div class="mb-3 text-center">
                            <a class="btn btn-primary" id="editRecipientSearchAddress">Cari lokasi</a>
                        </div>
                        <div id="editRecipientMap" class="rounded-1" style="height: 300px;"></div>
                        <input type="hidden" name="latitude" id="editRecipientLatitude">
                        <input type="hidden" name="longitude" id="editRecipientLongitude">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="reset" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary me-10" id="btn_edit_recipient">
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
        var recipientmap, recipientmarker, editMap, editMarker;

        function initRecipientMap() {
            recipientmap = new google.maps.Map(document.getElementById("recipientMap"), {
                zoom: 12,
                center: {
                    lat: -7.968376,
                    lng: 112.632354,
                },
                streetViewControl: false,
            });

            recipientmarker = new google.maps.Marker({
                position: new google.maps.LatLng(-7.968376, 112.632354),
                map: recipientmap,
                draggable: true,
            });

            google.maps.event.addListener(recipientmarker, 'dragend', function() {
                $('#recipientLatitude').val(recipientmarker.getPosition().lat());
                $('#recipientLongitude').val(recipientmarker.getPosition().lng());
            });

            editMap = new google.maps.Map(document.getElementById("editRecipientMap"), {
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
                $('#editRecipientLatitude').val(editMarker.getPosition().lat());
                $('#editRecipientLongitude').val(editMarker.getPosition().lng());
            });
        }

        function geocodeAddress() {
            var recipientGeocoder = new google.maps.Geocoder();
            if ($('#recipientSubdistrict_id').val() == null || $('#recipientAddress').val() == '') {
                return toastr.error('Silahkan lengkapi alamat terlebih dahulu!');
            }
            var zone = $('#recipientSubdistrict_id').select2('data')[0].text + ", " + $('#recipientCity_id').select2('data')[0].text + ", " + $('#recipientProvince_id').select2('data')[0].text;
            var address = $('#recipientAddress').val() + ", " + zone;


            recipientGeocoder.geocode({
                address: address
            }, function(results, status) {
                if (status === google.maps.GeocoderStatus.OK) {
                    var location = results[0].geometry.location;
                    recipientmap.setZoom(18);
                    recipientmap.setCenter(new google.maps.LatLng(location.lat(), location.lng()));
                    recipientmarker.setPosition(new google.maps.LatLng(location.lat(), location.lng()));
                    $('#recipientLatitude').val(location.lat());
                    $('#recipientLongitude').val(location.lng());
                } else {
                    toastr.error(status, 'Error');
                }
            });
        }

        function editGeocodeAddress() {
            var recipientGeocoder = new google.maps.Geocoder();
            if ($('#editRecipientSubdistrict_id').val() == null || $('#editRecipentAddress').val() == '') {
                return toastr.error('Silahkan lengkapi alamat terlebih dahulu!');
            }
            var zone = $('#editRecipientSubdistrict_id').select2('data')[0].text + ", " + $('#editRecipientCity_id').select2('data')[0].text + ", " + $('#editRecipientProvince_id').select2('data')[0].text;
            var address = $('#editRecipentAddress').val() + ", " + zone;

            recipientGeocoder.geocode({
                address: address
            }, function(results, status) {
                if (status === google.maps.GeocoderStatus.OK) {
                    var location = results[0].geometry.location;
                    editMap.setZoom(18);
                    editMap.setCenter(new google.maps.LatLng(location.lat(), location.lng()));
                    editMarker.setPosition(new google.maps.LatLng(location.lat(), location.lng()));
                    $('#editRecipientLatitude').val(location.lat());
                    $('#editRecipientLongitude').val(location.lng());
                } else {
                    toastr.error(status, 'Error');
                }
            });
        }

        $('#recipientSubdistrict_id').on('change', function() {
            geocodeAddress();
        });
        $('#recipientSearchAddress').on('click', function() {
            geocodeAddress();
        });
        $('#editRecipientSubdistrict_id').on('change', function() {
            editGeocodeAddress();
        });
        $('#editRecipientSearchAddress').on('click', function() {
            editGeocodeAddress();
        });
    </script>
    @if (!request()->input('user'))
        <script>
            $(document).ready(function() {
                $('#recipientUser_id').select2({
                    ajax: {
                        url: "{{ route('recipients.create') }}",
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
                        dropddownParent: $("#addRecipientModal > .modal-dialog > .modal-content"),
                        width: '100%',
                    }
                });
                $('#editUser_id').select2({
                    ajax: {
                        url: "{{ route('recipients.create') }}",
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
                        dropddownParent: $("#editRecipientModal > .modal-dialog > .modal-content"),
                        width: '100%',
                    }
                });
            });
        </script>
    @endif
    <script>
        $('#recipientUser_id').on('change', function() {
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

        $('#btnAddRecipient').on('click', function() {
            $('.province-select').select2({
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
                dropdownParent: $("#addRecipientModal > .modal-dialog > .modal-content"),
                width: '100%'
            });
            $('.city-select').select2({
                placeholder: 'Pilih provinsi terlebih dahulu!',
                dropdownParent: $("#addRecipientModal > .modal-dialog > .modal-content"),
                width: '100%',
            });
            $('.subdistrict-select').select2({
                placeholder: 'Pilih kota terlebih dahulu!',
                dropdownParent: $("#addRecipientModal > .modal-dialog > .modal-content"),
                width: '100%',
            });
        });

        $('.province-select').on('change', function() {
            var province_id = $(this).val();
            $('.city-select').select2({
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
                dropdownParent: $("#addRecipientModal > .modal-dialog > .modal-content"),
                width: '100%',
            });
        });

        $('.city-select').on('change', function() {
            var city_id = $(this).val();
            $('.subdistrict-select').select2({
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
                dropdownParent: $("#addRecipientModal > .modal-dialog > .modal-content"),
                width: '100%',
            });
        });

        $('#addRecipientForm').submit(function(e) {
            e.preventDefault();
            $('#btn_add_recipient').prop('disabled', true);
            $('#btn_add_recipient').attr("data-kt-indicator", "on");
            $.ajax({
                url: "{{ route('recipients.store') }}",
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
                    $('#addRecipientModal').modal('toggle');
                    $('#addRecipientForm').trigger('reset');
                    $('#recipients-table').DataTable().ajax.reload();
                }
            });
            $('#btn_add_recipient').prop('disabled', false);
            $('#btn_add_recipient').removeAttr("data-kt-indicator");
        });

        $(document).on('click', '#editRecipient', function() {
            $('#editRecipientProvince_id').select2({
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
                dropdownParent: $("#editRecipientModal > .modal-dialog > .modal-content"),
                width: '100%'
            });
            $('#editRecipientCity_id').select2({
                placeholder: 'Pilih provinsi terlebih dahulu!',
                dropdownParent: $("#editRecipientModal > .modal-dialog > .modal-content"),
                width: '100%',
            });
            $('#editRecipientSubdistrict_id').select2({
                placeholder: 'Pilih kota terlebih dahulu!',
                dropdownParent: $("#editRecipientModal > .modal-dialog > .modal-content"),
                width: '100%',
            });

            var id = $(this).data('id');
            var url = "{{ route('recipients.edit', ':id') }}";
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
                    $('#recipientId').val(data.id);
                    if (data.user_id != null) {
                        $('#editRecipientUser_id').append($("<option selected></option>").val(data.user_id).text(data.user.name)).trigger('change');
                    }
                    $('#editName').val(data.name);
                    $('#editPhonenumber').val(data.phonenumber.substring(2));
                    $('#editRecipentAddress').val(data.address);
                    $('#editRecipientProvince_id').append($("<option selected></option>").val(data.subdistrict.city.province.id).text(data.subdistrict.city.province.name)).trigger('change');
                    $('#editRecipientCity_id').append($("<option selected></option>").val(data.subdistrict.city.id).text(data.subdistrict.city.name)).trigger('change');
                    $('#editRecipientSubdistrict_id').append($("<option selected></option>").val(data.subdistrict_id).text(data.subdistrict.name)).trigger('change');
                    $('#editPostal_code').val(data.postal_code);
                    $('#editNote').val(data.note);
                    if (data.default) {
                        $('#editDefault').attr('checked', true);
                    } else {
                        $('#editDefault').removeAttr('checked');
                    }
                    $('#editRecipientLatitude').val(data.latitude);
                    $('#editRecipientLongitude').val(data.longitude);
                    editMap.setZoom(18);
                    editMap.setCenter(new google.maps.LatLng(data.latitude, data.longitude));
                    editMarker.setPosition(new google.maps.LatLng(data.latitude, data.longitude));
                }
            });
        });
        $('#editRecipientProvince_id').on('change', function() {
            var province_id = $(this).val();
            $('#editRecipientCity_id').select2({
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
                dropdownParent: $("#editRecipientModal > .modal-dialog > .modal-content"),
                width: '100%',
            });
        });

        $('#editRecipientCity_id').on('change', function() {
            var city_id = $(this).val();
            $('#editRecipientSubdistrict_id').select2({
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
                dropdownParent: $("#editRecipientModal > .modal-dialog > .modal-content"),
                width: '100%',
            });
        });

        $('#editRecipientForm').submit(function(e) {
            e.preventDefault();
            var id = $('#recipientId').val();
            var url = "{{ route('recipients.update', ':id') }}";
            $('#btn_edit_recipient').prop('disabled', true);
            $('#btn_edit_recipient').attr("data-kt-indicator", "on");
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
                    $('#editRecipientModal').modal('toggle');
                    $('#recipients-table').DataTable().ajax.reload();
                }
            });
            $('#btn_edit_recipient').prop('disabled', false);
            $('#btn_edit_recipient').removeAttr("data-kt-indicator");
        });
    </script>
@endpush
