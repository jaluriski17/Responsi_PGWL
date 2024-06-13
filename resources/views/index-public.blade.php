@extends('template')



@section('content')
    <div id="map" style="width: 100vw; height: 100vh; margin: 0"></div>
@endsection

@section('script')

    <script>
        //map
        var map = L.map('map').setView([-7.55, 109], 10);

        //Basemap
        var om = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        var sa = L.tileLayer('https://tiles.stadiamaps.com/tiles/alidade_satellite/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);




 /* GeoJSON Point */
 var point = L.geoJson(null, {
            onEachFeature: function(feature, layer) {
                var popupContent = "Nama: " + feature.properties.name + "<br>" +
                    "Deskripsi: " + feature.properties.description + "<br>" +
                    "Foto: <img src='{{ asset('storage/images/') }}/" + feature.properties.image + "'class='img-thumbnail' alt='...'>"
                    ;

                layer.on({
                    click: function(e) {
                        point.bindPopup(popupContent);
                    },
                    mouseover: function(e) {
                        point.bindTooltip(feature.properties.name);
                    },
                });
            },
        });
        $.getJSON("{{ route('api.points') }}", function(data) {
            point.addData(data);
            map.addLayer(point);
        });

        /* GeoJSON Polyline */
        var polyline = L.geoJson(null, {
            onEachFeature: function(feature, layer) {
                var popupContent = "Nama: " + feature.properties.name + "<br>" +
                    "Deskripsi: " + feature.properties.description + "<br>" +
                    "Foto: <img src='{{ asset('storage/images/') }}/" + feature.properties.image + "'class='img-thumbnail' alt='...'>"
                    ;
                layer.on({
                    click: function(e) {
                        polyline.bindPopup(popupContent);
                    },
                    mouseover: function(e) {
                        polyline.bindTooltip(feature.properties.name);
                    },
                });
            },
        });
        $.getJSON("{{ route('api.polylines') }}", function(data) {
            polyline.addData(data);
            map.addLayer(polyline);
        });

        /* GeoJSON polygon */
        var polygon = L.geoJson(null, {
            onEachFeature: function(feature, layer) {
                var popupContent = "Nama: " + feature.properties.name + "<br>" +
                    "Deskripsi: " + feature.properties.description + "<br>" +
                    "Foto: <img src='{{ asset('storage/images/') }}/" + feature.properties.image + "'class='img-thumbnail' alt='...'>"
                    ;
                layer.on({
                    click: function(e) {
                        polygon.bindPopup(popupContent);
                    },
                    mouseover: function(e) {
                        polygon.bindTooltip(feature.properties.name);
                    },
                });
            },
        });
        $.getJSON("{{ route('api.polygons') }}", function(data) {
            polygon.addData(data);
            map.addLayer(polygon);
        });



        // Function to create popup content
        function createPopupContent(feature) {
            return " Kecamatan : " + feature.properties.WADMKC + "<br>" +
                    " Luas:  " + feature.properties.Luas;
        }

        // Function to style each feature
        function style(feature) {
            return {
                fillColor: getRandomColor(),
                weight: 1,
                opacity: 1,
                color: 'white',
                dashArray: '3',
                fillOpacity: 0.5
            };
        }

        // Function to generate random color
        function getRandomColor() {
            var letters = '0123456789ABCDEF';
            var color = '#';
            for (var i = 0; i < 6; i++) {
                color += letters[Math.floor(Math.random() * 16)];
            }
            return color;
        }

        /* GeoJSON shp*/
        var polygons  = L.geoJson(null, {
            style: style, // Apply the style function here
            onEachFeature: function(feature, layer) {
                var popupContent = createPopupContent(feature);
                layer.on({
                    click: function(e) {
                        layer.bindPopup(popupContent).openPopup(e.latlng);
                    },

                });
            },
        });

        // Load GeoJSON data
        $.getJSON("{{ asset('cilacap.json') }}", function(data) {
            polygons.addData(data);
            map.addLayer(polygons);
        });

        

        //layer control
        var overlayMaps = {
            "Point": point,
            "Polyline": polyline,
            "Polygon": polygon
        };


        var basemap ={
            "OpenStreetMap":om,
            " Stadia AlidadeSatellite":sa
        };

        var layerControl = L.control.layers(basemap, overlayMaps).addTo(map);


        //var layerControl = L.control.layers(null, overlayMaps, {collapsed: false}).addTo(map);

    </script>
@endsection
