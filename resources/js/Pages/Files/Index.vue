<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head } from '@inertiajs/vue3';
    import TextInput from '@/Components/TextInput.vue';
    import PrimaryButton from '@/Components/PrimaryButton.vue';
    import InputLabel from '@/Components/InputLabel.vue';
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
                                <input type="file" placeholder="Storage Location"/>
                            </div>
                            <div class="">
                                <InputLabel>Storage Location</InputLabel>
                                <TextInput class="w-full" type="text" placeholder="Storage Location"/>
                            </div>
                            <div class="">
                                <InputLabel>Details</InputLabel>
                                <TextInput class="w-full" type="text" placeholder="Details"/>
                            </div>
                            <div class="">
                                <PrimaryButton>Save</PrimaryButton>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>

</template>
