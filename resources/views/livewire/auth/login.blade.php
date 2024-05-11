<div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen pt:mt-0 bg-dots">
    <div class="mb-8">
        <a href="{{ route('home') }}" class="flex items-center justify-center">
            <x-logo class="h-24" />
        </a>
        <div class="flex flex-col items-center justify-center ">
            <h1 class="mb-2 text-4xl font-extrabold leading-none tracking-tight text-gray-900 ">Performance Monitoring Guidelines</h1>
            <h1 class="text-4xl font-extrabold text-gray-900 "><span class="text-transparent bg-clip-text bg-gradient-to-r to-emerald-600 from-sky-400">PMG-i</span> </h1>
        </div>
    </div>

    <!-- Card -->
    <div class="w-full max-w-sm p-6 space-y-8 bg-white rounded-lg shadow-xl sm:p-8 ">
        <div class="flex justify-center">
            <h2 class="text-2xl font-bold text-gray-900 ">
                Sign in
            </h2>
        </div>
        <form class="mt-8 space-y-6" wire:submit.prevent="authenticate">
            <div>
                <label for="email" class="block mb-2 text-sm font-medium text-gray-900 ">Your email</label>
                <input wire:model="email" type="email" name="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 @error('email') border-red-300 text-red-900 placeholder-red-300 focus:border-red-300 focus:ring-red @enderror" placeholder="name@company.com" required>

                @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="password" class="block mb-2 text-sm font-medium text-gray-900 ">Your password</label>
                <input wire:model="password" type="password" name="password" id="password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 @error('password') border-red-300 text-red-900 placeholder-red-300 focus:border-red-300 focus:ring-red @enderror" required>

                @error('password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex justify-center">
                <button type="submit" class="w-full px-5 py-3 text-base text-center text-white rounded-lg font-xs bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 sm:w-auto">
                    Login
                </button>
            </div>
        </form>
    </div>
</div>
