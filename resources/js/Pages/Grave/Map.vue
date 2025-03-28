<template>
    <div>
        <l-map ref="mymap" style="width: 100%; height: 500px; border-radius: 10px;" 
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

            <!-- Tile Layer -->
            <l-tile-layer :url="url" :attribution="attribution" />
        </l-map>
    </div>
</template>

<script>
import { latLng, icon } from "leaflet";
import "leaflet-routing-machine";
import { LMap, LTileLayer, LMarker, LPopup } from "@vue-leaflet/vue-leaflet";

export default {
    props: ['coordinate'],
    components: { LMap, LTileLayer, LMarker, LPopup },
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
            })
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
        // setMarker(event) {
        //     this.markerLatLng = event.latlng;
        //     this.updateRoute();
        // },
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
