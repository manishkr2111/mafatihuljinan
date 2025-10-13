@extends('layouts.admin')

@section('title', 'Hijri Events')

@section('content')
<div class=" mt-10 bg-white p-6 rounded-xl shadow-lg">
    @if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <ul class="list-disc list-inside">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
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
                    <option value="english">English</option>
                    <option value="hindi">Hindi</option>
                    <option value="gujarati">Gujarati</option>
                    <option value="french">French</option>
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

        <!-- Filter by Month -->
        <div class="mb-4">
            <select id="searchByMonth" class="border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#034E7A]" onchange="filterByMonth(this.value)">
                <option value="">-- Select Month --</option>
                @foreach($months as $month)
                <option value="{{ $month }}">{{ $month }}</option>
                @endforeach
            </select>
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
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2"><input type="checkbox" class="checkBoxClass" value="{{ $event->id }}"></td>
                        <td class="px-4 py-2">{{ $event->date }} {{ $event->month }}</td>
                        <td class="px-4 py-2">{{ $event->event }}</td>
                        <td class="px-4 py-2">{{ $event->text_color }}</td>
                        <td class="px-4 py-2">{{ ucfirst($event->language) }}</td>
                        <td class="px-4 py-2">
                            <a href="{{ route('admin.hijri.date.event.edit', $event->id) }}" class="text-[#034E7A] hover:underline">Edit</a>
                            <form action="{{ route('admin.hijri.date.event.delete', $event->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this event?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
    function filterByMonth(month) {
        const rows = document.querySelectorAll('#eventstable tbody tr');
        rows.forEach(row => {
            const rowMonth = row.children[1].innerText.split(' ')[1];
            row.style.display = (month === "" || rowMonth === month) ? '' : 'none';
        });
    }
</script>

@endsection