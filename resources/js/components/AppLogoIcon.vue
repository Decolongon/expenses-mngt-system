<script setup lang="ts">
import type { HTMLAttributes } from 'vue';

defineOptions({
    inheritAttrs: false,
});

type Props = {
    className?: HTMLAttributes['class'];
};

defineProps<Props>();
</script>

<template>
    <svg
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 512 512"
        :class="className"
        v-bind="$attrs"
    >
        <g fill="currentColor">
            <rect x="128" y="132" width="240" height="130" rx="30" />
            <rect x="112" y="196" width="272" height="212" rx="34" />
            <path
                fill-rule="evenodd"
                clip-rule="evenodd"
                d="M330 294c0-14.354 11.646-26 26-26h84c13.255 0 24 10.745 24 24v24c0 13.255-10.745 24-24 24h-84c-14.354 0-26-11.646-26-26v-20Zm70-5a15 15 0 1 0 0 30 15 15 0 0 0 0-30Z"
            />
        </g>
    </svg>
</template>
