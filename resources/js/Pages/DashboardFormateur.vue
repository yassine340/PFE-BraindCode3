<template>
    <Head title="Tableau de bord" />
    <AuthenticatedLayout>
      <template #header>
        <DashboardHeader />
      </template>
  
      <div class="py-6 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-7xl">
          <!-- Top Cards -->
          <div class="grid grid-cols-1 gap-6 mb-6 md:grid-cols-3">
            <MetricCard 
              title="Nombre de rendez-vous réalisés" 
              value="25 rendez-vous" 
              icon="calendar" 
            />
            
            <MetricCard 
              title="Nombre de formation achetées" 
              value="150 formations" 
              icon="book" 
            />
            
            <MetricCard 
              title="Taux de recommandation sur le site web" 
              value="67.4%" 
              icon="award" 
            />
          </div>
          
          <!-- Bottom Section -->
          <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <StartupProgress />
            <GainsExpensesChart />
          </div>
          
          <!-- Action Buttons -->
          <ActionButtons />
        </div>
      </div>
    </AuthenticatedLayout>
  </template>
  
  <script setup>
  import { Head } from '@inertiajs/vue3';
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
  import DashboardHeader from '@/Components/DashboardFormateur/DashboardHeader.vue';
  import MetricCard from '@/Components/DashboardFormateur/MetricCard.vue';
  import StartupProgress from '@/Components/DashboardFormateur/StartupProgress.vue';
  import GainsExpensesChart from '@/Components/DashboardFormateur/GainsExpensesChart.vue';
  import ActionButtons from '@/Components/DashboardFormateur/ActionButtons.vue';
  </script>