@extends('layouts.admin')

@section('title', 'Edit Marquee Text')

@section('content')
<div class=" mt-10 bg-white p-6 rounded-xl shadow-lg">

    <!-- Success Message -->
    @if(session('success'))
    <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-6">
        {{ session('success') }}
    </div>
    @endif

    <h2 class="text-2xl font-bold text-[#034E7A] mb-6">Edit Marquee Text</h2>

    <form action="{{ route('admin.marquee.update', $marqueeText->id) }}" method="POST" class="space-y-4">
        @csrf

        <div class="mb-4">
            <label class="block font-medium text-[#034E7A] mb-1">Marquee Text</label>
            <textarea name="text" rows="4" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#034E7A]" required>{!! $marqueeText->text !!}</textarea>
        </div>

        <div class="mb-4">
            <label class="block font-medium text-[#034E7A] mb-1">Language</label>
            <select name="language" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#034E7A]" required>
                @foreach(validLanguages() as $language)
                <option value="{{ $language }}" @if($marqueeText->language == $language) selected @endif>{{ ucfirst($language) }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex gap-2">
            <button type="submit" class="bg-[#034E7A] text-white px-6 py-2 rounded hover:bg-[#02629B] transition">Update</button>
            <a href="{{ route('admin.marquee.index') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded hover:bg-gray-400 transition">Cancel</a>
        </div>
    </form>

</div>
@endsection