<script setup>
    import TextInput from '../TextInput.vue';
    import _ from 'lodash';
    import { onMounted, ref, watch } from 'vue'

    const users = ref([])

    const userPage = ref(1)

    const showLoading = ref(false)

    const search = ref("")

    const emit = defineEmits(['navChat'])

    const goToChat = (id, name) =>{
        emit('navChat', {chat: true, user_id: id, user_name: name})
    }

    const searchUsers = (page, search) => {
        axios.get('/usersToChat?page='+page+'&search='+search)
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

    const getUsersChatList = (page) => {
        axios.get('/userChatList?page='+page)
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

    const searchUserFunction = _.debounce(function(){
        users.value = []
        if(search.value){
            searchUsers(1, search.value)
        }else{
            getUsersChatList(1)
        }
    }, 300)

    const onScroll = ({ target: { scrollTop, clientHeight, scrollHeight }}) => {
        if (scrollTop + clientHeight >= scrollHeight) {
            userPage.value += 1
            showLoading.value = true
            searchUsers(userPage.value, search.value)
        }
    }

    const formatText = (text) => {
        if(text.length > 20){
            text = text.slice(0, 20) + " ..."
        }
        return text
    }

    onMounted(() => {
        getUsersChatList(1)
    })
</script>

<style>

</style>

<template>
    <div class="flex flex-col h-96">
        <div class="flex w-full p-2">
            <TextInput class="w-full rounded" placeholder="Search..." @input="searchUserFunction()" v-model="search"></TextInput>
        </div>
        <div id="userChatList" class="flex w-full mt-2 flex-col overflow-auto" ref="userChatList" v-on:scroll="onScroll($event)">
            <button v-for="user in users" type="button" class="w-full hover:bg-indigo-700 hover:text-white rounded p-2" @click="goToChat(user.id, user.name)">
                <div class="flex gap-4">
                    <div class="flex items-center gap-4">
                        <div class="relative inline-flex items-center justify-center w-10 h-10 overflow-hidden bg-gray-100 rounded-full dark:bg-gray-600">
                            <span class="font-medium text-gray-600 dark:text-gray-300">SP</span>
                        </div>
                    </div>
                    <div class="flex flex-col">
                        <div class=""><p class="float-left">{{ user.name }}</p></div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            <p class="float-left">{{formatText(user.message == undefined ? '' : user.message)}}</p>
                        </div>
                    </div>
                </div>
            </button>
        </div>
        <div class="flex justify-center" v-if="showLoading">
            Loading ...
        </div>
    </div>
</template>
