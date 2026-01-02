@extends('layouts.admin')

@section('title', 'Edit Hijri Event')

@section('content')
<div class=" mt-10 bg-white p-6 rounded-xl shadow-lg">
    <h2 class="text-2xl font-bold text-[#034E7A] mb-6">Edit Event</h2>

    <form action="{{ route('admin.hijri.date.event.update', $hijriEvent->id) }}" method="POST" class="space-y-4">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block font-medium text-[#034E7A] mb-1">Select Date</label>
                <select name="date" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#034E7A]">
                    @for($i = 1; $i <= 30; $i++)
                        <option value="{{ $i }}" @if($hijriEvent->date == $i) selected @endif>{{ $i }}</option>
                        @endfor
                </select>
            </div>

            <div>
                <label class="block font-medium text-[#034E7A] mb-1">Select Month</label>
                <select name="month" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#034E7A]">
                    @foreach($months as $month)
                    <option value="{{ $month }}" @if($hijriEvent->month == $month) selected @endif>{{ $month }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block font-medium text-[#034E7A] mb-1">Text Color</label>
                <select name="textcolor" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#034E7A]">
                    <option value="Green" @if($hijriEvent->text_color=='Green') selected @endif>Green</option>
                    <option value="Black" @if($hijriEvent->text_color=='Black') selected @endif>Black</option>
                    <option value="Light Black" @if($hijriEvent->text_color=='Light Black') selected @endif>Light Black</option>
                    <option value="White" @if($hijriEvent->text_color=='White') selected @endif>White</option>
                </select>
            </div>
            <div>
                <label class="block font-medium text-[#034E7A] mb-1">Language</label>
                <select name="textcolor" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#034E7A]">
                    @foreach(validLanguages() as $language)
                    <option value="{{ $language }}">{{ ucfirst($language) }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div>
            <label class="block font-medium text-[#034E7A] mb-1">Event Name</label>
            <input type="text" name="event" value="{{ $hijriEvent->event }}" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#034E7A]">
        </div>

        <button type="submit" class="bg-[#034E7A] text-white px-6 py-2 rounded hover:bg-[#02629B] transition">Update</button>
    </form>
</div>
@endsection