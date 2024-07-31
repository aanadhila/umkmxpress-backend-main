@extends('layouts.app')
@section('title', 'Clusters')

@push('css')
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />
    <style>
        #map {
            height: 600px;
        }
    </style>
@endpush

@section('page-title')
    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
        <h1 class="page-heading d-flex text-dark fw-bold flex-column justify-content-center my-0">
            Pemetaan Wilayah Pengiriman UMKM
        </h1>
    </div>
@endsection

@section('content')
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="col-12">
                            <form id="search-form" class="row justify-content-end" action="" method="GET">
                                <div class="col-3">
                                    <select id="mySelect" name="sender_id" class="form-control">
                                        <option value="">--Pilih--</option>
                                        @foreach ($senders as $sender)
                                            <option value="{{ $sender->id }}">
                                                {{ $sender->name . ' - ' . $sender->address }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-3">
                                    <input type="date" id="dateFilter" name="date" value="{{ date('Y-m-d') }}"
                                        class="form-control">
                                </div>
                                <div class="col-3">
                                    <input type="number" id="courierCount" name="courier_count" min="1"
                                        value="1" class="form-control" placeholder="Number of Couriers">
                                </div>
                                <div class="col-2 d-flex flex-row">
                                    <button id="btnCari" class="btn btn-primary me-2" type="submit">Cari</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card card-docs flex-row-fluid mb-5">
        <div class="card-body pt-0">
            <div id="map"></div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="infoModalLabel">Informasi Pengiriman</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Nama Penerima:</strong> <span id="recipient-name"></span></p>
                    <p><strong>Nama Lokasi:</strong> <span id="location-name"></span></p>
                    <p><strong>Cluster:</strong> <span id="cluster-id"></span></p>
                    <p><strong>Kurir:</strong> <span id="courier-name"></span></p>
                    <p><strong>Barang:</strong></p>
                    <ul id="items-list"></ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="reclusterConfirmationModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Reclustering</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Current assignments use <span id="existingClusterCount"></span> clusters.</p>
                    <p>You've requested <span id="newClusterCount"></span> clusters.</p>
                    <p>Do you want to recluster the shipments?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmRecluster">Confirm Recluster</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="confirmationModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Reassignment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to reassign the couriers?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="cancelReassign">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmReassign">Confirm</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://unpkg.com/@turf/turf/turf.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        $(document).ready(function() {
            var map = L.map('map').setView([-7.9563115, 112.6240056], 13);
            var markersLayer = L.layerGroup().addTo(map);
            var clusterColors = [
                '#FF5733', '#33FF57', '#3357FF', '#FF33F1', '#33FFF1',
                '#F1FF33', '#FF8C33', '#33FFAA', '#8C33FF', '#FF3333'
            ];
            var currentClusterData = null;

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
            }).addTo(map);

            let previousCourierCount = $('#courierCount').val();
            let isAssignmentRearranged = false;

            $('#btnCari').click(function(e) {
                console.log('Button clicked');
                e.preventDefault();
                fetchData(false);
            });

            function fetchData(forceReassign = false) {
                const sender = $('#mySelect').val();
                const date = $('#dateFilter').val();
                const courierCount = $('#courierCount').val();

                if (!sender || !date || !courierCount || courierCount < 1) {
                    toastr.error('Please fill in all fields correctly', 'Error');
                    return;
                }

                $.ajax({
                    url: `{{ route('sender-packets.index') }}`,
                    type: 'GET',
                    data: {
                        sender_id: sender,
                        date: date,
                        courier_count: courierCount,
                        force_reassign: forceReassign
                    },
                    success: function(response) {
                        if ($.isEmptyObject(response.clusterData)) {
                            toastr.error('No shipments found for this date', 'Error');
                        } else if (response.existingAssignments && !forceReassign) {
                            if (response.existingClusterCount != courierCount) {
                                showReclusterConfirmation(response.existingClusterCount, courierCount);
                            } else {
                                updateMap(response.clusterData, true);
                            }
                        } else {
                            updateMap(response.clusterData, false);
                        }
                        currentClusterData = response.clusterData;
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                        toastr.error('An error occurred. Please try again.', 'Error');
                    }
                });
            }

            function updateMap(clusterData, isExisting) {
                if (isExisting && JSON.stringify(clusterData) === JSON.stringify(currentClusterData)) {
                    // Data hasn't changed, no need to update the map
                    return;
                }

                markersLayer.clearLayers();

                clusterData.forEach(function(cluster, index) {
                    var color = isExisting ? cluster.color : clusterColors[index % clusterColors.length];

                    cluster.shipments.forEach(function(shipment) {
                        addMarkerToMap(shipment, color, cluster);
                    });

                    if (cluster.shipments.length > 2) {
                        drawConvexHull(cluster.shipments, color);
                    }
                });

                fitMapToBounds();
            }

            function addMarkerToMap(shipment, color, cluster) {
                var marker = L.circleMarker([shipment.lat, shipment.lon], {
                        radius: 8,
                        fillColor: color,
                        color: color,
                        weight: 1,
                        opacity: 1,
                        fillOpacity: 0.8
                    }).addTo(markersLayer)
                    .bindPopup(`
            Cluster: ${cluster.cluster_id}<br>
            Courier: ${cluster.courier.user.name}<br>
            Recipient: ${shipment.recipient.name}<br>
            Address: ${shipment.recipient.address}
        `)
                    .on('click', function() {
                        $('#recipient-name').text(shipment.recipient.name);
                        $('#location-name').text(shipment.recipient.address);
                        $('#cluster-id').text(cluster.cluster_id);
                        $('#courier-name').text(cluster.courier.user.name);
                        $('#items-list').empty();
                        shipment.items.forEach(function(item) {
                            $('#items-list').append(
                                `<li>${item.name} (Weight: ${item.weight}, Amount: ${item.amount})</li>`
                            );
                        });
                        $('#infoModal').modal('show');
                    });
            }

            function drawConvexHull(shipments, color) {
                var points = shipments.map(shipment => [shipment.lon, shipment.lat]);
                var convexHull = turf.convex(turf.points(points));
                if (convexHull) {
                    L.geoJSON(convexHull, {
                        style: {
                            color: color,
                            weight: 2,
                            opacity: 0.6,
                            fillOpacity: 0.2
                        }
                    }).addTo(markersLayer);
                }
            }

            function fitMapToBounds() {
                var group = new L.featureGroup(markersLayer.getLayers());
                map.fitBounds(group.getBounds());
            }


            function showReclusterConfirmation(existingCount, newCount) {
                $('#existingClusterCount').text(existingCount);
                $('#newClusterCount').text(newCount);
                $('#reclusterConfirmationModal').modal('show');
            }


            $('#confirmRecluster').click(function() {
                $('#reclusterConfirmationModal').modal('hide');
                fetchData(true);
            });

            function updateMap(clusterData, isExisting) {
                markersLayer.clearLayers();

                clusterData.forEach(function(cluster, index) {
                    var color = isExisting ? cluster.color : clusterColors[index % clusterColors.length];

                    cluster.shipments.forEach(function(shipment) {
                        addMarkerToMap(shipment, color, cluster);
                    });

                    if (cluster.shipments.length > 2) {
                        drawConvexHull(cluster.shipments, color);
                    }
                });

                fitMapToBounds();
            }

            function showConfirmationPopup() {
                $('#confirmationModal').modal('show');
            }

            $('#confirmReassign').click(function() {
                $('#confirmationModal').modal('hide');
                fetchData(true);
            });

            $('#cancelReassign').click(function() {
                $('#confirmationModal').modal('hide');
                // Reset the courier count to the previous value
                $('#courierCount').val(previousCourierCount);
            });


            function updateMap(clusterData) {
                markersLayer.clearLayers();

                clusterData.forEach(function(cluster, index) {
                    var color = clusterColors[index % clusterColors.length];
                    var points = [];

                    cluster.shipments.forEach(function(shipment) {
                        var marker = L.circleMarker([shipment.lat, shipment.lon], {
                                radius: 8,
                                fillColor: color,
                                color: color,
                                weight: 1,
                                opacity: 1,
                                fillOpacity: 0.8
                            }).addTo(markersLayer)
                            .bindPopup(`
                    Cluster: ${cluster.cluster_id}<br>
                    Courier: ${cluster.courier.user.name}<br>
                    Recipient: ${shipment.recipient.name}<br>
                    Address: ${shipment.recipient.address}
                `)
                            .on('click', function() {
                                $('#recipient-name').text(shipment.recipient.name);
                                $('#location-name').text(shipment.recipient.address);
                                $('#cluster-id').text(cluster.cluster_id);
                                $('#courier-name').text(cluster.courier.user.name);
                                $('#items-list').empty();
                                shipment.items.forEach(function(item) {
                                    $('#items-list').append(
                                        `<li>${item.name} (Weight: ${item.weight}, Amount: ${item.amount})</li>`
                                    );
                                });
                                $('#infoModal').modal('show');
                            });

                        points.push([shipment.lon, shipment.lat]);
                    });

                    if (points.length > 2) {
                        var convexHull = turf.convex(turf.points(points));
                        if (convexHull) {
                            L.geoJSON(convexHull, {
                                style: {
                                    color: color,
                                    weight: 2,
                                    opacity: 0.6,
                                    fillOpacity: 0.2
                                }
                            }).addTo(markersLayer);
                        }
                    }
                });

                // Fit the map to show all markers
                var group = new L.featureGroup(markersLayer.getLayers());
                map.fitBounds(group.getBounds());
            }
        });
    </script>
@endpush
