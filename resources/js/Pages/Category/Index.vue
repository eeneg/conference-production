<script setup>
    import { useForm } from '@inertiajs/vue3';
    import SettingsLayout from '@/Layouts/SettingsLayout.vue';
    import TextInput from '@/Components/TextInput.vue';
    import PrimaryButton from '@/Components/PrimaryButton.vue';
    import InputLabel from '@/Components/InputLabel.vue';
    import Modal from '@/Components/Modal.vue';
    import SecondaryButton from '@/Components/SecondaryButton.vue';
    import DangerButton from '@/Components/DangerButton.vue';
    import Pagination from '@/Components/Pagination.vue'
    import InputError from '@/Components/InputError.vue';
    import {ref} from  'vue';
    import axios from 'axios';

    const props = defineProps({category: Object})

    const form = useForm({
        id: null,
        title: null,
        type: "1",
        details: null
    })

    const confirmForm = useForm({
        password: null
    })

    const edit = ref(false)

    const confirmingCategoryDeletion = ref(false)

    var header = ""
    var message = ""
    var success = true

    const modalShow = ref(false)


    const submit = () => {
        form.submit('post', route('category.store'),{
            preserveScroll: true,
            onSuccess: () => {
                header = "Success!"
                success = true
                message = "Category Submitted Successfuly"
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
        form.submit('patch', route('category.update', form.id),{
            preserveScroll: true,
            onSuccess: () => {
                edit.value = false
                header = "Success!"
                success = true
                message = "Category Updated Successfuly"
                modalShow.value = true
                form.reset()
            },
            onError: () => {
                preserveScroll: true,
                header = "Error!"
                success = false
                message = "Something went wrong!"
                modalShow.value = true
            }
        })
    }

    const checkCategory = () => {
        axios.get(route('category.check', form.id))
        .then(e => {
            console.log(e.data)
            if(e.data){
                confirmingCategoryDeletion.value = true
            }else{
                header = "Error!"
                success = false
                message = "There are Files and Attachments linked to this Category, thus it cannot be deleted!"
                modalShow.value = true
            }
        })
        .catch(e => {

        })
    }

    const deleteCategory = () => {
        confirmForm.submit('delete', route('category.destroy', form.id),{
            preserveScroll: true,
            onSuccess: () => {
                edit.value = false
                header = "Success!"
                success = true
                message = "Category Deleted Successfuly"
                modalShow.value = true
                form.reset()
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
        confirmingCategoryDeletion.value = false
    }

    const closeModal = () => {
        modalShow.value =  false
    }



</script>
<template>

    <SettingsLayout :type="2">
        <div>
            <div class="py-5">
                <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div class="flex flex-row">
                            <div class="pl-5 pr-6 mt-3 grow mb-3">
                                <header>
                                    <h2 class="text-lg font-medium text-gray-900">Category</h2>

                                    <p class="mt-1 text-sm text-gray-600">
                                        Management tab for file categories
                                    </p>
                                </header>
                            </div>
                        </div>
                        <div class="mt-3 mb-3 mr-3 pr-6 pl-5">
                            <div class="flex flex-row space-x-4">
                                <div class="basis-1/2">
                                    <InputLabel>Title</InputLabel>
                                    <TextInput class="w-full" type="text" v-model="form.title" placeholder="Title"/>
                                    <InputError :message="form.errors.title" class="mt-2" />
                                </div>
                                <div class="basis-1/2">
                                    <InputLabel>Category Type</InputLabel>
                                    <select name="type" id="type" v-model="form.type" class="border rounded w-full text-gray-700 border-gray-300">
                                        <option value="1">File</option>
                                        <option value="2">Reference</option>
                                    </select>
                                    <InputError :message="form.errors.type" class="mt-2" />
                                </div>
                            </div>
                            <div class="flex flex-row space-x-4 mt-4">
                                <div class="basis-full">
                                    <InputLabel>Details</InputLabel>
                                    <textarea class="w-full rounded border-gray-300" type="text" v-model="form.details" placeholder="Details"></textarea>
                                    <InputError :message="form.errors.details" class="mt-2" />
                                </div>
                            </div>
                            <div class="flex flex-row">
                                <div class="space-x-3 mt-2">
                                    <PrimaryButton @click="submit" v-if="edit == false">Save</PrimaryButton>
                                    <PrimaryButton @click="update" v-if="edit">Save Changes</PrimaryButton>
                                    <SecondaryButton v-if="edit" @click="clearForm">Cancel</SecondaryButton>
                                    <DangerButton v-if="edit" class="float-right" @click="checkCategory">Delete</DangerButton>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3 mb-3 mr-3 pr-6 pl-5 flex flex-row">
                            <div class="basis-full p-2 max-h-80 overflow-auto mb-5">
                                <table class="w-full table-auto text-center">
                                    <thead>
                                        <tr>
                                            <th class="border-b border-slate-300" style="width: 30%;">Title</th>
                                            <th class="border-b border-slate-300" style="width: 30%;">Type</th>
                                            <th class="border-b border-slate-300" style="width: 10%;"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="i in props.category.data" class="border-b-2 py-2">
                                            <td class="py-2 text-wrap">
                                                <div class="flex flex-col text-left">
                                                    <div class="text-lg">
                                                        {{ i.title }}
                                                    </div>
                                                    <div class="text-xs text-gray-500">
                                                        {{ i.details }}
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-2 text-wrap">{{i.type == "1" ? "File" : "Reference"}}</td>
                                            <td class="py-2 text-wrap"><button class="border-b-2 border-b-indigo-400 hover:border-b-indigo-800" @click="fillForm(i)">Edit</button></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div>
                            <Pagination :data="props.category"></Pagination>
                        </div>
                    </div>
                </div>
            </div>


            <Modal :show="confirmingCategoryDeletion" @close="closeModal">
                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-900">
                        Are you sure you want to delete the category?
                    </h2>

                    <p class="mt-1 text-sm text-gray-600">
                        Once the category is deleted, all of its resources and data will be permanently deleted. Please
                        enter your password to confirm you would like to permanently delete the category.
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
                            @keyup.enter="deleteCategory"
                        />

                        <InputError :message="confirmForm.errors.password" class="mt-2" />
                    </div>

                    <div class="mt-6 flex justify-end">
                        <SecondaryButton @click="closeDeleteModal"> Cancel </SecondaryButton>

                        <DangerButton
                            class="ml-3"
                            :class="{ 'opacity-25': confirmForm.processing }"
                            :disabled="confirmForm.processing"
                            @click="deleteCategory"
                        >
                            Delete Category
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

        </div>
    </SettingsLayout>

</template>
