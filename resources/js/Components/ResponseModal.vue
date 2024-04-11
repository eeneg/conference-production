<script setup>
import Modal from './Modal.vue'
import SecondaryButton from './SecondaryButton.vue'

const props = defineProps({success:Boolean, header:String, message:String,responseModalProp:Boolean})

const emit = defineEmits(['closeResponseModal'])

const closeResponseModal = () => {
    emit('closeResponseModal', false)
}
</script>

<template>
    <Modal :show="props.responseModalProp">
        <div class="p-6">
            <h2 :class="{'text-lg font-medium text-green-500': props.success == true, 'text-lg font-medium text-red-500': props.success == false}">
                {{ props.header }}
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                {{ props.message }}
            </p>


            <SecondaryButton
                class="w-full mt-2 place-content-center bg-red-400"
                @click="closeResponseModal">
                    <p>OK</p>
            </SecondaryButton>
        </div>
    </Modal>
</template>
