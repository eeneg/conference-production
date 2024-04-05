<script setup>
import Modal from './Modal.vue'
import { useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import PrimaryButton from './PrimaryButton.vue';
import SecondaryButton from './SecondaryButton.vue';
import InputLabel from './InputLabel.vue';
import InputError from './InputError.vue';
import { onMounted } from 'vue';
import axios from 'axios';

const props = defineProps({showPollModal:Boolean, pollID:String, initiatorID:String})

const user_id = usePage().props.auth.user.id;

const errors = ref('')

const sbmtBtnTxt = ref('Submit')

const initiator = user_id == props.initiatorID

const pollForm = useForm({
    poll_id: props.pollID,
    user_id: user_id,
    vote: null
})

const showModal = ref(props.showPollModal)
const pollTitle = ref('')
const pollDetails = ref('')
const pollType = ref('')

const pollTrueCount = ref(0)
const pollFalseCount = ref(0)
const result = ref('')
const percentage = ref(null)

const getPoll = () => {
    axios.get('/getPoll/'+props.pollID)
    .then(({data}) => {
        pollTitle.value = data.title
        pollDetails.value = data.details
        pollType.value = data.type
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
    if(!initiator){
        pollForm.vote = value
        errors.value = ''
    }
}

const submitPollResponse = () => {
    sbmtBtnTxt.value = 'Loading...'
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

    if(pollTrueCount.value > pollFalseCount.value){
        percentage.value = 1
        result.value = "Approved"
    }else if(pollTrueCount.value < pollFalseCount.value){
        percentage.value = 2
        result.value = "Dissaproved"
    }else if((pollTrueCount.value == pollFalseCount.value) && (pollTrueCount.value != 0 && pollFalseCount.value != 0)){
        percentage.value = 3
        result.value = "Impasse"
    }
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

const getUserPoll = () => {
    axios.get('/getUserPoll/'+props.pollID+'/'+user_id)
    .then(({data}) => {
        pollForm.vote = data.vote
    })
    .catch(e => {
        console.log(e)
    })
}

const endPoll = () => {
    axios.post('/endPoll', {poll_id: props.pollID})
    .then(({data}) => {
        closeModal()
    })
    .catch(e => {
        errors.value = e.response.data.message
        console.log(e)
    })
}

onMounted(() => {
    getPoll()
    getPollCount()
    getUserPoll()
    window.Echo.private('poll-vote').listen('PollVoteSubmittedEvent', (e) => {
        setPollCount(e.vote)
        sbmtBtnTxt.value = 'Submit'
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

            <p class="mt-5 capitalize">
                Type: {{ pollType }}
            </p>

            <div class="mt-3 space-y-3">
                <div class="border p-6 rounded shadow border-l-8 border-green-400 hover:border-2 hover:border-l-8" @click="setValue(true)">
                    <div class="flex">
                        <div class="grow">
                            <input  type="radio" class="" id="yes" v-if="!initiator" v-model="pollForm.vote" :value="true" name="selection"></input>
                            <label for="yes" class="ml-2">YES</label>
                        </div>
                        <div class="text-green-400 text-xl" v-if="percentage == 1 || percentage == 3">
                            {{ result }}
                        </div>
                    </div>
                    <div class="mt-3" v-if="pollTrueCount != 0">
                        <div class="w-full rounded-full">
                            <div class="bg-blue-600 text-xs font-medium h-2.5 text-blue-100 text-center p-0.5 leading-none rounded-full" :style="{width: pollTrueCount+'%'}"></div>
                        </div>
                    </div>
                </div>
                <div class="border p-6 rounded shadow border-l-8 border-red-400 hover:border-2 hover:border-l-8" @click="setValue(false)">
                    <div class="flex">
                        <div class="grow">
                            <input type="radio" class="" id="no" v-if="!initiator" v-model="pollForm.vote" :value="false" name="selection"></input>
                            <label for="no" class="ml-2">NO</label>
                        </div>
                        <div class="text-red-400 text-xl" v-if="percentage == 2 || percentage == 3">
                            {{ result }}
                        </div>
                    </div>
                    <div class="mt-3" v-if="pollFalseCount != 0">
                        <div class="w-full rounded-full">
                            <div class="bg-blue-600 text-xs font-medium h-2.5 text-blue-100 text-center p-0.5 leading-none rounded-full" :style="{width: pollFalseCount+'%'}"></div>
                        </div>
                    </div>
                </div>
                <div class="rounded" v-if="pollForm.vote == false && !initiator">
                    <InputLabel for="no-reason">Explanatory Note</InputLabel>
                    <textarea id="no-reason" name="no-reason" class="h-full w-full rounded border-gray-300"> </textarea>
                </div>
                <InputError :message="errors" class="mt-2" />
            </div>

            <div class="mt-3 flex flex-row space-x-2">
                <SecondaryButton class="w-full justify-center" @click="closeModal" v-if="initiator">Cancel Poll</SecondaryButton>
                <PrimaryButton class="w-full justify-center" @click="endPoll" v-if="initiator">Conclude Poll</PrimaryButton>
            </div>
            <div class="mt-3" v-if="!initiator">
                <PrimaryButton class="w-full justify-center" @click="submitPollResponse">{{sbmtBtnTxt}}</PrimaryButton>
            </div>
        </div>
    </Modal>
</template>
