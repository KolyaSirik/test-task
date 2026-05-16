<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import {
    ChevronLeft,
    Globe,
    AlertCircle,
    CheckCircle2,
    Clock,
} from 'lucide-vue-next';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import domainRoutes from '@/routes/domains';
import type { Domain, DomainCheck } from '@/types';

defineProps<{
    domain: Domain;
    checks: {
        data: DomainCheck[];
        links: { url: string | null; label: string; active: boolean }[];
    };
}>();
</script>

<template>
    <Head :title="`Logs - ${domain.url}`" />

    <div class="p-6">
        <div class="mb-6 flex items-center gap-4">
            <Button variant="ghost" size="icon" as-child>
                <Link :href="domainRoutes.index()">
                    <ChevronLeft class="h-4 w-4" />
                </Link>
            </Button>
            <div>
                <h1 class="flex items-center gap-2 text-2xl font-semibold">
                    <Globe class="h-6 w-6 text-muted-foreground" />
                    {{ domain.url }}
                </h1>
                <p class="mt-1 text-sm text-muted-foreground">
                    Check history and performance logs.
                </p>
            </div>
        </div>

        <div class="mb-8 grid grid-cols-1 gap-6 md:grid-cols-3">
            <div class="rounded-xl border bg-card p-6 shadow-sm">
                <p class="mb-1 text-sm font-medium text-muted-foreground">
                    Current Status
                </p>
                <div class="flex items-center gap-2">
                    <Badge
                        v-if="domain.is_up === true"
                        variant="default"
                        class="bg-emerald-500 hover:bg-emerald-600"
                        >UP</Badge
                    >
                    <Badge
                        v-else-if="domain.is_up === false"
                        variant="destructive"
                        >DOWN</Badge
                    >
                    <Badge v-else variant="secondary">Pending</Badge>
                </div>
            </div>
            <div class="rounded-xl border bg-card p-6 shadow-sm">
                <p class="mb-1 text-sm font-medium text-muted-foreground">
                    Last Checked
                </p>
                <p class="text-2xl font-semibold">
                    {{
                        domain.last_checked_at
                            ? new Date(domain.last_checked_at).toLocaleString()
                            : 'Never'
                    }}
                </p>
            </div>
            <div class="rounded-xl border bg-card p-6 shadow-sm">
                <p class="mb-1 text-sm font-medium text-muted-foreground">
                    Settings
                </p>
                <p class="text-sm text-muted-foreground">
                    Interval: {{ domain.check_interval }}m | Timeout:
                    {{ domain.request_timeout }}s
                </p>
            </div>
        </div>

        <div class="overflow-hidden rounded-md border bg-card">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b bg-muted/50">
                        <th
                            class="h-12 w-[200px] px-4 text-left align-middle font-medium"
                        >
                            Date
                        </th>
                        <th
                            class="h-12 w-[100px] px-4 text-left align-middle font-medium"
                        >
                            Result
                        </th>
                        <th
                            class="h-12 w-[100px] px-4 text-left align-middle font-medium"
                        >
                            Code
                        </th>
                        <th
                            class="h-12 w-[120px] px-4 text-left align-middle font-medium"
                        >
                            Response Time
                        </th>
                        <th
                            class="h-12 px-4 text-left align-middle font-medium"
                        >
                            Errors / Info
                        </th>
                    </tr>
                </thead>
                <tbody class="[&_tr:last-child]:border-0">
                    <tr
                        v-for="check in checks.data"
                        :key="check.id"
                        class="border-b transition-colors hover:bg-muted/50"
                    >
                        <td class="p-4 align-middle whitespace-nowrap">
                            {{ new Date(check.created_at).toLocaleString() }}
                        </td>
                        <td class="p-4 align-middle">
                            <div class="flex items-center gap-1">
                                <CheckCircle2
                                    v-if="check.is_successful"
                                    class="h-4 w-4 text-emerald-500"
                                />
                                <AlertCircle
                                    v-else
                                    class="h-4 w-4 text-destructive"
                                />
                                <span
                                    :class="
                                        check.is_successful
                                            ? 'text-emerald-500'
                                            : 'font-medium text-destructive'
                                    "
                                >
                                    {{
                                        check.is_successful
                                            ? 'Success'
                                            : 'Failed'
                                    }}
                                </span>
                            </div>
                        </td>
                        <td class="p-4 align-middle font-mono">
                            {{ check.status_code || '-' }}
                        </td>
                        <td class="p-4 align-middle text-muted-foreground">
                            <div class="flex items-center gap-1">
                                <Clock class="h-3 w-3" />
                                {{ check.response_time }}ms
                            </div>
                        </td>
                        <td
                            class="max-w-[300px] truncate p-4 align-middle text-muted-foreground"
                            :title="check.error_message || ''"
                        >
                            {{ check.error_message || '-' }}
                        </td>
                    </tr>
                    <tr v-if="checks.data.length === 0">
                        <td
                            colspan="5"
                            class="p-8 text-center text-muted-foreground"
                        >
                            No check logs available yet.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Simple Pagination -->
        <div
            v-if="checks.links.length > 3"
            class="mt-6 flex items-center justify-center gap-1"
        >
            <template v-for="(link, key) in checks.links" :key="key">
                <div
                    v-if="link.url === null"
                    class="rounded-md border px-3 py-1 text-sm text-muted-foreground"
                >
                    <span v-html="link.label" />
                </div>
                <Link
                    v-else
                    :href="link.url"
                    class="rounded-md border px-3 py-1 text-sm hover:bg-muted"
                    :class="{
                        'bg-primary text-primary-foreground hover:bg-primary/90':
                            link.active,
                    }"
                >
                    <span v-html="link.label" />
                </Link>
            </template>
        </div>
    </div>
</template>
