@extends('layouts.admin')
@section('title', 'Event Popups')

@section('content')

<div class="mt-4">

    <!-- Upload Event Popup Section -->
    <div class="bg-white rounded shadow p-5 mb-6">
        <h2 class="text-lg font-semibold text-[#034E7A] mb-4">Upload Event Popup</h2>

        <form action="{{ route('admin.eventpopup.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div class="flex space-x-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Language</label>
                    <select name="language"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#034E7A]">
                        @foreach (validLanguages() as $language)
                        <option value="{{ $language }}">{{ ucfirst($language) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Date</label>
                    <select name="date"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#034E7A]"
                        required>
                        <option value="">Select Date</option>
                        @for($i = 1; $i <= 31; $i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Month</label>
                    <select name="month"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#034E7A]"
                        required>
                        <option value="">Select Month</option>
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Name / Title</label>
                <input type="text" name="title"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#034E7A]"
                    placeholder="Title" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Upload Image</label>
                <input type="file" name="file" accept="image/*"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#034E7A]"
                    required>
            </div>
            <button type="submit"
                class="bg-[#034E7A] text-white px-5 py-2 rounded hover:bg-[#02629B] transition w-full sm:w-auto">
                Upload
            </button>
        </form>
    </div>
    <div>
        <!-- Search Box -->
        <div class="bg-white rounded shadow p-4 mb-1">
            <input type="text" id="searchInput"
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#034E7A]"
                placeholder="Search by Title / Name...">
        </div>
        <!-- Event Popups Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach ($eventPopups as $eventPopup)
            <div class="bg-white rounded shadow p-4 flex flex-col hover:shadow-lg transition max-h-100 event-card"
                data-title="{{ strtolower($eventPopup->title) }}">
                <div class="mb-3 min-h-48">
                    @if($eventPopup->imgurl)
                    <img src="{{ asset('storage/' . $eventPopup->imgurl) }}"
                        alt="Event Image" class="w-full object-cover rounded">
                    @else
                    <div class="w-full h-48 bg-gray-100 flex items-center justify-center rounded text-gray-400">
                        No Image
                    </div>
                    @endif
                </div>

                <h3 class="text-lg font-semibold text-[#034E7A] mb-1 truncate">{{ ucwords($eventPopup->title) }}</h3>
                <p class="text-sm text-gray-600 mb-1 text-[#034E7A]">
                    Date: {{ $eventPopup->date }} - Month: {{ $eventPopup->month }}
                </p>
                <p class="text-sm text-gray-600 mb-3 text-[#034E7A]">Language: {{ $eventPopup->language ?? 'N/A' }}</p>

                <form action="{{ route('admin.eventpopup.destroy', $eventPopup->id) }}" method="POST" class="mt-auto">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition w-full">
                        Delete
                    </button>
                </form>

            </div>
            @endforeach
        </div>
    </div>
</div>
<script>
    document.getElementById('searchInput').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const eventCards = document.querySelectorAll('.event-card');

        eventCards.forEach(card => {
            const title = card.getAttribute('data-title');
            if (title.includes(searchTerm)) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    });
</script>
@endsection