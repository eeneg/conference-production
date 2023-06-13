<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head, useForm } from '@inertiajs/vue3';
    import InputLabel from '@/Components/InputLabel.vue';
    import PrimaryButton from '@/Components/PrimaryButton.vue';
    import InputError from '@/Components/InputError.vue';
    import { QuillEditor } from '@vueup/vue-quill';
    import '@vueup/vue-quill/dist/vue-quill.snow.css';

    const props = defineProps({id: String, content: {type: String, required: false}, conf_title: String})

    const form = useForm({
        id: props.id,
        content: props.content ? props.content : null
    })

    const submit = () => {
        form.post(route('minutes.store'), {
            onSuccess: () => {

            },
            onErrorCaptured: () => {

            }
        })
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
                        <form @submit.prevent="submit()" class="mt-1 space-y-6">

                            <div class="pr-6 pl-6">

                                <InputLabel for="agenda" value="Minutes"/>

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
</template>
