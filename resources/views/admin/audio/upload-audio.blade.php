@extends('layouts.admin')
@section('title', 'Upload Audio')

@section('content')
@if(session('success'))
<div class="mb-4 p-3 bg-green-100 text-green-700 border border-green-300 rounded">
    {{ session('success') }}
</div>
@endif

<!-- Upload Audio Section -->
<div class="mt-1">
    <div class="bg-white rounded shadow p-5 mb-6">
        <h2 class="text-lg font-semibold text-[#034E7A] mb-4">Upload Audio File</h2>

        <!-- Upload Form -->
        <form action="{{ route('admin.uploadAudio') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-medium text-[#034E7A] mb-2">Choose Audio File</label>
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


            <button
                type="submit"
                class="bg-[#034E7A] text-white px-5 py-2 rounded hover:bg-[#02629B] transition">
                Upload Audio
            </button>
        </form>
    </div>
</div>

<!-- Upload Result Section -->
@if(session('audio_url'))
<div class="bg-white rounded shadow p-5 mt-6">
    <h2 class="text-lg font-semibold text-[#034E7A] mb-3">Uploaded Audio</h2>

    <p class="text-sm text-gray-700 mb-3"> File uploaded successfully. URL:</p>

    <div class="p-3 border rounded bg-gray-50">
        <a href="{{ session('audio_url') }}" target="_blank" class="text-[#034E7A] underline">
            {{ session('audio_url') }}
        </a>
    </div>

    <audio controls class="mt-4 w-full">
        <source src="{{ session('audio_url') }}" type="audio/mpeg">
        Your browser does not support the audio element.
    </audio>
</div>
@endif


<!-- All Uploaded Audio Files -->
@if($filesPaginated->count() > 0)
<div class="bg-white rounded shadow p-5 mt-6">
    <h2 class="text-lg font-semibold text-[#034E7A] mb-3">All Uploaded Audio Files</h2>

    @foreach($filesPaginated as $file)
    <div class="mb-4 border-b pb-3">
        <p class="text-sm font-medium text-[#034E7A]">{{ $file['name'] }}</p>

        <audio controls class="mt-2 w-full">
            <source src="{{ $file['url'] }}" type="audio/mpeg">
        </audio>

        <a href="{{ $file['url'] }}" target="_blank" class="text-[#034E7A] underline text-sm mt-2 inline-block">
            Open File
        </a>
    </div>
    @endforeach

    <!-- Pagination Links -->
    <div class="mt-4">
        {{ $filesPaginated->links('pagination::bootstrap-5') }}
    </div>
</div>
@else
<div class="bg-white rounded shadow p-5 mt-6">
    <h2 class="text-lg font-semibold text-[#034E7A] mb-3">No Uploaded Audio Files</h2>
</div>
@endif

@endsection