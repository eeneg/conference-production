<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head, useForm } from '@inertiajs/vue3';
    import Pagination from '@/Components/Pagination.vue'
    import TextInput from '@/Components/TextInput.vue';
    import InputLabel from '@/Components/InputLabel.vue';
    import PrimaryButton from '@/Components/PrimaryButton.vue';
    import { PlusCircleIcon, FolderIcon } from '@heroicons/vue/20/solid'
    import { router } from '@inertiajs/vue3'
    import NavLink from '@/Components/NavLink.vue';
    import moment from 'moment'

    const props = defineProps({upcoming: Object, finished: Object})

    const form = useForm({
        search: ''
    })

    const search = () => {

    }

    const conferenceForm = () => {
        router.get(route('conferences.create'))
    }

    const formatDate = (date) => {
        return moment(date).format('L')
    }



</script>

<style>


</style>

<template>
    <Head title="Conferences" />


    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Conferences</h2>
        </template>

        <div class="py-5">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="flex flex-row">
                        <div class="pr-6 pl-6 mt-3 grow">
                            <header>
                                <h2 class="text-lg font-medium text-gray-900">Upcoming Conferences</h2>

                                <p class="mt-1 text-sm text-gray-600">
                                    List of upcoming conferences.
                                </p>
                            </header>
                        </div>
                        <div class="pr-6 pl-6 mt-3">
                            <PrimaryButton @click="conferenceForm()">
                                Add
                                <PlusCircleIcon class="h-5 w-5" aria-hidden="true" />
                            </PrimaryButton>
                        </div>
                    </div>

                    <div class="pr-6 pl-6 mt-3">
                        <table class="w-full">
                            <thead>
                                <tr>
                                    <th class="" style="width: 30%;">
                                        Title
                                    </th>
                                    <th class="" style="width: 30%;">
                                        Date
                                    </th>
                                    <th class="" style="width: 30%;">
                                        Input Minutes
                                    </th>
                                    <th class="" style="width: 30%;">

                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-slate-700">
                                <tr v-for="conf in props.upcoming.data">
                                    <td class="text-center p-2 border-b border-slate-100">
                                        {{conf.title}}
                                    </td>
                                    <td class="text-center p-2 border-b border-slate-100">
                                        {{formatDate(conf.date)}}
                                    </td>
                                    <td class="text-center p-2 border-b border-slate-100">
                                        <NavLink :href="route('minutes.create', {'id' : conf.id})">Minutes</NavLink>
                                    </td>
                                    <td class="text-center p-2 border-b border-slate-100">
                                        <NavLink :href="route('conferences.show', {'id' : conf.id})">Action</NavLink>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="p-6">
                        <Pagination :data="props.upcoming"></Pagination>
                    </div>
                </div>
            </div>
        </div>

        <div class="py-5">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="pr-6 pl-6 mt-3">
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">Finished Conferences</h2>

                            <p class="mt-1 text-sm text-gray-600">
                                List of finished conferences.
                            </p>
                        </header>
                    </div>
                    <form @submit.prevent="search" class="mt-1 space-y-6 flex flex-row">
                        <div class="p-6 grow">

                            <InputLabel for="search" value="Search"/>

                            <TextInput
                                id="search"
                                type="search"
                                v-model="form.search"
                                class="mt-1 block w-full"
                            >
                            </TextInput>

                        </div>
                    </form>
                    <div class="pr-6 pl-6 mt-3">
                        <table class="w-full">
                            <thead class="divide-slate-700">
                                <tr>
                                    <th style="width: 30%;">Title</th>
                                    <th style="width: 30%;">Date</th>
                                    <th style="width: 30%;">Input Minutes</th>
                                    <th style="width: 30%;"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-slate-700">
                                <tr v-for="conf in props.finished.data">
                                    <td class="text-center p-2 border-b border-slate-100">{{conf.title}}</td>
                                    <td class="text-center p-2 border-b border-slate-100">{{conf.date}}</td>
                                    <td class="text-center p-2 border-b border-slate-100">Minutes</td>
                                    <td class="text-center p-2 border-b border-slate-100">
                                        Action
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="p-6">
                        <Pagination :data="props.finished"></Pagination>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
