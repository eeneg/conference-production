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
    import Modal from '@/Components/Modal.vue';
    import {nextTick, ref} from 'vue';
    import DangerButton from '@/Components/DangerButton.vue';
    import SecondaryButton from '@/Components/SecondaryButton.vue';

    const props = defineProps({conf: Object, edit: Boolean, storage:Object})

    const modalShow = ref(false)
    const confirmingConferenceDeletion = ref(false);
    const passwordInput = ref(null);

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


    var header = ""
    var message = ""

    var form = useForm({
        title: '',
        agenda: '',
        date: '',
        status: 'pending',
        attachments: []
    })

    const deleteForm = useForm({
        id: null,
        password: null
    })

    if(props.edit){
        Object.assign(form, JSON.parse(JSON.stringify(props.conf)))
    }

    const emit = defineEmits(['passData', 'deleteConf'])

    var category = ''

    var categories = []

    const submitData = (e) => {

        var checkFile = []
        var storage_id = []

        form.attachments.forEach(function(e){
            checkFile.push(e.files.length > 0 ? 1 : 0)
            if(e.files.length > 0){
                e.files.forEach(function(f){
                    storage_id.push(f.storage_id != null ? 1 : 0)
                })
            }
        })

        if(checkFile.includes(0) || storage_id.includes(0)){
            alert("Category Files and Storage Location Cannot be Empty")
        }else{
            emit('passData', form);
        }

        category = ''
        categories = []
    }

    const addCategory = (cat) => {
        if (cat && !categories.includes(cat)) {
            form.attachments.push({category: cat, category_order: form.attachments.length, files:[]})
            categories.push(cat)
            category = ''
        }else{
            header = "Error!"
            message = "Duplicate Category Title"
            modalShow.value = true
        }
    }

    const getFiles = ($event, index) => {
        let files = $event.target.files

        files.forEach(function(e, i){
            let hasSameFileName = false
            form.attachments[index].files.forEach(function(existingFiles){
                if(existingFiles.name == e.name ){
                    hasSameFileName = true
                }
            })
            console.log(hasSameFileName)
            if(hasSameFileName == false){
                form.attachments[index].files.push({file: e, name: e.name, file_details: null, storage_id: null, file_order: form.attachments[index].files.length})
            }else{
                header = "Error!"
                message = "Duplicate Files."
                modalShow.value = true
                document.getElementById('fileUpload').value = []
            }
        })
    }

    const closeModal = () => {
        modalShow.value = false
    }

    const removeAttachments = (index) => {

       if(confirm("Are you sure?\n\nThis will delete all files under this category!!\n\nYou CANNOT revert this!!")){
            form.attachments.splice(index, 1)

            categories.splice(index, 1)
        }else{

        }

    }

    const formatCategory = (text) => {
        if(text instanceof String){
            return text.toUpperCase()
        }else{
            return text
        }
    }

    const removeFile = (index, number) => {

        form.attachments[index].files.splice(number, 1)

        form.attachments[index].files.map(function($e, $i){
            $e.file_order = $i
            return $e;
        })

        document.getElementById('fileUpload').value = []

    }

    const confirmConferenceDeletion = () => {
        confirmingConferenceDeletion.value = true;

        nextTick(() => passwordInput.value.focus());
    };

    const closeDeleteModal = () => {
        confirmingConferenceDeletion.value = false;

        deleteForm.reset()
    };

    const deleteConference = (e) => {
        deleteForm.id = props.conf.id
        emit('deleteConf', deleteForm);
        passwordInput.value.focus()
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

                        <select name="status" id="status" v-model="form.status" class="border rounded text-gray-700 border-gray-300">
                            <option value="pending">Pending</option>
                            <option value="completed">Completed</option>
                        </select>

                        <InputError :message="form.errors.status" class="mt-2" />

                    </div>

                </div>
                <div class="pr-6 pl-6">

                    <InputLabel for="agenda" value="Agenda"/>

                    <InputError :message="form.errors.agenda" class="mt-2" />

                    <QuillEditor theme="snow" v-model:content="form.agenda" :toolbar="toolbarOptions" contentType="html" style="min-height: 500px;max-height: 500px; overflow-y: auto;"/>

                </div>

                <div class="pr-6 pl-6 flex flex-col mt-3">

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
                    <div class="grid grid-cols-2 gap-4">

                        <div class="border" v-for="(attachments, i) in form.attachments">

                            <p class="text-center p-2">{{ (i + 1) + '.' }} {{ formatCategory(attachments.category )}}</p>

                            <hr>

                            <div class="p-2">

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
                                    accept="application/pdf">

                                <draggable
                                    v-model="form.attachments[i].files"
                                    @start="drag=true"
                                    @end="drag=false"
                                    class="max-h-64 overflow-auto"
                                    item-key="id">
                                    <template #item="{element, index}">
                                        <div class="flex flex-row border">
                                            <div class="basis w-10 p-2 border-r-2 text-center">
                                                {{ index + 1}}
                                            </div>

                                            <div class="basis basis-full text-center whitespace-normal break-all cursor-ns-resize p-2">
                                                <p class="text-blue-500">File name:</p> {{ edit == true ? element.name : element.file.name  }}

                                                <input
                                                v-model="form.attachments[i].files[index].file_details"
                                                    type="text"
                                                    name="details"
                                                    class="font-medium text-gray-700 border-gray-300 mt-2 w-full"
                                                    placeholder="File Details">

                                                <select
                                                    v-model="form.attachments[i].files[index].storage_id"
                                                    name="storage_id"
                                                    id="storage_id"
                                                    class="border w-full text-gray-700 border-gray-300 mt-0"
                                                    :class="{'border-red-600':form.attachments[i].files[index].storage_id == null}">
                                                    <option :value="null" selected>Storage Location</option>
                                                    <option :value="storage.id" v-for="storage in props.storage">{{ storage.title.charAt(0).toUpperCase() + storage.title.slice(1) }}</option>
                                                </select>
                                                <InputError :message="'Cannot be Empty'" v-if="form.attachments[i].files[index].storage_id == null"/>
                                            </div>

                                            <div class="basis border-l-2 text-center p-2">
                                                <button class="btn" type="button" @click="removeFile(i, index)">
                                                    <XCircleIcon class="h-5 w-5 text-center text-red-500"/>
                                                </button>
                                            </div>
                                        </div>
                                    </template>
                                </draggable>

                                <div class="text-center mt-3" v-if="attachments.files.length == 0">
                                    <p class="text-red-700">Category Files Cannot be Empty</p>
                                </div>

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

<div class="py-5" v-if="edit">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="pr-6 pl-6 mt-3">
                <div class="pr-6 pl-6 flex flex-col mt-3">
                    <header>
                        <h2 class="text-lg font-medium text-gray-900">Delete Conference</h2>

                        <p class="mt-1 text-sm text-gray-600">
                            Once the Conference is deleted, all of its resources and data will be permanently deleted. Before deleting,
                            please download any data or information that you wish to retain.
                        </p>
                    </header>

                </div>
                <div class="pr-6 pl-6 mt-2">
                    <DangerButton class="mt-2 mb-4 text-center" @click="confirmConferenceDeletion">Delete Conference</DangerButton>
                </div>
            </div>

            <Modal :show="confirmingConferenceDeletion" @close="closeDeleteModal">
                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-900">
                        Are you sure you want to delete this Conference data?
                    </h2>

                    <p class="mt-1 text-sm text-gray-600">
                        Once its deleted, all of its resources and data will be permanently deleted. Please
                        enter your password to confirm you would like to permanently delete the Conference data.
                    </p>

                    <div class="mt-6">
                        <InputLabel for="password" value="Password" class="sr-only" />

                        <TextInput
                            id="password"
                            ref="passwordInput"
                            v-model="deleteForm.password"
                            type="password"
                            class="mt-1 block w-3/4"
                            placeholder="Password"
                            @keyup.enter="deleteConference"
                        />

                        <InputError :message="deleteForm.errors.password" class="mt-2" />
                    </div>

                    <div class="mt-6 flex justify-end">
                        <SecondaryButton @click="closeDeleteModal"> Cancel </SecondaryButton>

                        <DangerButton
                            class="ml-3"
                            :class="{ 'opacity-25': deleteForm.processing }"
                            :disabled="deleteForm.processing"
                            @click="deleteConference"
                        >
                            Delete Conference
                        </DangerButton>
                    </div>
                </div>
            </Modal>
        </div>
    </div>
</div>

<Modal :show="modalShow">
    <div class="p-6">
        <h2 class="text-lg font-medium text-red-500">
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
</template>
