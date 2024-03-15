<script setup>
    import FindFileLayout from '@/Layouts/FindFileLayout.vue';
    import Modal from '@/Components/Modal.vue';
    import TextInput from '@/Components/TextInput.vue';
    import SecondaryButton from '@/Components/SecondaryButton.vue';
    import ComboBox from '@/Components/ComboBox.vue';
    import DangerButton from '@/Components/DangerButton.vue';
    import PrimaryButton from '@/Components/PrimaryButton.vue';
    import { ArrowDownTrayIcon, DocumentIcon, BookOpenIcon, TrashIcon } from '@heroicons/vue/20/solid';
    import InputLabel from '@/Components/InputLabel.vue';
    import { useForm } from '@inertiajs/vue3';
    import { ref, nextTick, onMounted } from 'vue';
    import axios from 'axios';


    const props = defineProps({files: Object, storage: Object, category: Object})

    const files = ref(props.files)

    const combobox = ref()

    const path = ref(null)

    const pdfModalShow = ref(false)

    const responseModal = ref(false)
    var header = ""
    var success = true
    var message = ""

    const confirmingFileDeletion = ref(false)

    const form = useForm({
        search: props.search,
        storage: null,
        category: []
    })

    const deleteForm = useForm({
        id: null,
    })

    const deleteModal = (id) => {
        confirmingFileDeletion.value = true
        deleteForm.id = id
    }

    const deleteFile = () => {
        deleteForm.submit('delete', route('files.destroy', deleteForm.id),{
           onSuccess: () => {
                header = "Success!"
                success = true
                message = "Submitted Successfuly"
                confirmingFileDeletion.value = false
           },
           onError: () => {
                header = "Error!"
                success = false
                message = "Failed to Submit"
                confirmingFileDeletion.value = false
           }
        })
    }

    const getCategoryId = (id) => {
        form.category = id.value
    }

    const closeDeleteModal = () => {
        confirmingFileDeletion.value = false
    }

    const search = () => {
        if(form.category.length != 0){
            form.category = form.category.map(e => e.title).join(', ')
        }

        axios.post(route('file.search'), form)
        .then(({data}) => {
            files.value = data
            console.log(files.value)
        })
        .catch(e => {

        })
    }

    const reset = () => {
        form.reset()
        combobox.value.reset()
    }

    const closeModal = () => {
        pdfModalShow.value = false
    }

    const viewFile = (file) => {
        path.value = file.slice(7)
        pdfModalShow.value = true
    }
</script>
<template>
    <FindFileLayout :type="2">
        <div class="py-5">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="flex flex-row">
                        <div class="pl-5 pr-6 mt-3 grow">
                            <header>
                                <h2 class="text-lg font-medium text-gray-900">Search Files</h2>

                                <p class="mt-1 text-sm text-gray-600">
                                    Searches Files via File Name, Storage, Content and Category (e.g. Ordinances, Resolutions)
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
                                    <InputLabel value="File Name/Content" for="search" />
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm mt-5">
                                            <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
                                                <path d="M505 442.7L405.3 343c-4.5-4.5-10.6-7-17-7H372c27.6-35.3 44-79.7 44-128C416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c48.3 0 92.7-16.4 128-44v16.3c0 6.4 2.5 12.5 7 17l99.7 99.7c9.4 9.4 24.6 9.4 33.9 0l28.3-28.3c9.4-9.4 9.4-24.6.1-34zM208 336c-70.7 0-128-57.2-128-128 0-70.7 57.2-128 128-128 70.7 0 128 57.2 128 128 0 70.7-57.2 128-128 128z"/>
                                            </svg>
                                        </span>
                                    </div>
                                    <TextInput id="search_file" @keyup.enter="search" type="search" class="w-full pl-9" v-model="form.search"/>
                                </div>
                                <div class="grow">
                                    <InputLabel value="Storage"/>
                                    <select v-model="form.storage" class="w-full rounded text-gray-700 border-gray-300">
                                        <option :value="storage.id" v-for="storage in props.storage">{{ storage.title }}</option>
                                    </select>
                                </div>
                                <div class="grow">
                                    <InputLabel value="Category"/>
                                    <ComboBox @passData="getCategoryId($event)" :data="props.category" ref="combobox"></ComboBox>
                                </div>
                            </div>
                            <div class="flex items-center justify-center pr-6 pl-6 mt-1">
                                <PrimaryButton class="items-center justify-center w-full">
                                    <span class="text-gray-500 sm:text-sm mr-2">
                                        <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
                                            <path d="M505 442.7L405.3 343c-4.5-4.5-10.6-7-17-7H372c27.6-35.3 44-79.7 44-128C416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c48.3 0 92.7-16.4 128-44v16.3c0 6.4 2.5 12.5 7 17l99.7 99.7c9.4 9.4 24.6 9.4 33.9 0l28.3-28.3c9.4-9.4 9.4-24.6.1-34zM208 336c-70.7 0-128-57.2-128-128 0-70.7 57.2-128 128-128 70.7 0 128 57.2 128 128 0 70.7-57.2 128-128 128z"/>
                                        </svg>
                                    </span>
                                    Search
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                    <div class="grow pl-5 pr-5 pb-5">
                        <div class="border rounded p-2 pl-4 mt-2" v-for="file in files.data">
                            <div class="flex">
                                <div class="flex items-center justify-center ml-1">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-indigo-300 text-red-900">
                                        <DocumentIcon class="w-5 h-5 stroke-gray-900 fill-none " aria-hidden="true" />
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="flex ml-2">
                                        <p class="text-lg float-left truncate w-96 text-indigo-800">{{ file.file_name }}</p>
                                    </div>
                                    <div class="flex ml-2">
                                        <p class="">Category: </p>
                                        <p class="text-md ml-2 truncate w-96 text-gray-500">{{ file.category.map(e => e.title).join(', ')}}</p>
                                    </div>
                                    <div class="flex ml-2">
                                        <p class="">Storage: </p>
                                        <p class="text-md ml-2 truncate w-96 text-gray-500">{{ file.storage.title }}</p>
                                    </div>
                                    <div class="flex ml-2">
                                        <p class="">Details: </p>
                                        <p class="text-md ml-2 truncate w-96 text-gray-500">{{ file.details }}</p>
                                    </div>
                                </div>
                                <div class="grow mt-2">
                                    <div class="flex items-center justify-center float-right">
                                        <button @click="deleteModal(file.id)">
                                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-red-300 hover:bg-red-400 text-red-900 mr-1">
                                                <TrashIcon class="w-5 h-5 fill-black " aria-hidden="true" />
                                            </div>
                                        </button>
                                        <a :href="route('file.download',{ id:file.id })">
                                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-green-300 hover:bg-green-400 text-red-900 mr-1">
                                                <ArrowDownTrayIcon class="w-5 h-5 stroke-gray-900 fill-black " aria-hidden="true" />
                                            </div>
                                        </a>
                                        <button @click="viewFile(file.path)">
                                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-300 hover:bg-blue-400 text-red-900">
                                                <BookOpenIcon class="w-5 h-5 stroke-gray-900 fill-none aria-hidden" aria-hidden="true" />
                                            </div>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <!-- <Pagination :data="props.files"></Pagination> -->
                    </div>
                </div>
            </div>
            <Modal :show="pdfModalShow" :maxWidth="'2xl'" @close="closeModal">
                <div class="p-6">

                    <div class="mt-6 h-64" style="height: 50rem;">
                        <embed :src="'/storage/'+path" style="width: 100%; height: 100%;"  type="application/pdf">
                    </div>

                    <div class="mt-6 flex justify-end">
                        <SecondaryButton @click="closeModal"> Cancel </SecondaryButton>
                    </div>
                </div>
            </Modal>
        </div>

        <Modal :show="responseModal">
            <div class="p-6">
                <h2 :class="{'text-lg font-medium text-green-500': success == true, 'text-lg font-medium text-red-500': success == false}">
                    {{ header }}
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    {{message}}
                </p>


                <SecondaryButton
                    class="w-full mt-2 place-content-center bg-red-400"
                    @click="closeModal">
                                <p>OK</p>
                </SecondaryButton>
            </div>
        </Modal>

        <Modal :show="confirmingFileDeletion" @close="closeDeleteModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Are you sure you want to delete this File?
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    Once its deleted, all of its resources and data will be permanently deleted. Please
                    enter your password to confirm you would like to permanently delete the File.
                </p>

                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeDeleteModal"> Cancel </SecondaryButton>

                    <DangerButton
                        class="ml-3"
                        :class="{ 'opacity-25': deleteForm.processing }"
                        :disabled="deleteForm.processing"
                        @click="deleteFile"
                    >
                        Delete File
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </FindFileLayout>

</template>
