<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
//import ChatBox from '@/Components/ChatBox.vue'
import { Head } from '@inertiajs/vue3'
import { TabGroup, TabList, Tab, TabPanels, TabPanel } from '@headlessui/vue'
import { Disclosure, DisclosureButton, DisclosurePanel } from '@headlessui/vue'
import { ChevronUpIcon } from '@heroicons/vue/20/solid'
import { ref } from 'vue'
import { computed } from 'vue'

import '@vueup/vue-quill/dist/vue-quill.snow.css'

const props = defineProps({
    conf: Object,
    attachments: Object,
})

const tabs = [
    'Agenda',
    'Attachments',
    'Notes',
]

const URL = window.URL

const host = location.origin

const embed = ref(null)

const url = computed(() => new URL(embed.value?.path ?? '', host + '/storage/').href)

const action = computed(() => embed.value?.is_video || embed.value?.is_audio ? 'Playing' : 'Previewing')

const title = computed(() => embed.value?.is_previewable ? `${action.value} ${embed.value.file_name}` : `Conferences ${props.conf.title}`)

const reembed = (attachment) => embed.value = embed.value === attachment ? null : attachment
</script>

<template>
    <Head :title="title" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">{{ conf.title }}</h2>
        </template>

        <div class="py-5">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden sm:rounded-lg">
                    <TabGroup>
                        <TabList class="flex flex-row py-3 space-x-3 bg-white rounded-lg p">
                            <Tab v-for="tab in tabs" v-slot="{ selected }">
                                <button
                                    :class="[
                                        'w-full mx-3 py-0.5 text-sm font-medium leading-5',
                                        selected
                                            ? 'border-b-2 border-indigo-400'
                                            : 'hover:text-gray-500 border-b-2 hover:border-indigo-200',
                                    ]"
                                >
                                    {{ tab }}
                                </button>
                            </Tab>
                        </TabList>

                        <TabPanels>
                            <TabPanel class="rounded-xl">
                                <div class="ql-container ql-snow">
                                    <div class="prose max-w-none ql-editor ql-blank" v-html="conf.agenda"></div>
                                </div>
                            </TabPanel>

                            <TabPanel class="rounded-xl">
                                <div class="flex gap-3">
                                    <div class="w-full max-w-xs rounded-2xl">
                                        <Disclosure v-for="(attachments, category) in props.attachments" v-slot="{ open }">
                                            <DisclosureButton
                                                class="flex justify-between w-full px-4 py-2 my-2 text-sm font-medium text-left bg-white rounded-lg hover:bg-indigo-100"
                                            >
                                                <span> {{ category }} </span>
                                                <ChevronUpIcon
                                                    :class="open ? 'rotate-180 transform' : ''"
                                                    class="w-5 h-5 text-indigo-500"
                                                />
                                            </DisclosureButton>

                                            <DisclosurePanel class="pt-1 pb-2 text-sm text-gray-500">
                                                <div class="pl-3 space-y-1">
                                                    <button
                                                        v-for="attachment in attachments"
                                                        @click="reembed(attachment)"
                                                        aria-current="page"
                                                        :class="['block px-3 py-1 text-sm font-medium rounded-md w-full text-left text-gray-700 hover:bg-indigo-100',
                                                            attachment.id === embed?.id ? 'bg-white' : ''
                                                        ]"
                                                    >
                                                        {{ attachment.file_name }}
                                                    </button>
                                                </div>
                                            </DisclosurePanel>
                                        </Disclosure>
                                    </div>

                                    <div class="flex-1 h-screen rounded-lg">

                                        <div class="w-full py-2">
                                            <template v-if="! embed">
                                                <p class="italic">Select an attachment from categories in the right to preview...</p>
                                            </template>

                                            <template v-else-if="! embed.is_previewable">
                                                <p class="">
                                                    File is not previewable in browser. You can download it by clicking the link below.
                                                </p>

                                                <a :href="url" class="italic text-indigo-700 underline">
                                                    {{ embed.file_name }}
                                                </a>
                                            </template>

                                            <template v-else>
                                                <div class="flex flex-row">
                                                    <div class="flex-1">
                                                        <p>
                                                            {{ action }} <span class="font-bold">{{ embed.file_name }}</span> from <strong> category {{ embed.category }} </strong>.
                                                        </p>
                                                        <p>
                                                            Download:
                                                            <a :href="route('attachment.download', embed.id)" class="text-indigo-700 underline">
                                                                <span class="italic">click here</span>
                                                            </a>
                                                        </p>
                                                    </div>

                                                    <div class="text-3xl font-bold text-yellow-500">
                                                        NOTES::TODO ADD HERE <!-- // TODO -->
                                                    </div>
                                                </div>
                                            </template>
                                        </div>

                                        <embed
                                            v-if="embed?.is_previewable && !embed?.is_media"
                                            class="w-full h-full rounded-lg"
                                            :class="[]"
                                            :src="embed?.is_text_previewable ? route('attachment.content', embed.id) : url"
                                        >

                                        <img
                                            v-else-if="embed?.is_image"
                                            class="w-full h-auto rounded-md"
                                            :src="url"
                                        />

                                        <video
                                            v-else-if="embed?.is_video"
                                            class="w-full h-auto rounded-md"
                                            controls
                                            :autoplay="false"
                                        >
                                            <source :src="url" :type="embed.mime">
                                        </video>

                                        <audio
                                            v-else-if="embed?.is_audio"
                                            class="w-full max-w-md mx-auto my-3"
                                            controls
                                            :autoplay="false"
                                        >
                                            <source :src="url" :type="embed.mime">
                                        </audio>
                                    </div>
                                </div>
                            </TabPanel>

                            <TabPanel class="rounded-xl">
                                <div class="flex gap-3 my-2">
                                    <div class="flex flex-col gap-2">
                                        <button
                                            class="w-full max-w-xs px-3 py-2 text-sm italic font-bold text-left text-gray-700 uppercase bg-white rounded-md hover:bg-indigo-100"
                                        >
                                            General Notes
                                        </button>
                                        <template v-for="(attachments) in attachments">
                                            <button
                                                v-for="attachment in attachments"
                                                class="w-full max-w-xs px-3 py-2 text-sm font-medium text-left text-gray-700 bg-white rounded-md hover:bg-indigo-100"
                                            >
                                                {{ attachment.file_name }}
                                            </button>
                                        </template>
                                    </div>

                                    <div class="text-3xl font-bold text-yellow-500">
                                        NOTES::TODO VIEW HERE<!-- // TODO -->
                                    </div>
                                </div>
                            </TabPanel>
                        </TabPanels>
                    </TabGroup>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>

<style scoped>
.ql-container {
    border: none;
}
</style>
