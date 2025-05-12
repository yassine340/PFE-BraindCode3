<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
import Footer from "@/Components/Footer.vue";
import { defineAsyncComponent } from 'vue';
const AppHeader = defineAsyncComponent(() =>
    import('@/Components/Welcome/AppHeader.vue')
);
</script>
<template>
    <AppHeader :canLogin="true" :canRegister="true" />
    <div class="flex min-h-screen flex-col items-center bg-[#F1F9FF] pt-6 sm:justify-center sm:pt-0">
        <div class="mb-8 text-center">
        </div>

        <div class="w-full ">
            <slot />
        </div>
    </div>
    <div class="w-full" >
      <Footer class="mt-12" />
    </div>
</template>

<style scoped>
/* Additional animations for page transitions */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
}

.min-h-screen {
    animation: fadeIn 0.6s ease-out;
}
</style>