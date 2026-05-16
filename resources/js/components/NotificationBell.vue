<script setup lang="ts">
import { Bell, Check, Globe, AlertTriangle } from 'lucide-vue-next';
import { usePage, router } from '@inertiajs/vue3';
import { computed } from 'vue';
import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { Badge } from '@/components/ui/badge';
import domainRoutes from '@/routes/domains';
import notificationRoutes from '@/routes/notifications';

const page = usePage();
const notifications = computed(() => page.props.auth.notifications || []);
const unreadCount = computed(() => notifications.value.length);

const markAsRead = (e: Event) => {
    e.stopPropagation();
    router.post(notificationRoutes.markAsRead().url, {}, {
        preserveScroll: true,
    });
};

const goToDomain = (id: number) => {
    router.get(domainRoutes.show(id).url);
};

const formatDate = (date: string) => {
    return new Date(date).toLocaleString([], { month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' });
};
</script>

<template>
    <DropdownMenu>
        <DropdownMenuTrigger as-child>
            <Button variant="ghost" size="icon" class="relative" data-test="notification-bell">
                <Bell class="h-5 w-5" />
                <Badge
                    v-if="unreadCount > 0"
                    class="absolute -top-1 -right-1 h-4 min-w-[1rem] px-1 flex items-center justify-center text-[10px] bg-destructive text-destructive-foreground border-2 border-background"
                >
                    {{ unreadCount }}
                </Badge>
            </Button>
        </DropdownMenuTrigger>
        <DropdownMenuContent align="end" class="w-80">
            <DropdownMenuLabel class="flex items-center justify-between">
                <span>Notifications</span>
                <Button
                    v-if="unreadCount > 0"
                    variant="ghost"
                    size="sm"
                    class="h-auto p-0 text-xs text-muted-foreground hover:text-primary"
                    @click="markAsRead"
                >
                    Mark all as read
                </Button>
            </DropdownMenuLabel>
            <DropdownMenuSeparator />
            <div class="max-h-[300px] overflow-y-auto">
                <div v-if="unreadCount === 0" class="p-4 text-center text-sm text-muted-foreground">
                    No new notifications
                </div>
                <DropdownMenuItem
                    v-for="notification in notifications"
                    :key="notification.id"
                    class="flex flex-col items-start gap-1 p-3 cursor-pointer focus:bg-accent"
                    @select="goToDomain(notification.data.domain_id)"
                >
                    <div class="flex items-start gap-2 w-full">
                        <div class="mt-1">
                            <Globe v-if="notification.data.is_up" class="h-4 w-4 text-emerald-500" />
                            <AlertTriangle v-else class="h-4 w-4 text-destructive" />
                        </div>
                        <div class="flex-1 space-y-1">
                            <p class="text-sm font-medium leading-none">
                                {{ notification.data.is_up ? 'Domain UP' : 'Domain DOWN' }}
                            </p>
                            <p class="text-xs text-muted-foreground line-clamp-2">
                                {{ notification.data.message }}
                            </p>
                        </div>
                    </div>
                    <span class="text-[10px] text-muted-foreground self-end">
                        {{ formatDate(notification.created_at) }}
                    </span>
                </DropdownMenuItem>
            </div>
        </DropdownMenuContent>
    </DropdownMenu>
</template>
