<script setup lang="ts">

    import { defineProps, defineEmits } from 'vue'

    interface Props {
        error: string
        label: string
        name: string
        modelValue: string
    }

    const props = defineProps<Props>();

    const emit = defineEmits<{
        (e: 'update:modelValue', value: string): void
    }>()

</script>

<template>
    <div class="mb-4">
        <label :for="props.name" class="block text-sm font-medium mb-1">
            {{ props.label }}
        </label>
        <input
            :name="props.name"
            type="text"
            :value="props.modelValue"
            @input="(e: Event) => emit('update:modelValue', (e.target as HTMLInputElement).value)"
            class="w-1/2 rounded-md border border-gray-300 bg-gray-100 p-2 text-sm focus:border-blue-500 focus:outline-none text-black"
        />
        <div v-if="error.length > 0">
            <p class="text-sm text-red-500">{{ error }}</p>
        </div>
    </div>
</template>
