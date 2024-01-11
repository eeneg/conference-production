<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';


</script>
<template>
    <Head title="Attachments" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Attachments</h2>
        </template>

        <div class="py-5">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="flex flex-row">
                        <div class="pl-5 pr-6 mt-3 grow">
                            <header>
                                <h2 class="text-lg font-medium text-gray-900">Search Attachments</h2>

                                <p class="mt-1 text-sm text-gray-600">
                                    File searching
                                </p>
                            </header>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>

</template>
