<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const showToast = computed(() => !!page.props.flash?.success);
const message = computed(() => page.props.flash?.success || '');
</script>

<template>
    <Transition
        enter-active-class="transition duration-300 ease-out"
        enter-from-class="translate-y-2 opacity-0"
        enter-to-class="translate-y-0 opacity-100"
        leave-active-class="transition duration-200 ease-in"
        leave-from-class="translate-y-0 opacity-100"
        leave-to-class="translate-y-2 opacity-0"
    >
        <div v-if="showToast"
            class="fixed bottom-6 right-6 z-50 bg-emerald-600/90 backdrop-blur-sm text-white px-4 py-2.5 rounded-lg shadow-lg text-sm font-medium">
            {{ message }}
        </div>
    </Transition>
</template>
