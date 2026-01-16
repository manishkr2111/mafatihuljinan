@extends('layouts.admin')
@section('title', 'Tafsir')
@section('content')
<div class="mt-2">
    <!-- Top controls: Create + Filter + Count + Search -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 space-y-3 md:space-y-0">
        <!-- Left side: Create button -->
        <a href="{{ route('admin.tafsir.create') }}"
            class="bg-[#034E7A] text-white px-3 py-1 rounded hover:bg-[#02629B] transition w-fit">
            Create Tafsir
        </a>

        <!-- Right side controls -->
        <div class="flex flex-wrap items-center gap-4">
            <!-- Search by Title -->
            <div class="flex items-center">
                <label for="searchTitle" class="mr-2 font-medium text-[#034E7A]">Search Title:</label>
                <input type="text" id="searchTitle" placeholder="Enter title..."
                    class="border rounded px-2 py-1 focus:outline-none focus:ring-2 focus:ring-[#034E7A]">
            </div>

            <!-- Language Filter -->
            <div class="flex items-center">
                <label for="languageFilter" class="mr-2 font-medium text-[#034E7A]">Language:</label>
                <select id="languageFilter"
                    class="border rounded px-2 py-1 focus:outline-none focus:ring-2 focus:ring-[#034E7A]">
                    <option value="">All</option>
                    @foreach(validLanguages() as $language)
                    <option value="{{ $language }}">{{ ucfirst($language) }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Total Count -->
            <div class="font-medium text-[#034E7A]">
                Total: <span id="tafsirCount"></span>
            </div>
        </div>
    </div>

    <!-- Tafsir Table -->
    <div class="overflow-x-auto bg-white rounded shadow">
        <table class="min-w-full border-collapse" id="tafsirTable">
            <thead>
                <tr class="bg-[#034E7A] text-white text-left">
                    <th class="px-5 py-1 border-b border-gray-151">#</th>
                    <th class="px-2 py-1 border-b border-gray-151">Title</th>
                    <th class="px-2 py-1 border-b border-gray-151">Post Type</th>
                    <th class="px-2 py-1 border-b border-gray-151">Language</th>
                    <th class="px-2 py-1 border-b border-gray-151">Action</th>
                </tr>
            </thead>
            <tbody>
                @php $counter = 1; @endphp
                @foreach($tafsirs as $tafsir)
                <tr class="border-b border-gray-200 hover:bg-[#E6F0F8]">
                    <td class="px-6 py-3 border text-[#034E7A] font-medium">{{ $counter++ }}</td>
                    <td class="px-6 py-3 border text-[#034E7A] tafsir-title">{{ $tafsir->title }}</td>
                    <td class="px-6 py-3 border text-[#034E7A]">
                        {{ commonPostTypeOptions()[$tafsir->post_type] ?? '-' }}
                    </td>
                    <td class="px-6 py-3 border text-[#034E7A] tafsir-language">{{ $tafsir->language }}</td>
                    <td class="px-6 py-3 border flex gap-2">
                        <a href="{{ route('admin.tafsir.show', $tafsir->id) }}" target="_blank"
                            class="text-white bg-[#034E7A] px-3 py-1 rounded hover:bg-[#02629B] transition">
                            View Here
                        </a>
                        <a href="{{ route('admin.tafsir.edit', $tafsir->id) }}" target="_blank"
                            class="text-white bg-[#034E7A] px-3 py-1 rounded hover:bg-[#02629B] transition">
                            Edit
                        </a>
                        <form action="{{ route('admin.tafsir.destroy', $tafsir->id) }}" method="POST"
                            class="inline" onsubmit="return confirm('Are you sure you want to delete this Tafsir?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                            class="text-white bg-red-800 px-3 py-1 rounded hover:bg-red-400 transition">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Filter + Search + Count JS -->
<script>
    const languageFilter = document.getElementById('languageFilter');
    const searchTitle = document.getElementById('searchTitle');
    const tafsirCount = document.getElementById('tafsirCount');

    // Function to update table visibility based on search & filter
    function filterTafsirs() {
        const selectedLang = languageFilter.value.toLowerCase();
        const searchText = searchTitle.value.toLowerCase();
        const rows = document.querySelectorAll('#tafsirTable tbody tr');
        let visibleCount = 0;

        rows.forEach(row => {
            const lang = row.querySelector('.tafsir-language').textContent.toLowerCase();
            const title = row.querySelector('.tafsir-title').textContent.toLowerCase();

            const matchesLang = !selectedLang || lang === selectedLang;
            const matchesTitle = !searchText || title.includes(searchText);

            const show = matchesLang && matchesTitle;
            row.style.display = show ? '' : 'none';
            if (show) visibleCount++;
        });

        tafsirCount.textContent = visibleCount;
    }

    // Event listeners
    languageFilter.addEventListener('change', filterTafsirs);
    searchTitle.addEventListener('keyup', filterTafsirs);

    // Initialize count on page load
    document.addEventListener('DOMContentLoaded', filterTafsirs);
</script>
@endsection