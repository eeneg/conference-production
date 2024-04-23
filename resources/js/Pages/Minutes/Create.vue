<script setup>
    import { Head, useForm, router } from '@inertiajs/vue3';
    import PrimaryButton from '@/Components/PrimaryButton.vue';
    import InputError from '@/Components/InputError.vue';
    import { QuillEditor } from '@vueup/vue-quill';
    import { ref } from 'vue';
    import SecondaryButton from '@/Components/SecondaryButton.vue';
    import Modal from '@/Components/Modal.vue';
    import '@vueup/vue-quill/dist/vue-quill.snow.css';
    import _ from 'lodash'

    const props = defineProps({id: String, content: {type: String, required: false}, conf_title: String})
    const modalShow = ref(false)

    var header = ""
    var success = true
    var message = ""
    var edited = false

    var toolbarOptions = [
        ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
        ['blockquote', 'code-block', 'image'],

        [{ 'header': 1 }, { 'header': 2 }],               // custom button values
        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
        [{ 'script': 'sub'}, { 'script': 'super' }],      // superscript/subscript
        [{ 'indent': '-1'}, { 'indent': '+1' }],          // outdent/indent
        [{ 'direction': 'rtl' }],                         // text direction

        [{ 'size': ['small', false, 'large', 'huge'] }],  // custom dropdown
        [{ 'header': [1, 2, 3, 4, 5, 6, false] }],

        [{ 'color': [] }, { 'background': [] }],          // dropdown with defaults from theme
        [{ 'font': [] }],
        [{ 'align': [] }],

        ['clean']                                         // remove formatting button
    ];

    const form = useForm({
        id: props.id,
        content: props.content ? props.content : ''
    })

    const minutesEdited = () => {
        edited = true
    }

    const inputSave = _.debounce(() => {
        submit()
    }, 400)

    const submit = () => {
        form.post(route('minutes.store'), {
            onSuccess: () => {
                edited = false
            },
            onError: () => {
                header.value = "Error!"
                success.value = false
                message.value = "Failed to Submit"
                modalShow.value = true
                console.log('asd')
            }
        })
    }

    const printMinutes = () => {
        router.visit(route('minutes.show',form.id), {
            method: 'get',
            onSuccess: e => {
                var f = document.getElementById('i')
                f.contentWindow.document.write(e.props.content)
                setTimeout(function () {
                    f.contentWindow.focus()
                    f.contentWindow.print()

                    f.contentWindow.document.open();
                    f.contentWindow.document.write("");
                    f.contentWindow.document.close();
                }, 500);
            }
        })
    }

    const closeModal = () => {
        modalShow.value = false
    }

</script>

<style>

</style>

<template>
    <Head title="Conference Form" />

    <header class="bg-white shadow">
        <div class="px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Minutes Form <br> {{ props.conf_title }}</h2>
        </div>
    </header>

    <iframe id="i" hidden>

    </iframe>

    <div class="py-5">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="pr-6 pl-6 mt-3">
                    <form @submit.prevent="submit()" class="mt-1">

                        <div class="flex justify-between pr-6 pl-6 items-center">
                            <div>
                                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Minutes</h2>
                            </div>
                            <div class="grow pr-2 items-center pl-2">
                                <Transition enter-from-class="opacity-0" leave-to-class="opacity-0" class="transition float-right ease-in-out">
                                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Saved.</p>
                                </Transition>
                            </div>

                            <PrimaryButton
                                type="button"
                                :class="{'bg-gray-300 hover:bg-gray-400':edited}"
                                @click="printMinutes"
                                :disabled="edited"
                            >
                                Print
                            </PrimaryButton>
                        </div>

                        <div class="pr-6 pl-6 mt-3">

                            <InputError :message="form.errors.content" class="mt-2" />

                            <QuillEditor @input="inputSave" theme="snow" :toolbar="toolbarOptions" @textChange="minutesEdited" v-model:content="form.content" contentType="html" style="min-height: 500px;max-height: 500px; overflow-y: auto;"/>

                        </div>


                        <div class="pr-6 pl-6 mt-3 pb-3 border-t-2">
                            <PrimaryButton
                                type="submit"
                                class="mt-4 w-full place-content-center"
                            >
                                Submit
                            </PrimaryButton>
                        </div>

                    </form>
                </div>
            </div>
        </div>
        <Modal :show="modalShow">
            <div class="p-6">
                <h2 :class="{'text-lg font-medium text-green-500': success == true, 'text-lg font-medium text-red-500': success == false}">
                    {{ header }}
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    {{message}}
                </p>


                <SecondaryButton
                    class="w-full mt-2 place-content-center bg-red-400"
                    @click="closeModal">
                                <p>OK</p>
                </SecondaryButton>
            </div>
        </Modal>
    </div>

</template>
