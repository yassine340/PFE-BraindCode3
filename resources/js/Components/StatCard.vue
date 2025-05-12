// StatCard.vue
<template>
  <div :class="`bg-${colorClass}-50 rounded-lg p-4 border border-${colorClass}-100`">
    <div class="flex items-center">
      <div :class="`flex-shrink-0 bg-${colorClass}-100 rounded-full p-3`">
        <i :class="`${icon} text-${colorClass}-600`"></i>
      </div>
      <div class="ml-4">
        <p :class="`text-sm font-medium text-${colorClass}-600`">{{ label }}</p>
        <p class="text-2xl font-semibold text-gray-900">{{ value }}</p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    icon: {
      type: String,
      required: true
    },
    label: {
      type: String,
      required: true
    },
    value: {
      type: Number,
      required: true
    },
    colorClass: {
      type: String,
      default: 'blue'
    }
  }
}
</script>