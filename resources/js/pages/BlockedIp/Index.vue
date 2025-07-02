<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { ref } from 'vue';
import { useForm, usePage, router } from '@inertiajs/vue3';
import BaseModal from '@/components/BaseModal.vue';

const page = usePage();
const blockedIps = ref(page.props.blocked_ips.data);

const form = useForm({
    ip_address: '',
    reason: '',
});

const showAddModal = ref(false);

const submit = () => {
    form.post(route('blocked-ips.store'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            showAddModal.value = false;
        },
    });
};

const updateStatus = (ip: any) => {
    router.put(route('blocked-ips.update', ip.id), {
        reason: ip.reason,
        is_active: !ip.is_active,
    });
};

const deleteIp = (id: number) => {
    if (confirm('Hapus IP ini?')) {
        router.delete(route('blocked-ips.destroy', id));
    }
};
</script>

<template>
    <AppLayout>
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h1 class="text-2xl font-semibold">Blocked IP</h1>
                <button class="btn btn-primary" @click="showAddModal = true">Tambah IP</button>
            </div>

            <BaseModal :open="showAddModal" @close="showAddModal = false">
                <template #title>
                    Tambah IP Terblokir
                </template>
                <form @submit.prevent="submit" class="flex flex-col gap-4">
                    <div>
                        <label class="block mb-1 font-medium">IP Address</label>
                        <input v-model="form.ip_address" type="text" class="input w-full bg-sky-50 p-2 rounded text-slate-900" />
                        <div class="text-red-600 text-sm" v-if="form.errors.ip_address">{{ form.errors.ip_address }}</div>
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Reason</label>
                        <input v-model="form.reason" type="text" class="input w-full bg-sky-50 p-2 rounded text-slate-900" />
                    </div>
                    <div class="flex justify-end gap-2 mt-2">
                        <button type="button" class="btn bg-orange-600 px-2 py-1 rounded cursor-pointer" @click="showAddModal = false">Batal</button>
                        <button type="submit" class="btn btn-primary bg-green-700 px-2 py-1 rounded cursor-pointer">Tambah</button>
                    </div>
                </form>
            </BaseModal>

            <div class="overflow-auto rounded-xl border">
                <table class="min-w-full text-sm">
                    <thead class="">
                        <tr>
                            <th class="p-3 text-left font-semibold">IP Address</th>
                            <th class="p-3 text-left font-semibold">Reason</th>
                            <th class="p-3 text-left font-semibold">Status</th>
                            <th class="p-3 text-left font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="ip in blockedIps" :key="ip.id" class="border-t hover:bg-gray-50">
                            <td class="p-3 font-mono">{{ ip.ip_address }}</td>
                            <td class="p-3">{{ ip.reason }}</td>
                            <td class="p-3">
                                <span
                                    class="cursor-pointer px-2 py-1 rounded text-white text-xs transition"
                                    :class="ip.is_active ? 'bg-red-600 hover:bg-red-700' : 'bg-gray-400 hover:bg-gray-500'"
                                    @click="updateStatus(ip)"
                                >
                                    {{ ip.is_active ? 'Blocked' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="p-3">
                                <button @click="deleteIp(ip.id)" class="text-red-600 hover:underline">Hapus</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
