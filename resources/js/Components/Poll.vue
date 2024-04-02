<script setup>
import Modal from './Modal.vue'
import { useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import PrimaryButton from './PrimaryButton.vue';
import SecondaryButton from './SecondaryButton.vue';
import InputError from './InputError.vue';
import { onMounted } from 'vue';
import axios from 'axios';

const props = defineProps({showPollModal:Boolean, pollID:String, initiatorID:String})

const user_id = usePage().props.auth.user.id;

const errors = ref('')

const pollForm = useForm({
    poll_id: props.pollID,
    user_id: user_id,
    vote: null
})

const showModal = ref(props.showPollModal)
const pollTitle = ref('')
const pollDetails = ref('')

const pollTrueCount = ref(0)
const pollFalseCount = ref(0)

const getPoll = () => {
    axios.get('/getPoll/'+props.pollID)
    .then(({data}) => {
        pollTitle.value = data.title
        pollDetails.value = data.details
    })
    .catch(e => {
        console.log(e)
    })
}

const closeModal = () => {
    axios.post('/setPollActive', {id: props.pollID, initiatorID: user_id, value: false})
    .then(({data}) => {

    })
    .catch(e => {
        console.log(e)
    })
}

const setValue = (value) => {
    pollForm.vote = value
    errors.value = ''
}

const submitPollResponse = () => {
    axios.post('/submitPollResponse', pollForm)
    .then(({data}) => {

    })
    .catch(e => {
        errors.value = e.response.data.message
        console.log(e)
    })
}

const setPollCount = (e) => {
    pollTrueCount.value = (e.tr / e.u) * 100
    pollFalseCount.value = (e.fa / e.u) * 100
}

const getPollCount = () => {
    axios.get('/countPollVotes/'+props.pollID)
    .then(({data}) => {
        setPollCount(data)
    })
    .catch(e => {
        errors.value = e.response.data.message
        console.log(e)
    })
}

const endPoll = () => {
    axios.post('/endPoll', {poll_id: props.pollID})
    .then(({data}) => {
        closeModal()
    })
    .catch(e => {
        console.log(e)
    })
}

onMounted(() => {
    getPoll()
    getPollCount()
    window.Echo.private('poll-vote').listen('PollVoteSubmittedEvent', (e) => {
        setPollCount(e.vote)
    });
})

</script>

<template>
    <Modal :show="showModal">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900">
                {{ pollTitle }}
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                {{ pollDetails }}
            </p>

            <div class="mt-6 space-y-5">
                <div class="border p-6 rounded shadow border-l-8 border-green-400 hover:border-2 hover:border-l-8" @click="setValue(true)">
                    <div class="">
                        <input type="radio" class="" id="yes" v-model="pollForm.vote" :value="true" name="selection"></input>
                        <label for="yes" class="ml-2">YES</label>
                    </div>
                    <div class="mt-3" v-if="pollTrueCount != 0">
                        <div class="w-full rounded-full">
                            <div class="bg-blue-600 text-xs font-medium h-2.5 text-blue-100 text-center p-0.5 leading-none rounded-full" :style="{width: pollTrueCount+'%'}"></div>
                        </div>
                    </div>
                </div>
                <div class="border p-6 rounded shadow border-l-8 border-red-400 hover:border-2 hover:border-l-8" @click="setValue(false)">
                    <div>
                        <input type="radio" class="" id="no" v-model="pollForm.vote" :value="false" name="selection"></input>
                        <label for="no" class="ml-2">NO</label>
                    </div>
                    <div class="mt-3" v-if="pollFalseCount != 0">
                        <div class="w-full rounded-full">
                            <div class="bg-blue-600 text-xs font-medium h-2.5 text-blue-100 text-center p-0.5 leading-none rounded-full" :style="{width: pollFalseCount+'%'}"></div>
                        </div>
                    </div>
                </div>
                <InputError :message="errors" class="mt-2" />
            </div>

            <div class="mt-3">
                <SecondaryButton class="w-full justify-center" @click="endPoll" v-if="user_id == props.initiatorID">End Poll</SecondaryButton>
            </div>
            <div class="mt-3">
                <SecondaryButton class="w-full justify-center" @click="closeModal" v-if="user_id == props.initiatorID">Cancel Poll</SecondaryButton>
            </div>
            <div class="mt-3">
                <PrimaryButton class="w-full justify-center" @click="submitPollResponse">Submit</PrimaryButton>
            </div>
        </div>
    </Modal>
</template>
