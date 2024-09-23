<header>
    <nav class="fixed z-50 w-full px-4 py-3 bg-white border-b border-gray-200">
        <div class="flex items-center justify-between px-8 mx-auto max-w-screen-2xl">
            <div class="flex items-center justify-start">
                <a href="{{ route('home') }}" class="flex mr-14">
                    <x-logo class="h-10 mr-3" />
                    <span class="self-center text-2xl font-semibold text-transparent bg-clip-text bg-gradient-to-r to-emerald-600 from-sky-400">PMG-i</span>
                </a>

                @auth
                    <div class="items-center justify-between hidden w-full lg:flex lg:w-auto lg:order-1">
                        <ul class="flex flex-col mt-4 space-x-6 text-sm font-medium lg:flex-row xl:space-x-8 lg:mt-0">
                            <li>
                                <a href="/"
                                    class="block rounded  {{ Route::currentRouteName() === 'home' ? 'text-primary-700' : 'text-gray-700 hover:text-primary-700' }}"
                                    aria-current="page"
                                >Dashboard</a>
                            </li>
                            @if(hasAccess('maklumat-warga-kerja'))
                                <li>
                                    <a href="{{ route('master-list-warga-kerja') }}" class="block {{ Route::currentRouteName() === 'maklumat-warga-kerja' ? 'text-primary-700' : 'text-gray-700 hover:text-primary-700' }}">Master List Warga Kerja</a>
                                </li>
                            @endif

                            @if(hasAccess('rekod-pmgi'))
                                <li>
                                    <a href="{{ route('rekod-pmgi') }}" class="block {{ Route::currentRouteName() === 'rekod-pmgi' ? 'text-primary-700' : 'text-gray-700 hover:text-primary-700' }}">
                                        Rekod PMGi
                                    </a>
                                </li>
                            @endif

                            @if(hasAccess('prestasi-bulanan') || hasAccess('prestasi-kumulatif'))
                                <li>
                                    <button
                                        id="prestasiDropdownLink"
                                        data-dropdown-toggle="prestasiDropdown"
                                        class="flex items-center justify-between w-full py-2 pl-3 pr-4 font-medium  border-b border-gray-100 hover:bg-gray-50 md:hover:bg-transparent md:border-0 md:hover:text-primary-700 md:p-0 md:w-auto
                                        {{ Illuminate\Support\Str::startsWith(Route::currentRouteName(), 'prestasi') ? 'text-primary-700' : 'text-gray-700 hover:text-primary-700' }}"
                                    >
                                        Prestasi
                                        <x-icon name="chevron-down" class="w-4 h-4 ml-1" />
                                    </button>

                                    <div id="prestasiDropdown" class="z-20 hidden font-normal bg-white divide-y divide-gray-100 rounded shadow w-44 " style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate(769px, 52px);" data-popper-placement="bottom">
                                        <ul class="py-1 text-sm text-gray-700 " aria-labelledby="dropdownLargeButton">
                                            @if(hasAccess('prestasi-bulanan'))
                                                <li>
                                                    <a href="{{ route('prestasi.bulanan') }}" class="block px-4 py-2 hover:bg-gray-100 {{ Route::currentRouteName() === 'prestasi.bulanan' ? 'text-primary-700' : 'text-gray-700 hover:text-primary-700' }}">Prestasi Bulanan</a>
                                                </li>
                                            @endif

                                            @if(hasAccess('prestasi-kumulatif'))
                                                <li>
                                                    <a href="{{ route('prestasi.kumulatif') }}" class="block px-4 py-2 hover:bg-gray-100 {{ Route::currentRouteName() === 'prestasi.kumulatif' ? 'text-primary-700' : 'text-gray-700 hover:text-primary-700' }}">Prestasi Kumulatif</a>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </li>
                            @endif

                            @if(hasAccess('lantikan-urusetia-negeri') || hasAccess('lantikan-pym-mc'))
                                <li>
                                    <button
                                        id="lantikanDropdownLink"
                                        data-dropdown-toggle="lantikanDropdown"
                                        class="flex items-center justify-between w-full py-2 pl-3 pr-4 font-medium  border-b border-gray-100 hover:bg-gray-50 md:hover:bg-transparent md:border-0 md:hover:text-primary-700 md:p-0 md:w-auto
                                        {{ Illuminate\Support\Str::startsWith(Route::currentRouteName(), 'lantikan') ? 'text-primary-700' : 'text-gray-700 hover:text-primary-700' }}"
                                    >
                                        Lantikan
                                        <x-icon name="chevron-down" class="w-4 h-4 ml-1" />
                                    </button>

                                    <div id="lantikanDropdown" class="z-20 hidden font-normal bg-white divide-y divide-gray-100 rounded shadow w-50 " style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate(769px, 52px);" data-popper-placement="bottom">
                                        <ul class="py-1 text-sm text-gray-700 " aria-labelledby="dropdownLargeButton">
                                            @if(hasAccess('lantikan-urusetia-negeri'))
                                                <li>
                                                    <a href="{{ route('lantikan.urusetia-negeri') }}" class="block px-4 py-2 hover:bg-gray-100 {{ Route::currentRouteName() === 'lantikan.urusetia-negeri' ? 'text-primary-700' : 'text-gray-700 hover:text-primary-700' }}">Lantikan Urusetia Negeri</a>
                                                </li>
                                            @endif

                                            @if(hasAccess('lantikan-pym-mc'))
                                                <li>
                                                    <a href="{{ route('lantikan.penilai') }}" class="block px-4 py-2 hover:bg-gray-100 {{ Route::currentRouteName() === 'lantikan.penilai' ? 'text-primary-700' : 'text-gray-700 hover:text-primary-700' }}">Lantikan PYM & PMC</a>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </li>
                            @endif
                            {{-- <li>
                                <a href="#" class="block text-gray-700 hover:text-primary-700">Laporan</a>
                            </li> --}}
                            @if(hasAccess('tetapan-akses-pengguna') || hasAccess('tetapan-info-pyd-pym-pmc') || hasAccess('tetapan-ahli-jtt'))
                                <li>
                                    <button id="tetapanDropdownLink" data-dropdown-toggle="tetapanDropdown" class="flex items-center justify-between w-full py-2 pl-3 pr-4 font-medium  border-b border-gray-100 hover:bg-gray-50 md:hover:bg-transparent md:border-0 md:hover:text-primary-700 md:p-0 md:w-auto
                                        {{ Illuminate\Support\Str::startsWith(Route::currentRouteName(), 'tetapan') ? 'text-primary-700' : 'text-gray-700 hover:text-primary-700' }}">
                                        Tetapan
                                        <x-icon name="chevron-down" class="w-4 h-4 ml-1" />
                                    </button>

                                    <div id="tetapanDropdown" class="z-20 hidden font-normal bg-white divide-y divide-gray-100 rounded shadow w-50 " style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate(769px, 52px);" data-popper-placement="bottom">
                                        <ul class="py-1 text-sm text-gray-700 " aria-labelledby="dropdownLargeButton">
                                            @if(hasAccess('tetapan-akses-pengguna'))
                                                <li>
                                                    <a href="{{ route('tetapan.user-access') }}" class="block px-4 py-2 hover:bg-gray-100 {{ Route::currentRouteName() === 'tetapan.user-access' ? 'text-primary-700' : 'text-gray-700 hover:text-primary-700' }}">Akses Pengguna</a>
                                                </li>
                                            @endif

                                            @if(hasAccess('tetapan-info-pyd-pym-pmc'))
                                                <li>
                                                    <a href="{{ route('tetapan.info-pegawai') }}" class="block px-4 py-2 hover:bg-gray-100 {{ Route::currentRouteName() === 'tetapan.info-pegawai' ? 'text-primary-700' : 'text-gray-700 hover:text-primary-700' }}">Info PYD, PYM & PMC</a>
                                                </li>
                                            @endif

                                            @if(hasAccess('tetapan-ahli-jtt'))
                                                <li>
                                                    <a href="{{ route('tetapan.ahli-jtt') }}" class="block px-4 py-2 hover:bg-gray-100 {{ Route::currentRouteName() === 'tetapan.ahli-jtt' ? 'text-primary-700' : 'text-gray-700 hover:text-primary-700' }}">Ahli JTT</a>
                                                </li>
                                            @endif
                                            <li>
                                                <a href="{{ route('tetapan.meeting-room') }}" class="block px-4 py-2 hover:bg-gray-100 {{ Route::currentRouteName() === 'tetapan.meeting-room' ? 'text-primary-700' : 'text-gray-700 hover:text-primary-700' }}">Bilik Mesyuarat</a>
                                            </li>
                                            <li>
                                                <a href="{{ route('tetapan.peratusan-kriteria') }}" class="block px-4 py-2 hover:bg-gray-100 {{ Route::currentRouteName() === 'tetapan.peratusan-kriteria' ? 'text-primary-700' : 'text-gray-700 hover:text-primary-700' }}">Peratusan PMGi</a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                            @endif
                        </ul>
                    </div>
                @endauth
            </div>

            <div class="flex items-center justify-between lg:order-2">
                @auth
                    <div class="hidden mr-3 -mb-1 sm:block">
                        <span></span>
                    </div>

                    <button type="button" data-dropdown-toggle="notification-dropdown" class="p-2 text-gray-500 rounded-lg hover:text-gray-900 hover:bg-gray-100 focus:ring-4 focus:ring-gray-300 ">
                        <span class="sr-only">View notifications</span>
                        <x-icon solid name="bell" class="w-6 h-6" />
                    </button>

                    <div class="z-50 hidden max-w-sm my-4 overflow-hidden text-base list-none bg-white divide-y divide-gray-100 rounded shadow-lg " id="notification-dropdown" style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate(1584px, 62px);" data-popper-placement="bottom">
                        <div class="block px-4 py-2 text-base font-medium text-center text-gray-700 bg-gray-50 ">
                            Notifications
                        </div>
                        <div>
                            <a href="#" class="flex px-4 py-3 border-b hover:bg-gray-100 ">
                                <div class="flex-shrink-0">
                                    <img class="rounded-full w-11 h-11" src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/avatars/bonnie-green.png" alt="Bonnie Green avatar">
                                    <div class="absolute flex items-center justify-center w-5 h-5 ml-6 -mt-5 border border-white rounded-full bg-primary-700 ">
                                        <x-icon solid name="inbox-in" class="w-3 h-3 text-white" />
                                    </div>
                                </div>
                                <div class="w-full pl-3">
                                    <div class="text-gray-500 font-normal text-sm mb-1.5 ">New message from <span class="font-semibold text-gray-900 ">Bonnie Green</span>: "Hey, what's up? All set for the presentation?"</div>
                                    <div class="text-xs font-medium text-primary-700 ">a few moments ago</div>
                                </div>
                            </a>
                            <a href="#" class="flex px-4 py-3 border-b hover:bg-gray-100 ">
                                <div class="flex-shrink-0">
                                    <img class="rounded-full w-11 h-11" src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/avatars/jese-leos.png" alt="Jese Leos avatar">
                                    <div class="absolute flex items-center justify-center w-5 h-5 ml-6 -mt-5 bg-gray-900 border border-white rounded-full ">
                                        <x-icon solid name="user-add" class="w-3 h-3 text-white" />
                                    </div>
                                </div>
                                <div class="w-full pl-3">
                                    <div class="text-gray-500 font-normal text-sm mb-1.5 "><span class="font-semibold text-gray-900 ">Jese leos</span> and <span class="font-medium text-gray-900 ">5 others</span> started following you.</div>
                                    <div class="text-xs font-medium text-primary-700 ">10 minutes ago</div>
                                </div>
                            </a>
                            <a href="#" class="flex px-4 py-3 border-b hover:bg-gray-100 ">
                                <div class="flex-shrink-0">
                                    <img class="rounded-full w-11 h-11" src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/avatars/joseph-mcfall.png" alt="Joseph McFall avatar">
                                    <div class="absolute flex items-center justify-center w-5 h-5 ml-6 -mt-5 bg-red-600 border border-white rounded-full ">
                                        <x-icon solid name="heart" class="w-3 h-3 text-white" />
                                    </div>
                                </div>
                                <div class="w-full pl-3">
                                    <div class="text-gray-500 font-normal text-sm mb-1.5 "><span class="font-semibold text-gray-900 ">Joseph Mcfall</span> and <span class="font-medium text-gray-900 ">141 others</span> love your story. See it and view more stories.</div>
                                    <div class="text-xs font-medium text-primary-700 ">44 minutes ago</div>
                                </div>
                            </a>
                            <a href="#" class="flex px-4 py-3 border-b hover:bg-gray-100 ">
                                <div class="flex-shrink-0">
                                    <img class="rounded-full w-11 h-11" src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/avatars/roberta-casas.png" alt="Roberta Casas image">
                                    <div class="absolute flex items-center justify-center w-5 h-5 ml-6 -mt-5 bg-green-400 border border-white rounded-full ">
                                        <x-icon solid name="chat-alt" class="w-3 h-3 text-white" />
                                    </div>
                                </div>
                                <div class="w-full pl-3">
                                    <div class="text-gray-500 font-normal text-sm mb-1.5 "><span class="font-semibold text-gray-900 ">Leslie Livingston</span> mentioned you in a comment: <span class="font-medium text-primary-700">@bonnie.green</span> what do you say?</div>
                                    <div class="text-xs font-medium text-primary-700 ">1 hour ago</div>
                                </div>
                            </a>
                            <a href="#" class="flex px-4 py-3 hover:bg-gray-100 ">
                                <div class="flex-shrink-0">
                                    <img class="rounded-full w-11 h-11" src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/avatars/robert-brown.png" alt="Robert image">
                                    <div class="absolute flex items-center justify-center w-5 h-5 ml-6 -mt-5 bg-purple-500 border border-white rounded-full ">
                                        <x-icon solid name="video-camera" class="w-3 h-3 text-white" />
                                    </div>
                                </div>
                                <div class="w-full pl-3">
                                    <div class="text-gray-500 font-normal text-sm mb-1.5 "><span class="font-semibold text-gray-900 ">Robert Brown</span> posted a new video: Glassmorphism - learn how to implement the new design trend.</div>
                                    <div class="text-xs font-medium text-primary-700 ">3 hours ago</div>
                                </div>
                            </a>
                        </div>
                        <a href="#" class="block py-2 text-base font-normal text-center text-gray-900 bg-gray-50 hover:bg-gray-100">
                            <div class="inline-flex items-center ">
                                <x-icon solid name="eye" class="w-5 h-5 mr-2" />
                                View all
                            </div>
                        </a>
                    </div>

                    <button type="button" class="flex flex-shrink-0 mx-3 text-sm bg-gray-800 rounded-full md:mr-0 focus:ring-4 focus:ring-gray-300 " id="userMenuDropdownButton" aria-expanded="false" data-dropdown-toggle="userMenuDropdown">
                        <span class="sr-only">Open user menu</span>
                        <img class="w-8 h-8 rounded-full" src="https://flowbite.com/docs/images/people/profile-picture-5.jpg" alt="user photo">
                    </button>

                    <div class="z-50 hidden w-56 my-4 text-base list-none bg-white divide-y divide-gray-100 rounded shadow " id="userMenuDropdown" style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate(1712px, 58px);" data-popper-placement="bottom">
                        <div class="px-4 py-3">
                            <span class="block text-sm font-semibold text-gray-900 ">{{ auth()->user()->username }}</span>
                            <span class="block text-sm font-light text-gray-500 truncate ">{{ auth()->user()->bankOfficer->email }}</span>
                        </div>
                        {{-- <ul class="py-1 font-light text-gray-500 " aria-labelledby="userMenuDropdownButton">
                            <li>
                                <a href="#" class="block px-4 py-2 text-sm hover:bg-gray-100 ">My profile</a>
                            </li>
                            <li>
                                <a href="#" class="block px-4 py-2 text-sm hover:bg-gray-100 ">Account settings</a>
                            </li>
                        </ul>
                        <ul class="py-1 font-light text-gray-500 " aria-labelledby="userMenuDropdownButton">
                            <li>
                                <a href="#" class="flex items-center px-4 py-2 text-sm hover:bg-gray-100 ">
                                    <x-icon solid name="heart" class="w-5 h-5 mr-2 text-gray-400" />My likes</a>
                            </li>
                            <li>
                                <a href="#" class="flex items-center px-4 py-2 text-sm hover:bg-gray-100 ">
                                    <x-icon solid name="collection" class="w-5 h-5 mr-2 text-gray-400" /> Collections</a>
                            </li>
                        </ul> --}}
                        <ul class="py-1 font-light text-gray-500 " aria-labelledby="dropdown">
                            <li>
                                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="block px-4 py-2 text-sm hover:bg-gray-100 " role="menuitem" tabindex="-1" id="user-menu-item-1">Sign out</a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>

                    <button type="button" id="toggleMobileMenuButton" data-collapse-toggle="toggleMobileMenu" class="items-center p-2 text-gray-500 rounded-lg md:ml-2 lg:hidden hover:text-gray-900 hover:bg-gray-100 focus:ring-4 focus:ring-gray-300">
                        <span class="sr-only">Open menu</span>
                        <x-icon solid name="menu" class="w-6 h-6" />
                    </button>
                @endauth

                @guest
                <div class="items-center justify-between hidden w-full lg:flex lg:w-auto lg:order-1">
                    <ul class="flex flex-col mt-4 space-x-6 text-sm font-medium lg:flex-row xl:space-x-8 lg:mt-0">
                        <li>
                            <a href="/" class="block text-gray-700 rounded hover:text-primary-700" aria-current="page">Log Masuk</a>
                        </li>
                    </ul>
                </div>
                @endguest
            </div>
        </div>
    </nav>

    <nav class="bg-white">
        <ul id="toggleMobileMenu" class="flex-col hidden w-full pt-16 mt-0 text-sm font-medium lg:hidden">
            <li class="block border-b ">
                <a href="#" class="block px-4 py-3 text-gray-900 lg:py-0 lg:hover:underline lg:px-0" aria-current="page">Home</a>
            </li>
            <li class="block border-b ">
                <a href="#" class="block px-4 py-3 text-gray-900 lg:py-0 lg:hover:underline lg:px-0">Messages</a>
            </li>
            <li class="block border-b ">
                <a href="#" class="block px-4 py-3 text-gray-900 lg:py-0 lg:hover:underline lg:px-0">Profile</a>
            </li>
            <li class="block border-b ">
                <a href="#" class="block px-4 py-3 text-gray-900 lg:py-0 lg:hover:underline lg:px-0">Settings</a>
            </li>
            <li class="block border-b ">
                <button type="button" data-collapse-toggle="dropdownMobileNavbar" class="flex items-center justify-between w-full px-4 py-3 text-gray-900 lg:py-0 lg:hover:underline lg:px-0">Dropdown <svg class="w-6 h-6 text-gray-500 " aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg></button>
                <ul id="dropdownMobileNavbar" class="hidden">
                    <li class="block border-t border-b ">
                        <a href="#" class="block px-4 py-3 text-gray-900 lg:py-0 lg:hover:underline lg:px-0">Item 1</a>
                    </li>
                    <li class="block border-b ">
                        <a href="#" class="block px-4 py-3 text-gray-900 lg:py-0 lg:hover:underline lg:px-0">Item 2</a>
                    </li>
                    <li class="block">
                        <a href="#" class="block px-4 py-3 text-gray-900 lg:py-0 lg:hover:underline lg:px-0">Item 3</a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
</header>
