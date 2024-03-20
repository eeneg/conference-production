<script setup>
    import { useForm } from '@inertiajs/vue3';
    import TextInput from '@/Components/TextInput.vue';
    import InputLabel from '@/Components/InputLabel.vue';
    import PrimaryButton from '@/Components/PrimaryButton.vue';
    import FindFileLayout from '@/Layouts/FindFileLayout.vue';
    import { DocumentIcon, ArrowDownTrayIcon, BookOpenIcon } from '@heroicons/vue/20/solid';
    import Pagination from '@/Components/Pagination.vue';
    import Modal from '@/Components/Modal.vue';
    import SecondaryButton from '@/Components/SecondaryButton.vue';
    import {nextTick, ref} from 'vue';


    const props = defineProps({files: Object, storage: Object})
    var path = null
    var modalShow = ref(false)

    const search_button_text = ref("SEARCH")

    const form = useForm({
        search: props.search,
        storage: null
    })

    const search = () => {
        search_button_text.value = "LOADING..."

        form.get(route('attachment.index'), {
            preserveScroll: true,
            preserveState: true,
            onStart: () => search_button_text.value = "LOADING...",
            onFinish: () => nextTick(() => document.getElementById('search_attachment').focus(),search_button_text.value = "SEARCH")
        })
    }

    const reset = () => {
        form.search = props.search
        form.storage = null
        form.get(route('attachment.index'), {
            preserveScroll: true,
            preserveState: true,
        })
    }

    const closeModal = () => {
        modalShow.value = false
    }

    const viewFile = (file) => {
        path = file
        modalShow.value = true
    }

</script>
<template>
    <FindFileLayout :type="1">
        <div class="py-5">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="flex flex-row">
                        <div class="pl-5 pr-6 mt-3 grow">
                            <header>
                                <h2 class="text-lg font-medium text-gray-900">Search Conference Attachments</h2>

                                <p class="mt-1 text-sm text-gray-600">
                                    Searches Conference attachment files via File Name, Storage and Content
                                </p>
                            </header>
                        </div>
                    </div>
                    <div class="flex flex-row-reverse mt-3 pl-5 pr-5">
                        <div class="">
                            <SecondaryButton @click="reset">Reset</SecondaryButton>
                        </div>
                    </div>
                    <div class="grow">
                        <form @submit.prevent="search">
                            <div class="pr-6 pl-6 pb-1 flex space-x-2">
                                <div class="grow relative rounded-md shadow-sm">
                                    <InputLabel value="Search" for="search" />
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm mt-5">
                                            <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
                                                <path d="M505 442.7L405.3 343c-4.5-4.5-10.6-7-17-7H372c27.6-35.3 44-79.7 44-128C416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c48.3 0 92.7-16.4 128-44v16.3c0 6.4 2.5 12.5 7 17l99.7 99.7c9.4 9.4 24.6 9.4 33.9 0l28.3-28.3c9.4-9.4 9.4-24.6.1-34zM208 336c-70.7 0-128-57.2-128-128 0-70.7 57.2-128 128-128 70.7 0 128 57.2 128 128 0 70.7-57.2 128-128 128z"/>
                                            </svg>
                                        </span>
                                    </div>

                                    <TextInput id="search_attachment" type="search" class="w-full pl-9" v-model="form.search"/>
                                </div>
                                <div class="">
                                    <InputLabel value="Storage"/>
                                    <select v-model="form.storage" class="w-full rounded text-gray-700 border-gray-300">
                                        <option :value="storage.id" v-for="storage in props.storage">{{ storage.title }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="flex items-center justify-center pr-6 pl-6 mt-1">
                                <PrimaryButton class="items-center justify-center w-full">
                                    <span class="text-gray-500 sm:text-sm mr-2">
                                        <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
                                            <path d="M505 442.7L405.3 343c-4.5-4.5-10.6-7-17-7H372c27.6-35.3 44-79.7 44-128C416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c48.3 0 92.7-16.4 128-44v16.3c0 6.4 2.5 12.5 7 17l99.7 99.7c9.4 9.4 24.6 9.4 33.9 0l28.3-28.3c9.4-9.4 9.4-24.6.1-34zM208 336c-70.7 0-128-57.2-128-128 0-70.7 57.2-128 128-128 70.7 0 128 57.2 128 128 0 70.7-57.2 128-128 128z"/>
                                        </svg>
                                    </span>
                                    {{ search_button_text }}
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                    <div class="grow pl-5 pr-5 pb-5">
                        <div class="border rounded p-2 pl-2 mt-2" v-for="file in props.files.data">
                            <div class="flex">
                                <div class="flex items-center p-1 justify-center">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-indigo-300 text-red-900">
                                        <DocumentIcon class="w-5 h-5 stroke-gray-900 fill-none " aria-hidden="true" />
                                    </div>
                                </div>
                                <div class="grow p-1 text-sm">
                                    <div class="flex ml-2">
                                        <p class="text-lg float-left truncate text-indigo-800">{{ file.file_name }}</p>
                                    </div>
                                    <div class="flex ml-2">
                                        <p>Conference: </p>
                                        <p class="text-md ml-2 truncate lg:w-96 md:w-32 sm:w-32 text-gray-500">{{ file.conference.title }}</p>
                                    </div>
                                    <div class="flex ml-2">
                                        <p>Storage: </p>
                                        <p class="text-md ml-2 truncate lg:w-96 md:w-32 sm:w-32 text-gray-500">{{ file.storage.title }}</p>
                                    </div>
                                    <div class="flex ml-2">
                                        <p>Details: </p>
                                        <p class="text-md ml-2 truncate lg:w-96 md:w-32 sm:w-32 text-gray-500">{{ file.details }}</p>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <div class="flex items-center justify-center float-right">
                                        <a :href="route('attachment.edit',{id: file.id})">
                                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-green-300 hover:bg-green-400 text-red-900 mr-1">
                                                <ArrowDownTrayIcon class="w-5 h-5 stroke-gray-900 fill-black " aria-hidden="true" />
                                            </div>
                                        </a>
                                        <a @click="viewFile('/storage/' + file.path)">
                                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-300 hover:bg-blue-400 text-red-900">
                                                <BookOpenIcon class="w-5 h-5 stroke-gray-900 fill-none aria-hidden" aria-hidden="true" />
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <Pagination :data="props.files"></Pagination>
                    </div>
                </div>
            </div>
        </div>

        <Modal :show="modalShow" :maxWidth="'2xl'" @close="closeModal">
            <div class="p-6">

                <div class="mt-6 h-64" style="height: 50rem;">
                    <embed :src="path" style="width: 100%; height: 100%;"  type="application/pdf">
                </div>

                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModal"> Cancel </SecondaryButton>
                </div>
            </div>
        </Modal>

    </FindFileLayout>

</template>
