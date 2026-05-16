<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import domainRoutes from '@/routes/domains';
import DomainForm from './Form.vue';
import type { Domain } from '@/types';

const props = defineProps<{
    domain: Domain;
}>();

</script>

<template>
    <Head title="Edit Domain" />

    <div class="p-6">
        <div class="mb-6">
            <h1 class="text-2xl font-semibold">Edit Domain</h1>
            <p class="text-sm text-muted-foreground mt-1">Update monitoring settings for {{ domain.url }}.</p>
        </div>

        <div class="rounded-xl border bg-card p-6 shadow-sm">
            <DomainForm :domain="domain" />
        </div>
    </div>
</template>
