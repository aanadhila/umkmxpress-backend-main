<!--

To run this demo, you need to replace 'YOUR_API_KEY' with an API key from the ArcGIS Developers dashboard.

Sign up for a free account and get an API key.

https://developers.arcgis.com/documentation/mapping-apis-and-services/get-started/

-->


<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="initial-scale=1,maximum-scale=1,user-scalable=no" />
    <title>Esri Leaflet</title>

    <!-- Load Leaflet from CDN -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>

    <!-- Load Esri Leaflet from CDN -->
    <script src="https://unpkg.com/esri-leaflet@3.0.12/dist/esri-leaflet.js"></script>
    <script src="https://unpkg.com/esri-leaflet-vector@4.2.3/dist/esri-leaflet-vector.js"></script>

    <!-- Load Esri Leaflet Geocoder from CDN -->
    <link rel="stylesheet" href="https://unpkg.com/esri-leaflet-geocoder@3.1.4/dist/esri-leaflet-geocoder.css">
    <script src="https://unpkg.com/esri-leaflet-geocoder@3.1.4/dist/esri-leaflet-geocoder.js"></script>

    <style>
      body {
        margin: 0;
        padding: 0;
      }
      #map {
        position: absolute;
        top: 0;
        bottom: 0;
        right: 0;
        left: 0;
        font-family: Arial, Helvetica, sans-serif;
        font-size: 14px;
        color: #323232;
      }
    </style>
  </head>
  <body>
    <div id="map"></div>

    <script>

      const apiKey = "AAPKa054eb039c504f29aff73842de44ff3dw82sLafelK4vx18Sa-NmAGzWxvULt5EBQrs0XcGY35C0UFZA9loxpXu7s2SHU06U";

      const basemapEnum = "arcgis/navigation";

      const map = L.map("map", {
        minZoom: 2
      })

      map.setView([-33.8688, 151.2093], 14); // Sydney

      L.esri.Vector.vectorBasemapLayer(basemapEnum, {
        apiKey: apiKey
      }).addTo(map);

      const searchControl = L.esri.Geocoding.geosearch({
        position: "topright",
        placeholder: "Enter an address or place e.g. 1 York St",
        useMapBounds: false,

        providers: [
          L.esri.Geocoding.arcgisOnlineProvider({
            apikey: apiKey,
            nearby: {
              lat: -33.8688,
              lng: 151.2093
            }
          })
        ]

      }).addTo(map);

      const results = L.layerGroup().addTo(map);

      searchControl.on("results", (data) => {
        results.clearLayers();

        for (let i = data.results.length - 1; i >= 0; i--) {
          const marker = L.marker(data.results[i].latlng);

          const lngLatString = `${Math.round(data.results[i].latlng.lng * 100000) / 100000}, ${
            Math.round(data.results[i].latlng.lat * 100000) / 100000
          }`;
          marker.bindPopup(`<b>${lngLatString}</b><p>${data.results[i].properties.LongLabel}</p>`);

          results.addLayer(marker);

          marker.openPopup();

        }

      });

    </script>
  </body>
</html>