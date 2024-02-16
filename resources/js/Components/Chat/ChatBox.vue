<script setup>
    import { ref } from 'vue';
    import ChatBody from './ChatBody.vue';
    import ChatNav from './ChatNav.vue';
    import { ArrowLeftCircleIcon } from '@heroicons/vue/20/solid';

    const chat = ref(false)

    const data = ref({id: null, name: null})

    const emit = defineEmits(['closeChat'])

    const goToChat = (bool) => {
        chat.value = bool.chat
        data.value.name = bool.user_name
        data.value.id = bool.user_id
    }

    const goToChatNav = () => {
        chat.value = false
    }

    const close = () => {
        emit('closeChat', false)
    }

</script>

<style>

</style>

<template>
    <div>
        <div class="mx-auto w-96">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg border">
                <div class="flex flex-row">
                    <div class="pl-3 pr-6 grow border-b-2 flex flex-row">
                        <button v-if="chat == true" type="button" @click="goToChatNav">
                            <ArrowLeftCircleIcon class="h-8 w-8"/>
                        </button>
                        <header v-if="chat == true">
                            <h2 v-if="chat == true" class="text-lg mt-2 pl-1 font-medium text-gray-900 w-56 truncate">{{ data.name }}</h2>
                        </header>
                    </div>

                    <div class="border-b-2 p-3">
                        <a class="float-right hover:bg-indigo-200 rounded" role="button" @click="close(false)">
                            <svg x="0px" y="0px" width="20" height="20" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10" opacity=".35"></circle><path d="M14.812,16.215L7.785,9.188c-0.384-0.384-0.384-1.008,0-1.392l0.011-0.011c0.384-0.384,1.008-0.384,1.392,0l7.027,7.027	c0.384,0.384,0.384,1.008,0,1.392l-0.011,0.011C15.82,16.599,15.196,16.599,14.812,16.215z"></path><path d="M7.785,14.812l7.027-7.027c0.384-0.384,1.008-0.384,1.392,0l0.011,0.011c0.384,0.384,0.384,1.008,0,1.392l-7.027,7.027	c-0.384,0.384-1.008,0.384-1.392,0l-0.011-0.011C7.401,15.82,7.401,15.196,7.785,14.812z"></path>
                            </svg>
                        </a>
                    </div>
                </div>

                <div class="">
                    <ChatNav v-if="chat == false"  @navChat="goToChat($event)"></ChatNav>
                    <ChatBody v-if="chat == true" :props="data.id"></ChatBody>
                </div>

            </div>
        </div>
    </div>
</template>
