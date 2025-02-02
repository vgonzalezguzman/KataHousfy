<script setup>
import { ref, onMounted } from 'vue';
import Layout from '@/Components/Layout.vue';
import Planet from '@/Components/Planet.vue';
import Obstacle from '@/Components/Obstacle.vue';
import Rover from '../Components/Rover.vue';
import RoverMovement from '../Components/RoverMovement.vue';

const planetRef = ref(null);
const planetData = ref([]);
const isGen = ref(false);
const processedPlanetData = ref([]);
const finalizedPlanetData = ref([]);

onMounted(() => {
    setTimeout(() => {
        if (planetRef.value) {
            planetData.value = planetRef.value.planet;
            isGen.value = planetRef.value.isGen;
        }
    }, 100);
})

const handleProcessedPlanetData = (updatedPlanetData) => {
    processedPlanetData.value = updatedPlanetData;
};

const handleFinalizedMap = (finalizeMapData) => {
    finalizedPlanetData.value = finalizeMapData;
}
</script>
<template>
    <div class="container">
        <Layout>
            <Planet ref="planetRef" />
            <Obstacle v-if="planetData.length" :planetData="planetData" @processed="handleProcessedPlanetData" />
            <Rover v-if="processedPlanetData.length" :processedPlanetData="processedPlanetData"
                @finalizedMap="handleFinalizedMap" />
            <RoverMovement v-if="finalizedPlanetData.length" :finalizedPlanetData="finalizedPlanetData" />
        </Layout>
    </div>
</template>

<style>
.container {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100vh;
    background-color: #f3f4f6;
}
</style>