@extends('layouts.admin')

@section('title', 'Create Sahifas Shlulbayt Post')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8 px-4">
    <div class="max-w-7xl mx-auto">

        @if ($errors->any())
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 rounded-lg p-6 shadow-sm">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-3 flex-1">
                    <h3 class="text-sm font-medium text-red-800 mb-2">Please correct the following errors:</h3>
                    <ul class="list-disc list-inside space-y-1 text-sm text-red-700">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif

        <form action="{{ route('admin.english.post.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Main Information Card -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="bg-gradient-to-r from-[#034E7A] to-[#02629B] px-6 py-4">
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Main Information
                    </h2>
                </div>
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Title <span class="text-red-500">*</span></label>
                            <input type="text" name="title" value="{{ old('title') }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#034E7A] focus:border-transparent transition" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Sort Number</label>
                            <input type="number" name="sort_number" value="{{ old('sort_number') }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#034E7A] focus:border-transparent transition">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Search Text</label>
                            <input type="text" name="search_text" value="{{ old('search_text') }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#034E7A] focus:border-transparent transition">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                            <select name="status" required
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#034E7A] focus:border-transparent transition bg-white">
                                <option value="draft" {{ old('status')=='draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ old('status')=='published' ? 'selected' : '' }}>Published</option>
                                <option value="archived" {{ old('status')=='archived' ? 'selected' : '' }}>Archived</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Roman Data</label>
                        <textarea name="roman_data" rows="2"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#034E7A] focus:border-transparent transition">{{ old('roman_data') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Categories Card -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="bg-gradient-to-r from-[#034E7A] to-[#02629B] px-6 py-4">
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                        Categories
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 ">
                        <div>
                            @include('admin.english.posts.partials.category-checkbox', ['categories' => $categories->take(ceil($categories->count()/2))])
                        </div>
                        <div>
                            @include('admin.english.posts.partials.category-checkbox', ['categories' => $categories->slice(ceil($categories->count()/2))])
                        </div>
                    </div>



                </div>
            </div>

            <!-- Arabic Section Card -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="bg-gradient-to-r from-[#034E7A] to-[#02629B] px-6 py-4">
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129" />
                        </svg>
                        Arabic
                    </h2>
                </div>
                <div class="p-6 space-y-6">
                    <div class="flex flex-wrap gap-6">
                        <label class="flex items-center gap-3 px-4 py-3 bg-blue-50 border-2 border-blue-200 rounded-lg hover:bg-blue-100 cursor-pointer transition">
                            <input type="checkbox" name="arabic_islrc_enabled" value="1" {{ old('arabic_islrc_enabled', false) ? 'checked' : '' }}
                                class="w-5 h-5 text-[#034E7A] border-gray-300 rounded focus:ring-[#034E7A] focus:ring-2">
                            <span class="text-sm font-semibold text-gray-700">Enable ISLRC</span>
                        </label>
                        <label class="flex items-center gap-3 px-4 py-3 bg-blue-50 border-2 border-blue-200 rounded-lg hover:bg-blue-100 cursor-pointer transition">
                            <input type="checkbox" name="arabic_4line" value="1" {{ old('arabic_4line', false) ? 'checked' : '' }}
                                class="w-5 h-5 text-[#034E7A] border-gray-300 rounded focus:ring-[#034E7A] focus:ring-2">
                            <span class="text-sm font-semibold text-gray-700">4-line format</span>
                        </label>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Arabic Audio URL</label>
                        <input type="url" name="arabic_audio_url" value="{{ old('arabic_audio_url') }}"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#034E7A] focus:border-transparent transition">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Arabic Content</label>
                        <textarea name="arabic_content" rows="5" dir=""
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#034E7A] focus:border-transparent transition font-arabic">{{ old('arabic_content') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Simple Arabic</label>
                        <textarea id="simple_arabic" class="tinymce-editor" name="simple_arabic" rows="4"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#034E7A] focus:border-transparent transition">{{ old('simple_arabic') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Transliteration Section Card -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="bg-gradient-to-r from-[#034E7A] to-[#02629B] px-6 py-4">
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Transliteration
                    </h2>
                </div>
                <div class="p-6 space-y-6">
                    <div class="flex flex-wrap gap-6">
                        <label class="flex items-center gap-3 px-4 py-3 bg-green-50 border-2 border-green-200 rounded-lg hover:bg-green-100 cursor-pointer transition">
                            <input type="checkbox" name="transliteration_islrc_enabled" value="1" {{ old('transliteration_islrc_enabled', false) ? 'checked' : '' }}
                                class="w-5 h-5 text-[#034E7A] border-gray-300 rounded focus:ring-[#034E7A] focus:ring-2">
                            <span class="text-sm font-semibold text-gray-700">Enable ISLRC</span>
                        </label>
                        <label class="flex items-center gap-3 px-4 py-3 bg-green-50 border-2 border-green-200 rounded-lg hover:bg-green-100 cursor-pointer transition">
                            <input type="checkbox" name="transliteration_4line" value="1" {{ old('transliteration_4line', false) ? 'checked' : '' }}
                                class="w-5 h-5 text-[#034E7A] border-gray-300 rounded focus:ring-[#034E7A] focus:ring-2">
                            <span class="text-sm font-semibold text-gray-700">4-line format</span>
                        </label>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Transliteration Audio URL</label>
                        <input type="url" name="transliteration_audio_url" value="{{ old('transliteration_audio_url') }}"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#034E7A] focus:border-transparent transition">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Transliteration Content</label>
                        <textarea name="transliteration_content" rows="5"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#034E7A] focus:border-transparent transition">{{ old('transliteration_content') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Simple Transliteration</label>
                        <textarea id="simple_transliteration" class="tinymce-editor" name="simple_transliteration" rows="4"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#034E7A] focus:border-transparent transition">{{ old('simple_transliteration') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Translation Section Card -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="bg-gradient-to-r from-[#034E7A] to-[#02629B] px-6 py-4">
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129" />
                        </svg>
                        Translation
                    </h2>
                </div>
                <div class="p-6 space-y-6">
                    <div class="flex flex-wrap gap-6">
                        <label class="flex items-center gap-3 px-4 py-3 bg-purple-50 border-2 border-purple-200 rounded-lg hover:bg-purple-100 cursor-pointer transition">
                            <input type="checkbox" name="translation_islrc_enabled" value="1" {{ old('translation_islrc_enabled', false) ? 'checked' : '' }}
                                class="w-5 h-5 text-[#034E7A] border-gray-300 rounded focus:ring-[#034E7A] focus:ring-2">
                            <span class="text-sm font-semibold text-gray-700">Enable ISLRC</span>
                        </label>
                        <label class="flex items-center gap-3 px-4 py-3 bg-purple-50 border-2 border-purple-200 rounded-lg hover:bg-purple-100 cursor-pointer transition">
                            <input type="checkbox" name="translation_4line" value="1" {{ old('translation_4line', false) ? 'checked' : '' }}
                                class="w-5 h-5 text-[#034E7A] border-gray-300 rounded focus:ring-[#034E7A] focus:ring-2">
                            <span class="text-sm font-semibold text-gray-700">4-line format</span>
                        </label>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Translation Audio URL</label>
                        <input type="url" name="translation_audio_url" value="{{ old('translation_audio_url') }}"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#034E7A] focus:border-transparent transition">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Translation Content</label>
                        <textarea name="translation_content" rows="5"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#034E7A] focus:border-transparent transition">{{ old('translation_content') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Simple Translation</label>
                        <textarea id="simple_translation" class="tinymce-editor" name="simple_translation" rows="5"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#034E7A] focus:border-transparent transition">{{ old('simple_translation') }}</textarea>
                    </div>

                </div>
            </div>

            <!-- Next Post & Internal Links Card -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="bg-gradient-to-r from-[#034E7A] to-[#02629B] px-6 py-4">
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                        </svg>
                        Links & Navigation
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Next Post Title</label>
                            <input type="text" name="next_post_title" value="{{ old('next_post_title') }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#034E7A] focus:border-transparent transition">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Next Post URL</label>
                            <input type="text" name="next_post_url" value="{{ old('next_post_url') }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#034E7A] focus:border-transparent transition">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Internal Link</label>
                            <input type="text" name="internal_link" value="{{ old('internal_link') }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#034E7A] focus:border-transparent transition">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap gap-4 justify-end bg-white rounded-xl shadow-md p-6">
                <a href="{{ route('admin.english.post.index') }}"
                    class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition font-semibold flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Cancel
                </a>
                <button type="submit"
                    class="px-8 py-3 bg-gradient-to-r from-[#034E7A] to-[#02629B] text-white rounded-lg hover:from-[#02629B] hover:to-[#034E7A] transition font-semibold shadow-lg hover:shadow-xl flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Save Post
                </button>
            </div>
        </form>
    </div>
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