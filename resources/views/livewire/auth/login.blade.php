<div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen pt:mt-0 bg-dots">
    <div class="mb-8">
        <a href="{{ route('home') }}" class="flex items-center justify-center">
            <x-logo class="h-24" />
        </a>
        <div class="flex flex-col items-center justify-center text-center">
            <h1 class="mb-2 text-4xl font-extrabold leading-none tracking-tight text-gray-900 ">Performance Monitoring Guidelines</h1>
            <h1 class="text-4xl font-extrabold text-gray-900 "><span class="text-transparent bg-clip-text bg-gradient-to-r to-emerald-600 from-sky-400">PMG-i</span> </h1>
        </div>
    </div>

    <!-- Card -->
    <div class="w-full max-w-sm p-6 space-y-8 bg-white rounded-lg shadow-xl sm:p-8 ">
        <div class="flex justify-center">
            <h2 class="text-2xl font-bold text-gray-900 ">
                Log Masuk
            </h2>
        </div>
        <form class="mt-8 space-y-6" wire:submit.prevent="authenticate">
            <div>
                <label for="text" class="block mb-2 text-sm font-medium text-gray-900 ">User ID</label>
                <input wire:model="userId" type="text" name="text" id="text" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 @error('userId') border-red-300 text-red-900 placeholder-red-300 focus:border-red-300 focus:ring-red @enderror" placeholder="ABCD1234" required>

                @error('userId')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="password" class="block mb-2 text-sm font-medium text-gray-900 ">Kata Laluan</label>
                <input wire:model="password" type="password" name="password" id="password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 @error('password') border-red-300 text-red-900 placeholder-red-300 focus:border-red-300 focus:ring-red @enderror" @if($isProduction) required @endif>

                @error('password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-start">
                <!-- Checkbox -->
                <div class="relative flex items-start">
                    <div class="flex items-center h-5">
                        <input 
                            type="checkbox" 
                            class="transition duration-100 ease-in-out rounded form-checkbox border-secondary-300 text-primary-600 focus:ring-primary-600 focus:border-primary-400 dark:border-secondary-500 dark:checked:border-secondary-600 dark:focus:ring-secondary-600 dark:focus:border-secondary-500 dark:bg-secondary-600 dark:text-secondary-600 dark:focus:ring-offset-secondary-800" 
                            wire:model.live="tnc" 
                            name="tnc"
                        >
                    </div>
                </div>

                <!-- Link -->
                <div class="ml-2 text-sm">
                    <div 
                        class="text-blue-500 cursor-pointer hover:underline" 
                        wire:click="openTncCard"
                    >
                        Perakuan pegawai mengambil maklum berhubung prestasi kerja
                    </div>
                </div>
            </div>

            <div class="flex items-start">
                <!-- Checkbox -->
                <div class="relative flex items-start">
                    <div class="flex items-center h-5">
                        <input 
                            type="checkbox" 
                            class="transition duration-100 ease-in-out rounded form-checkbox border-secondary-300 text-primary-600 focus:ring-primary-600 focus:border-primary-400 dark:border-secondary-500 dark:checked:border-secondary-600 dark:focus:ring-secondary-600 dark:focus:border-secondary-500 dark:bg-secondary-600 dark:text-secondary-600 dark:focus:ring-offset-secondary-800" 
                            wire:model.live="tnc2" 
                            name="tnc2"
                        >
                    </div>
                </div>

                <!-- Link -->
                <div class="ml-2 text-sm">
                    <div 
                        class="text-blue-500 cursor-pointer hover:underline" 
                        wire:click="openTnc2Card"
                    >
                        Pernyataan Akuan Kerahsiaan (Non-Dislosure Agreement)
                    </div>
                </div>
            </div>
            
            <div class="flex justify-center">
                <button type="submit"
                        class="w-full px-5 py-3 text-base text-center text-white rounded-lg font-xs
                        @if($tnc && $tnc2) bg-primary-700 hover:bg-primary-800 focus:ring-primary-300
                        @else bg-gray-400 cursor-not-allowed @endif focus:ring-4 sm:w-auto"
                        @if(!$tnc || !$tnc2) disabled @endif>
                    Log Masuk
                </button>
            </div>
        </form>
    </div>

    <x-modal.card title="Perakuan pegawai mengambil maklum berhubung prestasi kerja" align="center" blur wire:model.defer="tncModal">
        <p>Saya akur dan ambil maklum prestasi kerja saya yang dibentangkan dan sedar bahawa tindakan tatatertib boleh dikenakan ke atas saya sekiranya prestasi kerja saya tidak mencapai tahap minimum yang ditetapkan.</p>
    </x-modal.card>

    <x-modal.card title="Perakuan pegawai mengambil maklum berhubung prestasi kerja" align="center" blur wire:model.defer="tnc2Modal">
        <p>Dengan ini, saya mengakui dan bersetuju bahawa sebarang maklumat yang diperoleh melalui sistem ini adalah sulit dan hanya untuk kegunaan rasmi. Saya bersetuju untuk tidak mendedahkan, berkongsi atau menggunakan sebarang maklumat yang diperoleh di sini kepada mana-mana pihak ketiga tanpa kebenaran bertulis daripada pihak yang diberi kuasa. Kegagalan mematuhi perakuan ini boleh mengakibatkan tindakan undang-undang atau tindakan disiplin dalaman termasuk penamatan kepada akses sistem ini.</p>
    </x-modal.card>
</div>
