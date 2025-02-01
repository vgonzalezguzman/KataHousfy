<script setup>
import { ref, onMounted } from "vue";

const rows = 200;
const cols = 200;

const planet = ref([]);
const isGen = ref(false);

onMounted(() => {
    generatePlanet();
});

defineExpose({
    planet,
    isGen
});

const generatePlanet = () => {
    planet.value = Array.from({ length: rows }, () => Array(cols).fill(null));
    isGen.value = true;
};
</script>

<template>
    <p>OK</p>
</template>