<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head, useForm } from '@inertiajs/vue3';
    import TextInput from '@/Components/TextInput.vue';
    import PrimaryButton from '@/Components/PrimaryButton.vue';
    import InputLabel from '@/Components/InputLabel.vue';
    import Modal from '@/Components/Modal.vue';
    import SecondaryButton from '@/Components/SecondaryButton.vue';
    import DangerButton from '@/Components/DangerButton.vue';
    import InputError from '@/Components/InputError.vue';
    import {ref} from  'vue';
    import axios from 'axios';

    const props = defineProps({storage: Object})

    const form = useForm({
        id: null,
        title: null,
        location: null,
        details: null
    })

    const confirmForm = useForm({
        password: null
    })

    const edit = ref(false)

    const confirmingStorageDeletion = ref(false)

    var header = ""
    var message = ""
    var success = true

    const modalShow = ref(false)


    const submit = () => {
        form.submit('post', route('storage.store'),{
            onSuccess: () => {
                header = "Success!"
                success = true
                message = "Storage Location Submitted Successfuly"
                modalShow.value = true
                form.reset()
            },
            onError: () => {
                header = "Error!"
                success = false
                message = "Something went wrong"
                modalShow.value = true
            }
        })
    }

    const update = () => {
        form.submit('patch', route('storage.update', form.id),{
            onSuccess: () => {
                edit.value = false
                header = "Success!"
                success = true
                message = "Storage Location Updated Successfuly"
                modalShow.value = true
                form.reset()
            },
            onError: () => {
                header = "Error!"
                success = false
                message = "Something went wrong!"
                modalShow.value = true
            }
        })
    }

    const checkStorage = () => {
        axios.get(route('storage.check', form.id))
        .then(e => {
            console.log(e.data)
            if(e.data){
                confirmingStorageDeletion.value = true
            }else{
                header = "Error!"
                success = false
                message = "There are Files and Attachments inside this storage, thus it cannot be deleted!"
                modalShow.value = true
            }
        })
        .catch(e => {

        })
    }

    const deleteStorage = () => {
        confirmForm.submit('delete', route('storage.destroy', form.id),{
            onSuccess: () => {
                edit.value = false
                header = "Success!"
                success = true
                message = "Storage Location Deleted Successfuly"
                modalShow.value = true
                closeDeleteModal()
                confirmForm.reset()
            },
            onError: () => {
                edit.value = false
                header = "Error!"
                success = true
                message = "Something went wrong!"
                modalShow.value = true
            }
        })
    }

    const fillForm = (i) => {
        Object.assign(form, i)
        edit.value = true
    }

    const clearForm = () => {
        form.reset()
        edit.value = false
    }

    const closeDeleteModal = () => {
        confirmingStorageDeletion.value = false
    }

    const closeModal = () => {
        modalShow.value =  false
    }



</script>
<template>
    <Head title="Attachments" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Storage</h2>
        </template>

        <div class="py-5">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="flex flex-row">
                        <div class="pl-5 pr-6 mt-3 grow mb-3">
                            <header>
                                <h2 class="text-lg font-medium text-gray-900">Storage Location</h2>

                                <p class="mt-1 text-sm text-gray-600">
                                    Create, Edit, or Destroy Storage location for your files
                                </p>
                            </header>
                        </div>
                    </div>
                    <div class="mt-3 mb-3 mr-3 pr-6 pl-5 flex flex-nowrap min-h-80 max-h-96">
                        <div class="space-y-6 mr-3 basis-1/2">
                            <div class="">
                                <InputLabel>Title</InputLabel>
                                <TextInput class="w-full" type="text" v-model="form.title" placeholder="Title"/>
                                <InputError :message="form.errors.title" class="mt-2" />
                            </div>
                            <div class="">
                                <InputLabel>Location</InputLabel>
                                <TextInput class="w-full" type="text" v-model="form.location" placeholder="Location"/>
                                <InputError :message="form.errors.location" class="mt-2" />
                            </div>
                            <div class="">
                                <InputLabel>Details</InputLabel>
                                <TextInput class="w-full" type="text" v-model="form.details" placeholder="Details"/>
                                <InputError :message="form.errors.details" class="mt-2" />
                            </div>
                            <div class="space-x-3">
                                <PrimaryButton @click="submit" v-if="edit == false">Save</PrimaryButton>
                                <PrimaryButton @click="update" v-if="edit">Save Changes</PrimaryButton>
                                <SecondaryButton v-if="edit" @click="clearForm">Cancel</SecondaryButton>
                                <DangerButton v-if="edit" class="float-right" @click="checkStorage">Delete</DangerButton>
                            </div>
                        </div>
                        <div class="ml-3 mb-5 overflow-y-auto basis-1/2">
                            <table class="w-full table-auto text-center">
                                <thead>
                                    <tr>
                                        <th class="border-b border-slate-300" style="width: 30%;">Title</th>
                                        <th class="border-b border-slate-300" style="width: 30%;">Location</th>
                                        <th class="border-b border-slate-300" style="width: 20%;">Details</th>
                                        <th class="border-b border-slate-300" style="width: 20%;"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="i in props.storage">
                                        <td>{{i.title}}</td>
                                        <td>{{i.location}}</td>
                                        <td>{{i.details}}</td>
                                        <td><button class="border-b-2 border-b-indigo-400 hover:border-b-indigo-800" @click="fillForm(i)">Edit</button></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <Modal :show="confirmingStorageDeletion" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Are you sure you want to delete the storage location?
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    Once the storage location is deleted, all of its resources and data will be permanently deleted. Please
                    enter your password to confirm you would like to permanently delete the storage location.
                </p>

                <div class="mt-6">
                    <InputLabel for="password" value="Password" class="sr-only" />

                    <TextInput
                        id="password"
                        ref="passwordInput"
                        v-model="confirmForm.password"
                        type="password"
                        class="mt-1 block w-3/4"
                        placeholder="Password"
                        @keyup.enter="deleteStorage"
                    />

                    <InputError :message="confirmForm.errors.password" class="mt-2" />
                </div>

                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeDeleteModal"> Cancel </SecondaryButton>

                    <DangerButton
                        class="ml-3"
                        :class="{ 'opacity-25': confirmForm.processing }"
                        :disabled="confirmForm.processing"
                        @click="deleteStorage"
                    >
                        Delete Storage Location
                    </DangerButton>
                </div>
            </div>
        </Modal>


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
