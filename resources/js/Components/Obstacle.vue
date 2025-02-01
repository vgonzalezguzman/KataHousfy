<script setup>
import { ref, watch, defineEmits } from 'vue';

const props = defineProps({
    planetData: {
        type: Array,
        required: true
    }
});

const emit = defineEmits(['processed']);

const processedPlanetData = ref([]);
const cols = ref(props.planetData.length);
const rows = ref(props.planetData[0].length);


const addObstaclesToPlanet = (planetData) => {
    let obstaclePosition = [];
    const obstacleNumber = Math.floor(Math.random() * ((cols.value + rows.value) / 4));
    console.log(obstacleNumber);
    for (let i = 0; i < obstacleNumber; i++) {
        const x = Math.floor(Math.random() * cols.value);
        const y = Math.floor(Math.random() * rows.value);
        obstaclePosition.push({ x, y });
    }

    obstaclePosition.forEach(({ x, y }) => {
        if (planetData[x] && planetData[x][y] !== undefined) {
            planetData[x][y] = 1;
        }
    });

    return planetData;
}

watch(() => props.planetData, (newPlanetData) => {
    processedPlanetData.value = addObstaclesToPlanet(newPlanetData);
    emit('processed', processedPlanetData.value);
}, { immediate: true });
</script>

<template>
</template>