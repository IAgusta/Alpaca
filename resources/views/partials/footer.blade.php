<footer class="bg-white dark:bg-gray-900">
  <div class="mx-auto w-full max-w-screen-xl">
    <div class="grid grid-cols-2 gap-8 px-4 py-6 lg:py-8 md:grid-cols-5">
        <div class="flex items-center justify-center">
            <img src="img/logo.png" class="max-w-full h-auto" alt="logo-application">
        </div>
        <div>
          <h2 class="mb-4 text-sm font-semibold text-gray-900 uppercase dark:text-white">Product</h2>
          <ul class="text-gray-500 dark:text-gray-400 font-medium">
              <li class="mb-4">
                  <a href="{{ route('user.course') }}" class=" hover:underline">Kelas Online</a>
              </li>
              <li class="mb-4">
                  <a href="{{ route('plugins.monitoring') }}" class="hover:underline">Cari Murid</a>
              </li>
              <li class="mb-4">
                  <a href="{{ route('plugins.robotControl') }}" class="hover:underline">Kontrol Robot</a>
              </li>
              <li class="mb-4">
                  <a href="{{ route('prices') }}" class="hover:underline">Langganan</a>
              </li>
          </ul>
        </div>
        <div>
          <h2 class="mb-4 text-sm font-semibold text-gray-900 uppercase dark:text-white">Informasi</h2>
          <ul class="text-gray-500 dark:text-gray-400 font-medium">
              <li class="mb-4">
                  <a href="{{ route('about') }}" class="hover:underline">Tentang</a>
              </li>
              <li class="mb-4">
                  <a href="{{ route('terms') }}" class="hover:underline">Syarat dan Ketentuan</a>
              </li>
              <li class="mb-4">
                  <a href="{{ route('faq') }}" class="hover:underline">Bantuan</a>
              </li>
              <li class="mb-4">
                  <a href="{{ route('privacy-policy') }}" class="hover:underline">Kebijakan Privasi</a>
              </li>
          </ul>
        </div>
        <div>
          <h2 class="mb-4 text-sm font-semibold text-gray-900 uppercase dark:text-white">Hubungi Kami</h2>
          <ul class="text-gray-500 dark:text-gray-400 font-medium">
              <li class="mb-4">
                  <a href="{{ route('contact') }}" class="hover:underline">Kontak</a>
              </li>
              <li class="mb-4">
                  <a href="{{ route('news') }}" class="hover:underline">Berita</a>
              </li>
              <li class="mb-4">
                  <a href="https://docs.google.com/forms/d/e/1FAIpQLScIvdilxzsOfFCzkolZAj9-eZoQypunWG6aIzc6Sg7x-MPxOw/viewform?usp=dialog" target="_blank" class="hover:underline">Kritik dan Saran</a>
              </li>
          </ul>
        </div>
        <div>
        <h2 class="mb-4 text-sm font-semibold text-gray-900 uppercase dark:text-white">Social Media</h2>
        <div class="flex space-x-5 rtl:space-x-reverse">
            <a href="https://github.com/IAgusta/Alpaca" target="_blank" class="text-gray-400 hover:text-gray-900 dark:hover:text-white">
                <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 .333A9.911 9.911 0 0 0 6.866 19.65c.5.092.678-.215.678-.477 0-.237-.01-1.017-.014-1.845-2.757.6-3.338-1.169-3.338-1.169a2.627 2.627 0 0 0-1.1-1.451c-.9-.615.07-.6.07-.6a2.084 2.084 0 0 1 1.518 1.021 2.11 2.11 0 0 0 2.884.823c.044-.503.268-.973.63-1.325-2.2-.25-4.516-1.1-4.516-4.9A3.832 3.832 0 0 1 4.7 7.068a3.56 3.56 0 0 1 .095-2.623s.832-.266 2.726 1.016a9.409 9.409 0 0 1 4.962 0c1.89-1.282 2.717-1.016 2.717-1.016.366.83.402 1.768.1 2.623a3.827 3.827 0 0 1 1.02 2.659c0 3.807-2.319 4.644-4.525 4.889a2.366 2.366 0 0 1 .673 1.834c0 1.326-.012 2.394-.012 2.72 0 .263.18.572.681.475A9.911 9.911 0 0 0 10 .333Z" clip-rule="evenodd"/>
                </svg>
                <span class="sr-only">GitHub account</span>
            </a>
            <a href="https://discord.gg/ExzAJfgE" target="_blank" class="text-gray-400 hover:text-gray-900 dark:hover:text-white">
                <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 21 16">
                    <path d="M16.942 1.556a16.3 16.3 0 0 0-4.126-1.3 12.04 12.04 0 0 0-.529 1.1 15.175 15.175 0 0 0-4.573 0 11.585 11.585 0 0 0-.535-1.1 16.274 16.274 0 0 0-4.129 1.3A17.392 17.392 0 0 0 .182 13.218a15.785 15.785 0 0 0 4.963 2.521c.41-.564.773-1.16 1.084-1.785a10.63 10.63 0 0 1-1.706-.83c.143-.106.283-.217.418-.33a11.664 11.664 0 0 0 10.118 0c.137.113.277.224.418.33-.544.328-1.116.606-1.71.832a12.52 12.52 0 0 0 1.084 1.785 16.46 16.46 0 0 0 5.064-2.595 17.286 17.286 0 0 0-2.973-11.59ZM6.678 10.813a1.941 1.941 0 0 1-1.8-2.045 1.93 1.93 0 0 1 1.8-2.047 1.919 1.919 0 0 1 1.8 2.047 1.93 1.93 0 0 1-1.8 2.045Zm6.644 0a1.94 1.94 0 0 1-1.8-2.045 1.93 1.93 0 0 1 1.8-2.047 1.918 1.918 0 0 1 1.8 2.047 1.93 1.93 0 0 1-1.8 2.045Z"/>
                </svg>
                <span class="sr-only">Discord community</span>
            </a>
            <a href="https://youtube.com/@ikraamagusta?si=aaz--YvkvX0got9O" target="_blank" class="text-gray-400 hover:text-gray-900 dark:hover:text-white">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-youtube" viewBox="0 0 16 16">
                    <path d="M8.051 1.999h.089c.822.003 4.987.033 6.11.335a2.01 2.01 0 0 1 1.415 1.42c.101.38.172.883.22 1.402l.01.104.022.26.008.104c.065.914.073 1.77.074 1.957v.075c-.001.194-.01 1.108-.082 2.06l-.008.105-.009.104c-.05.572-.124 1.14-.235 1.558a2.01 2.01 0 0 1-1.415 1.42c-1.16.312-5.569.334-6.18.335h-.142c-.309 0-1.587-.006-2.927-.052l-.17-.006-.087-.004-.171-.007-.171-.007c-1.11-.049-2.167-.128-2.654-.26a2.01 2.01 0 0 1-1.415-1.419c-.111-.417-.185-.986-.235-1.558L.09 9.82l-.008-.104A31 31 0 0 1 0 7.68v-.123c.002-.215.01-.958.064-1.778l.007-.103.003-.052.008-.104.022-.26.01-.104c.048-.519.119-1.023.22-1.402a2.01 2.01 0 0 1 1.415-1.42c.487-.13 1.544-.21 2.654-.26l.17-.007.172-.006.086-.003.171-.007A100 100 0 0 1 7.858 2zM6.4 5.209v4.818l4.157-2.408z"/>
                </svg>
              <span class="sr-only">Youtube account</span>
            </a>
            <a href="https://web.facebook.com/profile.php?id=100012710521025" target="_blank" class="text-gray-400 hover:text-gray-900 dark:hover:text-white">
                <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                      <path fill-rule="evenodd" d="M6.135 3H8V0H6.135a4.147 4.147 0 0 0-4.142 4.142V6H0v3h2v9.938h3V9h2.021l.592-3H5V3.591A.6.6 0 0 1 5.592 3h.543Z" clip-rule="evenodd"/>
                  </svg>
                <span class="sr-only">Facebook page</span>
            </a>
            </div>
        </div>
  </div>
</footer>
