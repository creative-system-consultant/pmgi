<main x-data="{ showPrestasiKumulatif: @entangle('showPrestasiKumulatif'), 
                showRekodPmgi: @entangle('showRekodPmgi') }">

<div class="px-4 pt-6 2xl:px-0">
<div class="p-4 my-4 bg-white rounded-lg border shadow-sm">

    {{-- ===================== HEADER ===================== --}}
    <div class="flex items-center mb-4">
        <h3 class="text-xl font-bold">Ulasan Pegawai Yang Dinilai (PYD)</h3>

        @if($perakuan && auth()->user()->USERID == $sessionSetting->pyd_id)
            <button class="ml-4 px-4 py-2 bg-indigo-700 text-white rounded-lg"
                    wire:click="updates">
                Kemaskini
            </button>
        @endif
    </div>

    {{-- FORM INFO PYD --}}
    @if(!$perakuan)
    <div class="bg-primary-100 border p-6 rounded-lg mb-6">
        <div class="grid grid-cols-3 gap-4 w-[60%]">

            <p class="font-semibold flex items-center">Nama Pegawai Yang Dinilai</p>
            <div class="col-span-2"><x-input wire:model="pydName" disabled /></div>

            <p class="font-semibold flex items-center">Jawatan</p>
            <div class="col-span-2"><x-input wire:model="pydPosition" disabled /></div>

            <p class="font-semibold flex items-center">Nombor Pekerja</p>
            <div class="col-span-2"><x-input wire:model="pydStaffNo" disabled /></div>

            <p class="font-semibold flex items-center">Negeri/Cawangan</p>
            <div class="col-span-2"><x-input wire:model="stateBranch" disabled /></div>
        </div>
    </div>
    @endif


    {{-- ===================== MASALAH YG DIHADAPI ===================== --}}
    <div class="mt-4 w-[70%]">
        <div class="flex items-center bg-lime-300 p-3 rounded-lg">
            <h3 class="mr-4 font-medium">Masalah yang dihadapi :</h3>

            @if($perakuan && auth()->user()->USERID != $sessionSetting->pyd_id)
                <x-select class="flex-1"
                    :options="$problemSelection"
                    option-label="description"
                    option-value="id"
                    wire:model="problem"
                    disabled />
            @else
                <x-select class="flex-1"
                    :options="$problemSelection"
                    option-label="description"
                    option-value="id"
                    wire:model="problem" />
            @endif

            <x-icon solid name="information-circle" class="ml-2 w-6 h-6 cursor-pointer" wire:click="openInfo" />
        </div>


        {{-- ===================== PUNCA MASALAH ===================== --}}
        <div class="my-4">
            <x-textarea label="Nyatakan punca bagi masalah tersebut :"
                        wire:model="reason"
                        :disabled="$perakuan && auth()->user()->USERID != $sessionSetting->pyd_id" />
        </div>


        {{-- ===================== PELAN TINDAKAN ===================== --}}
        <div class="mb-4">
            <x-textarea label="Pelan tindakan untuk meningkatkan prestasi :"
                        wire:model="actionPlan"
                        :disabled="$perakuan && auth()->user()->USERID != $sessionSetting->pyd_id" />
        </div>


        {{-- ===================== ULASAN ===================== --}}
        <div class="mb-6">
            <x-textarea label="Ulasan (Jika ada) :"
                        wire:model="comment"
                        :disabled="$perakuan && auth()->user()->USERID != $sessionSetting->pyd_id" />
        </div>


        {{-- ============================================================
                           LAMPIRAN 1, 2, 3
           ============================================================ --}}
        @if(!$perakuan)

        {{-- ******** Lampiran 1 ******** --}}
        <div class="mb-4">
            <label class="font-semibold">Lampiran 1 (Jika berkaitan) :</label>

            @if($attachment)
                <div wire:ignore>
                    <p class="mt-1 text-sm">
                        Fail sedia ada:
                        <a href="{{ asset('storage/'.$attachment) }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        onclick="event.stopPropagation(); event.preventDefault(); window.open(this.href, '_blank');"
                        class="text-blue-600 underline">
                            {{ basename($attachment) }}
                        </a>
                    </p>
                </div>
            @endif


            <input type="file" wire:model="file1"
                class="mt-2 block w-full border rounded p-2">
        </div>



        {{-- ******** Lampiran 2 ******** --}}
        <div class="mb-4">
            <label class="font-semibold">Lampiran 2 (Jika berkaitan) :</label>

            @if($attachment2)
                <p class="mt-1 text-sm">
                    Fail sedia ada:
                    <a href="{{ asset('storage/'.$attachment2) }}"
                    target="_blank"
                    class="text-blue-600 underline">
                        {{ basename($attachment2) }}
                    </a>
                </p>
            @endif

            <input type="file" wire:model="file2"
                class="mt-2 block w-full border rounded p-2">
        </div>


        {{-- ******** Lampiran 3 ******** --}}
        <div class="mb-4">
            <label class="font-semibold">Lampiran 3 (Jika berkaitan) :</label>

            @if($attachment3)
                <p class="mt-1 text-sm">
                    Fail sedia ada:
                    <a href="{{ asset('storage/'.$attachment3) }}"
                    target="_blank"
                    class="text-blue-600 underline">
                        {{ basename($attachment3) }}
                    </a>
                </p>
            @endif

            <input type="file" wire:model="file3"
                class="mt-2 block w-full border rounded p-2">
        </div>

            {{-- SUBMIT BUTTON --}}
            <button class="px-4 py-2 bg-primary-700 text-white rounded-lg"
                    wire:click="submit">
                Hantar
            </button>

        @endif
    </div>
</div>
</div>

</main>
