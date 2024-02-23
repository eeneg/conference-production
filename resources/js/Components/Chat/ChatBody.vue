<script setup>
    import { ref, onMounted } from 'vue';
    import PrimaryButton from '../PrimaryButton.vue';
    import TextInput from '../TextInput.vue';
    import axios from 'axios';

    const props = defineProps({user_id:String})

    const messages = ref([])

    const message = ref('')

    const getMessages = () => {
      axios.get(route('messages.show', {id: props.user_id}))
      .then(({data}) => {
          if(data.data.length > 0){
            transformData(data.data)
          }
      })
      .catch(e => {
          console.log(e)
      })
    }

    const sendMessage = () => {
        axios.post(route('messages.store'), {message: message.value, receiver_id: props.user_id})
        .then(e => {
            messages.value = []
            getMessages()
        })
        .catch(e => {

        })
    }

    const transformData = (data) => {
      let ar = []
      data.forEach((e, i) => {
        let len = ar.length
        if(i == 0){
          ar.push(e)
        }else{
          if(ar[len-1].user_id == e.user_id){
            ar.push(e)
          }else{
            messages.value.push(ar)
            ar = []
            ar.push(e)
          }
        }
      })
      messages.value.push(ar)
    }

    onMounted(() => {
        getMessages()
    })
</script>

<style>
.chat {
  width: 300px;
  border: solid 1px #EEE;
  display: flex;
  flex-direction: column;
  padding: 10px;
}

.messages {
  margin-top: 30px;
  display: flex;
  flex-direction: column;
}

.message {
  border-radius: 20px;
  padding: 8px 15px;
  margin-top: 5px;
  margin-bottom: 5px;
  display: inline-block;
}

.yours {
  align-items: flex-start;
}

.yours .message {
  margin-right: 25%;
  background-color: #eee;
  position: relative;
}

.yours .message.last:before {
  content: "";
  position: absolute;
  z-index: 0;
  bottom: 0;
  left: -7px;
  height: 20px;
  width: 20px;
  background: #eee;
  border-bottom-right-radius: 15px;
}
.yours .message.last:after {
  content: "";
  position: absolute;
  z-index: 1;
  bottom: 0;
  left: -10px;
  width: 10px;
  height: 20px;
  background: white;
  border-bottom-right-radius: 10px;
}

.mine {
  align-items: flex-end;
}

.mine .message {
  color: white;
  margin-left: 25%;
  background: linear-gradient(to bottom, #00D0EA 0%, #0085D1 100%);
  background-attachment: fixed;
  position: relative;
}

.mine .message.last:before {
  content: "";
  position: absolute;
  z-index: 0;
  bottom: 0;
  right: -8px;
  height: 20px;
  width: 20px;
  background: linear-gradient(to bottom, #00D0EA 0%, #0085D1 100%);
  background-attachment: fixed;
  border-bottom-left-radius: 15px;
}

.mine .message.last:after {
  content: "";
  position: absolute;
  z-index: 1;
  bottom: 0;
  right: -10px;
  width: 10px;
  height: 20px;
  background: white;
  border-bottom-left-radius: 10px;
}
</style>

<template>
        <div class="flex flex-col">
            <div class="flex flex-col flex-col-reverse w-full p-3 overflow-auto h-96">
                <div v-for="message in messages" :class="{'mine':message[0].user_id != props.user_id,'yours':message[0].user_id == props.user_id, }" class="messages">
                    <div v-for="(msg, index) in message.slice().reverse()" :class="{'last': (index+1) == message.length}" class="message">
                      {{ msg.message }}
                    </div>
                </div>
            </div>

            <div class="flex p-2">
                <div class="flex-initial p-1 w-full">
                    <TextInput class="w-full" placeholder="Aa" v-model="message"></TextInput>
                </div>
                <div class="flex-initial p-1">
                    <PrimaryButton class="h-full" @click="sendMessage">send</PrimaryButton>
                </div>
            </div>
        </div>
</template>
