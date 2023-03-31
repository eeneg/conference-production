<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head, useForm } from '@inertiajs/vue3';
    import TextInput from '@/Components/TextInput.vue';
    import InputLabel from '@/Components/InputLabel.vue';
    import { router } from '@inertiajs/vue3'
    import PrimaryButton from '@/Components/PrimaryButton.vue';
    import InputError from '@/Components/InputError.vue';
    import { QuillEditor } from '@vueup/vue-quill';
    import '@vueup/vue-quill/dist/vue-quill.snow.css';


    const form = useForm({
        title: '',
        agenda: '',
        date: '',
        attachments: []
    })

    const getFiles = ($event) => {
        form.attachments = $event.target.files
    }

    const submit = () => {
        form.post(route('conferences.store'), {
            onSuccess: () => form.reset(),
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

        <div class="py-5">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="pr-6 pl-6 mt-3">
                        <form @submit.prevent="submit()" class="mt-1 space-y-6">
                            <div class="pr-6 pl-6 flex flex-row">

                                <div class="basis-full mr-3">

                                    <InputLabel for="title" value="Title"/>

                                    <TextInput
                                        id="title"
                                        type="text"
                                        v-model="form.title"
                                        class="mt-1 block w-full"
                                    >
                                    </TextInput>

                                    <InputError :message="form.errors.title" class="mt-2" />

                                </div>

                                <div class="flex-initial p-1">

                                    <InputLabel for="date" value="Date"/>

                                    <input type="date" name="date" v-model="form.date" id="date" class="rounded font-medium text-gray-700 border-gray-300">

                                    <InputError :message="form.errors.date" class="mt-2" />

                                </div>

                            </div>
                            <div class="pr-6 pl-6">

                                <InputLabel for="agenda" value="Agenda"/>

                                <InputError :message="form.errors.agenda" class="mt-2" />

                                <QuillEditor theme="snow" v-model:content="form.agenda" contentType="html"/>

                            </div>
                            <div class="pr-6 pl-6">

                                <InputLabel for="attachments" value="Attachments"/>

                                <InputError :message="form.errors.attachments" class="mt-2" />

                                <input type="file" class="w-full" v-on:change="getFiles($event)" multiple>

                            </div>
                            <div class="pr-6 pl-6 mt-3 pb-3">
                                <PrimaryButton
                                    type="submit"
                                    class=""
                                >
                                    Sumbit
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>
