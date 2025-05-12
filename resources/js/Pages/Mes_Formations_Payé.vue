<script>
import PaidFormations from '@/Components/PaidFormations.vue';
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

export default {
  components: {
    PaidFormations,
    Head,
    AuthenticatedLayout

  }
}
</script>
<template>
  <Head title="Mes Formations" />
  <AuthenticatedLayout>
    <div>
      <paid-formations />
    </div>
  </AuthenticatedLayout>
</template>