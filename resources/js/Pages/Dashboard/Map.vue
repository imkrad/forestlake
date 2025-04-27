<template>
    <div>
        <l-map ref="mymap" style="width: 100%; height: 450px; border-radius: 10px;" 
            :zoom="zoom"
            :center="center"
            :options="mapOptions"
            @click="setMarker">
            
            <!-- Entrance Marker -->
            <l-marker :lat-lng="entrance" :icon="startIcon"></l-marker>

            <!-- Dynamic Marker -->
            <l-marker v-if="markerLatLng" :lat-lng="markerLatLng" :icon="endIcon"></l-marker>

            <!-- User's Current Location -->
            <l-marker v-if="userLocation" :lat-lng="userLocation" :icon="humanIcon">
                <l-popup>You are here</l-popup>
            </l-marker>

            <l-marker :lat-lng="fish" :icon="fishIcon"></l-marker>
            <l-marker :lat-lng="olive" :icon="oliveIcon"></l-marker>
            <l-marker :lat-lng="date" :icon="dateIcon"></l-marker>
            <l-marker :lat-lng="anahaw" :icon="anahawIcon"></l-marker>
            <l-marker :lat-lng="royal" :icon="royalIcon"></l-marker>
            <l-marker :lat-lng="sugar" :icon="sugarIcon"></l-marker>

            <!-- Tile Layer -->
            <l-tile-layer :url="url" :attribution="attribution" />
        </l-map>
    </div>
</template>

<script>
import { latLng, icon } from "leaflet";
import "leaflet-routing-machine";
import { LMap, LTileLayer, LMarker, LPopup, LPolygon } from "@vue-leaflet/vue-leaflet";

export default {
    props: ['coordinate'],
    components: { LMap, LTileLayer, LMarker, LPopup, LPolygon },
    data() {
        return {
            zoom: 18,
            center: latLng(6.9402912456685435, 122.08175137179076),
            url: "https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png",
            attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors',
            mapOptions: { zoomSnap: 0.5 },
            entrance: latLng(6.939866131447623, 122.07995602843239),
            markerLatLng: null,
            userLocation: null, // Store user's location
            routingControl: null,
            fish: latLng(6.93967,122.08069),
            olive: latLng(6.939928,122.081741),
            date: latLng(6.940354,122.082299),
            anahaw: latLng(6.94069,122.08275),
            royal: latLng(6.939971,122.082717),
            sugar: latLng(6.940072,122.083426),
            humanIcon: icon({
                iconUrl: "/images/icons/walking.png", // Human icon
                iconSize: [40, 40], // Adjust size
                iconAnchor: [20, 40], // Anchor point to align correctly
                popupAnchor: [0, -40] // Adjust popup position
            }),
            startIcon: icon({
                iconUrl: "/images/icons/start.png", // Human icon
                iconSize: [40, 40], // Adjust size
                iconAnchor: [20, 40], // Anchor point to align correctly
                popupAnchor: [0, -40] // Adjust popup position
            }),
            endIcon: icon({
                iconUrl: "/images/icons/end.png", // Human icon
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
    mounted() {
        this.$nextTick(() => {
            setTimeout(this.initRouting, 500);
            this.getUserLocation(); // Get user's current location
        });
    },
    methods: {
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
            this.updateRoute();
        },
        getUserLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        this.userLocation = latLng(position.coords.latitude, position.coords.longitude);
                        // this.center = this.userLocation; // Move map to user location
                    },
                    (error) => {
                        console.error("Error getting location: ", error.message);
                    }
                );
            } else {
                console.warn("Geolocation is not supported by this browser.");
            }
        }
    },
};
</script>

<style>
.leaflet-control-attribution {
    display: none;
}
</style>
