<template>
    <div>
        <l-map ref="mymap" style="width: 100%; height: 650px; border-radius: 10px;" 
            :zoom="zoom"
            :center="center"
            :options="mapOptions"
            >
            
            <!-- @click="setMarker" -->
            <!-- @click="addPolygonPoint" -->
            
            <l-marker :lat-lng="fish" :icon="fishIcon"></l-marker>
            <l-marker :lat-lng="olive" :icon="oliveIcon"></l-marker>
            <l-marker :lat-lng="date" :icon="dateIcon"></l-marker>
            <l-marker :lat-lng="anahaw" :icon="anahawIcon"></l-marker>
            <l-marker :lat-lng="royal" :icon="royalIcon"></l-marker>
            <l-marker :lat-lng="sugar" :icon="sugarIcon"></l-marker>



            <l-tile-layer :url="url" :attribution="attribution" />
            <l-polygon v-if="polygonPoints.length > 0"
                        :lat-lngs="[polygonPoints]"
                        color="blue"
                        :fill-opacity="0.3">
                <l-popup>Polygon being drawn</l-popup>
            </l-polygon>
            <l-polygon :lat-lngs="blockA1" color="green" :fill-opacity="0.3" @click="openSection(14,7)">
                <l-marker :lat-lng="['6.939654626083151','122.08062033030954']" :icon="getTextIcon('A')" @click="openSection(14,7)"></l-marker>
            </l-polygon>
            <!-- <l-polygon
    v-for="(box, index) in smallGraveBoxes"
    :key="index"
    :lat-lngs="box"
    color="gray"
    :weight="1"
    :fill-opacity="0.6"
  /> -->
            <l-polygon :lat-lngs="blockB1" color="green" :fill-opacity="0.3" @click="openSection(15,7)">
                <l-marker :lat-lng="['6.939931532141487','122.08093139114197']" :icon="getTextIcon('B')" @click="openSection(14,7)"></l-marker>
            </l-polygon>
            <l-polygon :lat-lngs="blockC1" color="green" :fill-opacity="0.3" @click="openSection(16,7)">
                <l-marker :lat-lng="['6.9400220590867665','122.08127463068124']" :icon="getTextIcon('C')" @click="openSection(14,7)"></l-marker>
            </l-polygon>
            <l-polygon :lat-lngs="blockD1" color="green" :fill-opacity="0.3" @click="openSection(17,7)">
                <l-marker :lat-lng="['6.939558773948081','122.08098502232']" :icon="getTextIcon('D')" @click="openSection(14,7)"></l-marker>
            </l-polygon>
            <l-polygon :lat-lngs="blockE1" color="green" :fill-opacity="0.3" @click="openSection(18,7)">
                <l-marker :lat-lng="['6.939771778666169','122.08132289874143']" :icon="getTextIcon('E')" @click="openSection(14,7)"></l-marker>
            </l-polygon>
        </l-map>
        <!-- <div class="mt-2">
      <button @click="clearPolygon" style="margin-right: 10px;">Clear</button>
      <button @click="savePolygon">Save</button>
    </div> -->
    </div>
    <Section ref="section"/>
</template>

<script>
import Section from './Section.vue';
import { latLng, icon, divIcon } from "leaflet";
import "leaflet-routing-machine";
import { LMap, LTileLayer, LMarker, LPopup, LPolygon, LTooltip } from "@vue-leaflet/vue-leaflet";

export default {
    props: ['coordinate'],
    components: { LMap, LTileLayer, LMarker, LPopup, LPolygon, LTooltip, Section },
    data() {
        return {
            zoom: 30,
            center: latLng(6.94034, 122.082267),
            url: "https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png",
            attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors',
            mapOptions: { 
                zoomSnap: 0.5,
                dragging: false,           // Disable dragging
                scrollWheelZoom: false,    // Disable zoom by scroll
                doubleClickZoom: false,    // Disable zoom by double click
                boxZoom: false,            // Disable box zoom
                touchZoom: false,          // Disable touch zoom
                keyboard: false,           // Disable keyboard navigation
                tap: false            
             },
            fish: latLng(6.93967,122.08069),
            olive: latLng(6.939928,122.081741),
            date: latLng(6.940354,122.082299),
            anahaw: latLng(6.94069,122.08275),
            royal: latLng(6.939971,122.082717),
            sugar: latLng(6.940072,122.083426),
            markerLatLng: null,
            userLocation: null, // Store user's location
            routingControl: null,
            humanIcon: icon({
                iconUrl: "/images/icons/walking.png", // Human icon
                iconSize: [40, 40], // Adjust size
                iconAnchor: [20, 40], // Anchor point to align correctly
                popupAnchor: [0, -40] // Adjust popup position
            }),
            fishIcon: icon({
                iconUrl: "/images/icons/1.png", // Human icon
                // iconSize: [120, 30], // Adjust size
                iconAnchor: [20, 40], // Anchor point to align correctly
                popupAnchor: [0, -40] // Adjust popup position
            }),
            oliveIcon: icon({
                iconUrl: "/images/icons/2.png", // Human icon
                // iconSize: [120, 30], // Adjust size
                iconAnchor: [20, 40], // Anchor point to align correctly
                popupAnchor: [0, -40] // Adjust popup position
            }),
            dateIcon: icon({
                iconUrl: "/images/icons/3.png", // Human icon
                // iconSize: [120, 30], // Adjust size
                iconAnchor: [20, 40], // Anchor point to align correctly
                popupAnchor: [0, -40] // Adjust popup position
            }),
            anahawIcon: icon({
                iconUrl: "/images/icons/4.png", // Human icon
                // iconSize: [120, 30], // Adjust size
                iconAnchor: [20, 40], // Anchor point to align correctly
                popupAnchor: [0, -40] // Adjust popup position
            }),
            royalIcon: icon({
                iconUrl: "/images/icons/5.png", // Human icon
                // iconSize: [120, 30], // Adjust size
                iconAnchor: [20, 40], // Anchor point to align correctly
                popupAnchor: [0, -40] // Adjust popup position
            }),
            sugarIcon: icon({
                iconUrl: "/images/icons/6.png", // Human icon
                // iconSize: [120, 30], // Adjust size
                iconAnchor: [20, 40], // Anchor point to align correctly
                popupAnchor: [0, -40] // Adjust popup position
            }),
            blockA1: [
                [6.939990108402171, 122.08064208765819],
                [6.939787754016145, 122.08047586219848],
                [6.939638650728658, 122.08040615474762],
                [6.9395108478732315, 122.0803632578548],
                [6.939393695225278, 122.08069034666262],
                [6.9397451530816845, 122.08083512367597]
            ],
    //         smallGraveBoxes: [
    //     [
    //       [6.93969, 122.08051],
    //       [6.93969, 122.08054],
    //       [6.93967, 122.08054],
    //       [6.93967, 122.08051],
    //     ],
    //     [
    //       [6.93967, 122.08051],
    //       [6.93967, 122.08054],
    //       [6.93965, 122.08054],
    //       [6.93965, 122.08051],
    //     ],
    //     // Add more boxes here...
    //   ],
            blockB1: [
                [6.939996498604283, 122.08064675331117],
                [6.939746218170099, 122.08083987236024],
                [6.939842070266989, 122.08114564418794],
                [6.940118976215063, 122.08107054233551]
            ],
            blockC1: [
                [6.940117911127487, 122.08108166032443],
                [6.939846330294729, 122.08115673751944],
                [6.9399208819114815, 122.08145704629948],
                [6.9401871375892075, 122.08139805707485]
            ],
            blockD1: [
                [6.939388370104226, 122.0807009116925],
                [6.939734502847468, 122.0808349781122],
                [6.939835680062802, 122.08114064954904],
                [6.939542798590344, 122.08124790268481],
                [6.939292517914935, 122.0810065831294]
            ],
            blockE1: [
                [6.939843667736772, 122.08115137486263],
                [6.939907569123648, 122.08145704629948],
                [6.9398170421563465, 122.08147849692662],
                [6.939678589113864, 122.08137660644769],
                [6.939545461150006, 122.08125862799837]
            ],
            polygonPoints: [], 
        };
    },
    watch: {
        coordinate: {
            immediate: true,
            deep: true,
            handler(newCoordinate) {
                if (newCoordinate && newCoordinate.latitude && newCoordinate.longitude) {
                    this.markerLatLng = latLng(newCoordinate.latitude, newCoordinate.longitude);
                    this.updateRoute();
                }
            }
        }
    },
    // mounted() {
    //     this.$nextTick(() => {
    //         setTimeout(this.initRouting, 500);
    //         this.getUserLocation(); // Get user's current location
    //     });
    // },
    methods: {
        openSection(data,area){
            this.$refs.section.show(data,area);
        },
        addPolygonPoint(e) {
            this.polygonPoints.push(e.latlng);
        },
        clearPolygon() {
            this.polygonPoints = [];
        },
        savePolygon() {
            const formattedPoints = this.polygonPoints.map(point => [point.lat, point.lng]);
            console.log("Formatted Polygon Coordinates:", formattedPoints);
            // console.log("Polygon coordinates to save:", this.polygonPoints);
        // You can POST this to an API or store in a DB
        },
        getTextIcon(text) {
            return divIcon({
                className: 'text-label',
                html: `<div style="background: transparent; font-size: 18px; color: black;">${text}</div>`,
                iconSize: [30, 30],
                iconAnchor: [15, 15]
            });
        },
        initRouting() {
            if (!this.$refs.mymap || !this.$refs.mymap.leafletObject) {
                console.warn("Map not ready yet.");
                return;
            }

            const map = this.$refs.mymap.leafletObject;

            if (this.routingControl) {
                map.removeControl(this.routingControl);
            }

            this.routingControl = L.Routing.control({
                waypoints: [
                    this.entrance,
                    this.markerLatLng || this.entrance
                ],
                routeWhileDragging: true,
                show: true,
                createMarker: () => null,
                lineOptions: { styles: [{ color: 'blue', weight: 5 }] }
            }).addTo(map);

            this.routingControl.on('routesfound', (e) => {
                const route = e.routes[0];
                map.fitBounds(L.latLngBounds(route.coordinates));
            });
        },
        updateRoute() {
            if (this.routingControl && this.markerLatLng) {
                this.routingControl.setWaypoints([
                    this.entrance,
                    this.markerLatLng
                ]);
            }
        },
        setMarker(event) {
            this.markerLatLng = event.latlng;
            console.log(this.markerLatLng);
        },
        // getUserLocation() {
        //     if (navigator.geolocation) {
        //         navigator.geolocation.getCurrentPosition(
        //             (position) => {
        //                 this.userLocation = latLng(position.coords.latitude, position.coords.longitude);
        //                 // this.center = this.userLocation; // Move map to user location
        //             },
        //             (error) => {
        //                 console.error("Error getting location: ", error.message);
        //             }
        //         );
        //     } else {
        //         console.warn("Geolocation is not supported by this browser.");
        //     }
        // }
    },
};
</script>

<style>
.leaflet-control-attribution {
    display: none;
}
.text-label {
    text-align: center;
    font-weight: bold;
    border-radius: 50%;
}
</style>
