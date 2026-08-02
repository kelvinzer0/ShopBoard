<script setup>
import { ref, watch, computed, onMounted, onUnmounted } from 'vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({ item: Object });
const emit = defineEmits(['close']);

const isEdit = computed(() => !!props.item);

const form = useForm({
    title: '',
    problem: '',
    benefit: '',
    budget: '',
    purchase_link: '',
});

watch(() => props.item, (val) => {
    if (val) {
        form.title = val.title || '';
        form.problem = val.problem || '';
        form.benefit = val.benefit || '';
        form.budget = val.budget || '';
        form.purchase_link = val.purchase_link || '';
    } else {
        form.reset();
    }
}, { immediate: true });

function submit() {
    if (isEdit.value) {
        form.put(route('items.update', props.item.id), {
            preserveScroll: true,
            onSuccess: () => emit('close'),
        });
    } else {
        form.post(route('items.store'), {
            preserveScroll: true,
            onSuccess: () => emit('close'),
        });
    }
}

function onKeydown(e) {
    if (e.key === 'Escape') emit('close');
}

onMounted(() => document.addEventListener('keydown', onKeydown));
onUnmounted(() => document.removeEventListener('keydown', onKeydown));
</script>

<template>
    <Teleport to="body">
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="emit('close')" />
            <div class="relative w-full max-w-lg bg-gray-900 border border-gray-700/50 rounded-2xl shadow-2xl">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-800">
                    <h2 class="text-lg font-semibold text-white">{{ isEdit ? 'Edit Item' : 'Add New Item' }}</h2>
                    <button @click="emit('close')" class="p-1 text-gray-400 hover:text-white transition-colors cursor-pointer">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form @submit.prevent="submit" class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">Title</label>
                        <input v-model="form.title" type="text" placeholder="e.g. Keychron V1"
                            class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-lg text-sm text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all"
                            :class="{ 'border-red-500': form.errors.title }" />
                        <p v-if="form.errors.title" class="mt-1 text-xs text-red-400">{{ form.errors.title }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">Problem <span class="text-amber-500/70">(Justifikasi)</span></label>
                        <textarea v-model="form.problem" rows="2" placeholder="e.g. Keyboard lama ghosting"
                            class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-lg text-sm text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all resize-none"
                            :class="{ 'border-red-500': form.errors.problem }" />
                        <p v-if="form.errors.problem" class="mt-1 text-xs text-red-400">{{ form.errors.problem }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1">Benefit <span class="text-emerald-500/70">(Value)</span></label>
                        <textarea v-model="form.benefit" rows="2" placeholder="e.g. Meningkatkan kecepatan coding"
                            class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-lg text-sm text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all resize-none"
                            :class="{ 'border-red-500': form.errors.benefit }" />
                        <p v-if="form.errors.benefit" class="mt-1 text-xs text-red-400">{{ form.errors.benefit }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Budget (IDR)</label>
                            <input v-model="form.budget" type="number" min="0" step="any" placeholder="1500000"
                                class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-lg text-sm text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all"
                                :class="{ 'border-red-500': form.errors.budget }" />
                            <p v-if="form.errors.budget" class="mt-1 text-xs text-red-400">{{ form.errors.budget }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Purchase Link</label>
                            <input v-model="form.purchase_link" type="url" placeholder="https://..."
                                class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-lg text-sm text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all"
                                :class="{ 'border-red-500': form.errors.purchase_link }" />
                            <p v-if="form.errors.purchase_link" class="mt-1 text-xs text-red-400">{{ form.errors.purchase_link }}</p>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" @click="emit('close')" class="px-4 py-2 text-sm text-gray-400 hover:text-white transition-colors cursor-pointer">Cancel</button>
                        <button type="submit" :disabled="form.processing"
                            class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 disabled:opacity-50 text-white text-sm font-medium rounded-lg transition-colors cursor-pointer">
                            {{ form.processing ? 'Saving...' : (isEdit ? 'Update' : 'Add Item') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </Teleport>
</template>
