<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import Pagination from '@/Components/Pagination.vue'
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { nextTick } from 'vue';

    const props = defineProps({users: Object, search:String})

    const form = useForm({
        search: props.search
    })

    const roleForm = useForm({
        password: null,
        user_id: null,
        role_id: null
    })

    const search = () => {
        form.get(route('users.index'), {
            preserveScroll: true,
            preserveState: true,
            onFinish: () => nextTick(() => document.getElementById('search').focus())
        })
    }

    const reset = () => {
        form.search = ""
        form.get(route('users.index'), {
            preserveScroll: true,
            preserveState: true,
        })
    }

    const formatRole = (role) => {
        if(role != null || role != ''){
            return role.charAt(0).toUpperCase() + role.slice(1)
        }else{
            return ''
        }
    }

    const actionBtn = (id) => {
        router.visit(route('users.edit', id))
    }

</script>

<template>
    <Head title="Users" />

    <header class="bg-white shadow">
        <div class="px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Users</h2>
        </div>
    </header>

    <div class="py-5">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="pl-6 pr-6 mt-3 grow">
                    <header>
                        <h2 class="text-lg font-medium text-gray-900">Users</h2>

                        <p class="mt-1 text-sm text-gray-600">
                            List of all users.
                        </p>
                    </header>
                </div>
                <form @submit.prevent="search" class="flex flex-row mt-1 space-y-6">
                    <div class="p-6 grow">
                        <InputLabel value="Search" for="search" />
                        <div class="relative mt-2 rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">
                                    <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
                                        <path d="M505 442.7L405.3 343c-4.5-4.5-10.6-7-17-7H372c27.6-35.3 44-79.7 44-128C416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c48.3 0 92.7-16.4 128-44v16.3c0 6.4 2.5 12.5 7 17l99.7 99.7c9.4 9.4 24.6 9.4 33.9 0l28.3-28.3c9.4-9.4 9.4-24.6.1-34zM208 336c-70.7 0-128-57.2-128-128 0-70.7 57.2-128 128-128 70.7 0 128 57.2 128 128 0 70.7-57.2 128-128 128z"/>
                                    </svg>
                                </span>
                            </div>

                            <TextInput id="search" type="search" class="block w-full mt-1 pl-9" v-model="form.search" />
                        </div>
                    </div>
                </form>
                <div class="flex flex-row-reverse pl-6 pr-6">
                    <div class="">
                        <SecondaryButton @click="reset">Reset</SecondaryButton>
                    </div>
                </div>
                <div class="pl-6 pr-6 text-gray-900">
                    <table class="w-full table-auto">
                        <thead>
                            <tr>
                                <th class="border-b border-slate-300" style="width: 30%;">Name</th>
                                <th class="border-b border-slate-300" style="width: 30%;">Email</th>
                                <th class="border-b border-slate-300" style="width: 20%;">Role</th>
                                <th class="border-b border-slate-300" style="width: 20%;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="user in props.users.data">
                                <td class="p-2 pl-8 text-center border-b border-slate-100">{{ user.name }}</td>
                                <td class="p-2 pl-8 text-center border-b border-slate-100">{{ user.email }}</td>
                                <td class="p-2 pl-8 text-center border-b border-slate-100 relative">
                                    {{ formatRole(user.roles[0].title) }}
                                </td>
                                <td class="p-2 pl-8 text-center border-b border-slate-100">
                                    <!-- <NavLink :href="route('users.edit', user.id)">Action</NavLink> -->
                                    <PrimaryButton @click="actionBtn(user.id)">Action</PrimaryButton>
                                </td>
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
</template>
