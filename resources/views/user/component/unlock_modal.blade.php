<div id="unlock-modal-{{ $course->id }}" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Buka Course
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="unlock-modal-{{ $course->id }}">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <div class="relative p-4 md:p-5">
                <form action="{{ route('user.course.add', $course->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="course_id" value="{{ $course->id }}">
                    <div>
                        <x-input-label for="lock_password" :value="__('Enter Your Course Password')" />
                        <div class="flex my-2">
                            <x-text-input id="lock_password" class="block mt-1 w-full" type="password" name="lock_password" required />
                            <x-input-error :messages="$errors->get('lock_password')" class="mt-2" />
                            <button class="ml-2 mt-1 px-4 py-2 rounded-lg flex items-center bg-orange-400 hover:bg-orange-500" type="submit">
                                <span><svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M240-160h480v-400H240v400Zm240-120q33 0 56.5-23.5T560-360q0-33-23.5-56.5T480-440q-33 0-56.5 23.5T400-360q0 33 23.5 56.5T480-280ZM240-160v-400 400Zm0 80q-33 0-56.5-23.5T160-160v-400q0-33 23.5-56.5T240-640h280v-80q0-83 58.5-141.5T720-920q83 0 141.5 58.5T920-720h-80q0-50-35-85t-85-35q-50 0-85 35t-35 85v80h120q33 0 56.5 23.5T800-560v400q0 33-23.5 56.5T720-80H240Z"/></svg></span>
                                <span class="ml-2">Buka</span>
                            </button>
                        </div>
                        <span class="text-xs text-gray-500 dark:text-gray-400">Tidak Punya Password? <a href="/{{ $course->authorUser->username }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Minta Izin</a></span>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>