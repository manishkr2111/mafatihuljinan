@extends('layouts.admin')

@section('title', 'Scheduled Notifications')

@section('content')
<div class="max-w-7xl mx-auto mt-10 bg-white p-6 rounded shadow">

    {{-- Instant Notification --}}
    <div class="mb-6 border rounded bg-gray-50 p-4">
        <h2 class="text-lg font-semibold text-[#034E7A] mb-3">
            Send Instant Notification
        </h2>

        <form action="{{ route('admin.notifications.instant.send') }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @csrf

            {{-- Title --}}
            <div>
                <label class="block text-sm font-medium mb-1 text-[#034E7A]">Title</label>
                <input type="text" name="title" required
                    class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-[#034E7A]">
            </div>

            {{-- Message --}}
            <div>
                <label class="block text-sm font-medium mb-1 text-[#034E7A]">Message</label>
                <input type="text" name="message" required
                    class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-[#034E7A]">
            </div>

            {{-- Image URL --}}
            <div>
                <label class="block text-sm font-medium mb-1 text-[#034E7A]">
                    Image URL <span class="text-xs text-gray-500">(optional)</span>
                </label>
                <input type="url" name="image_url"
                    class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-[#034E7A]">
            </div>

            {{-- Button --}}
            <div class="md:col-span-3">
                <button type="submit"
                    class="bg-[#034E7A] text-white px-5 py-2 rounded hover:bg-[#02629B] transition">
                    Send Now
                </button>
            </div>
        </form>
    </div>

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-xl font-semibold text-[#034E7A]">
            Scheduled Notifications
        </h1>

        <a href="{{ route('admin.notifications.schedule.create') }}"
            class="bg-[#034E7A] text-white px-4 py-2 rounded hover:bg-[#02629B] transition">
            + Create Schedule
        </a>
    </div>
    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="w-full border border-gray-200 text-sm">
            <thead class="bg-gray-100 text-[#034E7A]">
                <tr>
                    <th class="border px-3 py-2 text-left">Title</th>
                    <th class="border px-3 py-2">Frequency</th>
                    <th class="border px-3 py-2">Time</th>
                    <th class="border px-3 py-2">Schedule</th>
                    <th class="border px-3 py-2">Status</th>
                    <th class="border px-3 py-2">Last Run</th>
                    <th class="border px-3 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($schedules as $schedule)
                <tr class="hover:bg-gray-50">
                    <td class="border px-3 py-2">
                        <div class="font-medium">{{ $schedule->title }}</div>
                        <div class="text-xs text-gray-500">
                            {{ \Illuminate\Support\Str::limit($schedule->message, 50) }}
                        </div>
                    </td>

                    <td class="border px-3 py-2 text-center capitalize">
                        {{ $schedule->frequency }}
                    </td>

                    <td class="border px-3 py-2 text-center">
                        {{ sprintf('%02d:%02d', $schedule->send_hour, $schedule->send_minute) }}
                    </td>

                    <td class="border px-3 py-2 text-center text-xs">
                        @switch($schedule->frequency)
                        @case('weekly')
                        {{ ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'][$schedule->day_of_week] }}
                        @break
                        @case('monthly')
                        Day {{ $schedule->day_of_month }}
                        @break
                        @case('yearly')
                        {{ \Carbon\Carbon::create()->month($schedule->month_of_year)->format('F') }}
                        {{ $schedule->day_of_month }}
                        @break
                        @case('custom')
                        Custom
                        @break
                        @default
                        Daily
                        @endswitch
                    </td>

                    <td class="border px-3 py-2 text-center">
                        @if($schedule->is_active)
                        <span class="text-green-600 font-medium">Active</span>
                        @else
                        <span class="text-red-600 font-medium">Inactive</span>
                        @endif
                    </td>

                    <td class="border px-3 py-2 text-center text-xs">
                        {{ $schedule->last_run_at?->format('d M Y H:i') ?? '—' }}
                    </td>

                    <td class="border px-3 py-2 text-center space-x-2">
                        <a href="{{ route('admin.notifications.schedule.edit', $schedule->id) }}"
                            class="text-blue-600 hover:underline">
                            Edit
                        </a>

                        <form action="{{ route('admin.notifications.schedule.destroy', $schedule->id) }}"
                            method="POST"
                            class="inline"
                            onsubmit="return confirm('Are you sure you want to delete this schedule?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-6 text-gray-500">
                        No scheduled notifications found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection