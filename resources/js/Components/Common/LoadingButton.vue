<template>
  <button
    :disabled="loading || explicitDisable"

    class="add-btn focus:outline-none focus:ring-0
    flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed"
  >
    <svg
      v-if="loading"
      class="animate-spin -ml-1 mr-2 h-5 w-5 text-white"
      xmlns="http://www.w3.org/2000/svg"
      fill="none"
      viewBox="0 0 24 24"
    >
      <circle
        class="opacity-25"
        cx="12"
        cy="12"
        r="10"
        stroke="currentColor"
        stroke-width="4"
      />
      <path
        class="opacity-75"
        fill="currentColor"
        d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"
      />
    </svg>
    <span>{{ loading ? loadingText : text }}</span>
  </button>
</template>

<script>
export default {
  props: {
    loading: Boolean,
    explicitDisable: {type: Boolean, default: false},
    text: { type: String, default: 'Submit' },
    loadingText: { type: String, default: 'Processing...' },
  },
};
</script>
