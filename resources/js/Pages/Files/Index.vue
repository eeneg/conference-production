<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head } from '@inertiajs/vue3';
    import TextInput from '@/Components/TextInput.vue';



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
                        <div class="pl-5 pr-6 mt-3 grow">
                            <header>
                                <h2 class="text-lg font-medium text-gray-900">Upload File</h2>

                                <p class="mt-1 text-sm text-gray-600">
                                    Words here
                                </p>
                            </header>
                            <TextInput type="text" placeholder="Storage Location"/> <br>
                            <TextInput type="text" placeholder="Details"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="py-5">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg mt-4">
                    <div class="pl-5 pr-6 mt-3 grow">
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">Words here</h2>

                            <p class="mt-1 text-sm text-gray-600">
                                Words here
                            </p>
                        </header>
                        <table class="w-full mt-5">
                            <thead>
                                <tr>
                                    <th class="border-b border-slate-300" style="width: 30%;">
                                        Title
                                    </th>
                                    <th class="border-b border-slate-300" style="width: 30%;">
                                        Date
                                    </th>
                                    <th class="border-b border-slate-300" style="width: 30%;">
                                        Input Minutes
                                    </th>
                                    <th class="border-b border-slate-300" style="width: 10%;">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-slate-700">
                                <tr>
                                    <td class="p-2 text-center border-b border-slate-100">
                                        asd
                                    </td>
                                    <td class="p-2 text-center border-b border-slate-100">
                                        asd
                                    </td>
                                    <td class="p-2 text-center border-b border-slate-100">
                                        asd
                                    </td>
                                    <td class="p-2 text-center border-b border-slate-100">
                                        asd
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>

</template>
