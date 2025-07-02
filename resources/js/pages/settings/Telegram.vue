<script setup lang="ts">
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import InputError from '@/components/InputError.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Button } from '@/components/ui/button';
import { type BreadcrumbItem } from '@/types';

const page = usePage();

const form = useForm({
    bot_token: (page.props.token ?? '') as string,
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Telegram settings',
        href: '/settings/telegram',
    },
];

const submit = () => {
    form.post(route('settings.telegram.update'), {
        preserveScroll: true,
    });
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Telegram Settings" />

        <SettingsLayout>
            <div class="flex flex-col space-y-6">
                <HeadingSmall
                    title="Telegram Bot"
                    description="Masukkan token API Telegram Bot kamu agar aplikasi dapat mengirim pesan otomatis."
                />

                <form @submit.prevent="submit" class="space-y-6">
                    <div class="grid gap-2">
                        <Label for="bot_token">Bot Token</Label>
                        <Input
                            id="bot_token"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.bot_token"
                            required
                            placeholder="123456:ABCDEF..."
                        />
                        <InputError class="mt-2" :message="form.errors.bot_token" />
                    </div>

                    <div class="flex items-center gap-4">
                        <Button :disabled="form.processing">Simpan</Button>

                        <Transition
                            enter-active-class="transition ease-in-out"
                            enter-from-class="opacity-0"
                            leave-active-class="transition ease-in-out"
                            leave-to-class="opacity-0"
                        >
                            <p v-show="form.recentlySuccessful" class="text-sm text-neutral-600 dark:text-neutral-300">
                                Tersimpan.
                            </p>
                        </Transition>
                    </div>
                </form>
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
