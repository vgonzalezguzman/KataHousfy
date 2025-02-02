<script setup>
import { ref, watch, defineEmits } from 'vue';

const props = defineProps({
    finalizedPlanetData: {
        type: Array,
        required: true
    }
});

const emit = defineEmits(['updateRoverPosition']);

const roverPosition = ref({ x: Math.floor(props.finalizedPlanetData.length / 2), y: Math.floor(props.finalizedPlanetData[0].length / 2), direction: 'N' });
const previousPosition = ref({ x: Math.floor(props.finalizedPlanetData.length / 2), y: Math.floor(props.finalizedPlanetData[0].length / 2) });
const inputCommand = ref('');
const directions = ['N', 'E', 'S', 'O'];

const checkNextPosition = (x, y) => {
    let response = {success: false, errorCode: -1};
    //Response: true/false, error type.
    //error type: 0 = OK, 1 = out of bounds, 2 = obstacle found, 3 = something else went wrong
    if (x >= 0 && y >= 0 && x < props.finalizedPlanetData.length && y < props.finalizedPlanetData[0].length && props.finalizedPlanetData[x][y] !== 1) {
        response = {success: true, errorCode: 0};
    } else {
        if (x < 0 || y < 0 || x > props.finalizedPlanetData.length || y > props.finalizedPlanetData[0].length) {
            response = {success: false, errorCode: 1};
        } else if (props.finalizedPlanetData[x][y] === 1) {
            response = {success: false, errorCode: 2};
        } else {
            response = {success: false, errorCode: 3};
        }
    }
    
    return response;
};

const moveRover = (command) => {
    previousPosition.value = { ...roverPosition.value };
    let { x, y, direction } = roverPosition.value;

    if (command === 'R') {
        let newIndex = (directions.indexOf(direction) + 1) % 4;
        roverPosition.value.direction = directions[newIndex];
    } else if (command === 'L') {
        let newIndex = (directions.indexOf(direction) - 1 + 4) % 4;
        roverPosition.value.direction = directions[newIndex];
    } else if (command === 'F') {
        let newX = x, newY = y;
        if (direction === 'N') newX -= 1;
        else if (direction === 'S') newX += 1;
        else if (direction === 'E') newY += 1;
        else if (direction === 'O') newY -= 1;

        let response = checkNextPosition(newX, newY);
        if (response.success && response.errorCode === 0) {
            roverPosition.value.x = newX;
            roverPosition.value.y = newY;
        } else {
            if (!response.success && response.errorCode === 1) {
                console.warn("Error, out of bounds");
            } else if (!response.success && response.errorCode === 2) {
                console.warn("Error, encountered an object");
            } else if (!response.success && response.errorCode === 3) {
                console.warn("Error, something else went wrong");
            } else {
                console.warn("Error, something else went wrong")
            }
            return;
        }
    }

    const newGrid = props.finalizedPlanetData.map(row => [...row]);
    newGrid[previousPosition.value.x][previousPosition.value.y] = null;
    newGrid[roverPosition.value.x][roverPosition.value.y] = 5;

    emit('updateRoverPosition', newGrid);

}

watch(inputCommand, (newCommand) => {
    if (newCommand.length > 0) {
        for (let char of newCommand.toUpperCase()) {
            if (['F', 'R', 'L'].includes(char)) {
                moveRover(char);
            }
        }
        inputCommand.value = '';
    }
});

</script>
<template>
    <h2>Control del Rover</h2>
    <input v-model="inputCommand" placeholder="Entra les comandes (F, R, L)" />
    <p>Posició actual: {{ roverPosition.x }}, {{ roverPosition.y }} - Mirant a {{ roverPosition.direction }}</p>
    <p>Posició prèvia: {{ previousPosition.x }}, {{ previousPosition.y }}</p>
</template>