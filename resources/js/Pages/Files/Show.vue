<script setup>

    import FindFileLayout from '@/Layouts/FindFileLayout.vue';


    const props = defineProps({files: Object})


</script>
<template>
    <Head title="Attachments" />

    <FindFileLayout :type="2">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Attachments</h2>
        </template>
    </FindFileLayout>

</template>
