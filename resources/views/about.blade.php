<x-app-layout>
  @section('title', 'About Us - ' . config('app.name'))
    <!-- Hero Page -->
    <div class="relative isolate px-6 lg:px-8">
        <div class="mx-auto max-w-2xl py-20 sm:py-32 lg:py-20">
          <div class="text-center">
            <h1 class="text-5xl font-semibold tracking-tight text-balance text-gray-900 dark:text-white sm:text-7xl">Learning Management System to enrich your online courses.</h1>
            <p class="mt-8 text-lg font-medium text-pretty text-gray-500 dark:text-gray-200 sm:text-xl/8">Our LMS website offers a seamless and interactive learning experience, allowing users to access courses, track progress, and engage with content anytime, anywhere.</p>
            <div class="mt-10 flex items-center justify-center gap-x-6">
              <a href="{{ route('dashboard') }}" class="rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Get started</a>
              <a href="{{ route('about') }}" class="text-sm/6 font-semibold text-gray-900 dark:text-white">Learn more <span aria-hidden="true">→</span></a>
            </div>
          </div>
        </div>
        <div class="absolute inset-x-0 top-[calc(100%-13rem)] -z-10 transform-gpu overflow-hidden blur-3xl sm:top-[calc(100%-30rem)]" aria-hidden="true">
          <div class="relative left-[calc(50%+3rem)] aspect-1155/678 w-[36.125rem] -translate-x-1/2 bg-linear-to-tr from-[#ff80b5] to-[#9089fc] opacity-30 sm:left-[calc(50%+36rem)] sm:w-[72.1875rem]" style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"></div>
        </div>
    </div>

    <!-- Feature Section -->
    <div class="overflow-hidden py-24 sm:py-32">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
          <div class="mx-auto grid max-w-2xl grid-cols-1 gap-x-8 gap-y-16 sm:gap-y-20 lg:mx-0 lg:max-w-none lg:grid-cols-2">
            <div class="lg:pt-4 lg:pr-8">
              <div class="lg:max-w-lg">
                <h2 class="text-base/7 font-semibold text-indigo-600">Learn faster</h2>
                <p class="mt-2 text-4xl font-semibold tracking-tight text-pretty text-gray-900 dark:text-white sm:text-5xl">A Better Learning Management System</p>
                <p class="mt-6 text-lg/8 text-gray-600 dark:text-gray-300">Website features a fun and engaging UI, making learning an enjoyable experience for all users!</p>
                <dl class="mt-5 max-w-xl space-y-8 text-base/7 text-gray-600 dark:text-gray-300 lg:max-w-none">
                  <div class="relative pl-9">
                    <dt class="inline font-semibold text-gray-900 dark:text-white">
                      <svg class="absolute top-1 left-1 size-5 text-indigo-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                        <path fill-rule="evenodd" d="M5.5 17a4.5 4.5 0 0 1-1.44-8.765 4.5 4.5 0 0 1 8.302-3.046 3.5 3.5 0 0 1 4.504 4.272A4 4 0 0 1 15 17H5.5Zm3.75-2.75a.75.75 0 0 0 1.5 0V9.66l1.95 2.1a.75.75 0 1 0 1.1-1.02l-3.25-3.5a.75.75 0 0 0-1.1 0l-3.25 3.5a.75.75 0 1 0 1.1 1.02l1.95-2.1v4.59Z" clip-rule="evenodd" />
                      </svg>
                      Make You'r Own Courses.
                    </dt>
                    <dd class="inline">With Trainer, create and manage your own courses, customizing content to provide the best learning experience for your students..</dd>
                  </div>
                  <div class="relative pl-9">
                    <dt class="inline font-semibold text-gray-900 dark:text-white">
                      <svg class="absolute top-1 left-1 size-5 text-indigo-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                        <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 0 0-4.5 4.5V9H5a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-6a2 2 0 0 0-2-2h-.5V5.5A4.5 4.5 0 0 0 10 1Zm3 8V5.5a3 3 0 1 0-6 0V9h6Z" clip-rule="evenodd" />
                      </svg>
                      Make it Private.
                    </dt>
                    <dd class="inline">Lock your courses for private classes, giving you full control over who can access your content.</dd>
                  </div>
                  <div class="relative pl-9">
                    <dt class="inline font-semibold text-gray-900 dark:text-white">
                      <svg class="absolute top-1 left-1 size-5 text-indigo-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                        <path d="M4.632 3.533A2 2 0 0 1 6.577 2h6.846a2 2 0 0 1 1.945 1.533l1.976 8.234A3.489 3.489 0 0 0 16 11.5H4c-.476 0-.93.095-1.344.267l1.976-8.234Z" />
                        <path fill-rule="evenodd" d="M4 13a2 2 0 1 0 0 4h12a2 2 0 1 0 0-4H4Zm11.24 2a.75.75 0 0 1 .75-.75H16a.75.75 0 0 1 .75.75v.01a.75.75 0 0 1-.75.75h-.01a.75.75 0 0 1-.75-.75V15Zm-2.25-.75a.75.75 0 0 0-.75.75v.01c0 .414.336.75.75.75H13a.75.75 0 0 0 .75-.75V15a.75.75 0 0 0-.75-.75h-.01Z" clip-rule="evenodd" />
                      </svg>
                      Server Integrated.
                    </dt>
                    <dd class="inline">Our courses are securely saved with server integration, ensuring seamless access and progress tracking anytime, anywhere.</dd>
                  </div>
                </dl>
              </div>
            </div>
            <img data-theme-img="img/dashboard" src="{{ asset('img/dashboard-light.png') }}" alt="course screenshot" class="w-[48rem] max-w-none rounded-xl ring-1 shadow-xl ring-gray-400/10 sm:w-[57rem] md:-ml-4 lg:-ml-0" width="2432" height="1442">
          </div>
        </div>
    </div>

    @include('partials.top-courses')

  <!-- Elegant Bento Grid -->
  <section class="py-24 sm:py-32 bg-gray-50 dark:bg-gray-900">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
      <h2 class="text-center text-sm font-semibold text-indigo-600 tracking-wide uppercase">Alpaca Features</h2>
      <p class="mt-3 text-center text-4xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-5xl">Packed with powerful features</p>

      <div class="mt-16 grid gap-6 lg:grid-cols-3">
        
        <!-- Left Column -->
        <div class="flex flex-col gap-6">
          <!-- Many Courses -->
          <div class="group relative rounded-2xl bg-white/90 dark:bg-white/10 backdrop-blur-lg shadow-lg overflow-hidden transition-transform duration-300 hover:scale-[1.02]">
            <div class="p-6 sm:p-8">
              <h3 class="text-lg font-semibold text-gray-900 dark:text-white text-center">Many Courses</h3>
              <p class="mt-2 text-sm text-gray-600 dark:text-gray-300 text-center">We provide a variety of free courses.</p>
            </div>
            <div class="overflow-hidden">
              <img data-theme-img="img/many-course" src="img/many-course-light.png" alt="courses-image" class="transition-transform duration-300 group-hover:scale-110 w-full object-cover rounded-b-2xl">
            </div>
          </div>

          <!-- Integrated Exercise -->
          <div class="group relative rounded-2xl bg-white/90 dark:bg-white/10 backdrop-blur-lg shadow-lg overflow-hidden transition-transform duration-300 hover:scale-[1.02]">
            <div class="p-6 sm:p-8">
              <h3 class="text-lg font-semibold text-gray-900 dark:text-white text-center">Integrated Control</h3>
              <p class="mt-2 text-sm text-gray-600 dark:text-gray-300 text-center">Offers integrated controls to enhance learning Robotics.</p>
            </div>
            <div class="overflow-hidden">
              <img data-theme-img="img/control" src="img/control-light.png" alt="exercise-image" class="transition-transform duration-300 group-hover:scale-110 w-full object-cover rounded-b-2xl">
            </div>
          </div>
        </div>

        <!-- Center Column (Highlight Card) -->
        <div class="flex items-center justify-center">
          <div class="group relative rounded-2xl bg-white/90 dark:bg-white/10 backdrop-blur-lg shadow-2xl overflow-hidden transition-transform duration-300 hover:scale-[1.02] w-full h-full">
            <div class="p-6 sm:p-8">
              <h3 class="text-lg font-semibold text-gray-900 dark:text-white text-center">Mobile Friendly</h3>
              <p class="mt-2 text-sm text-gray-600 dark:text-gray-300 text-center">Fully responsive for all screen sizes and devices.</p>
            </div>
            <div class="p-4 flex justify-center">
              <img data-theme-img="img/mobile" src="img/mobile-light.png" alt="mobile-display" class="transition-transform duration-300 group-hover:scale-110 rounded-xl max-w-xs w-full object-top object-cover">
            </div>
          </div>
        </div>

        <!-- Right Column -->
        <div class="flex flex-col gap-6">
          <!-- User Finding -->
          <div class="group relative rounded-2xl bg-white/90 dark:bg-white/10 backdrop-blur-lg shadow-lg overflow-hidden transition-transform duration-300 hover:scale-[1.02]">
            <div class="p-6 sm:p-8">
              <h3 class="text-lg font-semibold text-gray-900 dark:text-white text-center">User Finding</h3>
              <p class="mt-2 text-sm text-gray-600 dark:text-gray-300 text-center">Find the right trainer or user easily.</p>
            </div>
            <div class="overflow-hidden">
              <img data-theme-img="img/find-user" src="img/find-user-light.png" alt="user-finding-image" class="transition-transform duration-300 group-hover:scale-110 w-full object-cover rounded-b-2xl">
            </div>
          </div>

          <!-- Profile Custom -->
          <div class="group relative rounded-2xl bg-white/90 dark:bg-white/10 backdrop-blur-lg shadow-lg overflow-hidden transition-transform duration-300 hover:scale-[1.02]">
            <div class="p-6 sm:p-8">
              <h3 class="text-lg font-semibold text-gray-900 dark:text-white text-center">Profile Custom</h3>
              <p class="mt-2 text-sm text-gray-600 dark:text-gray-300 text-center">Personalize your profile the way you like.</p>
            </div>
            <div class="overflow-hidden">
              <img data-theme-img="img/customized" src="img/customized-light.png" alt="profile-image" class="transition-transform duration-300 group-hover:scale-110 w-full object-cover rounded-b-2xl">
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>

</x-app-layout>