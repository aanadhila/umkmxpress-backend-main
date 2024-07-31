<!DOCTYPE html>
<html>
<head>
    <title>Google Static Maps Kecamatan</title>
</head>
<body>
    <div id="map" style="width: 100%; height: 500px;"></div>
    <img id="staticMap" style="width: 100%; height: 500px;">
    <script>
        function initMap() {
            const geocoder = new google.maps.Geocoder();
            const address = 'Gubeng, Surabaya, Jawa Timur'; // Ganti dengan nama kecamatan yang ingin dicari

            geocoder.geocode({ address: address }, (results, status) => {
                if (status === 'OK' && results[0]) {
                    const bounds = results[0].geometry.bounds;
                    const center = results[0].geometry.location;

                    const mapOptions = {
                        center: center,
                        zoom: 14,
                    };

                    const map = new google.maps.Map(document.getElementById('map'), mapOptions);

                    // Gambar batas kecamatan pada peta
                    new google.maps.Rectangle({
                        bounds: bounds,
                        map: map,
                        fillColor: '#AA0000',
                        fillOpacity: 0.35,
                        strokeColor: '#FF0000',
                        strokeOpacity: 0.8,
                        strokeWeight: 2
                    });

                    // Membuat URL gambar static maps dengan batas kecamatan
                    const staticMapURL = `https://maps.googleapis.com/maps/api/staticmap?center=${center.lat()},${center.lng()}&zoom=14&size=600x400&path=fillcolor:0xAA000033|color:0xFFFFFF00|${bounds.getSouthWest().lat()},${bounds.getSouthWest().lng()}|${bounds.getNorthEast().lat()},${bounds.getNorthEast().lng()}&key={{ config('app.gmaps_api_key') }}`;

                    // Menampilkan gambar static maps
                    document.getElementById('staticMap').src = staticMapURL;
                } else {
                    console.error('Geocode was not successful for the following reason:', status);
                }
            });
        }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('app.gmaps_api_key') }}&callback=initMap"></script>
</body>
</html>
