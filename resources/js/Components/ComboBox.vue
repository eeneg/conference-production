<template>
    <div class="w-full">
      <Combobox v-model="selected" by="id" multiple>
        <div class="relative">
          <div
            class="relative w-full cursor-default overflow-hidden rounded-lg bg-white text-left border border-gray-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-white/75 focus-visible:ring-offset-2 focus-visible:ring-offset-indigo-300 sm:text-sm"
          >
            <ComboboxInput
              class="w-full border-none h-10 py-3 pl-3 pr-10 text-sm leading-5 text-gray-900 focus:ring-0"
              :displayValue="(data) => data.map(e => e.title)"
              @change="query = $event.target.value"
            />
            <ComboboxButton
              class="absolute inset-y-0 right-0 flex items-center pr-2"
            >
              <ChevronUpDownIcon
                class="h-5 w-5 text-gray-400"
                aria-hidden="true"
              />
            </ComboboxButton>
          </div>
          <TransitionRoot
            leave="transition ease-in duration-100"
            leaveFrom="opacity-100"
            leaveTo="opacity-0"
            @after-leave="query = ''"
          >
            <ComboboxOptions
              class="absolute z-50 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black/5 focus:outline-none sm:text-sm"
            >
              <div
                v-if="filteredData.length === 0 && query !== ''"
                class="relative cursor-default select-none px-4 py-2 text-gray-700"
              >
                Nothing found.
              </div>

              <ComboboxOption
                v-for="data in filteredData"
                as="template"
                :key="data.id"
                :value="data"
                v-slot="{ selected, active }"
                @click="passData"
              >
                <li
                  class="relative cursor-default select-none py-2 pl-10 pr-4"
                  :class="{
                    'bg-indigo-600 text-white': active,
                    'text-gray-900': !active,
                  }"
                >
                  <span
                    class="block truncate"
                    :class="{ 'font-medium': selected, 'font-normal': !selected }"
                  >
                    {{ data.title }}
                  </span>
                  <span
                    v-if="selected"
                    class="absolute inset-y-0 left-0 flex items-center pl-3"
                    :class="{ 'text-white': active, 'text-indigo-600': !active }"
                  >
                    <CheckIcon class="h-5 w-5" aria-hidden="true" />
                  </span>
                </li>
              </ComboboxOption>
            </ComboboxOptions>
          </TransitionRoot>
        </div>
      </Combobox>
    </div>
  </template>

<script setup>
    import { ref, computed } from 'vue'
    import {
        Combobox,
        ComboboxInput,
        ComboboxButton,
        ComboboxOptions,
        ComboboxOption,
        TransitionRoot,
    } from '@headlessui/vue'
    import { CheckIcon, ChevronUpDownIcon } from '@heroicons/vue/20/solid'

    const props = defineProps({data:Object, selected:Object})

    let selected = ref(props.selected)
    const emit = defineEmits(['passData'])
    let query = ref('')

    const passData = () => {
        console.log(selected.value)
        emit('passData', selected);
    }

    const reset = () => {
        selected.value = []
    }

    defineExpose({
        reset
    })

  let filteredData = computed(() =>
    query.value === ''
        ? props.data
        : props.data.filter((data) =>
            data.title
                .toLowerCase()
                .replace(/\s+/g, '')
                .includes(query.value.toLowerCase().replace(/\s+/g, ''))
            )
    )
</script>
