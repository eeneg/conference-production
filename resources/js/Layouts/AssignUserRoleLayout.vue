<script setup>
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

    const props = defineProps({
        roles: Object,
        currentRole: Object
    })

    const user = usePage().props.auth.user

    const errorMessage = ref(null)

    const form = useForm({
        role_id: null,
        user_id: user.id
    })

    const submitRole = () => {
        form.submit(route('profile.assignRole'), {
            onSuccess: () => {
                form.role_id = null
            },
            onError: (e) => {
                console.log(e)
            }
        })
    }

    const clearSelectedRole = () => {
        form.role_id = null
        clearErrorMessage()
    }

    const checkRole = () => {

        const role = form.role_id

        if(role == null){
            errorMessage.value = "Selection cannot be Empty"
        }else if(role == props.currentRole.id){
            errorMessage.value = "User is already has this role"
        }else{
            submitRole()
            errorMessage.value = null
        }
    }

    const clearErrorMessage = () => {
        errorMessage.value = null
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

        <div class="mt-6 space-y-6">
            <div>
                <select @input="clearErrorMessage" name="cars" id="cars" v-model="form.role_id" class="border w-full rounded text-gray-700 border-gray-300">
                    <option :value="role.id" v-for="role in props.roles">{{ role.title.charAt(0).toUpperCase() + role.title.slice(1) }}</option>
                </select>
                <Transition enter-from-class="opacity-0" leave-to-class="opacity-0" class="transition ease-in-out">
                    <p v-if="errorMessage != null" class="text-sm text-red-600">{{ errorMessage }}</p>
                </Transition>
                <InputError :message="form.errors.role_id" class="mt-2" />
            </div>

            <div class="flex items-center gap-4">
                <DangerButton
                    class=""
                    type="button"
                    :class="{ 'opacity-25': form.processing }"
                    @click="clearSelectedRole"
                >
                        Clear
                </DangerButton>
                <PrimaryButton @click="checkRole">Save</PrimaryButton>

                <Transition enter-from-class="opacity-0" leave-to-class="opacity-0" class="transition ease-in-out">
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Saved.</p>
                </Transition>
            </div>
        </div>


    </section>
</template>
