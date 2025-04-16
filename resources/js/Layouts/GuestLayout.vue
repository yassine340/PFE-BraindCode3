<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
import Footer from "@/Components/Footer.vue";
</script>

<template>
    <div class="flex min-h-screen flex-col items-center bg-[#F1F9FF] pt-6 sm:justify-center sm:pt-0">
        <div class="mb-8 text-center">
            <Link href="/" class="inline-block">
                <img src="/image/logos/BraindCode.png" class="img-fluid" style="width: 200px;" alt="Logo" />
            </Link>
        </div>

        <div class="w-full ">
            <slot />
        </div>
    <!-- Footer section -->
    <div class="w-full" >
      <Footer class="mt-12" />
    </div>
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