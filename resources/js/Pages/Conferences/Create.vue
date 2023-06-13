<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import Form from '@/Pages/Conferences/Form.vue';
    import { Head, useForm } from '@inertiajs/vue3';

    const submit = () => {
        form.post(route('conferences.store'), {
            onSuccess: () => {
                form.reset()
                category = ''
                categories = []
            },
            onError: () => {

            }
        })
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

        <Form @submit.prevent="submit()">

        </Form>

    </AuthenticatedLayout>
</template>
