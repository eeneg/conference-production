<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import Storage from '../Storage/Index.vue'
    import Category from '../Category/Index.vue'
    import { Head, useForm } from '@inertiajs/vue3';

    const props = defineProps({storage:Object, category:Object})
</script>
<template>
    <Head title="Attachments" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Settings</h2>
        </template>

        <Storage :storage="props.storage"/>

        <Category :category="props.category"/>

    </AuthenticatedLayout>

</template>
