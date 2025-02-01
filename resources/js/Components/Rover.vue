<script setup>
import { ref, watch, defineEmits } from 'vue';

const props = defineProps({
    processedPlanetData: {
        type: Array,
        required: true
    }
});

const emit = defineEmits(['finalizedMap']);

const finalizedMap = ref([]);

const addRoverToMap = (planetData) => {
    const cols = ref(props.processedPlanetData.length);
    const rows = ref(props.processedPlanetData[0].length);
    const roverPositionX = Math.floor(cols.value / 2);
    const roverPositionY = Math.floor(rows.value / 2);
    planetData[roverPositionX][roverPositionY] = 5;

    return planetData;
};

watch(() => props.processedPlanetData, (newPlanetData) => {
    finalizedMap.value = addRoverToMap(newPlanetData);
    emit('finalizedMap', finalizedMap.value);
}, { immediate: true });
</script>
<template>
</template>