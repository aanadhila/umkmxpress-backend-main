@extends('layouts.app')
@section('title', 'Clusters')
@push('css')
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        #map {
            height: 600px;
        }
    </style>

@section('page-title')
    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
        <h1 class="page-heading d-flex text-dark fw-bold flex-column justify-content-center my-0">
            Pemetaan Wilayah Kluster UMKM
        </h1>
    </div>
@endsection
@section('content')
    <div class="card card-docs flex-row-fluid mb-5">
       
        <div class="card-body pt-0">
            <div id="map"></div>
        </div>
    </div>
  
@endsection
@push('js')
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://unpkg.com/@turf/turf/turf.min.js"></script>
<script>
    // Inisialisasi peta
    var map = L.map('map').setView([-7.9563115, 112.6240056], 13);

    // Parsing data JSON dari Laravel ke JavaScript
    const data = @json($json);

    // Tambahkan tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
    }).addTo(map);

    // Warna untuk masing-masing cluster
    var clusterColors = {
        0: 'purple',
        1: 'red',
        2: 'green'
    };

    // Group data berdasarkan klaster
    var clusters = {};
    data.forEach(function(item) {
        if (!clusters[item.kmeans_cluster]) {
            clusters[item.kmeans_cluster] = [];
        }
        clusters[item.kmeans_cluster].push(item);
    });

    // Tambahkan marker dan convex hull untuk setiap klaster
    Object.keys(clusters).forEach(function(clusterId) {
        var points = clusters[clusterId].map(function(item) {
            return [item.longitude, item.latitude];
        });

        var color = clusterColors[clusterId];

        // Tambahkan marker
        clusters[clusterId].forEach(function(item) {
            L.circleMarker([item.latitude, item.longitude], {
                radius: 8,
                fillColor: color,
                color: color,
                weight: 1,
                opacity: 1,
                fillOpacity: 0.8
            }).addTo(map)
            .bindPopup(item.name);
        });

        // Hitung convex hull menggunakan Turf.js
        // var convexHull = turf.convex(turf.points(points));
        var convexHull = null

        // Tambahkan convex hull ke peta
        if (convexHull) {
            L.geoJSON(convexHull, {
                style: {
                    color: color,
                    weight: 2,
                    opacity: 0.6,
                    fillOpacity: 0.2
                }
            }).addTo(map);
        }
    });
</script>
    @include('components.gmaps', ['function' => 'initMap'])
@endpush
