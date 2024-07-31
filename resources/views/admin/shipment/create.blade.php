@extends('layouts.app')
@section('title', 'Create Shipment')
@section('page-title')
    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
        <h1 class="page-heading d-flex text-dark fw-bold flex-column justify-content-center my-0">
            Create Shipment
        </h1>
    </div>
@endsection
@section('content')
    <form id="createShipment" method="POST" action="{{ route('shipments.store') }}">
        @csrf
        <div class="row">
            <div class="col-xl-6 mb-5">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            Informasi Pengirim
                        </div>
                        <div class="card-toolbar">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSenderModal" id="btnAddSender">
                                + Pengirim
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row px-xl-5">
                            <div class="col pb-3">
                                <label for="sender_id" class="form-label required">Pilih Data Pengirim</label>
                                <select class="form-select" name="sender_id" id="sender_id" data-control="select2" data-minimum-input-length="5" required>
                                </select>
                            </div>
                        </div>
                        <div class="row px-xl-5" style="display: none;" id="sender_data_wrapper">
                            <div class="col">
                                <label for="sender_id" class=" form-label">Data Pengirim</label>
                                <p id="sender_data"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 mb-5">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            Informasi Penerima
                        </div>
                        <div class="card-toolbar">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRecipientModal" id="btnAddRecipient">
                                + Penerima
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row px-xl-5">
                            <div class="col pb-3">
                                <label for="recipient_id" class="form-label required">Pilih Data Penerima</label>
                                <select class="form-select" name="recipient_id" id="recipient_id" data-control="select2" data-minimum-input-length="5" required>
                                </select>
                            </div>
                        </div>
                        <div class="row px-xl-5" style="display: none;" id="recipient_data_wrapper">
                            <div class="col">
                                <label for="recipient_data" class=" form-label">Data Penerima</label>
                                <p id="recipient_data"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-5">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            Informasi Pengiriman
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row px-xl-5">
                            <div class="col-xl-6" id="expeditionWrapper" style="display: none;">
                                <div class="row">
                                    <div class="col-xl-6 mb-3">
                                        <label for="distance" class="form-label">Estimasi jarak</label>
                                        <div class="input-group">
                                            <input type="text" name="distance" id="distance" class="form-control form-control-solid" placeholder="Estimasi jarak" readonly>
                                            <span class="input-group-text" id="group5">Km</span>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 mb-3">
                                        <label for="expedition_id" class="form-label required">Ekspedisi</label>
                                        <select class="form-select" name="expedition_id" id="expedition_id" data-control="select2">
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6" id="clusterWrapper" style="display: none;">
                                <div class="row">
                                    <div class="col-xl-6 mb-3">
                                        <label for="cluster" class="form-label">Cluster</label>
                                        <input type="text" class="form-control form-control-solid" id="cluster" name="new_cluster" readonly>
                                        <input type="hidden" name="new_cluster" id="cluster_id">
                                        <input type="hidden" name="new_instant_price" id="new_instant_price_id">
                                        <input type="hidden" name="new_sameday_price" id="new_sameday_price_id">
                                    </div>
                                    <div class="col-xl-6 mb-3">
                                        <label for="clusterCost" class="form-label required">Tarif</label>
                                        <select class="form-select" name="cost_type" id="clusterCost" data-control="select2" data-hide-search="true" data-placeholder="Pilih tarif">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 mb-3" id="specialCostWrapper" style="display: none;">
                                <label for="specialCost" class=" form-label">Tarif Khusus</label>
                                <input type="text" class="form-control form-control-solid" id="specialCost">
                                <input type="hidden" name="specialCost_id" id="specialCost_id">
                            </div>
                            <div class="col-xl-6 mb-3">
                                <label for="payment_method_id" class="form-label required">Metode Pembayaran</label>
                                <select class="form-select" name="payment_method_id" id="payment_method_id" data-control="select2" required>
                                </select>
                            </div>
                            <div class="col-xl-6 mb-3">
                                <div class="row">
                                    <div class="col-xl-6 pb-3" style="display: none;">
                                        <label for="time" class=" form-label">Waktu Pengiriman</label>
                                        <input type="text" name="time" id="time" class="form-control" placeholder="Waktu Pengiriman">
                                        <div class="fv-plugins-message-container invalid-feedback"></div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="row px-xl-5" id="courier_id_wrapper" style="display: none">

                            <div class="col-xl-3 pb-3">
                                <label for="courier_id" class=" form-label">Driver</label>
                                <select class="form-select" name="courier_id" id="courier_id_select_only" readonly>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-5">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            Informasi Paket
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="items">
                            <div data-repeater-list="items">
                                <div data-repeater-item>
                                    <div class="form-group row px-xl-5">
                                        <div class="col-xl-3 me-3 pt-3">
                                            <label for="name" class="form-label required">Nama Barang</label>
                                            <input type="text" class="form-control" placeholder="Masukkan Nama Barang" name="name" id="name" required>
                                        </div>
                                        <div class="col-xl-3 me-3 pt-3">
                                            <label for="amount" class="form-label required">Jumlah Barang</label>
                                            <input type="text" class="form-control amount" placeholder="Masukkan Jumlah Barang" name="amount" id="amount" required>
                                        </div>
                                        <div class="col-xl-3 me-3 pt-3">
                                            <label for="weight" class="form-label required">Berat Barang</label>
                                            <div class="input-group mb-5">
                                                <input type="text" class="form-control weight" placeholder="Masukkan Berat Barang" name="weight" id="weight" aria-describedby="group1" required>
                                                <span class="input-group-text" id="group1">Kg</span>
                                            </div>
                                        </div>
                                        <div class="col-xl-2 pt-3">
                                            <a href="javascript:;" data-repeater-delete class="btn btn-sm btn-light-danger mt-3 mt-md-8">
                                                <i class="la la-trash-o"></i>Delete
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="px-xl-5 pt-3">
                                <a href="javascript:;" data-repeater-create class="btn btn-light-primary">
                                    <i class="la la-plus"></i>Tambah Barang
                                </a>
                            </div>
                        </div>
                        <div class="row px-xl-5 pt-10">
                            <div class="col-xl-3">
                                <label class="form-label required">Dimensi</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" placeholder="Panjang" name="length" id="length" aria-describedby="group2" required>
                                    <span class="input-group-text" id="group2">cm</span>
                                </div>
                            </div>
                            <div class="col-xl-3">
                                <label class="form-label">Lebar</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" placeholder="Lebar" name="width" id="width" aria-describedby="group3" required>
                                    <span class="input-group-text" id="group3">cm</span>
                                </div>
                            </div>
                            <div class="col-xl-3">
                                <label class="form-label">Tinggi</label>
                                <div class="input-group mb-5">
                                    <input type="number" class="form-control" placeholder="Tinggi" name="height" id="height" aria-describedby="group4" required>
                                    <span class="input-group-text" id="group4">cm</span>
                                </div>
                            </div>
                            <div class="col-xl-3">
                                <label class=" form-label">Berat Dimensi</label>
                                <div class="input-group mb-5">
                                    <input type="number" class="form-control form-control-solid" placeholder="Berat Dimensi" name="dimension_weight" id="dimension_weight" aria-describedby="group5" readonly>
                                    <span class="input-group-text" id="group5">Kg</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="float-end">
                            <div class="text-end text-muted">
                                Total Biaya kirim
                            </div>
                            <input type="hidden" name="total_price" id="total_price" />
                            <div class="text-end mb-3">
                                <font style="font-size:20pt"><sup>Rp </sup><span id="textPrice">0</span></font>
                            </div>
                            <button type="submit" class="btn btn-sm btn-primary" id="btnAddShipment">
                                <span class="indicator-label">
                                    Buat Pengiriman
                                </span>
                                <span class="indicator-progress">
                                    Tunggu <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    @include('admin.senders.modals')
    @include('admin.recipients.modals')
@endsection
@push('js')
    <script src="{{ asset('assets/plugins/custom/formrepeater/formrepeater.bundle.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/df-number-format/2.1.6/jquery.number.min.js" integrity="sha512-3z5bMAV+N1OaSH+65z+E0YCCEzU8fycphTBaOWkvunH9EtfahAlcJqAVN2evyg0m7ipaACKoVk6S9H2mEewJWA==" crossorigin="anonymous"
        referrerpolicy="no-referrer"></script>
    <script>
        var im = new Inputmask({
            alias: 'numeric',
            allowMinus: false,
        });

        var items = $('#items').repeater({
            initEmpty: false,
            defaultValues: {
                'text-input': 'foo',
            },
            show: function() {
                $(this).slideDown();
                im.mask('.amount,.weight');
                getPrice();
            },
            hide: function(deleteElement) {
                $(this).slideUp(deleteElement);
                getPrice();
            },
            isFirstItemUndeletable: true,
        });

        function initMaps() {
            if (typeof initSenderMap === 'function') {
                initSenderMap();
            }
            if (typeof initRecipientMap === 'function') {
                initRecipientMap();
            }
        }

        $(document).ready(function() {
            im.mask('.amount,.weight,#length,#width,#height,#dimension_weight');

            $('#sender_id').select2({
                ajax: {
                    url: "{{ route('dropdown.senders') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        var query = {
                            search: params.term,
                        }
                        return query;
                    },
                },
                placeholder: 'Nomor HP pengirim (62...)',
                width: '100%',
            });

            $('#recipient_id').select2({
                ajax: {
                    url: "{{ route('dropdown.recipients') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        var query = {
                            search: params.term,
                        }
                        return query;
                    },
                },
                placeholder: 'Nomor HP penerima (62...)',
                width: '100%',
            });

            $('#expedition_id').select2({
                ajax: {
                    url: "{{ route('dropdown.expeditions') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        var query = {
                            search: params.term,
                        }
                        return query;
                    },
                },
                placeholder: 'Pilih Ekspedisi',
                width: '100%',
            });

            $('#payment_method_id').select2({
                ajax: {
                    url: "{{ route('dropdown.payments.methods') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        var query = {
                            search: params.term,
                        }
                        return query;
                    },
                },
                placeholder: 'Pilih Metode Pembayaran',
                width: '100%',
            });

            $("#time").flatpickr({
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i",
                time_24hr: true,
                defaultHour: 0,
            });

            // $('#courier_id').select2({
            //     ajax: {
            //         url: "{{ route('dropdown.couriers') }}",
            //         dataType: 'json',
            //         delay: 250,
            //         data: function(params) {
            //             var query = {
            //                 search: params.term,
            //             }
            //             return query;
            //         },
            //     },
            //     placeholder: 'Pilih Driver',
            //     width: '100%',
            // });
        });

        var senderLatitude, senderLongitude, senderSubdistrictId;
        $('#sender_id').on('change', async function() {
            await $.ajax({
                url: "{{ route('shipments.create') }}",
                type: "GET",
                dataType: "JSON",
                processData: false,
                contentType: false,
                cache: false,
                data: $.param({
                    id: $(this).val(),
                    getSender: true,
                }),
                error: function(data) {
                    toastr.error(data.responseJSON.message, 'Error');
                },
                success: function(data) {
                    $('#sender_data_wrapper').show();
                    $('#sender_data').text(data.address);
                    senderLatitude = data.latitude;
                    senderLongitude = data.longitude;
                    senderSubdistrictId = data.subdistrict_id;
                }
            });
            $(document).trigger('getDistance');
            getCost();
        });

        var recipientLatitude, recipientLongitude, recipientSubdistrictId;
        $('#recipient_id').on('change', async function() {
            await $.ajax({
                url: "{{ route('shipments.create') }}",
                type: "GET",
                dataType: "JSON",
                processData: false,
                contentType: false,
                cache: false,
                data: $.param({
                    id: $(this).val(),
                    getRecipient: true,
                }),
                error: function(data) {
                    toastr.error(data.responseJSON.message, 'Error');
                },
                success: function(data) {
                    $('#recipient_data_wrapper').show();
                    $('#recipient_data').text(data.address);
                    recipientLatitude = data.latitude;
                    recipientLongitude = data.longitude;
                    recipientSubdistrictId = data.subdistrict_id;
                }
            });
            $(document).trigger('getDistance');
            getCost();
        });

        var cost, clusterNextDayCost, clusterInstantCourierCost;
        async function getCost() {
            $('#clusterWrapper').hide();
            $('#specialCostWrapper').hide();
            $('#expeditionWrapper').hide();
            $('#clusterCost').removeAttr('required');
            $('#expedition_id').removeAttr('required');
            $('#cluster_id').val('');
            $('#specialCost_id').val('');
            $('#expedition_id').val('');

            if (senderSubdistrictId != null && recipientSubdistrictId != null) {
               
                await $.ajax({
                    url: "{{ route('shipments.create') }}",
                    type: 'GET',
                    dataType: 'json',
                    data: $.param({
                        lat: senderLatitude,
                        lon: senderLongitude,
                        senderSubdistrictId: senderSubdistrictId,
                        recipientSubdistrictId: recipientSubdistrictId,
                        getCost: true,
                    }),
                    success: function(data) {
                        if (data.cluster) {
                            $('#cluster').val(data.cluster);
                            $('#cluster_id').val(data.cluster);
                            $('#new_instant_price_id').val(data.instant);
                            $('#new_sameday_price_id').val(data.sameday);
                            $('#clusterCost').empty().trigger('change');
                            $('#clusterCost').append(new Option("", ""));
                            $('#clusterCost').append(new Option("Same Day - Rp " + data.sameday.toLocaleString('id-ID'), 'next_day'));
                            $('#clusterCost').append(new Option("Instant Courier - Rp " + data.instant.toLocaleString('id-ID'), 'instant_courier'));
                            $('#clusterCost').attr('required', true);
                            $('#clusterWrapper').show();
                            clusterNextDayCost = data.sameday;
                            clusterInstantCourierCost = data.instant;

                            // $('#courier_id_wrapper').show()

                            $.ajax({
                            url: "{{ route('getCourier') }}",
                            type: 'GET',
                            dataType: 'JSON',
                            data: $.param({
                                cluster: data.cluster
                            }),
                            success: function(data) {
                                var $courierSelect = $('#courier_id_select_only');
                                $courierSelect.empty(); // Kosongkan elemen select

                                $courierSelect.append(
                                        $('<option>', {
                                            value: data.courier_id,
                                            text: data.courier_name
                                        })
                                    );
                            },
                        });                            

                        } else if (data.specialCost) {
                            $('#specialCost').val("Rp" + data.specialCost.cost.toLocaleString('id-ID') + " (" + data.specialCost.origin.name + " - " + data.specialCost.destination.name + ")");
                            $('#specialCost_id').val(data.specialCost.id);
                            $('#specialCostWrapper').show();
                            cost = data.specialCost.cost;
                        } else {
                            $('#expeditionWrapper').show();
                            $('#expedition_id').attr('required', true);
                        }
                    }
                })
            }
        }

        $('#clusterCost').on('change', function() {
            if ($(this).val() == 'next_day') {
                cost = clusterNextDayCost;
                console.log('ini next day ' + cost)

            } else if ($(this).val() == 'instant_courier') {
                cost = clusterInstantCourierCost;
                console.log('ini instant kurir ' + cost)
            }
            getPrice();
        });

        var distance;
        $(document).on('getDistance', async function() {
            if ($('#recipient_id').val() != null && $('#sender_id').val() != null) {
                var sender = senderLatitude + ',' + senderLongitude;
                var recipient = recipientLatitude + ',' + recipientLongitude;

                console.log(sender)
                console.log(recipient)
                await $.ajax({
                    url: "{{ route('getDistance') }}",
                    type: 'GET',
                    dataType: 'JSON',
                    data: $.param({
                        origin: sender,
                        destination: recipient,
                    }),
                    success: function(data) {
                        console.log(data)
                        if (data.status == 'OK') {
                            distance = data.rows[0].elements[0].distance.text.replace(' km', '');
                            console.log("distance nya " + distance)
                            $('#distance').val(distance);
                        } else {
                            toastr.error(data.error_message, data.status);
                        }
                    },
                });
            }
        });

        let price = 0;
        $('#expedition_id').on('change', async function() {
            await $.ajax({
                url: "{{ route('shipments.create') }}",
                type: "GET",
                dataType: "JSON",
                processData: false,
                contentType: false,
                cache: false,
                data: $.param({
                    id: $(this).val(),
                    getPrice: true,
                }),
                error: function(data) {
                    toastr.error(data.responseJSON.message, 'Error');
                },
                success: function(data) {
                    cost = data * distance;
                    console.log('apakah ada distance baru?')
                }
            });
            getPrice();
        });

        var paymentCost;
        $('#payment_method_id').on('change', async function() {
            await $.ajax({
                url: "{{ route('shipments.create') }}",
                type: 'GET',
                dataType: 'JSON',
                data: $.param({
                    id: $(this).val(),
                    getPaymentCost: true,
                }),
                error: function(data) {
                    toastr.error(data.responseJSON.message, 'Error');
                },
                success: function(data) {
                    paymentCost = data.cost;
                }
            });
            getPrice();
        });

        $(document).on('input', '.amount,.weight', function() {
            getPrice();
        });

        $(document).on('input', '#length,#width,#height', function() {
            getDimensionWeight();
        });

        function getDimensionWeight() {
            var length = $('#length').val() == '' ? 0 : $('#length').val();
            var width = $('#width').val() == '' ? 0 : $('#width').val();
            var height = $('#height').val() == '' ? 0 : $('#height').val();

            var dimension_weight = (length * width * height) / 100;

            $('#dimension_weight').val(Math.floor(dimension_weight));
        }

        function getPrice() {
            // var totalWeight = 0,
            //     totalAmount = 0;
            // $.each(items.repeaterVal().items, function(i, item) {
            //     var weight = item.weight == '' ? 0 : parseFloat(item.weight);
            //     var amount = item.amount == '' ? 0 : parseInt(item.amount);
            //     totalWeight += weight;
            //     totalAmount += amount;
            // });
            // var totalPrice = (price * totalWeight) * totalAmount;
            if (cost != null && paymentCost != null) {
                var totalPrice = cost + paymentCost;
                $('#textPrice').text(totalPrice.toLocaleString('id-ID'));
                $('#total_price').val(totalPrice);
            }
        }

        $('#createShipment').submit(function(e) {
            e.preventDefault();
            Swal.fire({
                customClass: {
                    confirmButton: 'bg-primary',
                },
                title: 'Periksa kembali data anda',
                text: "Apakah anda yakin ingin menyimpan data ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya'
            }).then((result) => {
                if (result.isConfirmed) {
                    $(this).find("button[type='submit']").prop('disabled', true);
                    $(this).find("button[type='submit']").attr('data-kt-indicator', 'on');
                    $.ajax({
                        url: "{{ route('shipments.store') }}",
                        type: "POST",
                        dataType: "JSON",
                        processData: false,
                        contentType: false,
                        cache: false,
                        data: new FormData(this),
                        error: function(data) {
                            var response = data.responseJSON;
                            if (response.hasOwnProperty('messages')) {
                                var errors = response.messages;
                                var errorMessages = [];
                                $.each(errors, function(field, messages) {
                                    $.each(messages, function(index, message) {
                                        errorMessages.push(message + " ");
                                    });
                                });
                                toastr.error(errorMessages, 'Error');
                            } else {
                                toastr.error(response.message, 'Error');
                            }
                        },
                        success: function(data) {
                            window.location.assign(data.url);
                        }
                    });
                    $(this).find("button[type='submit']").prop('disabled', false);
                    $(this).find("button[type='submit']").attr('data-kt-indicator', 'off');
                }
            });
        });
    </script>
    @include('components.gmaps', ['function' => 'initMaps'])
@endpush
