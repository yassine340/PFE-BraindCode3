  <template>
  <app-layout>
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <group-management />
        </div>
      </div>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AuthenticatedLayout.vue';
import GroupManagement from '@/Components/GroupManagement.vue';

export default {
  components: {
    AppLayout,
    GroupManagement
  }
}
</script>