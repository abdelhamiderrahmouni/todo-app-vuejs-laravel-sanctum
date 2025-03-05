<script setup>
import Section from '@/components/Section.vue'
import TaskListItem from "@/components/Tasks/TaskListItem.vue";

import axios from "axios";
import {onMounted, ref} from "vue";

const tasks = ref([]);

const form = ref({
    name: '',
    body: ''
});

const errors = ref({});
const showTaskBody = ref(false);
const creatingTask = ref(false);

function toggleTaskBody() {
    showTaskBody.value = !showTaskBody.value;
}

onMounted(() => {
    axios.get('/api/tasks')
        .then(response => {
            tasks.value = response.data.data;
        })
        .catch (error => {
            if (error.response?.data?.errors) {
                console.log(error.response.data.errors);
                console.log(error.response.data.data.errors);
                errors.value = error.response.data.errors;
            }
        });
});

// listeners
function handleDeletedTask(taskId) {
    tasks.value = tasks.value.filter(task => task.id !== taskId);
}

function createTask() {
    if (creatingTask.value) {
        return;
    }

    creatingTask.value = true;

    axios.post('/api/tasks', form.value)
        .then(response => {
            tasks.value.push(response.data.data);
            form.value.name = '';
            form.value.body = '';
        })
        .finally(() => {
            creatingTask.value = false;
        });
}
</script>

<template>
  <main>
      <Section class="mb-16">
          <form @submit.prevent="createTask" class="flex flex-col items-start space-y-2 w-full my-16">
              <div class="w-full flex gap-6">
                  <div class="w-full">
                      <input type="text" v-model="form.name" class="form-input"
                             placeholder="what are you up to ?" />
                      <p v-if="errors.name" class="mt-1 text-sm text-red-600">{{ errors.name[0] }}</p>
                  </div>
                  <button type="button" @click="toggleTaskBody" class="cursor-pointer">
                      <svg v-show="!showTaskBody" class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 6l16 0" /><path d="M4 12l16 0" /><path d="M4 18l12 0" /></svg>
                      <svg v-show="showTaskBody" class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M18 6l-12 12" /><path d="M6 6l12 12" /></svg>
                  </button>
              </div>
              <div class="w-full" v-show="showTaskBody">
                  <textarea type="text" v-model="form.body" class="form-textarea"
                            rows="3" placeholder="Add more detail about your task"></textarea>
                  <p v-if="errors.body" class="mt-1 text-sm text-red-600">{{ errors.body[0] }}</p>
              </div>
              <button type="submit" :disabled="creatingTask" class="border bg-blue-400 hove:bg-blue-500 text-white px-4 py-1 rounded-lg cursor-pointer">
                  Add task
              </button>
          </form>

          <div class="space-y-4">
              <div>
                  <p class="font-semibold">Your list: </p>
                  <small>Double click the task name to edit it!</small>
              </div>
              <div class="space-y-2">
                  <template v-for="task in tasks" :key="task.id">
                      <TaskListItem :task="task" @task-deleted="handleDeletedTask"/>
                  </template>
              </div>
          </div>

      </Section>
  </main>
</template>
