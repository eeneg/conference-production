<script setup>
import Modal from '@/Components/Modal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputError from '@/Components/InputError.vue';
import ResponseModal from '../ResponseModal.vue';
import { ref } from  'vue';
import { useForm } from '@inertiajs/vue3';
import { onMounted } from 'vue';

const props = defineProps({poll:Object, conference_id:String, editMode: Boolean, createPollModal:Boolean})

const emit = defineEmits(['closeCreateUpdateModal', 'openResponseModal', 'pollUpdated'])

const saveButtonText = ref('Save')

const header = ref('')
const message = ref('')
const success = ref(false)
const responseModal = ref(false)

const closeResponseModal = (e) => {
    responseModal.value = e
}

const form = useForm({
    title: null,
    type: null,
    details: null,
    conf_id: props.conference_id
})

const closePollModal = () => {
    emit('closeCreateUpdateModal', false)
}

const submitPoll = () => {
    saveButtonText.value = 'Loading...'
    form.submit('post', route('poll.store'),{
        onSuccess: () => {
            form.reset()
            header.value = "Sucess!"
            success.value = true
            message.value = "Poll Submitted"
            responseModal.value = true
            saveButtonText.value = 'Save'
        },
        onErrorCaptured: () => {
            header.value = "Error!"
            success.value = false
            message.value = "Failed to Submit"
            responseModal.value = true
            saveButtonText.value = 'Save'
        }
    })
}

const pollID = ref(null)

const pollUpdated = () => {
    emit('pollUpdated', null)
}


const updatePoll = () => {
    saveButtonText.value = 'Loading...'
    form.submit('patch', route('poll.update', pollID.value),{
        onSuccess: () => {
            header.value = "Sucess!"
            success.value = true
            message.value = "Poll Updated"
            responseModal.value = true
            saveButtonText.value = 'Save'
            pollUpdated()
        },
        onError: () => {
            header.value = "Error!"
            success.value = false
            message.value = "Failed to Update"
            responseModal.value = true
            saveButtonText.value = 'Save'
        }
    })
}

const submit = () => {
    if(props.editMode){
        updatePoll()
    }else{
        submitPoll()
    }
}

onMounted(() => {
    if(props.editMode){
        pollID.value = props.poll.id
        form.title = props.poll.title
        form.type = props.poll.type
        form.details = props.poll.details
    }
})
</script>

<template>
    <Modal :show="props.createPollModal" :maxWidth="'2xl'">
        <div class="p-6">

            <div class="flex flex-row">
                <div class="basis-1/2">
                    <h2 class="text-lg font-medium text-black-500">

                    </h2>

                    <p class="mt-1 text-sm text-gray-600">

                    </p>
                </div>
                <div class="basis-1/2">
                    <a class="float-right" role="button" @click="closePollModal">
                        <svg x="0px" y="0px" width="20" height="20" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" opacity=".35"></circle><path d="M14.812,16.215L7.785,9.188c-0.384-0.384-0.384-1.008,0-1.392l0.011-0.011c0.384-0.384,1.008-0.384,1.392,0l7.027,7.027	c0.384,0.384,0.384,1.008,0,1.392l-0.011,0.011C15.82,16.599,15.196,16.599,14.812,16.215z"></path><path d="M7.785,14.812l7.027-7.027c0.384-0.384,1.008-0.384,1.392,0l0.011,0.011c0.384,0.384,0.384,1.008,0,1.392l-7.027,7.027	c-0.384,0.384-1.008,0.384-1.392,0l-0.011-0.011C7.401,15.82,7.401,15.196,7.785,14.812z"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <div class="mt-3 mb-3 mr-3 pr-6 pl-5 space-y-3">
                <div class="">
                    <InputLabel for="title">Title</InputLabel>
                    <TextInput id="title" class="w-full" v-model="form.title"> </TextInput>
                    <InputError :message="form.errors.title" class="mt-2" />
                </div>
                <div class="">
                    <InputLabel for="title">Type</InputLabel>
                    <select class="border-gray-300 rounded w-full mt-1" v-model="form.type">
                        <option selected :value="null" disabled>Select</option>
                        <option :value="'nominal'">Nominal</option>
                        <option :value="'majority'">Majority</option>
                    </select>
                    <InputError :message="form.errors.type" class="mt-2" />
                </div>
                <div class="mt-2">
                    <InputLabel for="details">Details</InputLabel>
                    <textarea class="w-full rounded border-gray-300" rows="10" v-model="form.details"></textarea>
                    <InputError :message="form.errors.details" class="mt-2" />
                </div>
                <div class="mt-2">
                    <PrimaryButton @click="submit">{{saveButtonText}}</PrimaryButton>
                </div>
            </div>

        </div>
    </Modal>

    <ResponseModal v-if="responseModal" @closeResponseModal="closeResponseModal($event)" :responseModalProp="responseModal" :header="header" :message="message" :success="success"/>
</template>
