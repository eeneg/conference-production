<script setup>
import PrimaryButton from '../PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import CreateUpdatePoll from './CreateUpdatePoll.vue';
import ListPoll from './ListPoll.vue';
import { ref } from 'vue';

const props = defineProps({conference:Object})

const editMode = ref(false)

const createPollModal = ref(false)
const showPollListModal = ref(false)

const createPoll = () => {
    createPollModal.value = true
    editMode.value = false
}

const closePollModal = (e) => {
    createPollModal.value = e
}

const openPollListModal = () => {
    showPollListModal.value = true
}

const closePollListModal = (e) => {
    showPollListModal.value = e
}

const poll = ref(null)

const openEditPollModal = (e) =>{
    poll.value = e
    createPollModal.value = true
    editMode.value = true
}

const listPoll = ref(null)

const pollUpdated = () => {
    listPoll.value.getPollExpose()
}

</script>

<template>
    <div class="flex space-x-2">
        <SecondaryButton @click="openPollListModal">View Poll</SecondaryButton>
        <PrimaryButton @click="createPoll">Add Poll</PrimaryButton>
    </div>
    <div>
        <CreateUpdatePoll
            v-if="createPollModal"
            @closeCreateUpdateModal="closePollModal($event)"
            @pollUpdated="pollUpdated()"
            :poll="poll"
            :conference_id="props.conference.id"
            :editMode="editMode"
            :createPollModal="createPollModal"
        />
        <ListPoll
            v-if="showPollListModal"
            @closePollListModal="closePollListModal($event)"
            @openEditPollModalEmit="openEditPollModal($event)"
            :conference_id="props.conference.id"
            :showPollListModal="showPollListModal"
            ref="listPoll"
        />
    </div>

</template>
