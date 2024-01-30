<template>
    <div class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6">
      <div class="flex flex-1 justify-between sm:hidden">
        <a href="#" class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Previous</a>
        <a href="#" class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Next</a>
      </div>
      <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
        <div>
          <p class="text-sm text-gray-700">
            Showing
            {{ ' ' }}
            <span class="font-medium">{{ data.from }}</span>
            {{ ' ' }}
            to
            {{ ' ' }}
            <span class="font-medium">{{ data.to }}</span>
            {{ ' ' }}
            of
            {{ ' ' }}
            <span class="font-medium">{{ data.total }}</span>
            {{ ' ' }}
            results
          </p>
        </div>
        <div>
          <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
            <!-- Current: "z-10 bg-indigo-600 text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600", Default: "text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:outline-offset-0" -->
            <Link
                class="relative hidden items-center px-4 py-2 text-sm font-medium text-gray-500 focus:z-20 md:inline-flex"
                v-for="(link, index, key) in data.links"
                :key="key"
                :href="(link.url ?? '#')"
                :preserve-scroll="true"
                :class="{'z-10 bg-indigo-600 text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600' : link.active == true, 'text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:outline-offset-0' : link.active == false }"
            >
                <ChevronLeftIcon v-if="index == 0" class="h-5 w-5" aria-hidden="true" />
                {{ index != 0 && index != last_index - 1 ? link.label : '' }}
                <ChevronRightIcon v-if="index == last_index - 1" class="h-5 w-5" aria-hidden="true" />
            </Link>
          </nav>
        </div>
      </div>
    </div>
  </template>

<script setup>

    import { ChevronLeftIcon, ChevronRightIcon } from '@heroicons/vue/20/solid'
    import { Link } from '@inertiajs/vue3';

    const items = [
        { id: 1, title: 'Back End Developer', department: 'Engineering', type: 'Full-time', location: 'Remote' },
        { id: 2, title: 'Front End Developer', department: 'Engineering', type: 'Full-time', location: 'Remote' },
        { id: 3, title: 'User Interface Designer', department: 'Design', type: 'Full-time', location: 'Remote' },
    ]

    const props = defineProps({data: Object})

    const data = props.data

    const last_index = data.links.length

    const decodeHTML =function(html) {
        var txt = document.createElement("textarea");
        txt.innerHTML = html;
        return txt.value;
    }
</script>
