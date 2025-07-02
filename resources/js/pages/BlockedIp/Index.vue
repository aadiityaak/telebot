<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { ref } from 'vue';
import { useForm, usePage, router } from '@inertiajs/vue3';

const page = usePage();
const blockedIps = ref(page.props.blocked_ips.data);

const form = useForm({
    ip_address: '',
    reason: '',
});

const submit = () => {
    form.post(route('blocked-ips.store'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
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
            <h1 class="text-2xl font-semibold mb-4">Blocked IP</h1>

            <form @submit.prevent="submit" class="mb-6 flex gap-4 items-end">
                <div>
                    <label>IP Address</label>
                    <input v-model="form.ip_address" type="text" class="input" />
                    <div class="text-red-600 text-sm" v-if="form.errors.ip_address">{{ form.errors.ip_address }}</div>
                </div>
                <div>
                    <label>Reason</label>
                    <input v-model="form.reason" type="text" class="input" />
                </div>
                <button type="submit" class="btn btn-primary">Tambah</button>
            </form>

            <table class="min-w-full text-sm border">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-2">IP Address</th>
                        <th class="p-2">Reason</th>
                        <th class="p-2">Status</th>
                        <th class="p-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="ip in blockedIps" :key="ip.id" class="border-t">
                        <td class="p-2 font-mono">{{ ip.ip_address }}</td>
                        <td class="p-2">{{ ip.reason }}</td>
                        <td class="p-2">
                            <span
                                class="cursor-pointer px-2 py-1 rounded text-white text-xs"
                                :class="ip.is_active ? 'bg-red-600' : 'bg-gray-400'"
                                @click="updateStatus(ip)"
                            >
                                {{ ip.is_active ? 'Blocked' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="p-2">
                            <button @click="deleteIp(ip.id)" class="text-red-600 hover:underline">Hapus</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AppLayout>
</template>

<style scoped>
.input {
    @apply border rounded px-3 py-2 w-full;
}
.btn-primary {
    @apply bg-blue-600 text-white px-4 py-2 rounded;
}
</style>
