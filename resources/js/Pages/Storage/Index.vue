<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head } from '@inertiajs/vue3';
    import TextInput from '@/Components/TextInput.vue';
    import PrimaryButton from '@/Components/PrimaryButton.vue';
    import InputLabel from '@/Components/InputLabel.vue';
    import Dropdown from '@/Components/Dropdown.vue'
    import DropdownLink from '@/Components/DropdownLink.vue'
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
                                    Create or Edit Storage location for your files
                                </p>
                            </header>
                        </div>
                    </div>
                    <div class="mt-3 mb-3 mr-3 pr-6 pl-5 flex flex-nowrap max-h-80">
                        <div class="space-y-6 mr-3 basis-1/2">
                            <div class="">
                                <InputLabel>Title</InputLabel>
                                <TextInput class="w-full" type="text" placeholder="Title"/>
                            </div>
                            <div class="">
                                <InputLabel>Location</InputLabel>
                                <TextInput class="w-full" type="text" placeholder="Location"/>
                            </div>
                            <div class="">
                                <InputLabel>Details</InputLabel>
                                <TextInput class="w-full" type="text" placeholder="Details"/>
                            </div>
                            <div class="">
                                <PrimaryButton>Save</PrimaryButton>
                            </div>
                        </div>
                        <div class="ml-3 mb-5 overflow-y-auto basis-1/2">
                            <table class="w-full table-auto text-center">
                                <thead>
                                    <tr>
                                        <th class="border-b border-slate-300" style="width: 30%;">Title</th>
                                        <th class="border-b border-slate-300" style="width: 30%;">Location</th>
                                        <th class="border-b border-slate-300" style="width: 20%;">Details</th>
                                        <th class="border-b border-slate-300" style="width: 20%;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>asd</td>
                                        <td>asd</td>
                                        <td>asd</td>
                                        <td>asd</td>
                                    </tr>
                                    <tr>
                                        <td>asd</td>
                                        <td>asd</td>
                                        <td>asd</td>
                                        <td>asd</td>
                                    </tr>
                                    <tr>
                                        <td>asd</td>
                                        <td>asd</td>
                                        <td>asd</td>
                                        <td>asd</td>
                                    </tr>
                                    <tr>
                                        <td>asd</td>
                                        <td>asd</td>
                                        <td>asd</td>
                                        <td>asd</td>
                                    </tr>
                                    <tr>
                                        <td>asd</td>
                                        <td>asd</td>
                                        <td>asd</td>
                                        <td>asd</td>
                                    </tr>
                                    <tr>
                                        <td>asd</td>
                                        <td>asd</td>
                                        <td>asd</td>
                                        <td>asd</td>
                                    </tr>
                                    <tr>
                                        <td>asd</td>
                                        <td>asd</td>
                                        <td>asd</td>
                                        <td>asd</td>
                                    </tr>
                                    <tr>
                                        <td>asd</td>
                                        <td>asd</td>
                                        <td>asd</td>
                                        <td>asd</td>
                                    </tr>
                                    <tr>
                                        <td>asd</td>
                                        <td>asd</td>
                                        <td>asd</td>
                                        <td>asd</td>
                                    </tr>
                                    <tr>
                                        <td>asd</td>
                                        <td>asd</td>
                                        <td>asd</td>
                                        <td>asd</td>
                                    </tr>
                                    <tr>
                                        <td>asd</td>
                                        <td>asd</td>
                                        <td>asd</td>
                                        <td>asd</td>
                                    </tr>
                                    <tr>
                                        <td>asd</td>
                                        <td>asd</td>
                                        <td>asd</td>
                                        <td>asd</td>
                                    </tr>
                                    <tr>
                                        <td>asd</td>
                                        <td>asd</td>
                                        <td>asd</td>
                                        <td>asd</td>
                                    </tr>
                                    <tr>
                                        <td>asd</td>
                                        <td>asd</td>
                                        <td>asd</td>
                                        <td>asd</td>
                                    </tr>
                                    <tr>
                                        <td>asd</td>
                                        <td>asd</td>
                                        <td>asd</td>
                                        <td>asd</td>
                                    </tr>
                                    <tr>
                                        <td>asd</td>
                                        <td>asd</td>
                                        <td>asd</td>
                                        <td>asd</td>
                                    </tr>
                                    <tr>
                                        <td>asd</td>
                                        <td>asd</td>
                                        <td>asd</td>
                                        <td>asd</td>
                                    </tr>
                                    <tr>
                                        <td>asd</td>
                                        <td>asd</td>
                                        <td>asd</td>
                                        <td>asd</td>
                                    </tr>
                                    <tr>
                                        <td>asd</td>
                                        <td>asd</td>
                                        <td>asd</td>
                                        <td>asd</td>
                                    </tr>
                                    <tr>
                                        <td>asd</td>
                                        <td>asd</td>
                                        <td>asd</td>
                                        <td>asd</td>
                                    </tr>
                                    <tr>
                                        <td>asd</td>
                                        <td>asd</td>
                                        <td>asd</td>
                                        <td>asd</td>
                                    </tr>
                                    <tr>
                                        <td>asd</td>
                                        <td>asd</td>
                                        <td>asd</td>
                                        <td>asd</td>
                                    </tr>
                                    <tr>
                                        <td>asd</td>
                                        <td>asd</td>
                                        <td>asd</td>
                                        <td>asd</td>
                                    </tr>
                                    <tr>
                                        <td>asd</td>
                                        <td>asd</td>
                                        <td>asd</td>
                                        <td>asd</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>

</template>
