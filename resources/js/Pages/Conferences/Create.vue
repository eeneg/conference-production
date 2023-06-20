<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import Form from '@/Pages/Conferences/Form.vue';
    import { Head, useForm } from '@inertiajs/vue3';
    import Modal from '@/Components/Modal.vue';
    import { nextTick, ref } from 'vue';
    import SecondaryButton from '@/Components/SecondaryButton.vue';


    var header = ""
    var message = ""

    const modalShow = ref(false)

    const submit = (form) => {
        form.post(route('conferences.store'), {
            onSuccess: () => {
                header = "Success!"
                message = "Confrence Submitted Successfuly"
                modalShow.value = true
            },
            onError: () => {
                header = "Error!"
                message = "Something went wrong"
                modalShow.value = true
            }
        })
    }

    const closeModal = () => {
        modalShow.value = false
    }



</script>

<style>
    .ql-editor{
        min-height:200px;
    }
</style>

<template>
    <Head title="Conference Form" />


    <AuthenticatedLayout>

        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Conference Form</h2>
        </template>

        <Form @passData ="submit($event)">

        </Form>

        <Modal :show="modalShow">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    {{ header }}
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    {{message}}
                </p>

                <SecondaryButton class="w-full mt-2 place-content-center" @click="closeModal"><p>OK</p></SecondaryButton>
            </div>
        </Modal>

    </AuthenticatedLayout>
</template>
