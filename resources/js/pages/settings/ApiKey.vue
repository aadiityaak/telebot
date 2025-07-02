<script setup lang="ts">
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { type BreadcrumbItem } from '@/types';

const page = usePage();
const form = useForm({});
const apiKey = ref((page.props.api_key ?? '') as string);

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Pengaturan Aplikasi',
        href: '/settings/api-key',
    },
];

const regenerate = () => {
    if (confirm('Generate ulang API Key? Semua request harus pakai key baru.')) {
        form.post(route('settings.api-key.generate'), {
            preserveScroll: true,
            onSuccess: () => {
                apiKey.value = usePage().props.api_key;
            },
        });
    }
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="API Key Aplikasi" />
        <SettingsLayout>
            <div class="flex flex-col space-y-6">
                <HeadingSmall
                    title="API Key Aplikasi"
                    description="Gunakan API Key ini untuk mengautentikasi request API eksternal ke aplikasi Anda."
                />

                <div class="grid gap-2">
                    <Label for="api_key">API Key Saat Ini</Label>
                    <Input
                        id="api_key"
                        type="text"
                        :value="apiKey"
                        v-model="apiKey"
                        readonly
                        class="font-mono bg-neutral-100 dark:bg-neutral-800 text-slate-900 dark:text-slate-200"
                    />
                </div>

                <div class="flex items-center gap-4">
                    <Button :disabled="form.processing" @click="regenerate">
                        Generate Ulang API Key
                    </Button>

                    <Transition
                        enter-active-class="transition ease-in-out"
                        enter-from-class="opacity-0"
                        leave-active-class="transition ease-in-out"
                        leave-to-class="opacity-0"
                    >
                        <p v-show="form.recentlySuccessful" class="text-sm text-neutral-600 dark:text-neutral-300">
                            API Key berhasil diperbarui.
                        </p>
                    </Transition>
                </div>
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
