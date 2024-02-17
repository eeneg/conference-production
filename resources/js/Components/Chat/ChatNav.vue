<script setup>
    import TextInput from '../TextInput.vue';
    import { onMounted, ref, watch } from 'vue'

    const users = ref([])

    const userPage = ref(1)

    const showLoading = ref(false)

    const emit = defineEmits(['navChat'])

    const goToChat = (id, name) =>{
        emit('navChat', {chat: true, user_id: id, user_name: name})
    }

    const getUsers = (page) => {
        axios.get('/usersToChat?page='+page)
        .then(({data}) => {
            data.data.forEach(e => {
                users.value.push(e)
            })
            showLoading.value = false
        })
        .catch(e =>{
            console.log(e)
            console.log('Something Went Wrong!')
        })
    }

    const onScroll = ({ target: { scrollTop, clientHeight, scrollHeight }}) => {
      if (scrollTop + clientHeight >= scrollHeight) {
        userPage.value += 1
        showLoading.value = true
        getUsers(userPage.value)
      }
    }

    onMounted(() => {
        getUsers(userPage)
    })
</script>

<style>

</style>

<template>
    <div class="flex flex-col h-96">
        <div class="flex w-full p-2">
            <TextInput class="w-full rounded" placeholder="Search..."></TextInput>
        </div>
        <div id="userChatList" class="flex w-full mt-2 flex-col overflow-auto" ref="userChatList" v-on:scroll="onScroll($event)">
            <button v-for="user in users" type="button" class="w-full hover:bg-indigo-700 hover:text-white rounded p-2" @click="goToChat(user.id, user.name)">
                <div class="flex items-center gap-4">
                    <div class="relative inline-flex items-center justify-center w-10 h-10 overflow-hidden bg-gray-100 rounded-full dark:bg-gray-600">
                        <span class="font-medium text-gray-600 dark:text-gray-300">JL</span>
                    </div>
                    <div class="font-medium">
                        <div>{{ user.name }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 float-left">Hey</div>
                    </div>
                </div>
            </button>
        </div>
        <div class="flex justify-center" v-if="showLoading">
            Loading ...
        </div>
    </div>
</template>
