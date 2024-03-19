<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import AssignUserRoleLayout from '@/Layouts/AssignUserRoleLayout.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head, usePage } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
    user: {
        type: Object
    },
    roles: {
        type: Object
    },
    currentRole: {
        type: Object
    }
});

const role = usePage().props.auth.role;
</script>

<template>
    <Head title="Profile" />

    <header class="bg-white shadow">
        <div class="px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Profile</h2>
        </div>
    </header>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <UpdateProfileInformationForm
                    :must-verify-email="mustVerifyEmail"
                    :status="status"
                    :user="user"
                    class="max-w-xl"
                />
            </div>

            <div v-if="role == 'administrator'" class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <AssignUserRoleLayout
                    :roles="roles"
                    :currentRole="currentRole"
                    class="max-w-xl"/>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <UpdatePasswordForm
                    :user="user"
                    class="max-w-xl"
                />
            </div>
        </div>
    </div>
</template>
