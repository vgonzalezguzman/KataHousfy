<script setup>
import { ref, defineEmits } from 'vue';

const props = defineProps({
    finalizedPlanetData: {
        type: Array,
        required: true
    }
});

const emit = defineEmits(['updateRoverPosition']);

const roverPosition = ref({
    x: Math.floor(props.finalizedPlanetData.length / 2),
    y: Math.floor(props.finalizedPlanetData[0].length / 2),
    direction: 'N'
});
const previousPosition = ref({
    x: Math.floor(props.finalizedPlanetData.length / 2),
    y: Math.floor(props.finalizedPlanetData[0].length / 2)
});

const directions = ['N', 'E', 'S', 'O'];

const file = ref(null);
const commands = ref([]);
const executionStopped = ref(false);
const errorMessage = ref("");
const showErrorPopup = ref(false);

const checkNextPosition = (x, y) => {
    let response = { success: false, errorCode: -1 };

    if (x >= 0 && y >= 0 && x < props.finalizedPlanetData.length && y < props.finalizedPlanetData[0].length && props.finalizedPlanetData[x][y] !== 1) {
        response = { success: true, errorCode: 0 };
    } else {
        if (x < 0 || y < 0 || x >= props.finalizedPlanetData.length || y >= props.finalizedPlanetData[0].length) {
            errorMessage.value = "Error: El rover no pot sortir del mapa!";
            console.warn(errorMessage.value);
            response = { success: false, errorCode: 1 };
        } else if (props.finalizedPlanetData[x][y] === 1) {
            errorMessage.value = "Error: El rover s'ha trovat un obstacle!";
            console.warn(errorMessage.value);
            response = { success: false, errorCode: 2 };
        } else {
            errorMessage.value = "Error: Alguna cosa ha anat malament.";
            console.warn(errorMessage.value);
            response = { success: false, errorCode: 3 };
        }

        showErrorPopup.value = true;
    }

    return response;
};

const moveRover = (command) => {
    if (executionStopped.value) return;

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
        if (!response.success) {
            executionStopped.value = true;
            return;
        }

        roverPosition.value.x = newX;
        roverPosition.value.y = newY;
    }

    const newGrid = props.finalizedPlanetData.map(row => [...row]);
    newGrid[previousPosition.value.x][previousPosition.value.y] = null;
    newGrid[roverPosition.value.x][roverPosition.value.y] = 5;

    console.log(newGrid);

    emit('updateRoverPosition', newGrid);
};

const handleFileChange = (event) => {
    const selectedFile = event.target.files[0];

    if (!selectedFile) return;

    if (selectedFile.type !== "text/plain") {
        errorMessage.value = "Només s'accepten fitxers .txt!";
        showErrorPopup.value = true;
        return;
    }

    file.value = selectedFile;
};

const processFile = () => {
    if (!file.value) {
        errorMessage.value = "No s'ha seleccionat cap fitxer!";
        showErrorPopup.value = true;
        return;
    }

    const reader = new FileReader();

    reader.onload = (event) => {
        const text = event.target.result;
        const lines = text.split(/\r?\n/);
        commands.value = lines.map(line => line.split(','))[0];

        console.log("Contingut processat:", commands.value);

        executionStopped.value = false;

        for (let command of commands.value) {
            if (executionStopped.value) break;

            if (['F', 'R', 'L'].includes(command)) {
                moveRover(command);
            }
        }
    };

    reader.readAsText(file.value);
};

const closePopup = () => {
    showErrorPopup.value = false;
};
</script>

<template>
    <h2>Control del Rover</h2>
    <p>200x200</p>
    <p>El moviment és basat en la lectura d'un arxiu TXT <br> que ha de constar dels paràmetres F, R o L. <br> Cada
        entrada ha d'estar separada per una coma.</p>

    <input type="file" accept=".txt" @change="handleFileChange" />
    <button @click="processFile" :disabled="!file">Enviar</button>

    <p>Posició actual: {{ roverPosition.x }}, {{ roverPosition.y }} - Mirant a {{ roverPosition.direction }}</p>
    <p>Posició prèvia: {{ previousPosition.x }}, {{ previousPosition.y }}</p>

    <div v-if="showErrorPopup" class="popup">
        <div class="popup-content">
            <p>{{ errorMessage }}</p>
            <button @click="closePopup">Close</button>
        </div>
    </div>
</template>

<style scoped>
.popup {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
}

.popup-content {
    background: white;
    padding: 20px;
    border-radius: 10px;
    text-align: center;
}
</style>