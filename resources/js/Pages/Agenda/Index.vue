<script setup>
import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { PlayIcon, PencilIcon, TrashIcon } from '@heroicons/vue/20/solid';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { ref } from  'vue';
import axios from 'axios';
import { onMounted } from 'vue';

const user_id = usePage().props.auth.user.id;

const props = defineProps({conference:Object})

const form = useForm({
    title: null,
    type: null,
    details: null,
    conf_id: props.conference.id
})

const pollID = ref(null)

const deleteForm = useForm({
    password: null,
    id: null
})

const polls = ref([])

const editMode = ref(false)

const createPollModal = ref(false)
const showPollModal = ref(false)
const responeModal = ref(false)
const confirmingPollDeletion = ref(false)

const saveButtonText = ref('Save')

const header = ref('')
const success = ref(false)
const message = ref('')

const loading = ref(false)

const pollModal = () => {
    header.value = 'Add Poll'
    message.value = 'Create Polls Here'
    saveButtonText.value = "Save"
    createPollModal.value = true
    form.reset()
    editMode.value = false
}

const closePollModal = () => {
    createPollModal.value = false
}

const showPollModalList = () => {
    showPollModal.value = true
    getPolls()
}

const closePollModalList = () => {
    showPollModal.value = false
}

const closeResponseModal = () => {
    responeModal.value = false
}

const openEditPollModal = (poll) => {
    header.value = 'Edit Poll'
    message.value = 'Edit Polls Here'
    saveButtonText.value = "Save Edits"
    createPollModal.value = true
    form.title = poll.title
    form.details = poll.details
    form.type = poll.type
    pollID.value = poll.id
    editMode.value = true
    closePollModalList()
}


const closeDeleteModal = () => {
    confirmingPollDeletion.value = false
}

const getPolls = () => {
    loading.value = true
    axios.get('/poll?id='+props.conference.id)
    .then(({data}) => {
        loading.value = false
        polls.value = data
    })
    .catch(e => {
        loading.value = false
        console.log(e)
        console.log('Failed to fetch polls')
    })
}

const submitPoll = () => {
    form.submit('post', route('poll.store'),{
        onSuccess: () => {
            form.reset()
            createPollModal.value = false
            header.value = "Sucess!"
            success.value = true
            message.value = "Poll Submitted"
            responeModal.value = true
        },
        onErrorCaptured: () => {
            header.value = "Error!"
            success.value = false
            message.value = "Failed to Submit"
            responeModal.value = true
        }
    })
}

const updatePoll = () => {
    form.submit('patch', route('poll.update', pollID.value),{
        onSuccess: () => {
            form.reset()
            createPollModal.value = false
            header.value = "Sucess!"
            success.value = true
            message.value = "Poll Updated"
            responeModal.value = true
        },
        onError: () => {
            header.value = "Error!"
            success.value = false
            message.value = "Failed to Update"
            responeModal.value = true
        }
    })
}

const deletePollModal = (id) => {
    confirmingPollDeletion.value = true
    deleteForm.id = id
}

const deletePoll = (id) => {
    deleteForm.delete(route('poll.destroy', {id: id}),{
        onSuccess: () => {
            header.value = "Success!"
            success.value = true
            message.value = "Poll deleted successfully"
            confirmingPollDeletion.value = false
            responeModal.value = true
            deleteForm.reset()
            getPolls()
        },
        onErrorCaptured: () => {
            header.value = "Failed!"
            success.value = false
            message.value = "Something went wrong"
            confirmingPollDeletion.value = false
            responeModal.value = true
            console.log(e)
        }
    })
}

const submit = () => {
    if(editMode.value){
        updatePoll()
    }else{
        submitPoll()
    }
}

const openVotingPollModal = (id, concluded) => {
    if(concluded && !confirm('Poll already concluded. \n\nProceed anyway?')){

    }else{
        axios.post('/setPollActive', {id: id, initiatorID: user_id, value:true})
        .then(({data}) => {

        })
        .catch(e => {
            console.log(e)
        })
    }
}

onMounted(() => {
    window.Echo.private('poll').listen('PollSetActiveEvent', (e) => {
        getPolls()
    });
})
</script>
<template>
    <Head title="Conferences" />

    <header class="bg-white shadow">
        <div class="px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Agenda/Order of Business</h2>
        </div>
    </header>


    <div class="py-5">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="flex flex-row">
                    <div class="pl-5 pr-6 mt-3 grow">
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">Order Of Business</h2>

                            <p class="mt-1 text-sm text-gray-600">
                                Order Of Business Page to view the agenda and create voting polls
                            </p>
                        </header>
                    </div>
                    <div class="p-5 space-x-2">
                        <SecondaryButton @click="showPollModalList">View Polls</SecondaryButton>
                        <PrimaryButton @click="pollModal">Add Poll</PrimaryButton>
                    </div>
                </div>
                <div class="flex">
                    <div class="pl-5 pr-6 mt-3 grow">
                        {{ props.conference.agenda }}
                    </div>
                </div>
            </div>
        </div>

        <Modal :show="createPollModal" :maxWidth="'2xl'">
            <div class="p-6">

                <div class="flex flex-row">
                    <div class="basis-1/2">
                        <h2 class="text-lg font-medium text-black-500">
                            {{ header }}
                        </h2>

                        <p class="mt-1 text-sm text-gray-600">
                            {{message}}
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


        <Modal :show="showPollModal" :maxWidth="'4xl'">
            <div class="p-6">

                <div class="flex flex-row">
                    <div class="basis-1/2">
                        <h2 class="text-lg font-medium text-black-500">
                            Poll
                        </h2>

                        <p class="mt-1 text-sm text-gray-600">
                            View Poll List Here
                        </p>
                    </div>
                    <div class="basis-1/2">
                        <a class="float-right" role="button" @click="closePollModalList">
                            <svg x="0px" y="0px" width="20" height="20" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10" opacity=".35"></circle><path d="M14.812,16.215L7.785,9.188c-0.384-0.384-0.384-1.008,0-1.392l0.011-0.011c0.384-0.384,1.008-0.384,1.392,0l7.027,7.027	c0.384,0.384,0.384,1.008,0,1.392l-0.011,0.011C15.82,16.599,15.196,16.599,14.812,16.215z"></path><path d="M7.785,14.812l7.027-7.027c0.384-0.384,1.008-0.384,1.392,0l0.011,0.011c0.384,0.384,0.384,1.008,0,1.392l-7.027,7.027	c-0.384,0.384-1.008,0.384-1.392,0l-0.011-0.011C7.401,15.82,7.401,15.196,7.785,14.812z"></path>
                            </svg>
                        </a>
                    </div>
                </div>

                <div class="mt-3 mb-3 mr-3 pr-6 pl-5">
                    <div class="flex items-center justify-center grow" role="status"  v-if="loading">
                        <svg aria-hidden="true" class="w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-indigo-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/><path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/></svg>
                        <span class="sr-only">Loading...</span>
                    </div>
                    <div class="border rounded flex mb-2" v-for="poll in polls">
                        <div class="flex items-center justify-center w-64 border-r p-2">
                            {{ poll.title }}
                        </div>
                        <div class="flex items-center justify-center w-64 border-r p-2 capitalize">
                            {{ poll.type }}
                        </div>
                        <div class="flex items-center justify-center w-64 p-2 border-r">
                            {{ (poll.result == "") || (poll.result == null) ? "No Result" : poll.result }}
                        </div>
                        <div class="flex items-center justify-center w-64 p-2 border-r" :class="{'text-green-900':poll.concluded, 'text-red-900':poll.concluded == false}">
                            {{ poll.concluded == false ? 'Unconcluded' : 'Concluded' }}
                        </div>
                        <div class="flex items-center justify-center float-right p-2">
                            <button @click="openVotingPollModal(poll.id, poll.concluded)">
                                <div class="flex h-5 w-5 items-center justify-center rounded-full bg-green-400 hover:bg-green-300 text-red-900 mr-1">
                                    <PlayIcon class="w-3 h-3 fill-black " aria-hidden="true" />
                                </div>
                            </button>
                            <button @click="openEditPollModal(poll)">
                                <div class="flex h-5 w-5 items-center justify-center rounded-full bg-blue-400 hover:bg-blue-300 text-red-900 mr-1">
                                    <PencilIcon class="w-3 h-3 fill-black " aria-hidden="true" />
                                </div>
                            </button>
                            <button @click="deletePollModal(poll.id)">
                                <div class="flex h-5 w-5 items-center justify-center rounded-full bg-red-500 hover:bg-red-300 text-red-900">
                                    <TrashIcon class="w-3 h-3 stroke-gray-900 fill-none aria-hidden" aria-hidden="true" />
                                </div>
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </Modal>

        <Modal :show="responeModal">
            <div class="p-6">
                <h2 :class="{'text-lg font-medium text-green-500': success == true, 'text-lg font-medium text-red-500': success == false}">
                    {{ header }}
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    {{message}}
                </p>


                <SecondaryButton
                    class="w-full mt-2 place-content-center bg-red-400"
                    @click="closeResponseModal">
                        <p>OK</p>
                </SecondaryButton>
            </div>
        </Modal>

        <Modal :show="confirmingPollDeletion" @close="closeDeleteModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Are you sure you want to delete this Poll?
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    Once its deleted, all of its resources and data will be permanently deleted. Please
                    enter your password to confirm you would like to permanently delete the Poll.
                </p>

                <div class="mt-6">
                    <InputLabel for="password" value="Password" class="sr-only" />

                    <TextInput
                        id="password"
                        ref="passwordInput"
                        v-model="deleteForm.password"
                        type="password"
                        class="mt-1 block w-3/4"
                        placeholder="Password"
                        @keyup.enter="deletePoll"
                    />

                    <InputError :message="deleteForm.errors.password" class="mt-2" />
                </div>

                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeDeleteModal"> Cancel </SecondaryButton>

                    <DangerButton
                        class="ml-3"
                        :class="{ 'opacity-25': deleteForm.processing }"
                        :disabled="deleteForm.processing"
                        @click="deletePoll"
                    >
                        Delete Poll
                    </DangerButton>
                </div>
            </div>
        </Modal>

    </div>

</template>
