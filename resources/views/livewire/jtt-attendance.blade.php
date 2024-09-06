<main>
    <div class="px-4 pt-6 2xl:px-0">
        <div class="p-4 my-4 bg-white border border-gray-200 rounded-lg shadow-sm sm:p-6 ">
            <div class="flex flex-col items-center justify-center">
                @if($status == 'success')
                <img class="h-96" src="{{ asset('image/animation/success.gif') }}" alt="success">
                @else
                <img class="h-96" src="{{ asset('image/animation/failed.gif') }}" alt="success">
                @endif
                <h3 class="text-3xl font-bold text-gray-900 ">{{ $title }}</h3>
                <p class="my-4 text-gray-600">{{ $subtitle }}</p>
            </div>
        </div>
    </div>
</main>
