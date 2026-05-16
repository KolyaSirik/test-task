<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import domainRoutes from '@/routes/domains';
import DomainForm from './Form.vue';

</script>

<template>
    <Head title="Add Domain" />

    <div class="p-6">
        <div class="mb-6">
            <h1 class="text-2xl font-semibold">Add Domain</h1>
            <p class="text-sm text-muted-foreground mt-1">Configure a new domain for monitoring.</p>
        </div>

        <div class="rounded-xl border bg-card p-6 shadow-sm">
            <DomainForm />
        </div>
    </div>
</template>
