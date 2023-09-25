<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import Form from '@/Pages/Conferences/Form.vue';
    import { Head } from '@inertiajs/vue3';
    import Modal from '@/Components/Modal.vue';
    import { useForm } from '@inertiajs/vue3';
    import { ref } from 'vue';
    import SecondaryButton from '@/Components/SecondaryButton.vue';

    const props = defineProps({conf:Object, edit:Boolean})

    var header = ""
    var message = ""
    var success = true

    const modalShow = ref(false)
    const confirmingConferenceDeletion = ref(false);

    const submit = (form) => {
        form.patch(route('conferences.update', {id: form.id}), {
            onSuccess: () => {
                header = "Success!"
                success = true
                message = "Confrence Submitted Successfuly"
                modalShow.value = true
                form.reset()
            },
            onError: (e) => {
                console.log(e)
                header = "Error!"
                success = false
                message = "Something went wrong"
                modalShow.value = true
            }
        })
    }

    const submitDelete = (deleteForm) => {
        deleteForm.delete(route('conferences.destroy', {id: deleteForm.id}), {
            preserveScroll: true,
            onSuccess: () => {
                header = "Success!"
                success = true
                message = "Confrence Submitted Successfuly"
                deleteForm.reset()
            },
            onError: (e) => {
                console.log(e)
                header = "Error!"
                success = false
                message = "Something went wrong"
                deleteForm.value = true
            }
        });
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
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Conference Edit Form</h2>
        </template>

        <Form @passData ="submit($event)" @deleteConf ="submitDelete($event)" :conf="props.conf" :edit="true">

        </Form>

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
