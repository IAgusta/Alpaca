<x-app-layout>
    @section('title', 'Preferences - '. config('app.name'))

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <form id="settingsForm" class="space-y-6">
                <!-- UI Language -->
                <div class="bg-white dark:text-white dark:bg-gray-800 shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium mb-4">UI Language</h3>
                    <select id="ui_language" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md dark:bg-gray-700 dark:border-gray-600">
                        <option value="en">English</option>
                        <option value="id">Indonesian</option>
                    </select>
                </div>

                <!-- Color Scheme -->
                <div class="bg-white dark:text-white dark:bg-gray-800 shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium mb-4">Color Scheme</h3>
                    <div class="grid grid-cols-3 gap-4">
                        @foreach(['default', 'dark', 'system'] as $theme)
                            <label class="relative flex flex-col items-center gap-2 cursor-pointer">
                                <input type="radio" name="color_scheme" value="{{ $theme }}" class="sr-only peer">
                                <div class="w-full aspect-video rounded-lg border-2 peer-checked:border-blue-500 peer-checked:ring-2 peer-checked:ring-blue-500
                                    {{ $theme == 'default' ? 'bg-white' : ($theme == 'dark' ? 'bg-gray-900' : 'bg-gradient-to-r from-gray-100 to-gray-900') }}">
                                </div>
                                <span class="text-sm font-medium peer-checked:text-blue-500">
                                    {{ ucfirst($theme) }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Image Quality -->
                <div class="bg-white dark:text-white dark:bg-gray-800 shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium mb-4">Image Quality</h3>
                    <div class="flex gap-6">
                        @foreach(['low', 'medium', 'high'] as $quality)
                            <label class="flex items-center">
                                <input type="radio" name="image_quality" value="{{ $quality }}" 
                                       class="form-radio text-blue-500" {{ $quality === 'medium' ? 'checked' : '' }}>
                                <span class="ml-2">{{ ucfirst($quality) }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Experimental Features -->
                <div class="bg-white dark:text-white dark:bg-gray-800 shadow rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium">Experimental Features</h3>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="experimental_features" value="1" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                </div>

                <!-- Save Button -->
                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-6 rounded-lg transition-colors">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>