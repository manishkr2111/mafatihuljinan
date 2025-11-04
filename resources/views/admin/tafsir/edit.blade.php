@extends('layouts.admin')
@section('title', 'Edit Tafsir')
@section('content')

<div class="max-w-3xl mx-2 mt-6 bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-semibold text-[#034E7A] mb-4">Edit Tafsir</h1>

    <form action="{{ route('admin.tafsir.update', $tafsir->id) }}" method="POST">
        @csrf

        <!-- Title -->
        <div class="mb-4">
            <label for="title" class="block font-medium text-[#034E7A] mb-1">Title <span class="text-red-500">*</span></label>
            <input type="text" name="title" id="title" value="{{ old('title', $tafsir->title) }}"
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
                <option value="{{ $key }}" {{ old('post_type', $tafsir->post_type) == $key ? 'selected' : '' }}>
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
                <option value="english" {{ old('language', $tafsir->language) == 'english' ? 'selected' : '' }}>English</option>
                <option value="hindi" {{ old('language', $tafsir->language) == 'hindi' ? 'selected' : '' }}>Hindi</option>
                <option value="gujarati" {{ old('language', $tafsir->language) == 'gujarati' ? 'selected' : '' }}>Gujarati</option>
                <option value="french" {{ old('language', $tafsir->language) == 'french' ? 'selected' : '' }}>French</option>
                <option value="spanish" {{ old('language', $tafsir->language) == 'spanish' ? 'selected' : '' }}>Spanish</option>
            </select>
            @error('language')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Content -->
        <div class="mb-4">
            <label for="content" class="block font-medium text-[#034E7A] mb-1">Content</label>
            <textarea name="content" id="content" rows="6"
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#034E7A]">{{ old('content', $tafsir->content) }}</textarea>
            @error('content')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="tafsir_html_content" class="block font-medium text-[#034E7A] mb-1">Tafsir Content</label>
            <textarea id="tafsir_html_content" class="tinymce-editor" name="tafsir_html_content" rows="4"
                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#034E7A] focus:border-transparent transition">{{ old('tafsir_html_content', $tafsir->tafsir_html_content) }}</textarea>
        </div>

        <!-- Buttons -->
        <div class="flex justify-between items-center mt-6">
            <a href="{{ route('admin.tafsir.index') }}"
                class="bg-gray-200 text-[#034E7A] px-4 py-2 rounded hover:bg-gray-300 transition">
                Cancel
            </a>
            <button type="submit"
                class="bg-[#034E7A] text-white px-4 py-2 rounded hover:bg-[#02629B] transition">
                Update Tafsir
            </button>
        </div>
    </form>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        tinymce.init({
            selector: 'textarea.tinymce-editor', // targets all textareas with this class
            height: 400,
            plugins: 'link image code lists table',
            toolbar: 'undo redo | styles | bold italic | alignleft aligncenter alignright | bullist numlist | link image | code',
            license_key: 'gpl', // self-hosted GPL mode
            menubar: true,
            branding: false, // hides "Powered by TinyMCE" branding
            setup: function(editor) {
                editor.on('init', function() {
                    console.log('TinyMCE initialized:', editor.id);
                });
            }
        });
    });
</script>
@endsection