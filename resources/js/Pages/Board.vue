<script setup>
import { ref, computed } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import KanbanColumn from '@/Components/KanbanColumn.vue';
import ItemModal from '@/Components/ItemModal.vue';
import Toast from '@/Components/Toast.vue';

const props = defineProps({
    board: Object,
});

const showModal = ref(false);
const editingItem = ref(null);

const columns = [
    { key: 'wishlist',  label: 'Wishlist',       color: 'indigo' },
    { key: 'ready',     label: 'Ready to Buy',    color: 'amber'  },
    { key: 'completed', label: 'Completed',       color: 'emerald'},
];

function subtotal(key) {
    return (props.board[key] || []).reduce((sum, item) => sum + parseFloat(item.budget), 0);
}

function formatRupiah(amount) {
    return 'Rp ' + amount.toLocaleString('id-ID');
}

function openCreateModal() {
    editingItem.value = null;
    showModal.value = true;
}

function openEditModal(item) {
    editingItem.value = item;
    showModal.value = true;
}

function closeModal() {
    showModal.value = false;
    editingItem.value = null;
}

function onDragEnd(event) {
    const { itemId, fromColumn, toColumn } = event;
    if (fromColumn === toColumn) return;
    router.patch(route('items.update-status', itemId), {
        status: toColumn,
    }, {
        preserveScroll: true,
    });
}

function deleteItem(item) {
    if (confirm(`Delete "${item.title}"?`)) {
        router.delete(route('items.destroy', item), {
            preserveScroll: true,
        });
    }
}
</script>

<template>
    <div class="min-h-screen bg-gray-950">
        <!-- Header -->
        <header class="border-b border-gray-800 bg-gray-950/80 backdrop-blur-sm sticky top-0 z-30">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <h1 class="text-xl font-bold tracking-tight text-white">ShopBoard</h1>
                </div>
                <button
                    @click="openCreateModal"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium rounded-lg transition-colors cursor-pointer"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Item
                </button>
            </div>
        </header>

        <!-- Budget Summary Bar -->
        <div class="border-b border-gray-800 bg-gray-900/50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 py-3 flex items-center gap-6 text-sm overflow-x-auto">
                <template v-for="col in columns" :key="col.key">
                    <div class="flex items-center gap-2 whitespace-nowrap">
                        <span :class="`w-2 h-2 rounded-full bg-${col.color}-500`" />
                        <span class="text-gray-400">{{ col.label }}:</span>
                        <span class="font-mono font-semibold text-white">{{ formatRupiah(subtotal(col.key)) }}</span>
                    </div>
                </template>
                <div class="flex items-center gap-2 whitespace-nowrap ml-auto">
                    <span class="text-gray-500">Total:</span>
                    <span class="font-mono font-bold text-indigo-400">{{ formatRupiah(subtotal('wishlist') + subtotal('ready') + subtotal('completed')) }}</span>
                </div>
            </div>
        </div>

        <!-- Kanban Board -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <KanbanColumn
                    v-for="col in columns"
                    :key="col.key"
                    :title="col.label"
                    :color="col.color"
                    :items="board[col.key] || []"
                    :column-key="col.key"
                    @drag-end="onDragEnd"
                    @edit="openEditModal"
                    @delete="deleteItem"
                />
            </div>

            <!-- Empty State -->
            <div
                v-if="!board.wishlist?.length && !board.ready?.length && !board.completed?.length"
                class="text-center py-20"
            >
                <div class="text-gray-600 text-6xl mb-4">
                </div>
                <p class="text-gray-400 text-lg">No items yet.</p>
                <p class="text-gray-500 text-sm mt-1">Click "Add Item" to get started.</p>
            </div>
        </div>

        <!-- Modal -->
        <ItemModal
            v-if="showModal"
            :item="editingItem"
            @close="closeModal"
        />

        <!-- Toast Notifications -->
        <Toast />
    </div>
</template>
