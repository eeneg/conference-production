<script setup>
import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';
import {ref} from  'vue';

const props = defineProps({conference:Object})

const form = useForm({
    title: null,
    details: null,
})

const showModal = ref(false)
const header = ref('')
const message = ref('')

const pollModal = () => {
    header.value = 'Add Poll'
    showModal.value = true
}

const closeModal = () => {
    showModal.value = false
}
</script>
<template>
    <Head title="Conferences" />

    <header class="bg-white shadow">
        <div class="px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Agenda/Order of Business</h2>
        </div>
    </header>


    <div class="py-5">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="flex flex-row">
                    <div class="pl-5 pr-6 mt-3 grow">
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">Order Of Business</h2>

                            <p class="mt-1 text-sm text-gray-600">
                                Order Of Business Page to view the agenda and create voting polls
                            </p>
                        </header>
                    </div>
                    <div class="p-5">
                        <PrimaryButton @click="pollModal">Add Poll</PrimaryButton>
                    </div>
                </div>
                <div class="flex">
                    <div class="pl-5 pr-6 mt-3 grow">
                        asd
                    </div>
                </div>
            </div>
        </div>

        <Modal :show="showModal" :maxWidth="'2xl'">
            <div class="p-6">

                <div class="flex flex-row">
                    <div class="basis-1/2">
                        <h2 class="text-lg font-medium text-black-500">
                            {{ header }}
                        </h2>

                        <p class="mt-1 text-sm text-gray-600">
                            {{message}}
                        </p>
                    </div>
                    <div class="basis-1/2">
                        <a class="float-right" role="button" @click="closeModal">
                            <svg x="0px" y="0px" width="20" height="20" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10" opacity=".35"></circle><path d="M14.812,16.215L7.785,9.188c-0.384-0.384-0.384-1.008,0-1.392l0.011-0.011c0.384-0.384,1.008-0.384,1.392,0l7.027,7.027	c0.384,0.384,0.384,1.008,0,1.392l-0.011,0.011C15.82,16.599,15.196,16.599,14.812,16.215z"></path><path d="M7.785,14.812l7.027-7.027c0.384-0.384,1.008-0.384,1.392,0l0.011,0.011c0.384,0.384,0.384,1.008,0,1.392l-7.027,7.027	c-0.384,0.384-1.008,0.384-1.392,0l-0.011-0.011C7.401,15.82,7.401,15.196,7.785,14.812z"></path>
                            </svg>
                        </a>
                    </div>
                </div>

                <div class="mt-3 mb-3 mr-3 pr-6 pl-5">
                    <div>
                        <InputLabel for="title">Title</InputLabel>
                        <TextInput id="title" v-model="form.title"> </TextInput>
                    </div>
                </div>

            </div>
        </Modal>
    </div>

</template>
