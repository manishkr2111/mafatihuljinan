@extends('layouts.admin')

@section('title', 'Create Sahifas Shlulbayt Post')

@section('content')
<div class="max-w-6xl mt-6 bg-white p-8 rounded shadow-lg">
    @if ($errors->any())
    <div class="mb-6 p-4 bg-red-100 text-red-700 rounded">
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('admin.english.post.store') }}" method="POST" class="space-y-8">
        @csrf

        {{-- Main Info --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-gray-700 font-medium mb-2">Title</label>
                <input type="text" name="title" value="{{ old('title') }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#034E7A]" required>
            </div>
            <div class="flex">
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Sort Number</label>
                    <input type="number" name="sort_number" value="{{ old('sort_number') }}"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#034E7A]">
                </div>
                <div>
                    {{-- Status & Submit --}}
                    <div class="mx-2">
                        <div class="w-full">
                            <label class="block text-gray-700 font-medium mb-2">Status</label>
                            <select name="status" required
                                class="w-full border border-gray-300 rounded mb-2 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#034E7A]">
                                <option value="draft" {{ old('status')=='draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ old('status')=='published' ? 'selected' : '' }}>Published</option>
                                <option value="archived" {{ old('status')=='archived' ? 'selected' : '' }}>Archived</option>
                            </select>
                        </div>
                        <div>
                            <button type="submit"
                                class="bg-[#034E7A] text-white px-6 py-2 rounded hover:bg-[#02629B] transition font-medium">
                                Save Post
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <label class="block text-gray-700 font-medium mb-2">Search Text</label>
            <input name="search_text" rows="2"
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#034E7A]" value="{{ old('search_text') }}">
        </div>

        <div>
            <label class="block text-gray-700 font-medium mb-2">Roman Data</label>
            <input name="roman_data" rows="2"
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#034E7A]">{{ old('roman_data') }}</input>
        </div>

        {{-- Categories --}}
        <div>
            <label class="block text-gray-700 font-medium mb-2">Categories</label>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-3 border p-4 rounded">
                @foreach($categories as $category)
                <label class="inline-flex items-center gap-2">
                    <input type="checkbox" name="category_ids[]" value="{{ $category->id }}"
                        {{ collect(old('category_ids'))->contains($category->id) ? 'checked' : '' }}>
                    <span>{{ $category->name }}</span>
                </label>
                @endforeach
            </div>
        </div>

        {{-- Arabic Section --}}
        <div class="border-t pt-6">
            <h2 class="text-2xl font-semibold text-[#034E7A] mb-4">Arabic</h2>
            <div class="flex items-center gap-6 mb-4">
                <label class="inline-flex items-center gap-2">
                    <input type="checkbox" name="arabic_islrc_enabled" value="1" {{ old('arabic_islrc_enabled', true) ? 'checked' : '' }}>
                    Enable ISLRC
                </label>
                <label class="inline-flex items-center gap-2">
                    <input type="checkbox" name="arabic_4line" value="1" {{ old('arabic_4line', true) ? 'checked' : '' }}>
                    4-line format
                </label>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-700 mb-1">Arabic Audio URL</label>
                    <input type="url" name="arabic_audio_url" value="{{ old('arabic_audio_url') }}"
                        class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-[#034E7A]">
                </div>
            </div>
            <div class="mt-4">
                <label class="block text-gray-700 mb-1">Arabic Content</label>
                <textarea name="arabic_content" rows="4" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-[#034E7A]">{{ old('arabic_content') }}</textarea>
            </div>
        </div>

        {{-- Transliteration Section --}}
        <div class="border-t pt-6">
            <h2 class="text-2xl font-semibold text-[#034E7A] mb-4">Transliteration</h2>
            <div class="flex items-center gap-6 mb-4">
                <label class="inline-flex items-center gap-2">
                    <input type="checkbox" name="transliteration_islrc_enabled" value="1" {{ old('transliteration_islrc_enabled', true) ? 'checked' : '' }}>
                    Enable ISLRC
                </label>
                <label class="inline-flex items-center gap-2">
                    <input type="checkbox" name="transliteration_4line" value="1" {{ old('transliteration_4line', true) ? 'checked' : '' }}>
                    4-line format
                </label>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-700 mb-1">Transliteration Audio URL</label>
                    <input type="url" name="transliteration_audio_url" value="{{ old('transliteration_audio_url') }}"
                        class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-[#034E7A]">
                </div>
            </div>
            <div class="mt-4 grid grid-cols-1 md:grid-cols-1 gap-6">
                <div>
                    <label class="block text-gray-700 mb-1">Transliteration Content</label>
                    <textarea name="transliteration_content" rows="4" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-[#034E7A]">{{ old('transliteration_content') }}</textarea>
                </div>
            </div>
            <div class="mt-4 grid grid-cols-1 md:grid-cols-1 gap-6">
                <div>
                    <label class="block text-gray-700 mb-1">Simple Transliteration</label>
                    <textarea name="simple_transliteration" rows="3" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-[#034E7A]">{{ old('simple_transliteration') }}</textarea>
                </div>
            </div>
        </div>

        {{-- Translation Section --}}
        <div class="border-t pt-6">
            <h2 class="text-2xl font-semibold text-[#034E7A] mb-4">Translation</h2>
            <div class="flex items-center gap-6 mb-4">
                <label class="inline-flex items-center gap-2">
                    <input type="checkbox" name="translation_islrc_enabled" value="1" {{ old('translation_islrc_enabled', true) ? 'checked' : '' }}>
                    Enable ISLRC
                </label>
                <label class="inline-flex items-center gap-2">
                    <input type="checkbox" name="translation_4line" value="1" {{ old('translation_4line', true) ? 'checked' : '' }}>
                    4-line format
                </label>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-700 mb-1">Translation Audio URL</label>
                    <input type="url" name="translation_audio_url" value="{{ old('translation_audio_url') }}"
                        class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-[#034E7A]">
                </div>
            </div>
            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-700 mb-1">Translation Content</label>
                    <textarea name="translation_content" rows="4" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-[#034E7A]">{{ old('translation_content') }}</textarea>
                </div>
                <div>
                    <label class="block text-gray-700 mb-1">Simple Translation</label>
                    <textarea name="simple_translation" rows="3" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-[#034E7A]">{{ old('simple_translation') }}</textarea>
                </div>
            </div>
        </div>

        {{-- Next Post & Internal Links --}}
        <div class="border-t pt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block text-gray-700 mb-1">Next Post Title</label>
                <input type="text" name="next_post_title" value="{{ old('next_post_title') }}" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-[#034E7A]">
            </div>
            <div>
                <label class="block text-gray-700 mb-1">Next Post URL</label>
                <input type="text" name="next_post_url" value="{{ old('next_post_url') }}" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-[#034E7A]">
            </div>
            <div>
                <label class="block text-gray-700 mb-1">Internal Link</label>
                <input type="text" name="internal_link" value="{{ old('internal_link') }}" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-[#034E7A]">
            </div>
        </div>


    </form>
</div>
@endsection