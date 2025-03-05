<script setup>
import axios from "axios";
import {ref} from "vue";

const props = defineProps({
    task: {
        type: Object,
        required: true
    }
})

const editEnabled = ref(false);
const updatingTask = ref(false);
const inputRef = ref(null);
const new_task_name = ref(props.task.name);
const new_task_body = ref(props.task.body);

const emit= defineEmits(["task-deleted"])

// methods
function enableTaskEdit() {
    editEnabled.value = true;

    if (editEnabled.value) {
        setTimeout(() => {
            inputRef.value.focus();
        }, 0);
    }
}

function disableTaskEdit() {
    editEnabled.value = false;
    new_task_name.value = props.task.name;
    new_task_body.value = props.task.body;
}

// server actions
function deleteTask(taskId) {
    confirm('Are you sure you want to delete this task?') &&
    axios.delete(`/api/tasks/${taskId}`)
        .then(response => {
            emit('task-deleted', taskId);
        });
}

function updateTask(task) {
    if (updatingTask.value)
        return;

    updatingTask.value = true;

    if (task.name !== new_task_name.value || task.body !== new_task_body.value) {
        axios.put(`/api/tasks/${task.id}`, {
                name: new_task_name.value,
                body: new_task_body.value,
            })
            .then(response => {
                editEnabled.value = false;
                task.name = response.data.data.name;
                task.body = response.data.data.body;
                new_task_name.value = response.data.data.name;
                new_task_body.value = response.data.data.body;
            })
            .finally(() => {
                updatingTask.value = false;
            });
    }else{
        editEnabled.value = false;
        updatingTask.value = false;
    }
}

function markTaskComplete(taskId) {
    axios.put(`/api/tasks/${taskId}`, {
            name: props.task.name,
            body: props.task.body,
            is_completed: !props.task.is_completed
        })
        .then(response => {
            props.task.is_completed = response.data.data.is_completed;
        });
}
</script>

<template>
    <div class="flex items-center justify-between space-x-4 border p-4 rounded-lg" :class="task.is_completed ? 'border-blue-600' : 'border-gray-200'">
        <div v-show="!editEnabled" @dblclick="enableTaskEdit" class="w-full">
            <p v-text="task.name" class="w-full"></p>
            <small v-text="task.body"></small>
        </div>

        <form @submit.prevent="updateTask(task)" class="w-full flex flex-col gap-y-2" v-show="editEnabled">
            <input class="form-input" v-model="new_task_name" type="text" ref="inputRef"/>

            <textarea type="text" v-model="new_task_body" class="form-textarea" placeholder="Add more detail about your task" ></textarea>

            <div class="flex gap-x-2">
                <button type="submit" :disabled="updatingTask" class="border bg-blue-400 hove:bg-blue-500 text-white px-3 py-1 text-sm rounded-lg cursor-pointer">
                    Save
                </button>
                <button @click="disableTaskEdit" class="border bg-red-400 hove:bg-red-500 text-white px-3 py-1 text-sm rounded-lg cursor-pointer">
                    Cancel
                </button>
            </div>
        </form>

        <div class="flex gap-x-2">
            <button @click="markTaskComplete(task.id)" class="cursor-pointer">
                <svg v-show="task.is_completed" class="w-4 h-4 text-blue-600" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 11l3 3l8 -8" /><path d="M20 12v6a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h9" /></svg>
                <svg v-show="!task.is_completed" class="w-4 h-4 text-gray-600" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 3m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v14a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" /></svg>
            </button>
            <button @click="deleteTask(task.id)" class="cursor-pointer">
                <svg class="w-4 h-4 text-red-600" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
            </button>
        </div>
    </div>
</template>
