<script setup>
import Modal from '../Modal.vue'
import PrimaryButton from '../PrimaryButton.vue';
import SecondaryButton from '../SecondaryButton.vue';
import InputLabel from '../InputLabel.vue';
import Dropdown from '../Dropdown.vue';
import DropdownLink from '../DropdownLink.vue'
import { BarsArrowUpIcon, PaperClipIcon } from '@heroicons/vue/20/solid';
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({file_id:String})

const showFileVersions = ref(false)
const openFileVerionsModal = () => {
    showFileVersions.value = true
}
const closeFileVersionModal = () => {
    showFileVersions.value = false
}

const form = useForm({
    'file_id':props.file_id,
    'file': null
})

const getFile = (e) => {
    form.file = e.target.files[0]
}


</script>

<template>
    <button @click="openFileVerionsModal">
        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-300 hover:bg-blue-400 text-red-900">
            <BarsArrowUpIcon class="w-5 h-5 stroke-gray-900 fill-black aria-hidden" aria-hidden="true" />
        </div>
    </button>

    <Modal :show="showFileVersions">
        <div class="flex flex-col">
            <div class="p-6">
                <h2 class="text-lg font-medium">
                    File Versions
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    View and Update File Versions
                </p>
            </div>

            <div class="pl-6 pr-6 flex">
                <div class="w-full">
                    <InputLabel>Upload new file</InputLabel>
                    <input type="file" v-on:change="getFile($event)" class="mt-1" accept="application/pdf"/>
                    <PrimaryButton class="float-right">Upload</PrimaryButton>
                </div>
            </div>

            <div class="pl-6 pr-6 flex flex-col mt-2">
                <div class="flex py-2 pl-1 border rounded mt-1" v-for="asd in 10">
                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-indigo-300 hover:bg-indigo-400 text-black-900">
                        <PaperClipIcon class="w-4 h-4 fill-black " aria-hidden="true" />
                    </div>
                    <div class="flex items-center grow">
                        <p class="text-lg w-60 truncate float-left ml-2">Sample File Name.pdf asdas dsadas asd asdas das asd asds asda d</p>
                    </div>
                    <div class="flex items-center grow">
                        12/12/2024
                    </div>
                    <div class="flex items-center justify-center float-right mr-1 space-x-1">
                        <Dropdown align="right" width="48">
                            <template #trigger>
                                <span class="inline-flex rounded-md">
                                    <button
                                        type="button"
                                        class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out bg-white border border-transparent rounded-md hover:text-gray-700 focus:outline-none"
                                    >
                                        <svg
                                            class="ml-2 -mr-0.5 h-4 w-4"
                                            xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20"
                                            fill="currentColor"
                                        >
                                            <path
                                                fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                    </button>
                                </span>
                            </template>

                            <template #content>
                                <DropdownLink>Delete</DropdownLink>
                                <DropdownLink>View</DropdownLink>
                                <DropdownLink>Set Latest</DropdownLink>
                            </template>
                        </Dropdown>
                    </div>
                </div>
            </div>

            <div class="pt-6 pl-6 pb-3">
                <SecondaryButton
                    class="mt-2 place-content-center bg-red-400"
                    @click="closeFileVersionModal">
                        <p>Close</p>
                </SecondaryButton>
            </div>
        </div>
    </Modal>
</template>
