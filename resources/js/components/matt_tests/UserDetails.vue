
<script setup lang="ts">
import type { User } from '@/types/user';

defineProps<{ user: User }>();

// interface Props {
//     name: string
//     email: string
// }

// defineProps<Props>();
</script>

<template>
    <header>
        <h3 class="mb-0.5 text-base font-medium">{{ user.name }}</h3>
        <p v-if="user.email" class="text-sm text-muted-foreground">
            {{ user.email }}
        </p>
    </header>
</template>