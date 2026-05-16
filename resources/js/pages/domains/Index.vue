<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Globe, MoreHorizontal, History, Edit, Trash2, Plus } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import domainRoutes from '@/routes/domains';
import type { Domain } from '@/types';

defineProps<{
    domains: Domain[];
}>();

const deleteForm = useForm({});

const deleteDomain = (id: number) => {
    if (confirm('Are you sure you want to delete this domain?')) {
        deleteForm.delete(domainRoutes.destroy(id).url);
    }
};
</script>

<template>
    <Head title="Domains" />

    <div class="p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold">Domains</h1>
                <p class="text-sm text-muted-foreground mt-1">Manage your domains and monitoring settings.</p>
            </div>
            <Button as-child>
                <Link :href="domainRoutes.create()">
                    <Plus class="mr-2 h-4 w-4" />
                    Add Domain
                </Link>
            </Button>
        </div>

        <div class="rounded-md border bg-card">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b bg-muted/50 transition-colors">
                        <th class="h-12 px-4 text-left align-middle font-medium">Domain URL</th>
                        <th class="h-12 px-4 text-left align-middle font-medium">Status</th>
                        <th class="h-12 px-4 text-left align-middle font-medium">Interval</th>
                        <th class="h-12 px-4 text-left align-middle font-medium">Last Checked</th>
                        <th class="h-12 px-4 text-right align-middle font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="[&_tr:last-child]:border-0">
                    <tr v-for="domain in domains" :key="domain.id" class="border-b transition-colors hover:bg-muted/50">
                        <td class="p-4 align-middle font-medium">
                            <div class="flex items-center gap-2">
                                <Globe class="h-4 w-4 text-muted-foreground" />
                                {{ domain.url }}
                            </div>
                        </td>
                        <td class="p-4 align-middle">
                            <Badge v-if="domain.is_up === true" variant="default" class="bg-emerald-500 hover:bg-emerald-600">UP</Badge>
                            <Badge v-else-if="domain.is_up === false" variant="destructive">DOWN</Badge>
                            <Badge v-else variant="secondary">Pending</Badge>
                        </td>
                        <td class="p-4 align-middle text-muted-foreground">
                            {{ domain.check_interval }} min
                        </td>
                        <td class="p-4 align-middle text-muted-foreground">
                            {{ domain.last_checked_at ? new Date(domain.last_checked_at).toLocaleString() : 'Never' }}
                        </td>
                        <td class="p-4 align-middle text-right">
                            <DropdownMenu>
                                <DropdownMenuTrigger as-child>
                                    <Button variant="ghost" size="icon">
                                        <MoreHorizontal class="h-4 w-4" />
                                    </Button>
                                </DropdownMenuTrigger>
                                <DropdownMenuContent align="end">
                                    <DropdownMenuItem as-child>
                                        <Link :href="domainRoutes.show(domain.id)">
                                            <History class="mr-2 h-4 w-4" />
                                            Logs
                                        </Link>
                                    </DropdownMenuItem>
                                    <DropdownMenuItem as-child>
                                        <Link :href="domainRoutes.edit(domain.id)">
                                            <Edit class="mr-2 h-4 w-4" />
                                            Edit
                                        </Link>
                                    </DropdownMenuItem>
                                    <DropdownMenuItem class="text-destructive focus:text-destructive" @click="deleteDomain(domain.id)">
                                        <Trash2 class="mr-2 h-4 w-4" />
                                        Delete
                                    </DropdownMenuItem>
                                </DropdownMenuContent>
                            </DropdownMenu>
                        </td>
                    </tr>
                    <tr v-if="domains.length === 0">
                        <td colspan="5" class="p-8 text-center text-muted-foreground">
                            No domains found. Add your first domain to start monitoring.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
