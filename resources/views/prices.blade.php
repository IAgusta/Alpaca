<x-app-layout>
  @section('title', 'Our Plans - '. config('app.name'))
  <div class="relative isolate bg-transparent px-6 pt-4 sm:py-16 lg:px-8">
      <div class="absolute inset-x-0 -top-3 -z-10 transform-gpu overflow-hidden px-36 blur-3xl" aria-hidden="true">
        <div class="mx-auto aspect-1155/678 w-[72.1875rem] bg-linear-to-tr from-[#ff80b5] to-[#9089fc] opacity-30" style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"></div>
      </div>
      <div class="mx-auto max-w-4xl text-center">
        <h2 class="text-base/7 font-semibold text-indigo-600">Pilihan Paket</h2>
        <p class="mt-2 text-5xl font-semibold tracking-tight text-balance text-gray-900 dark:text-white sm:text-6xl">Pilih rencana yang kamu inginkan</p>
      </div>
        <p class="mx-auto mt-6 max-w-2xl text-center text-lg font-medium text-pretty text-gray-600 dark:text-gray-300 sm:text-xl/8">Kami menawarkan jasa yang menarik untuk mu. Pilih sesuai kebutuhanmu, belajar mandiri berdasarkan keinginanmu atau melakukan tutor secara pribadi dengan team terbaik kami.</p>
        <div class="mx-auto mt-16 grid max-w-screen-xl grid-cols-1 items-center gap-y-6 sm:mt-20 sm:gap-y-0 lg:grid-cols-3">
          <!-- Free Plan -->
          <div class=" bg-white/60 dark:bg-white/95 p-8 ring-1 ring-gray-900/10 sm:mx-8 sm:p-10 lg:mx-0 rounded-3xl lg:rounded-r-none">
            <h3 id="tier-hobby" class="text-base/7 font-semibold text-indigo-600">Alpaca Student</h3>
            <p class="mt-4 flex items-baseline gap-x-2">
              <span class="text-5xl font-semibold tracking-tight text-gray-900">Gratis</span>
            </p>
            <p class="mt-6 text-base/7 text-gray-600">Rencana yang tepat jika ingin melakukan belajar dengan mandiri.</p>
            <ul role="list" class="mt-8 space-y-3 text-sm/6 text-gray-600 sm:mt-10">
              <li class="flex gap-x-3">
                <svg class="h-6 w-5 flex-none text-indigo-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                  <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" />
                </svg>
                Kursus Online Tidak Terbatas (Kecuali Terkunci)
              </li>
              <li class="flex gap-x-3">
                <svg class="h-6 w-5 flex-none text-indigo-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                  <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" />
                </svg>
                Materi Pembelajaran yang dapat dipelajari secara mandiri
              </li>
              <li class="flex gap-x-3">
                <svg class="h-6 w-5 flex-none text-indigo-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                  <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" />
                </svg>
                Akses Video pembelajaran setiap saat
              </li>
              <li class="flex gap-x-3">
                <svg class="h-6 w-5 flex-none text-indigo-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                  <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" />
                </svg>
                Materi Pembelajaran yang dapat diunduh
              </li>
            </ul>
            <a href="{{ route('register') }}" aria-describedby="tier-hobby" class="mt-8 block rounded-md px-3.5 py-2.5 text-center text-sm font-semibold text-indigo-600 ring-1 ring-indigo-200 ring-inset hover:ring-indigo-300 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 sm:mt-10">Bergabung Sekarang</a>
          </div>

          <!-- Tutor Plan -->
          <div class="bg-white/60 dark:bg-white/95 p-8 ring-1 ring-gray-900/10 sm:mx-8 sm:p-10 lg:mx-0 rounded-3xl">
            <h3 id="tier-hobby" class="text-base/7 font-semibold text-indigo-600">Sigma Alpaca</h3>
            <p class="mt-4 flex items-baseline gap-x-2">
              <span class="text-5xl font-semibold tracking-tight text-gray-900">50k</span>
              <span class="text-xl font-semibold text-gray-600">/bulan</span>
            </p>
            <p class="mt-6 text-base/7 text-gray-600">Paket Rencana yang tepat untuk belajar secara teratur dengan penawaran yang menggiurkan.</p>
            <ul role="list" class="mt-8 space-y-3 text-sm/6 text-gray-600 sm:mt-10">
              <li class="flex gap-x-3">
                <svg class="h-6 w-5 flex-none text-indigo-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                  <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" />
                </svg>
                Kursus Online Tidak Terbatas (Kecuali Terkunci)
              </li>
              <li class="flex gap-x-3">
                <svg class="h-6 w-5 flex-none text-indigo-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                  <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" />
                </svg>
                Materi Pembelajaran yang dapat dipelajari setiap saat
              </li>
              <li class="flex gap-x-3">
                <svg class="h-6 w-5 flex-none text-indigo-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                  <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" />
                </svg>
                Akses Video Pembelajaran setiap saat
              </li>
              <li class="flex gap-x-3">
                <svg class="h-6 w-5 flex-none text-indigo-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                  <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" />
                </svg>
                Materi Pembelajaran yang dapat diunduh
              </li>
              <li class="flex gap-x-3">
                <svg class="h-6 w-5 flex-none text-indigo-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                  <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" />
                </svg>
                Profile dan Banner Beranimasi
              </li>
              <li class="flex gap-x-3">
                <svg class="h-6 w-5 flex-none text-indigo-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                  <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" />
                </svg>
                Badge dan Avatar Premium 
                  <span class="inline-flex items-center gap-1 bg-gradient-to-r from-yellow-400 via-yellow-500 to-yellow-600 text-white text-xs font-semibold px-2.5 py-0.5 rounded-full relative overflow-hidden group cursor-default">
                    <span class="absolute inset-0 bg-gradient-to-r from-yellow-500 to-yellow-400 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 relative z-10" viewBox="0 0 20 20" fill="currentColor">
                      <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                    <span class="relative z-10">Alpaca +</span>
                  </span>
              </li> 
              <li class="flex gap-x-3">
                <svg class="h-6 w-5 flex-none text-indigo-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                  <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" />
                </svg>
                Uji Coba Kelas yang Terkunci
              </li>
            </ul>
            <a href="#" aria-describedby="tier-pro" class="mt-8 block rounded-md bg-indigo-500 px-3.5 py-2.5 text-center text-sm font-semibold text-white shadow-xs hover:bg-indigo-400 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500 sm:mt-10">Coming Soon</a>
          </div>
          
          <!-- Hire Us Plan -->
          <div class="relative bg-gray-900 dark:bg-gray-800/50 p-8 ring-1 shadow-2xl ring-gray-900/10 sm:p-10 rounded-3xl lg:rounded-l-none">
            <h3 id="tier-enterprise" class="text-base/7 font-semibold text-indigo-400">Alpaca Tutoring</h3>
            <p class="mt-4 flex items-baseline gap-x-2">
              <span class="text-2xl font-semibold tracking-tight text-white">Bisa dinegosiasikan</span>
            </p>
            <p class="mt-6 text-base/7 text-gray-300">Paket Tutor, Pembuatan maupun Perbaikan Skripsi Khusus Kota Padang.</p>
            <ul role="list" class="mt-8 space-y-3 text-sm/6 text-gray-300 sm:mt-10">
              <li class="flex gap-x-3">
                <svg class="h-6 w-5 flex-none text-indigo-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                  <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" />
                </svg>
                Pembuatan Skripsi Sesuai Format yang diinginkan
              </li>
              <li class="flex gap-x-3">
                <svg class="h-6 w-5 flex-none text-indigo-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                  <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" />
                </svg>
                Pembuatan Skripsi dengan metode ilmiah
              </li>
              <li class="flex gap-x-3">
                <svg class="h-6 w-5 flex-none text-indigo-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                  <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" />
                </svg>
                Pengecekkan Plagiarisme
              </li>
              <li class="flex gap-x-3">
                <svg class="h-6 w-5 flex-none text-indigo-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                  <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" />
                </svg>
                Jasa Ketik Skripsi
              </li>
              <li class="flex gap-x-3">
                <svg class="h-6 w-5 flex-none text-indigo-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                  <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" />
                </svg>
                Pembuatan Skripsi berbentuk Software dan Hardware
              </li>
              <li class="flex gap-x-3">
                <svg class="h-6 w-5 flex-none text-indigo-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                  <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" />
                </svg>
                Pengerjaan 7Hari Kerja  
              </li>
            </ul>
            <a href="https://wa.me/6282171639538" aria-describedby="tier-enterprise" class="mt-8 block rounded-md bg-indigo-500 px-3.5 py-2.5 text-center text-sm font-semibold text-white shadow-xs hover:bg-indigo-400 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500 sm:mt-10">Hubungi Kami</a>
          </div>
        </div>
  </div>
</x-app-layout>