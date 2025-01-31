<script setup>
    import { ref } from "vue";
    const count = ref(0);
</script>
<template>
    <h1  @click="count++">
        How To Install Vue 3 in Laravel 10 : ZWebCourses :)
    </h1>
    <p>{{ count }}</p>
</template>