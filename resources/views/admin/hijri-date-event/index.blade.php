@extends('layouts.admin')

@section('title', 'Hijri Date / Events')

@section('content')
<div class="mt-2 bg-white p-6 rounded-xl shadow-lg">
    <!-- Error messages -->
    @if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <ul class="list-disc list-inside">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Hijri Date Difference Form -->
    <div class="mb-8 grid grid-cols-1 md:grid-cols-2 gap-8 p-6 bg-white rounded-lg shadow-md">
        <!-- Left: Form -->
        <div>
            <h2 class="text-2xl font-bold text-[#034E7A] mb-6">Set Hijri Date Difference</h2>
            <form action="{{ route('admin.hijri.date.difference.store') }}" method="POST" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[#034E7A] font-medium mb-2">Select Day Difference</label>
                        <select name="day-difference" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#034E7A] focus:border-[#034E7A]">
                            @for($i = -5; $i <= 5; $i++)
                                <option value="{{ $i }}" {{ $i == $datediff ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <input type="hidden" name="rowid" value="0">
                <button type="submit" class="w-full md:w-auto bg-[#034E7A] text-white px-6 py-2 rounded-lg hover:bg-[#02629B] transition-colors font-semibold">Save</button>
            </form>
        </div>

        <!-- Right: Today's Hijri Date -->
        <div class="flex flex-col items-center justify-center bg-[#f0f4f8] rounded-lg p-6">
            <p class="text-gray-600 font-medium mb-2">Today's Hijri Date</p>
            <span class="text-2xl font-bold text-[#034E7A]">{{ $combined_date }}</span>
        </div>
    </div>

    <hr class="my-8">

    <!-- Add Event Form -->
    <h2 class="text-2xl font-bold text-[#034E7A] mb-6">Add Event</h2>
    <form action="{{ route('admin.hijri.date.event.store') }}" method="POST" class="space-y-4">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block font-medium text-[#034E7A] mb-1">Select Date</label>
                <select name="date" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#034E7A]">
                    @for($i = 1; $i <= 30; $i++)
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div>
                <label class="block font-medium text-[#034E7A] mb-1">Select Month</label>
                <select name="month" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#034E7A]" required>
                    @foreach($months as $month)
                    <option value="{{ $month }}">{{ $month }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block font-medium text-[#034E7A] mb-1">Text Color</label>
                <select name="textcolor" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#034E7A]">
                    <option value="">Select Color</option>
                    <option value="Green">Green</option>
                    <option value="Black">Black</option>
                    <option value="Light Black">Light Black</option>
                    <option value="White">White</option>
                </select>
            </div>
            <div>
                <label class="block font-medium text-[#034E7A] mb-1">Language</label>
                <select name="language" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#034E7A]" required>
                    @foreach(validLanguages() as $language)
                    <option value="{{ $language }}">{{ ucfirst($language) }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div>
            <label class="block font-medium text-[#034E7A] mb-1">Event Name</label>
            <input type="text" name="event" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#034E7A]" required>
        </div>

        <input type="hidden" name="rowid" value="0">

        <button type="submit" class="bg-[#034E7A] text-white px-6 py-2 rounded hover:bg-[#02629B] transition">Save</button>
    </form>

    <!-- Events List -->
    <div class="mt-10">
        <h2 class="text-2xl font-bold text-[#034E7A] mb-4">Events List</h2>

        <!-- Filters -->
        <div class="mb-4 flex flex-col md:flex-row gap-4">
            <select id="filterMonth" class="border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#034E7A]" onchange="filterTable()">
                <option value="">-- Filter by Month --</option>
                @foreach($months as $month)
                <option value="{{ strtolower($month) }}">{{ $month }}</option>
                @endforeach
            </select>

            <select id="filterLanguage" class="border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#034E7A]" onchange="filterTable()">
                <option value="">-- Filter by Language --</option>
                @foreach(validLanguages() as $language)
                <option value="{{ $language }}">{{ ucfirst($language) }}</option>
                @endforeach
            </select>

            <input type="text" id="filterEvent" placeholder="Search by Event Name" class="border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#034E7A]" onkeyup="filterTable()">
        </div>

        <div class="overflow-x-auto">
            <table id="eventstable" class="min-w-full border border-gray-200 divide-y divide-gray-200">
                <thead class="bg-[#034E7A] text-white">
                    <tr>
                        <th class="px-4 py-2"><input type="checkbox" class="selectall"></th>
                        <th class="px-4 py-2 text-left">Date</th>
                        <th class="px-4 py-2 text-left">Event</th>
                        <th class="px-4 py-2 text-left">Text Color</th>
                        <th class="px-4 py-2 text-left">Language</th>
                        <th class="px-4 py-2 text-left">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($events as $event)
                    <tr class="hover:bg-gray-50"
                        data-month="{{ strtolower($event->month) }}"
                        data-language="{{ strtolower($event->language) }}"
                        data-event="{{ strtolower($event->event) }}">
                        <td class="px-4 py-2 border"><input type="checkbox" class="checkBoxClass" value="{{ $event->id }}"></td>
                        <td class="px-4 py-2 border">{{ $event->date }} {{ $event->month }}</td>
                        <td class="px-4 py-2 border">{{ $event->event }}</td>
                        <td class="px-4 py-2 border">{{ $event->text_color }}</td>
                        <td class="px-4 py-2 border">{{ ucfirst($event->language) }}</td>
                        <td class="px-4 py-2 border">
                            <a href="{{ route('admin.hijri.date.event.edit', $event->id) }}" class="text-[#034E7A] hover:underline">Edit</a>
                            <form action="{{ route('admin.hijri.date.event.delete', $event->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this event?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                    <tr id="noResults" class="hidden">
                        <td colspan="6" class="text-center text-gray-500 py-4">No events found.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function filterTable() {
    const monthFilter = document.getElementById('filterMonth').value.toLowerCase();
    const langFilter = document.getElementById('filterLanguage').value.toLowerCase();
    const eventFilter = document.getElementById('filterEvent').value.toLowerCase();

    const rows = document.querySelectorAll('#eventstable tbody tr');
    let anyVisible = false;

    rows.forEach(row => {
        if(row.id === 'noResults') return;

        const month = row.dataset.month;
        const language = row.dataset.language;
        const eventName = row.dataset.event;

        const show =
            (!monthFilter || month === monthFilter) &&
            (!langFilter || language === langFilter) &&
            (!eventFilter || eventName.includes(eventFilter));

        row.style.display = show ? '' : 'none';
        if(show) anyVisible = true;
    });

    document.getElementById('noResults').style.display = anyVisible ? 'none' : '';
}
</script>
@endsection
