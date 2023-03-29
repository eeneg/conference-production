<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import Pagination from '@/Components/Pagination.vue'
import NavLink from '@/Components/NavLink.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({users: Object})

const form = useForm({
    search: ''
})

const search = () => {

}

</script>

<template>
    <Head title="Users" />


    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Users</h2>
        </template>

        <div class="py-5">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <form @submit.prevent="search" class="mt-6 space-y-6 flex flex-row">
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
                    <div class="pl-6 pr-6 text-gray-900">
                        <table class="w-full table-auto">
                            <thead>
                                <tr>
                                    <th><p class="float-left">Name</p></th>
                                    <th><p class="float-left">Email</p></th>
                                    <th><p class="float-left">Role</p></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="user in props.users.data">
                                    <td>{{ user.name }}</td>
                                    <td>{{ user.email }}</td>
                                    <td>{{ user.role }}</td>
                                    <td><NavLink :href="route('users.edit', user.id)">Action</NavLink></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="p-6">
                        <Pagination :data="props.users"></Pagination>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
