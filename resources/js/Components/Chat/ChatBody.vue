<script setup>
    import PrimaryButton from '../PrimaryButton.vue';
    import TextInput from '../TextInput.vue';
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
        <div class="flex flex-col h-96">
            <div class="flex flex-col flex-col-reverse w-full p-3 overflow-auto">
                <div class="mine messages">
                    <div class="message last">
                    Dude
                    </div>
                </div>
                <div class="yours messages">
                    <div class="message">
                    Hey!
                    </div>
                    <div class="message">
                    You there?
                    </div>
                    <div class="message last">
                    Hello, how's it going?
                    </div>
                </div>
                <div class="mine messages">
                    <div class="message">
                    Great thanks!
                    </div>
                    <div class="message last">
                        How about you? How about you? How about you? How about you?
                        How about you? How about you? How about you?How about you?
                    </div>
                </div>
            </div>

            <div class="flex p-2">
                <div class="flex-initial p-1 w-full">
                    <TextInput class="w-full" placeholder="Aa"></TextInput>
                </div>
                <div class="flex-initial p-1">
                    <PrimaryButton class="h-full">send</PrimaryButton>
                </div>
            </div>
        </div>
</template>
