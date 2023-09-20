<script setup>

    const props = defineProps({attachments: Object})

</script>
<template>
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
                    multiple
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
                                <p class="text-blue-500">File name:</p> {{ element.file.name }}

                                <input
                                v-model="form.attachments[i].files[index].file_details"
                                    type="text"
                                    name="details"
                                    class="font-medium text-gray-700 border-gray-300 mt-2 w-full"
                                    placeholder="File Details">

                                <input
                                    v-model="form.attachments[i].files[index].storage_location"
                                    type="text"
                                    name="storage_location"
                                    class="font-medium text-gray-700 border-gray-300 mt-0 w-full"
                                    placeholder="Storage Location">
                            </div>

                            <div class="basis border-l-2 text-center p-2">
                                <button class="btn" type="button" @click="removeFile(i, index)">
                                    <XCircleIcon class="h-5 w-5 text-center text-red-500"/>
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
</div>
</template>
