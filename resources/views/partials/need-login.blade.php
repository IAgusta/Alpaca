<!-- Modal -->
<div class="hidden fixed inset-0 z-40 backdrop-blur-sm bg-black/30" id="modalBackdrop"></div>
<div id="accessModal" tabindex="-1" aria-hidden="true" class="hidden fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-50">
  <div class="relative w-[95vw] lg:w-full lg:max-w-3xl p-4">
    <div class="relative bg-white dark:bg-gray-900 text-gray-900 dark:text-white rounded-lg shadow-xl p-8 flex flex-col items-center space-y-6 border border-gray-200 dark:border-gray-700">
      <!-- Close Button -->
      <button type="button" class="absolute top-4 right-4 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300" 
        onclick="document.getElementById('accessModal').classList.add('hidden'); document.getElementById('modalBackdrop').classList.add('hidden')">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>

      <!-- Image (Replace src with your image path) -->
      <img src="/img/cute.gif" alt="Cute character" class="w-64 h-42 rounded-lg">

      <!-- Message -->
      <p class="text-xl font-semibold text-center text-gray-900 dark:text-white">You must log in to fully access the website.</p>

      <!-- Buttons -->
      <div class="flex gap-4">
        <a href="/register" class="px-6 py-2 text-xs bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">Register</a>
        <a href="/login" class="px-6 py-2 text-xs bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">Log in</a>
      </div>
    </div>
  </div>
</div>