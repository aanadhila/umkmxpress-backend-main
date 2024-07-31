@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title')
    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
        <h1 class="page-heading d-flex text-dark fw-bold flex-column justify-content-center my-0">
            Dashboard
        </h1>
    </div>
@endsection
@section('content')
    <div class="row mb-xl-5">
        <div class="col-xl-8">
            <div class="card card-docs flex-row-fluid mb-2">
                <div class="card-header d-flex justify-content-between">
                    <div class="card-title align-items-start flex-column">
                        Ringkasan Pengiriman
                    </div>
                    <div class="card-toolbar">
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="d-flex">
                                <span class="svg-icon-3">
                                    <img src="{{ asset('assets/img/pengiriman-icon.svg') }}" alt="">
                                </span>
                                <div class="d-flex justify-content-between align-items-start flex-wrap">
                                    <div class="d-flex flex-column">
                                        <div class="d-flex align-items-center">
                                            <span class="text-gray-900 fs-5">Pengiriman</span>
                                        </div>
                                        <div class="d-flex flex-wrap">
                                            <font style="font-size: 35pt">{{ $shipments->count() }}</font>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="d-flex">
                                <span class="svg-icon-3">
                                    <img src="{{ asset('assets/img/dalam-perjalanan-icon.svg') }}" alt="">
                                </span>
                                <div class="d-flex justify-content-between align-items-start flex-wrap">
                                    <div class="d-flex flex-column">
                                        <div class="d-flex align-items-center">
                                            <span class="text-gray-900 fs-5">Dalam perjalanan</span>
                                        </div>
                                        <div class="d-flex flex-wrap">
                                            <font style="font-size: 35pt">{{ $active }}</font>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="d-flex">
                                <span class="svg-icon-3">
                                    <img src="{{ asset('assets/img/terkirim-icon.svg') }}" alt="">
                                </span>
                                <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                                    <div class="d-flex flex-column">
                                        <div class="d-flex align-items-center">
                                            <span class="text-gray-900 fs-5">Terkirim</span>
                                        </div>
                                        <div class="d-flex flex-wrap">
                                            <font style="font-size: 35pt">{{ $delivered }}</font>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card card-docs flex-row-fluid mb-2">
                <div class="card-header d-flex justify-content-between">
                    <div class="card-title align-items-start flex-column">
                        Ringkasan User
                    </div>
                    <div class="card-toolbar">
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="d-flex">
                                <span class="svg-icon-3">
                                    <img src="{{ asset('assets/img/courier-icon.svg') }}" alt="">
                                </span>
                                <div class="d-flex justify-content-between align-items-start flex-wrap">
                                    <div class="d-flex flex-column">
                                        <div class="d-flex align-items-center">
                                            <span class="text-gray-900 fs-5">Kurir</span>
                                        </div>
                                        <div class="d-flex flex-wrap">
                                            <font style="font-size: 35pt">{{ $courier }}</font>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="d-flex">
                                <span class="svg-icon-3">
                                    <img src="{{ asset('assets/img/user-icon.svg') }}" alt="">
                                </span>
                                <div class="d-flex justify-content-between align-items-start flex-wrap">
                                    <div class="d-flex flex-column">
                                        <div class="d-flex align-items-center">
                                            <span class="text-gray-900 fs-5">User</span>
                                        </div>
                                        <div class="d-flex flex-wrap">
                                            <font style="font-size: 35pt">{{ $user }}</font>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-6">
            <div class="card card-docs flex-row-fluid mb-2">
                <div class="card-header d-flex justify-content-between">
                    <div class="card-title align-items-start flex-column">
                        Riwayat Pengiriman
                    </div>
                    <div class="card-toolbar">
                    </div>
                </div>
                <div class="card-body p-0">
                    @forelse ($shipments as $shipment)
                        <a href="{{ route('shipments.show', $shipment->id) }}" class="text-gray-900 text-hover-primary">
                            <div class="row {{ $loop->first ? 'mb-5 pt-5' : ($loop->last ? 'mt-5 pb-5' : 'my-5') }} px-10">
                                <div class="col-xl-8">
                                    <div class="d-flex">
                                        <div class="flex-column me-3">
                                            <div class="fs-2 fw-bold">{{ $shipment->airway_bill }}</div>
                                            <div class="fs-7">{{ $shipment->sender->name }}</div>
                                            <div class="fs-7">{{ $shipment->sender->subdistrict->city->name }}-{{ $shipment->sender->subdistrict->name }}</div>
                                        </div>
                                        <div class="flex-column me-3">
                                            <div class="fs-2 fw-bold">&nbsp;</div>
                                            <div class="fs-7">
                                                <span class="svg-icon svg-icon-muted svg-icon-2"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M14.4 11H3C2.4 11 2 11.4 2 12C2 12.6 2.4 13 3 13H14.4V11Z" fill="currentColor" />
                                                        <path opacity="0.3" d="M14.4 20V4L21.7 11.3C22.1 11.7 22.1 12.3 21.7 12.7L14.4 20Z" fill="currentColor" />
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="fs-7">&nbsp;</div>
                                        </div>
                                        <div class="flex-column me-3">
                                            <div class="fs-2 fw-bold">&nbsp;</div>
                                            <div class="fs-7">{{ $shipment->recipient->name }}</div>
                                            <div class="fs-7">{{ $shipment->recipient->subdistrict->city->name }}-{{ $shipment->recipient->subdistrict->name }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-4 text-lg-end mt-3 mt-lg-0">
                                    <div class="fs-4 fw-bold">
                                        {{ $shipment->cost_type == 'next_day' ? 'Same Day' : 'Instant Courier' }} Delivery
                                    </div>
                                    <div class="fs-4 fw-bold">
                                        Rp {{ number_format($shipment->total_price - $shipment->payment->method->cost, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                        </a>
                        @if (!$loop->last)
                            <div class="separator"></div>
                        @endif
                    @empty
                        <div class="justify-center">
                            Belum ada pengiriman
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card card-docs flex-row-fluid mb-2">
                <div class="card-header d-flex justify-content-between">
                    <div class="card-title align-items-start flex-column">
                        Grafik Pengiriman
                    </div>
                    <div class="card-toolbar" data-kt-buttons="true" data-kt-initialized="1">
                        <a class="btn btn-sm btn-color-gray-700 btn-active btn-active-primary bg-secondary px-4 me-2 filterBtn active" id="yearFilter">Tahun Ini</a>
                        <a class="btn btn-sm btn-color-gray-700 btn-active btn-active-primary bg-secondary px-4 me-2 filterBtn" id="monthFilter">Bulan Ini</a>
                        <a class="btn btn-sm btn-color-gray-700 btn-active btn-active-primary bg-secondary px-4 me-2 filterBtn" id="weekFilter">Minggu Ini</a>
                        <a class="btn btn-sm btn-color-gray-700 btn-active btn-active-primary bg-secondary btn-flex fw-bold" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" id="filterBtnDate">
                            <span class="svg-icon svg-icon-6 svg-icon-muted me-1">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M19.0759 3H4.72777C3.95892 3 3.47768 3.83148 3.86067 4.49814L8.56967 12.6949C9.17923 13.7559 9.5 14.9582 9.5 16.1819V19.5072C9.5 20.2189 10.2223 20.7028 10.8805 20.432L13.8805 19.1977C14.2553 19.0435 14.5 18.6783 14.5 18.273V13.8372C14.5 12.8089 14.8171 11.8056 15.408 10.964L19.8943 4.57465C20.3596 3.912 19.8856 3 19.0759 3Z"
                                        fill="currentColor"></path>
                                </svg>
                            </span>
                            Tanggal
                        </a>
                        <div class="menu menu-sub menu-sub-dropdown w-250px w-md-300px" data-kt-menu="true">
                            <div class="px-7 py-5">
                                <div class="fs-5 text-dark fw-bold">Filter Tanggal</div>
                            </div>
                            <div class="separator border-gray-200"></div>
                            <div class="px-7 py-5">
                                <input type="text" class="form-control" id="dateRangePicker">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <canvas id="shipment-chart" class="mh-400px"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        var ctx = document.getElementById('shipment-chart');
        var primaryColor = KTUtil.getCssVariableValue('--kt-primary');
        var dangerColor = KTUtil.getCssVariableValue('--kt-danger');
        var successColor = KTUtil.getCssVariableValue('--kt-success');
        var fontFamily = KTUtil.getCssVariableValue('--bs-font-sans-serif');
        var chartData, myChart;

        async function getData(year, month, week, dateRange) {
            await $.ajax({
                url: "{{ url()->current() }}",
                method: 'GET',
                dataType: 'JSON',
                data: $.param({
                    year: year,
                    month: month,
                    week: week,
                    dateRange: dateRange,
                }),
                success: function(data) {
                    chartData = {
                        labels: data.labels,
                        datasets: [{
                            label: 'Pengiriman',
                            data: data.data,
                            backgroundColor: "blue",
                            borderSkipped: false,
                        }]
                    }
                }
            });
        }

        $(document).ready(async function() {
            var start = moment().subtract(29, "days");
            var end = moment();

            function cb(start, end) {
                $("#dateRangePicker").html(start.format("MMMM D, YYYY") + " - " + end.format("MMMM D, YYYY"));
            }

            $("#dateRangePicker").daterangepicker({
                startDate: start,
                endDate: end,
                ranges: {
                    "Today": [moment(), moment()],
                    "Yesterday": [moment().subtract(1, "days"), moment().subtract(1, "days")],
                    "Last 7 Days": [moment().subtract(6, "days"), moment()],
                    "Last 30 Days": [moment().subtract(29, "days"), moment()],
                    "This Month": [moment().startOf("month"), moment().endOf("month")],
                    "Last Month": [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")]
                }
            }, cb);

            cb(start, end);

            await getData(true, false, false);
            myChart = new Chart($('#shipment-chart'), {
                type: 'bar',
                data: chartData,
                options: {
                    scale: {
                        ticks: {
                            precision: 0
                        }
                    },
                    plugins: {
                        title: {
                            display: false,
                        }
                    },
                    responsive: true,
                    interaction: {
                        intersect: false,
                    },
                    scales: {
                        x: {
                            stacked: true,
                        },
                        y: {
                            stacked: true
                        }
                    }
                },
                defaults: {
                    global: {
                        defaultFont: fontFamily
                    }
                }
            });
        });

        const filterButtons = $('.filterBtn');
        filterButtons.on('click', async function() {
            filterButtons.removeClass('active');
            $('#filterBtnDate').removeClass('active');
            $(this).addClass('active');

            const clickedButtonId = $(this).attr('id');

            if (clickedButtonId == 'yearFilter') {
                await getData(1, 0, 0, 0);
            } else if (clickedButtonId == 'monthFilter') {
                await getData(0, 1, 0, 0);
            } else if (clickedButtonId == 'weekFilter') {
                await getData(0, 0, 1, 0);
            }
            myChart.data = chartData;
            myChart.update();
        });

        $('#dateRangePicker').on('apply.daterangepicker', async function() {
            filterButtons.removeClass('active');
            $('#filterBtnDate').addClass('active');
            const dateRange = $(this).val();
            await getData(0, 0, 0, dateRange);
            myChart.data = chartData;
            myChart.update();
        });
    </script>
@endpush
