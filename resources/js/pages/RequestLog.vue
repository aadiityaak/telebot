<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, usePage, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import BaseModal from '@/components/BaseModal.vue';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Request Log', href: '/log-request' },
];

interface LogItem {
    id: number;
    ip_address: string;
    method: string;
    endpoint: string;
    status_code: number;
    duration_ms: number;
    created_at: string;
    payload: any;
    response_body: any;
}

const pageProps = usePage().props;

const showDialog = ref(false);
const selectedLog = ref<LogItem | null>(null);

const logs = computed(() => pageProps.logs.data ?? []);
const sort = ref(pageProps.sort ?? 'created_at');
const order = ref(pageProps.order ?? 'desc');

const perPage = computed(() => pageProps.logs.per_page ?? 10);
const currentPage = computed(() => pageProps.logs.current_page ?? 1);
const totalPages = computed(() => pageProps.logs.last_page ?? 1);

const handleSort = (field: string) => {
    let newOrder = 'asc';
    if (sort.value === field && order.value === 'asc') {
        newOrder = 'desc';
    }
    router.get(
        route('logs'),
        { page: currentPage.value, sort: field, order: newOrder },
        { preserveState: true, preserveScroll: true }
    );
};

const goToPage = (page: number) => {
    router.get(
        route('logs'),
        { page, sort: sort.value, order: order.value },
        { preserveState: true, preserveScroll: true }
    );
};

const openDetailDialog = (log: LogItem) => {
    selectedLog.value = log;
    showDialog.value = true;
};

const closeDialog = () => {
    showDialog.value = false;
    selectedLog.value = null;
};

const formatJson = (data: any) => {
    try {
        return JSON.stringify(data, null, 2);
    } catch {
        return data;
    }
};

const statusClass = (code: number) => {
    if (code >= 200 && code < 300) return 'text-green-600 dark:text-green-400';
    if (code >= 400 && code < 500) return 'text-yellow-600 dark:text-yellow-400';
    return 'text-red-600 dark:text-red-400';
};
</script>

<template>
    <Head title="Request Log" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-1 flex-col gap-4 rounded-xl p-4">
            <h1 class="text-2xl font-semibold">Request Logs</h1>

            <div class="overflow-auto rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-100 dark:bg-sidebar">
                        <tr class="text-left">
                            <th class="p-3 font-semibold">#</th>
                            <th class="p-3 font-semibold cursor-pointer select-none" @click="handleSort('ip_address')">
                                IP
                                <span v-if="sort === 'ip_address'">
                                    <span v-if="order === 'asc'">&#9650;</span>
                                    <span v-else>&#9660;</span>
                                </span>
                            </th>
                            <th class="p-3 font-semibold cursor-pointer select-none" @click="handleSort('method')">
                                Method
                                <span v-if="sort === 'method'">
                                    <span v-if="order === 'asc'">&#9650;</span>
                                    <span v-else>&#9660;</span>
                                </span>
                            </th>
                            <th class="p-3 font-semibold cursor-pointer select-none" @click="handleSort('endpoint')">
                                Endpoint
                                <span v-if="sort === 'endpoint'">
                                    <span v-if="order === 'asc'">&#9650;</span>
                                    <span v-else>&#9660;</span>
                                </span>
                            </th>
                            <th class="p-3 font-semibold cursor-pointer select-none" @click="handleSort('status_code')">
                                Status
                                <span v-if="sort === 'status_code'">
                                    <span v-if="order === 'asc'">&#9650;</span>
                                    <span v-else>&#9660;</span>
                                </span>
                            </th>
                            <th class="p-3 font-semibold cursor-pointer select-none" @click="handleSort('duration_ms')">
                                Durasi
                                <span v-if="sort === 'duration_ms'">
                                    <span v-if="order === 'asc'">&#9650;</span>
                                    <span v-else>&#9660;</span>
                                </span>
                            </th>
                            <th class="p-3 font-semibold cursor-pointer select-none" @click="handleSort('created_at')">
                                Waktu
                                <span v-if="sort === 'created_at'">
                                    <span v-if="order === 'asc'">&#9650;</span>
                                    <span v-else>&#9660;</span>
                                </span>
                            </th>
                            <th class="p-3 font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-for="(log, index) in logs" :key="log.id">
                            <tr class="border-t dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-sidebar/70">
                                <td class="p-3">{{ (currentPage - 1) * perPage + index + 1 }}</td>
                                <td class="p-3">{{ log.ip_address }}</td>
                                <td class="p-3 font-mono">{{ log.method }}</td>
                                <td class="p-3 font-mono text-blue-700 dark:text-blue-400">{{ log.endpoint }}</td>
                                <td class="p-3">
                                    <span :class="['font-semibold', statusClass(log.status_code)]">
                                        {{ log.status_code }}
                                    </span>
                                </td>
                                <td class="p-3">{{ log.duration_ms }} ms</td>
                                <td class="p-3">{{ new Date(log.created_at).toLocaleString() }}</td>
                                <td class="p-3">
                                    <button @click="openDetailDialog(log)" class="text-sm text-blue-600 hover:underline">
                                        Detail
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
            <!-- Pagination Controls -->
            <div class="flex justify-between items-center mt-4" v-if="totalPages > 1">
                <button
                    class="px-3 py-1 rounded border text-sm"
                    :disabled="currentPage === 1"
                    @click="goToPage(currentPage - 1)"
                >
                    Prev
                </button>
                <div>
                    Page {{ currentPage }} of {{ totalPages }}
                </div>
                <button
                    class="px-3 py-1 rounded border text-sm"
                    :disabled="currentPage === totalPages"
                    @click="goToPage(currentPage + 1)"
                >
                    Next
                </button>
            </div>
        </div>

        <BaseModal :open="showDialog" @close="closeDialog">
            <template #title>
                Detail Log
            </template>
            <div v-if="selectedLog" class="space-y-4 mt-2">
                <div>
                    <strong>IP:</strong> {{ selectedLog.ip_address }}<br>
                    <strong>Method:</strong> <span class="font-mono">{{ selectedLog.method }}</span><br>
                    <strong>Endpoint:</strong> <span class="font-mono text-blue-700 dark:text-blue-400">{{ selectedLog.endpoint }}</span><br>
                    <strong>Status:</strong>
                    <span :class="['font-semibold', statusClass(selectedLog.status_code)]">
                        {{ selectedLog.status_code }}
                    </span><br>
                    <strong>Durasi:</strong> {{ selectedLog.duration_ms }} ms<br>
                    <strong>Waktu:</strong> {{ new Date(selectedLog.created_at).toLocaleString() }}
                </div>
                <div>
                    <strong>Payload:</strong>
                    <pre class="bg-white dark:bg-sidebar p-2 mt-1 rounded text-[11px] overflow-auto max-h-64">
{{ formatJson(selectedLog.payload) }}</pre>
                </div>
            </div>
        </BaseModal>
    </AppLayout>
</template>
