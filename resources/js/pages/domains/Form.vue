<script setup lang="ts">
import { useForm, Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import InputError from '@/components/InputError.vue';
import domainRoutes from '@/routes/domains';
import type { Domain } from '@/types';

const props = defineProps<{
    domain?: Domain;
}>();

const form = useForm({
    url: props.domain?.url ?? '',
    check_interval: props.domain?.check_interval ?? 5,
    request_timeout: props.domain?.request_timeout ?? 5,
    check_method: props.domain?.check_method ?? 'GET',
});

const submit = () => {
    if (props.domain) {
        form.put(domainRoutes.update(props.domain.id).url);
    } else {
        form.post(domainRoutes.store().url);
    }
};
</script>

<template>
    <form @submit.prevent="submit" class="space-y-6 max-w-2xl">
        <div class="space-y-2">
            <Label for="url">Domain URL</Label>
            <Input
                id="url"
                v-model="form.url"
                type="url"
                placeholder="https://example.com"
                required
                :class="{ 'border-destructive': form.errors.url }"
            />
            <InputError :message="form.errors.url" />
            <p class="text-xs text-muted-foreground">The full URL of the domain you want to monitor.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
                <Label for="check_interval">Check Interval (minutes)</Label>
                <Input
                    id="check_interval"
                    v-model="form.check_interval"
                    type="number"
                    min="1"
                    max="1440"
                    required
                    :class="{ 'border-destructive': form.errors.check_interval }"
                />
                <InputError :message="form.errors.check_interval" />
                <p class="text-xs text-muted-foreground">How often to check the domain status.</p>
            </div>

            <div class="space-y-2">
                <Label for="request_timeout">Request Timeout (seconds)</Label>
                <Input
                    id="request_timeout"
                    v-model="form.request_timeout"
                    type="number"
                    min="1"
                    max="30"
                    required
                    :class="{ 'border-destructive': form.errors.request_timeout }"
                />
                <InputError :message="form.errors.request_timeout" />
                <p class="text-xs text-muted-foreground">Maximum time to wait for a response.</p>
            </div>
        </div>

        <div class="space-y-2">
            <Label for="check_method">Check Method</Label>
            <Select v-model="form.check_method">
                <SelectTrigger>
                    <SelectValue placeholder="Select method" />
                </SelectTrigger>
                <SelectContent>
                    <SelectItem value="GET">HTTP GET</SelectItem>
                    <SelectItem value="HEAD">HTTP HEAD</SelectItem>
                </SelectContent>
            </Select>
            <InputError :message="form.errors.check_method" />
            <p class="text-xs text-muted-foreground">GET downloads the page, HEAD only checks headers (faster).</p>
        </div>

        <div class="flex items-center gap-4 pt-4">
            <Button type="submit" :disabled="form.processing">
                {{ domain ? 'Update Domain' : 'Add Domain' }}
            </Button>
            <Button variant="ghost" as-child>
                <Link :href="domainRoutes.index()">Cancel</Link>
            </Button>
        </div>
    </form>
</template>
