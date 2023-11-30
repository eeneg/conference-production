<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head, useForm } from '@inertiajs/vue3';
    import InputLabel from '@/Components/InputLabel.vue';
    import PrimaryButton from '@/Components/PrimaryButton.vue';
    import InputError from '@/Components/InputError.vue';
    import { QuillEditor } from '@vueup/vue-quill';
    import { ref } from 'vue';
    import SecondaryButton from '@/Components/SecondaryButton.vue';
    import Modal from '@/Components/Modal.vue';
    import '@vueup/vue-quill/dist/vue-quill.snow.css';

    const props = defineProps({id: String, content: {type: String, required: false}, conf_title: String})
    const modalShow = ref(false)

    var header = ""
    var success = true
    var message = ""

    const form = useForm({
        id: props.id,
        content: props.content ? props.content : null
    })

    const submit = () => {
        form.post(route('minutes.store'), {
            onSuccess: () => {
                header = "Success!"
                success = true
                message = "Submitted Successfuly"
                modalShow.value = true
            },
            onErrorCaptured: () => {
                header = "Error!"
                success = false
                message = "Failed to Submit"
                modalShow.value = true
            }
        })
    }

    const closeModal = () => {
        modalShow.value = false
    }

</script>

<style>

</style>

<template>
    <Head title="Conference Form" />


    <AuthenticatedLayout>

        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Minutes Form <br> {{ props.conf_title }}</h2>
        </template>

        <div class="py-5">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="pr-6 pl-6 mt-3">
                        <form @submit.prevent="submit()" class="mt-1">

                            <div class="flex justify-between pr-6 pl-6 items-center">
                                <div>
                                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Minutes</h2>
                                </div>
                                <div>
                                    <PrimaryButton
                                        type="button"
                                        class=""
                                    >
                                        Print
                                    </PrimaryButton>
                                </div>
                            </div>

                            <div class="pr-6 pl-6 mt-3">

                                <InputError :message="form.errors.content" class="mt-2" />

                                <QuillEditor theme="snow" v-model:content="form.content" contentType="html" style="min-height: 500px;max-height: 500px; overflow-y: auto;"/>

                            </div>


                            <div class="pr-6 pl-6 mt-3 pb-3 border-t-2">
                                <PrimaryButton
                                    type="submit"
                                    class="mt-4 w-full place-content-center"
                                >
                                    Submit
                                </PrimaryButton>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>


<Modal :show="modalShow">
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
</template>
