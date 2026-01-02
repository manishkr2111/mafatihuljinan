@extends('layouts.admin')
@section('title', 'Upload Audio')

@section('content')

<!-- Upload Audio Section -->
<div class="mt-1">
    <div class="bg-white rounded shadow p-5 mb-6">
        <h2 class="text-lg font-semibold text-[#034E7A] mb-4">Upload Audio File</h2>

        <!-- Upload Form -->
        <form action="{{ route('admin.uploadAudio') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <input type="hidden" name="language" value="{{$language}}">

            <div class="mb-4">
                <div class="mb-4 flex items-center gap-4">
                    <div>
                        <div class="mb-4">
                            <label for="languageSelect" class="block text-sm font-medium text-[#034E7A] mb-2">Language:</label>
                            <select id="languageSelect"
                                onchange="changeLanguage(this.value)"
                                class="w-full border border-gray-300 rounded px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-[#034E7A]">
                                @foreach(validLanguages() as $lang)
                                <option value="{{ $lang }}" {{ $language == $lang ? 'selected' : '' }}>
                                    {{ ucfirst($lang) }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-[#034E7A] mb-2">Select Post Type</label>
                            <select name="post_type"
                                class="w-full border border-gray-300 rounded px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-[#034E7A]"
                                required>
                                <option value="">Select Type</option>

                                @foreach(commonPostTypeOptions() as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>

                            @error('post_type')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-[#034E7A] mb-2">Choose Audio File (MP3, WAV, AAC)</label>
                <input
                    type="file"
                    name="audio"
                    accept=".mp3, .wav, .aac"
                    class="w-full border border-gray-300 rounded px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-[#034E7A]"
                    required>
                @error('audio')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <button
                type="submit"
                class="bg-[#034E7A] text-white px-5 py-2 rounded hover:bg-[#02629B] transition">
                Upload Audio
            </button>
        </form>

        <!-- Upload Result Section -->
        @if(session('audio_url'))
        <div class="bg-white rounded shadow p-5 mt-6">
            <h2 class="text-lg font-semibold text-[#034E7A] mb-3">Uploaded Audio</h2>

            <p class="text-sm text-gray-700 mb-3">File uploaded successfully. URL:</p>

            <div class="p-3 border rounded bg-gray-50 flex justify-between items-center">
                <a href="{{ session('audio_url') }}" target="_blank" class="text-[#034E7A] underline">
                    {{ session('audio_url') }}
                </a>

                <button onclick="copyToClipboard('{{ session('audio_url') }}')"
                    class="ml-4 text-sm px-3 py-1 rounded bg-blue-600 text-white hover:bg-blue-700 transition">
                    Copy URL
                </button>
            </div>

            <audio controls class="mt-4 w-full">
                <source src="{{ session('audio_url') }}" type="audio/mpeg">
                Your browser does not support the audio element.
            </audio>
        </div>
        @endif
    </div>
</div>

<hr>

<!-- All Uploaded Audio Files -->
@if($filesPaginated->count() > 0)
<div class="bg-white rounded shadow p-5 mt-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-semibold text-[#034E7A]">All Uploaded Audio Files</h2>

        <!-- Per Page Selector -->
        <div class="flex items-center gap-2">
            <label for="perPageSelect" class="text-sm text-gray-700">Show:</label>
            <select id="perPageSelect"
                onchange="changePerPage(this.value)"
                class="border border-gray-300 rounded px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-[#034E7A]">
                <option value="25" {{ request('per_page', 25) == 25 ? 'selected' : '' }}>25</option>
                <option value="50" {{ request('per_page', 25) == 50 ? 'selected' : '' }}>50</option>
                <option value="100" {{ request('per_page', 25) == 100 ? 'selected' : '' }}>100</option>
                <option value="150" {{ request('per_page', 25) == 150 ? 'selected' : '' }}>150</option>
                <option value="200" {{ request('per_page', 25) == 200 ? 'selected' : '' }}>200</option>
            </select>
            <span class="text-sm text-gray-700">per page</span>
        </div>
    </div>

    <!-- Search Box -->
    <div class="mb-4">
        <input
            type="text"
            id="searchInput"
            placeholder="Search by filename or URL..."
            class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#034E7A]"
            onkeyup="filterAudioFiles()">
    </div>

    <!-- Audio Files Container -->
    <div id="audioFilesContainer">
        @foreach($filesPaginated as $file)
        <div class="audio-file-item mb-4 border-b pb-3" data-filename="{{ strtolower($file['name']) }}" data-url="{{ strtolower($file['url']) }}">
            <p class="text-sm font-medium text-[#034E7A]">{{ $file['name'] }}</p>

            <audio controls class="mt-2 w-full">
                <source src="{{ $file['url'] }}" type="audio/mpeg">
            </audio>

            <div class="mt-2 flex items-center gap-4">

                <!-- Open File -->
                <a href="{{ $file['url'] }}" target="_blank" class="text-[#034E7A] underline text-sm mb-4">
                    Open File
                </a>

                <!-- Copy URL -->
                <button onclick="copyToClipboard('{{ $file['url'] }}')"
                    class="text-sm px-3 py-1 mb-4 rounded bg-blue-600 text-white hover:bg-blue-700 transition">
                    Copy URL
                </button>

                <!-- Delete Button -->
                <form action="{{ route('admin.deleteAudio') }}" method="POST" onsubmit="return confirm('Delete this audio?');">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="file_name" value="{{ $file['name'] }}">
                    <button type="submit"
                        class="text-sm px-3 py-1 rounded bg-red-600 text-white hover:bg-red-700 transition">
                        Delete
                    </button>
                </form>

            </div>
        </div>
        @endforeach
    </div>

    <!-- No Results Message -->
    <div id="noResults" class="hidden text-center py-4 text-gray-500">
        No audio files found matching your search.
    </div>

    <!-- Pagination Links -->
    <div class="mt-6 flex justify-center" id="paginationContainer">
        <nav aria-label="Page navigation">
            <ul class="inline-flex items-center -space-x-px flex-wrap gap-4">

                {{-- Previous Page Link --}}
                @if ($filesPaginated->onFirstPage())
                <li>
                    <span class="px-3 py-2 ml-0 leading-tight text-gray-400 bg-gray-200 border border-gray-300 rounded-l-lg cursor-not-allowed select-none">
                        Previous
                    </span>
                </li>
                @else
                <li>
                    <a href="{{ $filesPaginated->appends(['per_page' => request('per_page', 50)])->previousPageUrl() }}"
                        class="px-3 py-2 ml-0 leading-tight text-[#034E7A] bg-white border border-gray-300 rounded-l-lg hover:bg-[#034E7A] hover:text-white transition">
                        Previous
                    </a>
                </li>
                @endif

                {{-- Page Numbers --}}
                @foreach ($filesPaginated->appends(['per_page' => request('per_page', 50)])->getUrlRange(1, $filesPaginated->lastPage()) as $page => $url)
                @if ($page == $filesPaginated->currentPage())
                <li>
                    <span class="px-3 py-2 leading-tight bg-[#034E7A] text-white border border-gray-300">
                        {{ $page }}
                    </span>
                </li>
                @else
                <li>
                    <a href="{{ $url }}"
                        class="px-3 py-2 leading-tight text-[#034E7A] bg-white border border-gray-300 hover:bg-[#034E7A] hover:text-white transition">
                        {{ $page }}
                    </a>
                </li>
                @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($filesPaginated->hasMorePages())
                <li>
                    <a href="{{ $filesPaginated->appends(['per_page' => request('per_page', 50)])->nextPageUrl() }}"
                        class="px-3 py-2 leading-tight text-[#034E7A] bg-white border border-gray-300 rounded-r-lg hover:bg-[#034E7A] hover:text-white transition">
                        Next
                    </a>
                </li>
                @else
                <li>
                    <span class="px-3 py-2 leading-tight text-gray-400 bg-gray-200 border border-gray-300 rounded-r-lg cursor-not-allowed select-none">
                        Next
                    </span>
                </li>
                @endif

            </ul>
        </nav>
    </div>

</div>
@else
<div class="bg-white rounded shadow p-5 mt-6">
    <h2 class="text-lg font-semibold text-[#034E7A] mb-3">No Uploaded Audio Files</h2>
</div>
@endif

@endsection

<!-- Scripts -->
<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            alert("URL copied to clipboard!");
        });
    }

    function filterAudioFiles() {
        const searchInput = document.getElementById('searchInput');
        const filter = searchInput.value.toLowerCase();
        const audioItems = document.querySelectorAll('.audio-file-item');
        const noResults = document.getElementById('noResults');
        const paginationContainer = document.getElementById('paginationContainer');

        let visibleCount = 0;

        audioItems.forEach(item => {
            const filename = item.getAttribute('data-filename');
            const url = item.getAttribute('data-url');

            // Check if search term matches filename or URL
            if (filename.includes(filter) || url.includes(filter)) {
                item.style.display = '';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });

        // Show/hide no results message
        if (visibleCount === 0) {
            noResults.classList.remove('hidden');
        } else {
            noResults.classList.add('hidden');
        }

        // Hide pagination when searching
        if (filter.length > 0) {
            paginationContainer.style.display = 'none';
        } else {
            paginationContainer.style.display = 'flex';
        }
    }

    function changePerPage(perPage) {
        const currentUrl = new URL(window.location.href);
        currentUrl.searchParams.set('per_page', perPage);
        currentUrl.searchParams.delete('page'); // Reset to page 1
        window.location.href = currentUrl.toString();
    }
</script>

<script>
    function changeLanguage(lang) {
        const currentUrl = new URL(window.location.href);
        currentUrl.searchParams.set('language', lang);
        currentUrl.searchParams.delete('page'); // reset pagination
        window.location.href = currentUrl.toString();
    }
</script>