<script setup>
    import TextInput from '@/Components/TextInput.vue';
    import InputLabel from '@/Components/InputLabel.vue';
    import PrimaryButton from '@/Components/PrimaryButton.vue';
    import InputError from '@/Components/InputError.vue';
    import { QuillEditor } from '@vueup/vue-quill';
    import '@vueup/vue-quill/dist/vue-quill.snow.css';
    import draggable from 'vuedraggable'
    import { useForm } from '@inertiajs/vue3';
    import { XCircleIcon } from '@heroicons/vue/20/solid';

    const form = useForm({
        title: '',
        agenda: '',
        date: '',
        status: '',
        attachments: []
    })

    const emit = defineEmits(['passData'])

    var category = ''

    var categories = []

    const submitData = (e) => {
        emit('passData', form);
        form.reset()
        category = ''
        categories = []
    }

    const getFiles = ($event, index) => {
        let files = $event.target.files

        files.forEach(function(e){
            form.attachments[index][1].push(e)
        })

    }

    const addCategory = (category) => {

        if (category && !categories.includes(category)) {
            form.attachments.push([category, []])
            categories.push(category)
        }else{
            alert('Invalid!!!')
        }

    }

    const removeAttachments = (index) => {

        form.attachments.splice(index, 1)

        categories.splice(index, 1)

    }

    const formatCategory = (text) => {

        return text.toUpperCase()

    }

    const removeFile = (index, number) => {

        form.attachments[index][1].splice(number, 1)

        document.getElementById('fileUpload').value = []

    }
</script>

<template>
<div class="py-5">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="pr-6 pl-6 mt-3">
                <div class="pr-6 pl-6 flex flex-row">

                    <div class="basis-full mr-1">

                        <InputLabel for="title" value="Title"/>

                        <TextInput
                            id="title"
                            type="text"
                            v-model="form.title"
                            class="mt-1 block w-full"
                        >
                        </TextInput>

                        <InputError :message="form.errors.title" class="mt-2" />

                    </div>

                    <div class="flex-initial p-1">

                        <InputLabel for="date" value="Schedule:"/>

                        <input type="date" name="date" v-model="form.date" id="date" class="rounded font-medium text-gray-700 border-gray-300">

                        <InputError :message="form.errors.date" class="mt-2" />

                    </div>

                    <div class="flex-initial p-1">

                        <InputLabel for="date" value="Status:"/>

                        <select name="cars" id="cars" v-model="form.status" class="border rounded text-gray-700 border-gray-300">
                            <option value="pending">Pending</option>
                            <option value="completed">Completed</option>
                        </select>

                        <InputError :message="form.errors.status" class="mt-2" />

                    </div>

                </div>
                <div class="pr-6 pl-6">

                    <InputLabel for="agenda" value="Agenda"/>

                    <InputError :message="form.errors.agenda" class="mt-2" />

                    <QuillEditor theme="snow" v-model:content="form.agenda" contentType="html" style="min-height: 500px;max-height: 500px; overflow-y: auto;"/>

                </div>
                <div class="pr-6 pl-6 flex flex-col">

                    <div class="basis w-full">
                        <InputLabel for="attachments" value="Attachments (ex. Refferals, For Review)"/>
                    </div>

                    <div class="basis basis-full flex flex-row">

                        <div class="basis basis-full">

                            <input
                                type="text"
                                class="rounded font-medium text-gray-700 border-gray-300 mt-2 w-full"
                                placeholder="Category Title"
                                v-model="category"
                            >

                        </div>

                        <div class="basis p-2">
                            <PrimaryButton
                                type="button"
                                class="object-fill h-10"
                                @click="addCategory(category)"
                            >
                                Add
                            </PrimaryButton>
                        </div>

                    </div>

                </div>

                <div class="pr-6 pl-6">
                    <div class="grid grid-cols-3 gap-4">

                        <div class="border" v-for="(attachments, i) in form.attachments">

                            <p class="text-center p-2">{{ (i + 1) + '.' }} {{ formatCategory(attachments[0])}}</p>

                            <hr>

                            <input
                                type="file"
                                class="block p-2 w-full text-sm text-slate-500
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-full file:border-0
                                file:text-sm file:font-semibold
                                file:bg-grey-50 file:text-grey-700
                                hover:file:bg-green-100"
                                id="fileUpload"
                                v-on:change="getFiles($event, i)"
                                multiple
                                accept="application/pdf">

                            <draggable
                                v-model="form.attachments[i][1]"
                                @start="drag=true"
                                @end="drag=false"
                                class="max-h-64 overflow-auto"
                                item-key="id">
                                <template #item="{element, index}">
                                    <div class="flex flex-row border">
                                        <div class="basis w-10 p-2 border-r-2 text-center">
                                            {{ index + 1 }}
                                        </div>

                                        <div class="basis basis-full p-2 text-center whitespace-normal break-all">
                                            {{ element.name }}
                                        </div>

                                        <div class="basis border-l-2 text-center p-2">
                                            <button class="btn" type="button" @click="removeFile(i, index)">
                                                <XCircleIcon class="h-5 w-5 text-center"/>
                                            </button>
                                        </div>
                                    </div>
                                </template>
                            </draggable>


                            <PrimaryButton
                                type="button"
                                class="mt-4 w-full p-2 place-content-center bg-rose-600"

                                @click="removeAttachments(i)"
                            >
                                Remove
                            </PrimaryButton>

                        </div>

                    </div>
                </div>

                <div class="pr-6 pl-6 mt-3 pb-3 border-t-2">
                    <PrimaryButton
                        @click="submitData"
                        class="mt-4 w-full place-content-center"
                    >
                        Submit
                    </PrimaryButton>
                </div>
            </div>
        </div>
    </div>
</div>
</template>
