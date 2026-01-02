@extends('layouts.admin')

@section('title', isset($schedule) ? 'Edit Notification Schedule' : 'Create Notification Schedule')

@section('content')
<div class="max-w-6xl mx-auto mt-10 grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- LEFT: FORM --}}
    <div class="lg:col-span-2 bg-white p-6 rounded shadow">

        {{-- Errors --}}
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form
            action="{{ isset($schedule)
                ? route('admin.notifications.schedule.update', $schedule->id)
                : route('admin.notifications.schedule.store') }}"
            method="POST"
            class="space-y-4"
        >
            @csrf
            @isset($schedule)
                @method('PUT')
            @endisset

            {{-- Language --}}
            <div>
                <label class="block font-medium mb-1 text-[#034E7A]">Language</label>
                <select name="language" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-[#034E7A]" required>
                    <option value="">-- Select Language --</option>
                    @foreach(validLanguages() as $language)
                        <option value="{{ $language }}"
                            {{ old('language', $schedule->language ?? '') == $language ? 'selected' : '' }}>
                            {{ ucfirst($language) }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Title --}}
            <div>
                <label class="block font-medium mb-1 text-[#034E7A]">Notification Title</label>
                <input type="text" name="title"
                    value="{{ old('title', $schedule->title ?? '') }}"
                    class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-[#034E7A]"
                    required>
            </div>

            {{-- Message --}}
            <div>
                <label class="block font-medium mb-1 text-[#034E7A]">Notification Message</label>
                <textarea name="message" rows="3"
                    class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-[#034E7A]"
                    required>{{ old('message', $schedule->message ?? '') }}</textarea>
            </div>

            {{-- Image URL --}}
            <div>
                <label class="block font-medium mb-1 text-[#034E7A]">Image URL (optional)</label>
                <input type="url" name="image_url"
                    value="{{ old('image_url', $schedule->image_url ?? '') }}"
                    class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-[#034E7A]">
            </div>

            {{-- Frequency --}}
            <div>
                <label class="block font-medium mb-1 text-[#034E7A]">Frequency</label>
                <select name="frequency" id="frequency"
                    class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-[#034E7A]"
                    required>
                    @foreach (['daily','weekly','monthly','yearly','custom'] as $freq)
                        <option value="{{ $freq }}"
                            {{ old('frequency', $schedule->frequency ?? '') === $freq ? 'selected' : '' }}>
                            {{ ucfirst($freq) }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Time --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block font-medium mb-1 text-[#034E7A]">Send Hour</label>
                    <input type="number" name="send_hour" min="0" max="23"
                        value="{{ old('send_hour', $schedule->send_hour ?? '') }}"
                        class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-[#034E7A]"
                        required>
                </div>

                <div>
                    <label class="block font-medium mb-1 text-[#034E7A]">Send Minute</label>
                    <input type="number" name="send_minute" min="0" max="59"
                        value="{{ old('send_minute', $schedule->send_minute ?? '') }}"
                        class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-[#034E7A]"
                        required>
                </div>
            </div>

            {{-- Day of Week --}}
            <div id="dayOfWeekWrapper">
                <label class="block font-medium mb-1 text-[#034E7A]">Day of Week</label>
                <select name="day_of_week" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-[#034E7A]">
                    <option value="">-- Select Day --</option>
                    @foreach ([0=>'Sunday',1=>'Monday',2=>'Tuesday',3=>'Wednesday',4=>'Thursday',5=>'Friday',6=>'Saturday'] as $k=>$v)
                        <option value="{{ $k }}"
                            {{ old('day_of_week', $schedule->day_of_week ?? '') == (string)$k ? 'selected' : '' }}>
                            {{ $v }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Day of Month --}}
            <div id="dayOfMonthWrapper">
                <label class="block font-medium mb-1 text-[#034E7A]">Day of Month</label>
                <input type="number" name="day_of_month" min="1" max="31"
                    value="{{ old('day_of_month', $schedule->day_of_month ?? '') }}"
                    class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-[#034E7A]">
            </div>

            {{-- Month --}}
            <div id="monthWrapper">
                <label class="block font-medium mb-1 text-[#034E7A]">Month</label>
                <select name="month_of_year" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-[#034E7A]">
                    <option value="">-- Select Month --</option>
                    @foreach ([1=>'January',2=>'February',3=>'March',4=>'April',5=>'May',6=>'June',7=>'July',8=>'August',9=>'September',10=>'October',11=>'November',12=>'December'] as $k=>$v)
                        <option value="{{ $k }}"
                            {{ old('month_of_year', $schedule->month_of_year ?? '') == (string)$k ? 'selected' : '' }}>
                            {{ $v }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Active --}}
            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_active" value="1"
                    {{ old('is_active', $schedule->is_active ?? true) ? 'checked' : '' }}>
                <label class="text-[#034E7A] font-medium">Active</label>
            </div>

            {{-- Submit --}}
            <button type="submit"
                class="bg-[#034E7A] text-white px-4 py-2 rounded hover:bg-[#02629B] transition">
                {{ isset($schedule) ? 'Update Schedule' : 'Save Schedule' }}
            </button>
        </form>
    </div>

    {{-- RIGHT: HELP --}}
    <div class="bg-gray-50 p-6 rounded shadow-sm border h-fit">
        <h3 class="text-lg font-semibold text-[#034E7A] mb-4">Scheduling Guide</h3>
        <ul class="text-sm text-gray-700 space-y-2">
            <li><b>Daily</b> – Runs every day</li>
            <li><b>Weekly</b> – Runs on selected weekday</li>
            <li><b>Monthly</b> – Runs on selected date</li>
            <li><b>Yearly</b> – Runs once per year</li>
            <li><b>Custom</b> – Full control</li>
        </ul>
        <div class="mt-4 text-xs text-gray-500">⏱ Time is based on UTC</div>
    </div>
</div>

<script>
    const frequency = document.getElementById('frequency');
    const dayOfWeek = document.getElementById('dayOfWeekWrapper');
    const dayOfMonth = document.getElementById('dayOfMonthWrapper');
    const month = document.getElementById('monthWrapper');

    function toggleFields() {
        dayOfWeek.style.display = 'none';
        dayOfMonth.style.display = 'none';
        month.style.display = 'none';

        switch (frequency.value) {
            case 'weekly': dayOfWeek.style.display = 'block'; break;
            case 'monthly': dayOfMonth.style.display = 'block'; break;
            case 'yearly':
                dayOfMonth.style.display = 'block';
                month.style.display = 'block';
                break;
            case 'custom':
                dayOfWeek.style.display = 'block';
                dayOfMonth.style.display = 'block';
                month.style.display = 'block';
                break;
        }
    }

    frequency.addEventListener('change', toggleFields);
    toggleFields();
</script>
@endsection
