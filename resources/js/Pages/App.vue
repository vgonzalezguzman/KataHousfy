<script setup>
import { ref, onMounted } from 'vue';
import Layout from '@/Components/Layout.vue';
import Planet from '@/Components/Planet.vue';
import Obstacle from '@/Components/Obstacle.vue';

const planetRef = ref(null);
const planetData = ref([]);
const isGen = ref(false);
const processedPlanetData = ref([]);

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
</script>
<template>
    <Layout class="w-full h-full">
        <Planet ref="planetRef" />
        <Obstacle v-if="planetData.length" :planetData="planetData"  @processed="handleProcessedPlanetData" />
    </Layout>
</template>