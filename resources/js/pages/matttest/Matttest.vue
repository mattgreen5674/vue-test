<script setup lang="ts">
    import HeadingSmall from '@/components/HeadingSmall.vue';
    import UserDetails from '@/components/matt_tests/UserDetails.vue';
    import MattInput from '@/components/matt_tests/Input.vue';
    import AppLayout from '@/layouts/AppLayout.vue';
    import { Head } from '@inertiajs/vue3';
    import { dashboard, matttest } from '@/routes';
    import { type BreadcrumbItem, type User } from '@/types';
    import axios from 'axios';
    
    import { ref, watch } from 'vue';

    defineProps<{ users: User[] }>();


    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: 'Dashboard',
            href: dashboard().url,
        },
        {
            title: 'Matttest',
            href: matttest().url,
        },
    ];

    const isBlue = ref(true)
    const blueButtonClasses = "bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
    const greenButtonClasses = "bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700"

    function toggleColour() {
        isBlue.value = !isBlue.value
    }

    const showMessage = ref(false)
    const showIfMessage = ref(true)

    const inputValue = ref('Matt Test Input')
    const inputError = ref('')

    // This is watching the input - we could put it inside a function submit that is called after a submit button click.
    watch(inputValue, (newVal) => {
        if (!newVal) {
            inputError.value = 'This field is required'
        } else if (newVal.length < 3) {
            inputError.value = 'Minimum 3 characters required'
        } else if (newVal.length > 255) {
            inputError.value = 'Maximum 255 characters allowed'
        } else {
            inputError.value = ''
        }
    })

    interface MattTestData {
        id: number;
        name: string;
        description: string;
    }
    const mattTestData = ref<MattTestData[]>([]);

    function getData() {
        mattTestData.value = [] // reset the data befoer each call.

        axios.get('/matttestdata')
        .then(response => {
            mattTestData.value = response.data;
            console.table(response.data)
        })
        .catch(error => {
            //this.error = error;
            console.error('Error fetching matttestdata:', error);
        });
    }

    function sendData() {
        axios.post('/matttestdata', [{'name': 'sendTest', 'type' : 'sending'}])
        .then(response => {
            console.table(response.data)
        })
        .catch(error => {
            //this.error = error;
            console.error('Error fetching matttestdata:', error);
        });
    }

    interface ContactForm {
        name: string;
        email: string;
        message: string;
    }

    const form = ref<ContactForm>({
        name: '',
        email: '',
        message: '',
    });

    const errors = ref<Partial<Record<keyof ContactForm, string>>>({});
    const success = ref(false);

    const submitForm = async () => {
        errors.value = {};
        success.value = false;

        try {
            const response = await axios.post('/matttestdata/form', form.value);
            console.table(response.data)
            success.value = true;
            form.value = { name: '', email: '', message: '' }; // Reset form
        } catch (error: any) {
            if (error.response?.status === 422) {
                errors.value = error.response.data.errors;
            } else {
                console.error('Unexpected error:', error);
            }
        }
    };

    const validateForm = (): boolean => {
        errors.value = {};

        if (!form.value.name.trim()) {
            errors.value.name = 'Name is required.';
        } else if (form.value.name.length > 50) {
            errors.value.name = 'Name cannot be more than 50 characters.';
        }

        if (!form.value.email.trim()) {
            errors.value.email = 'Email is required.';
        } else if (!/\S+@\S+\.\S+/.test(form.value.email)) {
            errors.value.email = 'Invalid email format.';
        } else if (form.value.email.length > 50) {
            errors.value.email = 'Email cannot be more than 50 characters.';
        }

        if (!form.value.message.trim()) {
            errors.value.message = 'Message is required.';
        } else if (form.value.message.length > 100) {
            errors.value.message = 'Message cannot be more than 100 characters.';
        }

        return Object.keys(errors.value).length === 0;
    };


</script>

<template>
    <Head title="Matttest" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">

            <HeadingSmall title="Matt Test"/>

            <!-- Display users -->
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <ul>
                    <li v-for="(user, index) in users" :key="'user' + index">
                        {{ user.name + ': ' + user.email }}
                    </li>
                </ul>
            </div>

            <!-- Use vue component to dsiplay users -->
            <div v-for="user in users" :key="user.email">
                <UserDetails :user="user"/>
            </div>

            <!-- Perform Action -->
            <div>
                <button
                    :class="isBlue ? blueButtonClasses : greenButtonClasses"
                    @click="toggleColour"
                >
                    Click Me - to change my colour
                </button>
            </div>

            <!-- Show Hide -->
            <div>
                <button
                    :class="blueButtonClasses"
                    @click="showMessage = ! showMessage"
                >
                    {{ showMessage ? 'Hide Message' : 'Show message' }}
                </button>
                <p v-show="showMessage">👋 Hello, this is a toggled message!</p>
            </div>

            <!-- if else -->
            <div>
                <p v-if="showIfMessage">This is a an if message!</p>
                <p v-else="!showIfMessage">This is a an else message!</p>
            </div>

            <!-- Components -->
            <div>
                <MattInput
                    :error="inputError"
                    :inputValue="inputValue"
                    label="Some Text Input"
                    name="textInput" 
                    v-model="inputValue"
                />
                <p>{{ inputValue }}</p>
            </div>

            <!-- Get Data -->
            <div>
                <button
                    :class="blueButtonClasses"
                    @click="getData"
                >
                    {{ 'Get some data' }}
                </button>
                <p>Get some data from a controller</p>
                <div v-if="mattTestData.length > 0">
                    <table class="table-auto w-full border border-gray-300">
                        <thead>
                        <tr>
                            <th class="border px-4 py-2">ID</th>
                            <th class="border px-4 py-2">Name</th>
                            <th class="border px-4 py-2">Type</th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in mattTestData">
                                <td>{{ item.id }}</td>
                                <td>{{ item.name }}</td>
                                <td>{{ item.type }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div v-else>
                    There are no records to display - click the button above to retrieve!
                </div>
            </div>

            <!-- Send Data -->
            <div>
                <button
                    :class="blueButtonClasses"
                    @click="sendData"
                >
                    {{ 'Send some data' }}
                </button>
                <p>Send some data to a controller</p>
            </div>

            <!-- Send form data -->
            <form @submit.prevent="submitForm" class="space-y-4 max-w-md">
                <div>
                    <label class="block">Name</label>
                    <input v-model="form.name" type="text" class="w-1/2 rounded-md border border-gray-300 bg-gray-100 p-2 text-sm focus:border-blue-500 focus:outline-none text-black" />
                    <p v-if="errors.name" class="text-red-600">{{ errors.name }}</p>
                </div>

                <div>
                    <label class="block">Email</label>
                    <input v-model="form.email" type="email" class="w-1/2 rounded-md border border-gray-300 bg-gray-100 p-2 text-sm focus:border-blue-500 focus:outline-none text-black" />
                    <p v-if="errors.email" class="text-red-600">{{ errors.email }}</p>
                </div>

                <div>
                    <label class="block">Message</label>
                    <textarea v-model="form.message" class="w-1/2 rounded-md border border-gray-300 bg-gray-100 p-2 text-sm focus:border-blue-500 focus:outline-none text-black"></textarea>
                    <p v-if="errors.message" class="text-red-600">{{ errors.message }}</p>
                </div>

                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Send
                </button>

                <p v-if="success" class="text-green-600 mt-2">Your message was sent successfully!</p>
            </form>
        
        </div>
        
    </AppLayout>
</template>
