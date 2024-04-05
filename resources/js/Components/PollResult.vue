<script setup>
import Modal from './Modal.vue'
import PrimaryButton from './PrimaryButton.vue';
import { ref } from 'vue';
import { onMounted } from 'vue';

const props = defineProps({poll:Object, showModal:Boolean})

const emit = defineEmits(['close'])

const show = ref(props.showModal)

const res = props.poll.result == "Approved" ? 1 : props.poll.result == "Dissaproved" ? 2 : 3

const close = () => {
    emit('close', false)
}

onMounted(() => {

})

</script>

<template>
    <Modal :show="showModal">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900">
                {{ props.poll.title }}
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                {{ props.poll.title }}
            </p>

            <div
                class="border rounded p-6 flex justify-center uppercase text-lg font-bold"
                :class="{
                    'text-white bg-green-500 border-green-500':res == 1,
                    'text-white bg-red-500 border-red-500':res == 2,
                    'text-white bg-yellow-500 border-yellow-500':res == 3
                }"
            >
                <p>
                    {{ props.poll.result }}
                </p>
            </div>

            <div class="mt-2">
                <PrimaryButton class="flex w-full justify-center" @click="close">Close</PrimaryButton>
            </div>
        </div>
    </Modal>
</template>
