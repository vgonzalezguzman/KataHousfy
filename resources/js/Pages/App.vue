<script setup>
import { ref, onMounted } from 'vue';
import Layout from '@/Components/Layout.vue';
import Planet from '@/Components/Planet.vue';

const planetRef = ref(null);
const planetData = ref([]);
const isGen = ref(false);

onMounted(() => {
    setTimeout(() => {
        if (planetRef.value) {
            planetData.value = planetRef.value.planet;
            isGen.value = planetRef.value.isGen;
        }
    }, 100)
})
</script>
<template>
    <Layout class="w-full h-full">
        <Planet ref="planetRef"/>
        <p>Test Layout</p>
    </Layout>
</template>