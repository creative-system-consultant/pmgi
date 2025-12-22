<div class="flex flex-col p-4 my-4 mt-4 bg-white rounded-lg border border-gray-200 shadow-sm sm:p-6">
    <div class="mb-3">
        <p class="text-base font-normal text-gray-500">
            Soalan Lazim untuk <strong class="text-gray-900">{{ strtoupper($userJawatan) }}</strong>
        </p>
    </div>

    <div class="overflow-x-auto">
        <div class="inline-block min-w-full align-middle">
            <div class="overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr class="bg-gray-100">
                            <th class="p-2 text-xs font-medium tracking-wider text-left text-gray-500 uppercase border border-black">
                                File Name
                            </th>
                            <th class="p-2 text-xs font-medium tracking-wider text-center text-gray-500 uppercase border border-black">
                                Officer Level
                            </th>
                            <th class="p-2 text-xs font-medium tracking-wider text-center text-gray-500 uppercase border border-black">
                                Tindakan
                            </th>
                        </tr>
                    </thead>

                    <tbody class="bg-white">
                        @forelse ($documents as $doc)
                            <tr>
                                <td class="p-2 text-sm text-gray-500 border border-black">
                                    {{ $doc->file_name }}
                                </td>
                                <td class="p-2 text-sm text-center text-gray-500 border border-black">
                                    {{ strtoupper($doc->officer_lvl) }}
                                </td>
                                <td class="p-2 text-sm text-center text-gray-500 border border-black">
                                    @php
                                        $url = \Illuminate\Support\Facades\Storage::url($doc->file_path);
                                    @endphp

                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ $url }}" target="_blank"
                                        class="inline-flex items-center px-3 py-1 text-xs font-medium text-white rounded bg-primary-700 hover:bg-primary-800">
                                            View
                                        </a>

                                        <a href="{{ $url }}" download
                                        class="inline-flex items-center px-3 py-1 text-xs font-medium text-white rounded bg-gray-700 hover:bg-gray-800">
                                            Download
                                        </a>
                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="7"
                                    class="p-3 text-sm font-normal text-center text-gray-500 whitespace-nowrap border border-black">
                                    Tiada dokumen untuk jawatan {{ strtoupper($userJawatan) }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
