<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import Pagination from '@/Components/Pagination.vue'
import NavLink from '@/Components/NavLink.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({conf: Object})

const form = useForm({
    search: ''
})

const search = () => {

}

</script>

<template>
    <Head title="Conferences" />


    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Conferences</h2>
        </template>

        <div class="py-5">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="pr-6 pl-6 mt-3">
                        <table class="w-full">
                            <thead>
                                <tr>
                                    <th><p class="float-left">Title</p></th>
                                    <th><p class="float-left">Date</p></th>
                                    <th><p class="float-left">Status</p></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="conf in conf.data">
                                    <td>{{conf}}</td>
                                    <td>{{conf}}</td>
                                    <td>{{conf}}</td>
                                    <td>{{conf}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="p-6">
                        <Pagination :data="props.conf"></Pagination>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
