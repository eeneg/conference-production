<script setup>
    import PrimaryButton from './PrimaryButton.vue';
    import TextInput from './TextInput.vue';

    const a = 2
</script>

<style>
body {
  font-family: helvetica;
  display: flex ;
  flex-direction: column;
  align-items: center;
}

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
    <div>
        <div class="mx-auto w-96">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="flex flex-row">
                    <div class="pl-6 pr-6 mt-3 grow border-b-2">
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">Sample Name</h2>
                        </header>
                    </div>
                    <div class="border-b-2 p-3">
                        <a class="float-right hover:bg-indigo-200 rounded" role="button" >
                            <svg x="0px" y="0px" width="20" height="20" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10" opacity=".35"></circle><path d="M14.812,16.215L7.785,9.188c-0.384-0.384-0.384-1.008,0-1.392l0.011-0.011c0.384-0.384,1.008-0.384,1.392,0l7.027,7.027	c0.384,0.384,0.384,1.008,0,1.392l-0.011,0.011C15.82,16.599,15.196,16.599,14.812,16.215z"></path><path d="M7.785,14.812l7.027-7.027c0.384-0.384,1.008-0.384,1.392,0l0.011,0.011c0.384,0.384,0.384,1.008,0,1.392l-7.027,7.027	c-0.384,0.384-1.008,0.384-1.392,0l-0.011-0.011C7.401,15.82,7.401,15.196,7.785,14.812z"></path>
                            </svg>
                        </a>
                    </div>
                </div>

                <div class="pl-6 pr-6 mt-3 h-80 overflow-auto">
                    <div class="w-full">
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
        </div>
    </div>
</template>
