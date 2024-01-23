<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head, useForm } from '@inertiajs/vue3';
    import TextInput from '@/Components/TextInput.vue';
    import PrimaryButton from '@/Components/PrimaryButton.vue';
    import InputLabel from '@/Components/InputLabel.vue';
    import {ref} from  'vue';
    import SecondaryButton from '@/Components/SecondaryButton.vue'
    import Modal from '@/Components/Modal.vue';
    import InputError from '@/Components/InputError.vue';




    const props = defineProps({storage:Object})

    const form = useForm({
        files: null,
        storage_id: null,
        details: null
    })

    var header = ""
    var message = ""
    var success = true

    const modalShow = ref(false)

    const submit = () => {
        form.submit('post', route('files.store'),{
            onSuccess: () =>{
                header = "Success!"
                success = true
                message = "Storage Location Submitted Successfuly"
                modalShow.value = true
                form.reset()
            },
            onError: (e) => {
                header = "Error!"
                success = false
                message = "Something went wrong"
                modalShow.value = true
                console.log(e)
            }
        })
    }

    const update = () => {
        form.submit('patch', route('files.update'),{
            onSuccess: () =>{

            },
            onError: () => {

            }
        })
    }

    const deleteFile = () => {
        form.submit('delete', route('files.destroy'),{
            onSuccess: () =>{

            },
            onError: () => {

            }
        })
    }

    const closeModal = () => {
        modalShow.value = false
    }

    const getFiles = (e, i) => {
        form.files = e.target.files
    }

</script>
<template>
    <Head title="Attachments" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">File Upload</h2>
        </template>

        <div class="py-5">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="flex flex-row">
                        <div class="pl-5 pr-6 mt-3 grow mb-3">
                            <header>
                                <h2 class="text-lg font-medium text-gray-900">Files</h2>

                                <p class="mt-1 text-sm text-gray-600">
                                    Upload files
                                </p>
                            </header>
                        </div>
                    </div>
                    <div class="mt-3 mb-3 pr-6 pl-5 grid grid-cols-2">
                        <div class="space-y-6">
                            <div class="">
                                <InputLabel>Upload a File</InputLabel>
                                <input v-on:change="getFiles($event, i)" type="file" placeholder="Storage Location" multiple accept="application/pdf"   />
                                <InputError :message="form.errors.file" class="mt-2" />
                            </div>
                            <div class="">
                                <InputLabel>Storage Location</InputLabel>
                                <select v-model="form.storage_id" name="storage_id" id="storage_id" class="border w-full rounded text-gray-700 border-gray-300">
                                    <option selected>---</option>
                                    <option :value="storage.id" v-for="storage in props.storage">{{ storage.title.charAt(0).toUpperCase() + storage.title.slice(1) }}</option>
                                </select>
                                <InputError :message="form.errors.storage_id" class="mt-2" />
                            </div>
                            <div class="">
                                <InputLabel>Details</InputLabel>
                                <TextInput v-model="form.details" class="w-full" type="text" placeholder="Details"/>
                                <InputError :message="form.errors.details" class="mt-2" />
                            </div>
                            <div class="">
                                <PrimaryButton @click="submit">Save</PrimaryButton>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <Modal :show="modalShow">
            <div class="p-6">
                <h2 :class="{'text-lg font-medium text-green-500': success == true, 'text-lg font-medium text-red-500': success == false}">
                    {{ header }}
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    {{message}}
                </p>

                <SecondaryButton
                    class="w-full mt-2 place-content-center"
                    @click="closeModal">
                        <p>OK</p>
                </SecondaryButton>
            </div>
        </Modal>


    </AuthenticatedLayout>

</template>
