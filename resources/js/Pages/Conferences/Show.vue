<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'
import { TabGroup, TabList, Tab, TabPanels, TabPanel } from '@headlessui/vue'
import { Disclosure, DisclosureButton, DisclosurePanel } from '@headlessui/vue'
import { ChevronUpIcon } from '@heroicons/vue/20/solid'
import { QuillEditor } from '@vueup/vue-quill'

import '@vueup/vue-quill/dist/vue-quill.snow.css';
import { ref } from 'vue'
import { computed } from 'vue'

const props = defineProps({
    conf: Object,
    attachments: Object,
})

const tabs = [
    'Agenda',
    'Attachments',
]

const URL = window.URL

const host = location.origin

const embed = ref(null)

const url = computed(() => new URL(embed.value?.path ?? '', host + '/storage/').href)

const preview = (embed) => {
    if (embed.is_pdf) {
        return embed.path
    }
}
</script>

<template>
    <Head :title="`Conferences ${conf.title}`" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">{{ conf.title }}</h2>
        </template>

        <div class="py-5">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="p-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <TabGroup>
                        <TabList class="flex flex-row p-1 space-x-1 bg-gray-200 rounded-xl">
                            <Tab v-for="tab in tabs" v-slot="{ selected }">
                                <button
                                    :class="[
                                    'w-full rounded-lg py-2.5 text-sm font-medium leading-5 px-4',
                                    '',
                                    selected
                                        ? 'bg-white text-indigo-700 shadow'
                                        : 'text-gray-900 hover:bg-white/[0.5] hover:text-gray-500',
                                    ]"
                                >
                                    {{ tab }}
                                </button>
                            </Tab>
                        </TabList>

                        <TabPanels class="mt-2">
                            <TabPanel class="rounded-xl">
                                <QuillEditor theme="snow" v-model:content="conf.agenda" :toolbar="[]" :readOnly="true" contentType="html" style="min-height: 500px; overflow-y: auto;"/>
                            </TabPanel>

                            <TabPanel class="rounded-xl">
                                <div class="flex gap-3">
                                    <div class="w-full max-w-xs bg-white rounded-2xl">
                                        <Disclosure as="div" class="gap-3" v-for="(attachments, category) in props.attachments" v-slot="{ open }">
                                            <DisclosureButton
                                                class="flex justify-between w-full px-4 py-2 text-sm font-medium text-left bg-indigo-100 rounded-lg hover:bg-indigo-200 focus:outline-none focus-visible:ring focus-visible:ring-indigo-500/75"
                                            >
                                                <span> {{ category }} </span>
                                                <ChevronUpIcon
                                                    :class="open ? 'rotate-180 transform' : ''"
                                                    class="w-5 h-5 text-indigo-500"
                                                />
                                            </DisclosureButton>

                                            <DisclosurePanel class="pt-4 pb-2 text-sm text-gray-500">
                                                <div v-for="attachment in attachments">
                                                    <!-- <button @click="embed = attachment" class="">
                                                        {{ attachment.file_name }}
                                                    </button> -->
                                                </div>
                                                <div class="pt-2 pb-3 pl-3 space-y-1">
                                                    <button
                                                        v-for="attachment in attachments"
                                                        @click="embed = attachment"
                                                        aria-current="page"
                                                        :class="['block px-3 py-2 text-base font-medium rounded-md w-full text-left',
                                                            attachment.id === embed?.id ? 'text-white bg-indigo-900' : 'text-indigo-300 rounded-md hover:bg-indigo-700 hover:text-white'
                                                        ]"
                                                    >
                                                        {{ attachment.file_name }}
                                                    </button>
                                                </div>
                                            </DisclosurePanel>
                                        </Disclosure>
                                    </div>
                                    <div class="flex-1 h-screen rounded-lg">
                                        <embed
                                            v-if="embed?.is_previewable"
                                            class="w-auto h-full rounded-lg"
                                            :src="url"
                                        >
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
