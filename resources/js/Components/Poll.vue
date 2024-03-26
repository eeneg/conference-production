<script setup>
import Modal from './Modal.vue'

const pollForm = useForm({
    id: null,
    response: null
})

const votingPollModal = ref(false)

const pollTitle = ref("")
const pollDetails = ref("")

const openVotingPollModal = (title, details) => {
    votingPollModal.value = true
    showPollModal.value = false
    pollTitle.value = title
    pollDetails.value = details
}

const closeVotingPollModal = () => {
    votingPollModal.value = false
}
</script>

<template>
    <Modal :show="votingPollModal">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900">
                {{ pollTitle }}
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                {{ pollDetails }}
            </p>

            <div class="mt-6 space-y-5">
                <div class="border p-6 rounded shadow border-l-8 border-green-400">
                    <input type="radio" class="" id="yes" v-model="pollForm.response" :value="true" name="selection"></input>
                    <label for="yes" class="ml-2">YES</label>
                </div>
                <div class="border p-6 rounded shadow border-l-8 border-red-400">
                    <input type="radio" class="" id="no" v-model="pollForm.response" :value="false" name="selection"></input>
                    <label for="no" class="ml-2">NO</label>
                </div>
            </div>

            <div class="mt-3">
                <PrimaryButton class="w-full justify-center">Submit</PrimaryButton>
            </div>
        </div>
    </Modal>
</template>
