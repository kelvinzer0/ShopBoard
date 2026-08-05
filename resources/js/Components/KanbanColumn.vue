<script setup>
import { ref } from 'vue';
import ShoppingCard from './ShoppingCard.vue';

const props = defineProps({
    title: String,
    color: String,
    items: Array,
    columnKey: String,
});

const emit = defineEmits(['drag-end', 'edit', 'delete']);
const isDragOver = ref(false);

function onDragOver(e) {
    e.preventDefault();
    isDragOver.value = true;
}

function onDragLeave() {
    isDragOver.value = false;
}

function onDrop(e) {
    isDragOver.value = false;
    const itemId = parseInt(e.dataTransfer.getData('text/plain'));
    const fromColumn = e.dataTransfer.getData('from-column');
    if (fromColumn === props.columnKey) return;
    emit('drag-end', { itemId, fromColumn, toColumn: props.columnKey });
}

const colorMap = {
    indigo:  { border: 'border-indigo-500/30', bg: 'bg-indigo-500/5',  drag: 'ring-indigo-500/50' },
    amber:   { border: 'border-amber-500/30',  bg: 'bg-amber-500/5',   drag: 'ring-amber-500/50' },
    emerald: { border: 'border-emerald-500/30', bg: 'bg-emerald-500/5', drag: 'ring-emerald-500/50' },
};
const colors = colorMap[props.color] || colorMap.indigo;
</script>

<template>
    <div
        class="rounded-xl border bg-gray-900/80 flex flex-col min-h-[300px] transition-all"
        :class="[colors.border, isDragOver ? `ring-2 ${colors.drag} bg-gray-800/80` : '']"
        @dragover="onDragOver"
        @dragleave="onDragLeave"
        @drop="onDrop"
    >
        <div class="px-4 py-3 border-b border-gray-800 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span :class="`w-2.5 h-2.5 rounded-full bg-${color}-500`" />
                <h2 class="font-semibold text-sm text-gray-200">{{ title }}</h2>
            </div>
            <span class="text-xs text-gray-500 bg-gray-800 px-2 py-0.5 rounded-full">{{ items.length }}</span>
        </div>

        <div class="flex-1 p-3 space-y-3 overflow-y-auto">
            <ShoppingCard
                v-for="item in items"
                :key="item.id"
                :item="item"
                :column-key="columnKey"
                @edit="emit('edit', item)"
                @delete="emit('delete', item)"
            />
            <div v-if="!items.length" class="border-2 border-dashed border-gray-800 rounded-lg p-6 text-center">
                <p class="text-gray-600 text-xs">Drop items here</p>
            </div>
        </div>
    </div>
</template>
