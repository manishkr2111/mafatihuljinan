@extends('layouts.admin')
@section('title', 'Create Tafsir')
@section('content')

<div class="max-w-3xl mx-auto mt-6 bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-semibold text-[#034E7A] mb-4">Create Tafsir</h1>

    <form action="{{ route('admin.tafsir.store') }}" method="POST">
        @csrf

        <!-- Title -->
        <div class="mb-4">
            <label for="title" class="block font-medium text-[#034E7A] mb-1">Title <span class="text-red-500">*</span></label>
            <input type="text" name="title" id="title" value="{{ old('title') }}"
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#034E7A]" required>
            @error('title')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Post Type -->
        <div class="mb-4">
            <label for="post_type" class="block font-medium text-[#034E7A] mb-1">Post Type <span class="text-red-500">*</span></label>
            <select name="post_type" id="post_type"
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#034E7A]" required>
                <option value="">Select Post Type</option>
                @foreach(commonPostTypeOptions() as $key => $label)
                    <option value="{{ $key }}" {{ old('post_type') == $key ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            @error('post_type')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Language -->
        <div class="mb-4">
            <label for="language" class="block font-medium text-[#034E7A] mb-1">Language <span class="text-red-500">*</span></label>
            <select name="language" id="language"
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#034E7A]" required>
                <option value="">Select Language</option>
                <option value="english" {{ old('language') == 'english' ? 'selected' : '' }}>English</option>
                <option value="hindi" {{ old('language') == 'hindi' ? 'selected' : '' }}>Hindi</option>
                <option value="gujarati" {{ old('language') == 'gujarati' ? 'selected' : '' }}>Gujarati</option>
                <option value="french" {{ old('language') == 'french' ? 'selected' : '' }}>French</option>
                <option value="spanish" {{ old('language') == 'spanish' ? 'selected' : '' }}>Spanish</option>
            </select>
            @error('language')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Content -->
        <div class="mb-4">
            <label for="content" class="block font-medium text-[#034E7A] mb-1">Content</label>
            <textarea name="content" id="content" rows="6"
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#034E7A]">{{ old('content') }}</textarea>
            @error('content')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Buttons -->
        <div class="flex justify-between items-center mt-6">
            <a href="{{ route('admin.tafsir.index') }}"
                class="bg-gray-200 text-[#034E7A] px-4 py-2 rounded hover:bg-gray-300 transition">
                Cancel
            </a>
            <button type="submit"
                class="bg-[#034E7A] text-white px-4 py-2 rounded hover:bg-[#02629B] transition">
                Save Tafsir
            </button>
        </div>
    </form>
</div>

@endsection
