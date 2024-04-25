<script setup>
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { useForm, usePage } from '@inertiajs/vue3';

    const props = defineProps({
        roles: Object,
        currentRole: Object,
        fromProfile: Boolean,
        user_id: String,
    })

    const form = useForm({
        role_id: null,
        user_id: props.user_id
    })

    const clearSelectedRole = () => {
        form.role_id = null
    }

</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">User Role: {{ props.currentRole.title.charAt(0).toUpperCase() + props.currentRole.title.slice(1) }}</h2>

            <p class="mt-1 text-sm text-gray-600">
                Assign or Edit User role
            </p>
        </header>

        <form @submit.prevent="fromProfile ? form.post(route('profile.assignRole')) : form.post(route('user.role'))" class="mt-6 space-y-6">
            <div>
                <select name="role" id="role" v-model="form.role_id" class="border w-full rounded text-gray-700 border-gray-300">
                    <option :value="role.id" v-for="role in props.roles">{{ role.title.charAt(0).toUpperCase() + role.title.slice(1) }}</option>
                </select>
                <InputError :message="form.errors.role_id" class="mt-2" />
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing" :class="{ 'opacity-25': form.processing }">Save</PrimaryButton>
                <SecondaryButton
                    class=""
                    type="button"
                    :disabled="form.processing"
                    :class="{ 'opacity-25': form.processing }"
                    v-if="form.role_id != null"
                    @click="clearSelectedRole"
                >
                        Clear
                </SecondaryButton>

                <Transition enter-from-class="opacity-0" leave-to-class="opacity-0" class="transition ease-in-out">
                    <p v-if="form.processing" class="text-sm text-gray-600">Loading...</p>
                </Transition>

                <Transition enter-from-class="opacity-0" leave-to-class="opacity-0" class="transition ease-in-out">
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Saved.</p>
                </Transition>
            </div>
        </form>


    </section>
</template>
