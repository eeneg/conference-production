<script setup>
import { Head } from '@inertiajs/vue3';
import PollBody from '@/Components/Poll/PollBody.vue';


const props = defineProps({conference:Object})

</script>
<template>
    <Head title="Conferences" />

    <header class="bg-white shadow">
        <div class="px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Agenda/Order of Business</h2>
        </div>
    </header>


    <div class="py-5">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="flex flex-row">
                    <div class="pl-5 pr-6 mt-3 grow">
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">Order Of Business</h2>

                            <p class="mt-1 text-sm text-gray-600">
                                Order Of Business Page to view the agenda and create voting polls
                            </p>
                        </header>
                    </div>
                    <div class="p-5 space-x-2">
                        <PollBody :conference="props.conference"></PollBody>
                    </div>
                </div>
                <div class="flex">
                    <div class="pl-5 pr-6 pb-6 mt-3 grow">
                        <div class="max:h-screen" v-html="props.conference.agenda">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>
