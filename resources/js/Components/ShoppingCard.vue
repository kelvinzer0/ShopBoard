<script setup>
const props = defineProps({
    item: Object,
    columnKey: String,
});

const emit = defineEmits(['edit', 'delete']);

function formatRupiah(amount) {
    return 'Rp ' + parseFloat(amount).toLocaleString('id-ID');
}

function onDragStart(e) {
    e.dataTransfer.setData('text/plain', props.item.id);
    e.dataTransfer.setData('from-column', props.columnKey);
    e.dataTransfer.effectAllowed = 'move';
}
</script>

<template>
    <div
        draggable="true"
        @dragstart="onDragStart"
        class="group relative bg-gray-800/60 border border-gray-700/50 rounded-lg p-4 hover:border-gray-600 transition-all cursor-grab active:cursor-grabbing"
    >
        <div class="flex items-start justify-between gap-2 mb-2">
            <h3 class="font-medium text-sm text-gray-100 leading-snug">{{ item.title }}</h3>
            <span class="font-mono text-xs font-semibold text-indigo-400 whitespace-nowrap">{{ formatRupiah(item.budget) }}</span>
        </div>

        <div class="mb-1.5">
            <span class="text-[10px] font-semibold uppercase tracking-wider text-amber-500/70">Problem</span>
            <p class="text-xs text-gray-400 mt-0.5 leading-relaxed line-clamp-2">{{ item.problem }}</p>
        </div>

        <div class="mb-3">
            <span class="text-[10px] font-semibold uppercase tracking-wider text-emerald-500/70">Benefit</span>
            <p class="text-xs text-gray-400 mt-0.5 leading-relaxed line-clamp-2">{{ item.benefit }}</p>
        </div>

        <div class="flex items-center justify-between pt-2 border-t border-gray-700/50">
            <a
                v-if="item.purchase_link"
                :href="item.purchase_link"
                target="_blank"
                rel="noopener noreferrer"
                class="inline-flex items-center gap-1 text-[11px] text-indigo-400 hover:text-indigo-300 transition-colors"
                @click.stop
            >
                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                </svg>
                Buy
            </a>
            <span v-else class="text-[11px] text-gray-600">No link</span>

            <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                <button @click.stop="emit('edit', item)" class="p-1 text-gray-500 hover:text-indigo-400 transition-colors cursor-pointer" title="Edit">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </button>
                <button @click.stop="emit('delete', item)" class="p-1 text-gray-500 hover:text-red-400 transition-colors cursor-pointer" title="Delete">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</template>
