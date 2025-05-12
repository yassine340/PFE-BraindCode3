<script>
import StartupPaidFormations from '@/Components/StartupPaidFormations.vue';
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

export default {
  components: {
    StartupPaidFormations,
    Head,
    AuthenticatedLayout

  }
}
</script>
<template>
  <Head title="Mes Formations Startup" />
  <AuthenticatedLayout>
    <div>
      <startup-paid-formations />
    </div>
  </AuthenticatedLayout>
</template>