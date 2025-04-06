<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
    <div>
        <h3 class="text-lg font-medium text-gray-900">Plugins</h3>
        <div class="mt-2 flex flex-wrap gap-4">
            <!-- Robot Control -->
            <a href="{{ route('plugins.robotControl') }}" class="group">
                <div class="relative w-24 h-24 border rounded-lg shadow-md overflow-hidden bg-gray-200 hover:bg-slate-500 transition duration-300 flex items-center justify-center">
                    <!-- Icon (Visible by default, disappears on hover) -->
                    <span class="material-symbols-outlined text-5xl text-gray-900 transition-all duration-300 group-hover:opacity-0">
                        stadia_controller
                    </span>
                    <!-- Text (Hidden by default, appears centered on hover) -->
                    <p class="absolute opacity-0 text-lg font-semibold text-white transition-all duration-300 group-hover:opacity-100 group-hover:flex group-hover:items-center group-hover:justify-center w-full h-full text-center whitespace-nowrap">
                        Controler
                    </p>
                </div>
            </a>

            <!-- Find Users -->
            <a href="{{ route('plugins.search-users') }}" class="group">
                <div class="relative w-24 h-24 border rounded-lg shadow-md overflow-hidden bg-gray-200 hover:bg-slate-500 transition duration-300 flex items-center justify-center">
                    <!-- Icon (Visible by default, disappears on hover) -->
                    <span class="material-symbols-outlined text-5xl text-gray-900 transition-all duration-300 group-hover:opacity-0">
                        person_search
                    </span>
                    <!-- Text (Hidden by default, appears centered on hover) -->
                    <p class="absolute opacity-0 text-lg font-semibold text-white transition-all duration-300 group-hover:opacity-100 group-hover:flex group-hover:items-center group-hover:justify-center w-full h-full text-center whitespace-nowrap">
                        Find Users
                    </p>
                </div>
            </a>
        </div>
    </div>
</div>   