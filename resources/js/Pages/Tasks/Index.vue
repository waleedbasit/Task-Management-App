<script setup>
import {ref, computed} from 'vue';
import {Head} from '@inertiajs/vue3';
import {useForm} from '@inertiajs/vue3';
import TaskLayout from '@/Layouts/TaskLayout.vue';

const props = defineProps({
    tasks: {
        type: Array,
        default: () => []
    }
});
const form = useForm({
    id: null,
    title: '',
    description: '',
    completed: false,
});
const showModal = ref(false);
const activeTab = ref('current');


let createForm = useForm({
    title: '',
    description: '',
});

function toggleTaskStatus(task) {
    task.completed = !task.completed;
    axios.post(route('tasks.update', task.id), {completed: task.completed})
        .catch(error => {
            console.error('Error toggling task status:', error);
            task.completed = !task.completed;
        });
}

function deleteTask(taskId) {
    if (confirm('Are you sure you want to delete this task?')) {
        form.delete(route('tasks.destroy', {task: taskId}), {
            onSuccess: () => {
                location.reload();
            },
        });
    }
}

const submit = () => {
    createForm.post(route('tasks.store'), {
        onSuccess: () => {
            createForm.reset();
            showModal.value = false;
            fetchTasks();
        },
    });
};

function fetchTasks() {
    axios.get(route('dashboard'))
        .then(response => {
            props.tasks = response.data.tasks;
            location.reload();
        })
        .catch(error => {
            console.error(error);
        });
}

const currentTasks = computed(() => {
    return props.tasks ? props.tasks.filter(task => !task.completed) : [];
});

const completedTasks = computed(() => {
    return props.tasks ? props.tasks.filter(task => task.completed) : [];
});

</script>

<template>
    <TaskLayout>
        <Head title="Tasks">
            <link rel="icon" type="image/png" href="/images/logo.png">
        </Head>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tasks</h2>
        </template>


        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="py-2 ">
                    <button @click="showModal = true"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Create Task
                    </button>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex border-b border-gray-200">
                            <button @click="activeTab = 'current'"
                                    :class="{ 'border-blue-500 text-blue-500': activeTab === 'current' }"
                                    class="px-4 py-2 font-medium text-gray-500 hover:text-blue-500 hover:border-blue-500">
                                Current Tasks
                            </button>
                            <button @click="activeTab = 'completed'"
                                    :class="{ 'border-blue-500 text-blue-500': activeTab === 'completed' }"
                                    class="px-4 py-2 font-medium text-gray-500 hover:text-blue-500 hover:border-blue-500">
                                Completed Tasks
                            </button>
                        </div>

                        <div v-if="activeTab === 'current'" class="mt-6">
                            <div v-for="task in currentTasks" :key="task.id"
                                 class="border border-gray-300 p-4 rounded-lg shadow hover:shadow-lg transition-shadow duration-300 mb-4 flex items-center">
                                <div>
                                    <h2 class="text-lg font-bold">{{ task.title }}</h2>
                                    <p class="text-gray-700">{{ task.description }}</p>
                                </div>
                                <div class="ml-auto flex items-center">
                                    <button @click="toggleTaskStatus(task)"
                                            class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded text-sm mr-2">
                                        Complete
                                    </button>
                                    <button @click="deleteTask(task.id)"
                                            class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded text-sm">
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div v-if="activeTab === 'completed'" class="mt-6">
                            <div v-for="task in completedTasks" :key="task.id"
                                 class="border border-gray-300 p-4 rounded-lg shadow hover:shadow-lg transition-shadow duration-300 mb-4 flex items-center">
                                <div>
                                    <h2 class="text-lg font-bold">{{ task.title }}</h2>
                                    <p class="text-gray-700">{{ task.description }}</p>
                                </div>
                                <div class="ml-auto flex items-center">
                                    <button @click="toggleTaskStatus(task)"
                                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded text-sm mr-2">
                                        Reset
                                    </button>
                                    <button @click="deleteTask(task.id)"
                                            class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded text-sm">
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </TaskLayout>
    <div v-if="showModal" class="fixed z-10 inset-0 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true"
                 @click="showModal = false">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen"
                  aria-hidden="true">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left">

                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                Create New Task
                            </h3>
                            <div class="mt-2">
                                <form @submit.prevent="submit">
                                    <div class="mb-4">
                                        <label for="title"
                                               class="block text-gray-700 text-sm font-bold mb-2">Title:</label>
                                        <input type="text" id="title" v-model="createForm.title"
                                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                               required>
                                    </div>
                                    <div class="mb-4">
                                        <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description:</label>
                                        <textarea id="description" v-model="createForm.description"
                                                  class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                                  required></textarea>
                                    </div>
                                    <div class="px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                        <button type="submit"
                                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4
                                                py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2
                                                focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                                            Create
                                        </button>
                                        <button type="button"
                                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300
                                                shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50
                                                focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                                                @click="showModal = false">
                                            Cancel
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.border-b button.border-blue-500 {
    border-bottom-width: 2px;
}
</style>
