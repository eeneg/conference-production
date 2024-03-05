<script setup>
    import FindFileLayout from '@/Layouts/FindFileLayout.vue';
    import Modal from '@/Components/Modal.vue';
    import TextInput from '@/Components/TextInput.vue';
    import SecondaryButton from '@/Components/SecondaryButton.vue';
    import { ArrowDownTrayIcon, DocumentIcon, BookOpenIcon } from '@heroicons/vue/20/solid';
    import InputLabel from '@/Components/InputLabel.vue';
    import { useForm } from '@inertiajs/vue3';
    import { ref, nextTick } from 'vue';


    const props = defineProps({files: Object, storage: Object, category: Object})

    const path = ref(null)

    const modalShow = ref(false)

    const form = useForm({
        search: props.search,
    })

    const search = () => {
        form.get(route('file.index'), {
            preserveScroll: true,
            preserveState: true,
            onFinish: () => nextTick(() => document.getElementById('search_file').focus())
        })
    }

    const reset = () => {
        form.search = ""
        form.get(route('file.index'), {
            preserveScroll: true,
            preserveState: true,
        })
    }

    const closeModal = () => {
        modalShow.value = false
    }

    const viewFile = (file) => {
        path.value = file.slice(7)
        modalShow.value = true
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
                        <form @submit.prevent="search" class="flex flex-row mt-1 space-y-2">
                            <div class="pr-6 pl-6 pb-6 grow">
                                <InputLabel value="Search" for="search" />
                                <div class="relative mt-2 rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">
                                            <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
                                                <path d="M505 442.7L405.3 343c-4.5-4.5-10.6-7-17-7H372c27.6-35.3 44-79.7 44-128C416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c48.3 0 92.7-16.4 128-44v16.3c0 6.4 2.5 12.5 7 17l99.7 99.7c9.4 9.4 24.6 9.4 33.9 0l28.3-28.3c9.4-9.4 9.4-24.6.1-34zM208 336c-70.7 0-128-57.2-128-128 0-70.7 57.2-128 128-128 70.7 0 128 57.2 128 128 0 70.7-57.2 128-128 128z"/>
                                            </svg>
                                        </span>
                                    </div>

                                    <TextInput id="search_file" type="search" class="block w-full mt-1 pl-9" v-model="form.search"/>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="grow pl-5 pr-5 pb-5">
                        <div class="border rounded p-2 pl-4 mt-2" v-for="file in props.files.data">
                            <div class="flex">
                                <div class="flex items-center justify-center ml-1">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-indigo-300 text-red-900">
                                        <DocumentIcon class="w-5 h-5 stroke-gray-900 fill-none " aria-hidden="true" />
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="flex ml-2">
                                        <p class="text-lg float-left truncate w-96">{{ file.file_name }}</p>
                                    </div>
                                    <div class="flex ml-2">
                                        <p>Category: </p>
                                        <p class="text-md ml-2 truncate w-60">{{ file.category.title }}</p>
                                        <p>Storage: </p>
                                        <p class="text-md ml-2 truncate w-60">{{ file.storage.title }}</p>
                                    </div>
                                    <div class="flex ml-2">
                                        <p>Details: </p>
                                        <p class="text-md ml-2 truncate w-80">{{ file.details }}</p>
                                    </div>
                                </div>
                                <div class="grow mt-2">
                                    <div class="flex items-center justify-center float-right">
                                        <a :href="route('file.download',{ id:file.id })">
                                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-green-300 hover:bg-green-400 text-red-900 mr-1">
                                                <ArrowDownTrayIcon class="w-5 h-5 stroke-gray-900 fill-black " aria-hidden="true" />
                                            </div>
                                        </a>
                                        <a @click="viewFile(file.path)">
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
                        <!-- <Pagination :data="props.files"></Pagination> -->
                    </div>
                </div>
            </div>
            <Modal :show="modalShow" :maxWidth="'2xl'" @close="closeModal">
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
    </FindFileLayout>

</template>