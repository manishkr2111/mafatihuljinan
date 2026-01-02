@extends('layouts.admin')


@php $UrduPostTypeOptions = UrduPostTypeOptions() @endphp

@section('title', 'Edit ' . $UrduPostTypeOptions[$postType] . ' Post')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
    <div class="flex justify-between items-center mb-1 py-2">
        <a href="{{ route('admin.urdu.post.index' , ['post_type' => $postType]) }}"
            class="bg-[#034E7A] text-white px-4 py-1 rounded hover:bg-[#02629B] transition">
            <i class="fa fa-arrow-left" aria-hidden="true"></i>
            Back
        </a>
    </div>
    <div class="max-w-7xl">
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

        <form action="{{ route('admin.urdu.post.update', ['postId' => $Post->id]) }}?post_type={{ $postType }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            <input type="hidden" name="post_type" value="{{ $postType ?? 'sahifa' }}">
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
                            <input type="text" name="title" value="{{ old('title', $Post->title) }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#034E7A] focus:border-transparent transition" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Sort Number</label>
                            <input type="number" name="sort_number" value="{{ old('sort_number', $Post->sort_number) }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#034E7A] focus:border-transparent transition">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Search Text</label>
                            <input type="text" name="search_text" value="{{ old('search_text', $Post->search_text) }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#034E7A] focus:border-transparent transition">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                            <select name="status" required
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#034E7A] focus:border-transparent transition bg-white">
                                <option value="draft" {{ old('status', $Post->status)=='draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ old('status', $Post->status)=='published' ? 'selected' : '' }}>Published</option>
                                <option value="archived" {{ old('status', $Post->status)=='archived' ? 'selected' : '' }}>Archived</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Roman Data</label>
                            <input type="url" placeholder="https://example.com" name="roman_data" value="{{ old('roman_data', $Post->roman_data) }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#034E7A] focus:border-transparent transition">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Slug</label>
                            <div class="flex flex-wrap sm:flex-nowrap items-center border border-gray-300 rounded-lg px-3 py-2 focus-within:ring-2 focus-within:ring-[#034E7A]">
                                <span class="text-gray-500">https://dev.mafatihuljinan.org/</span>
                                <input type="text" name="slug" value="{{ old('slug', $Post->slug) }}"
                                    class="flex-1 bg-transparent focus:outline-none text-blue-600 font-medium ml-1" disabled>
                            </div>
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
                        <div class="grid  md:grid-cols-2 gap-3 border p-4 rounded max-h-[300px] overflow-y-auto">
                            @include('admin.urdu.posts.partials.edit-category-checkbox', [
                            'categories' => $categories,
                            'level' => 0,
                            'Post' => $Post,
                            'selectedIds' => $selectedIds
                            ])
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
                                <input type="checkbox" name="arabic_islrc" value="1" {{ old('arabic_islrc', $Post->arabic_islrc) ? 'checked' : '' }}
                                    class="w-5 h-5 text-[#034E7A] border-gray-300 rounded focus:ring-[#034E7A] focus:ring-2">
                                <span class="text-sm font-semibold text-gray-700">Enable ISLRC</span>
                            </label>
                            <label class="flex items-center gap-3 px-4 py-3 bg-blue-50 border-2 border-blue-200 rounded-lg hover:bg-blue-100 cursor-pointer transition">
                                <input type="checkbox" name="arabic_4line" value="1" {{ old('arabic_4line', $Post->arabic_4line) ? 'checked' : '' }}
                                    class="w-5 h-5 text-[#034E7A] border-gray-300 rounded focus:ring-[#034E7A] focus:ring-2">
                                <span class="text-sm font-semibold text-gray-700">4-line format</span>
                            </label>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Arabic Audio URL</label>
                            <input type="url" name="arabic_audio_url" value="{{ old('arabic_audio_url', $Post->arabic_audio_url) }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#034E7A] focus:border-transparent transition">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Arabic Content</label>
                            <textarea name="arabic_content" rows="5" dir=""
                                class="w-full text-left min-h-80 border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#034E7A] focus:border-transparent transition font-arabic">{{ old('arabic_content', $Post->arabic_content) }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Simple Arabic</label>
                            <textarea id="simple_arabic" class="tinymce-editor" name="simple_arabic" rows="4"
                                class="w-full min-h-80 text-left border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#034E7A] focus:border-transparent transition">{{ old('simple_arabic', $Post->simple_arabic) }}</textarea>
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
                                <input type="checkbox" name="transliteration_islrc" value="1" {{ old('transliteration_islrc', $Post->transliteration_islrc) ? 'checked' : '' }}
                                    class="w-5 h-5 text-[#034E7A] border-gray-300 rounded focus:ring-[#034E7A] focus:ring-2">
                                <span class="text-sm font-semibold text-gray-700">Enable ISLRC</span>
                            </label>
                            <label class="flex items-center gap-3 px-4 py-3 bg-green-50 border-2 border-green-200 rounded-lg hover:bg-green-100 cursor-pointer transition">
                                <input type="checkbox" name="transliteration_4line" value="1" {{ old('transliteration_4line', $Post->transliteration_4line) ? 'checked' : '' }}
                                    class="w-5 h-5 text-[#034E7A] border-gray-300 rounded focus:ring-[#034E7A] focus:ring-2">
                                <span class="text-sm font-semibold text-gray-700">4-line format</span>
                            </label>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Transliteration Audio URL</label>
                            <input type="url" name="transliteration_audio_url" value="{{ old('transliteration_audio_url', $Post->transliteration_audio_url) }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#034E7A] focus:border-transparent transition">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Transliteration Content</label>
                            <textarea name="transliteration_content" rows="5"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#034E7A] focus:border-transparent transition">{{ old('transliteration_content', $Post->transliteration_content) }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Simple Transliteration</label>
                            <textarea id="simple_transliteration" class="tinymce-editor" name="simple_transliteration" rows="4"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#034E7A] focus:border-transparent transition">{{ old('simple_transliteration', $Post->simple_transliteration) }}</textarea>
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
                                <input type="checkbox" name="translation_islrc" value="1" {{ old('translation_islrc', $Post->translation_islrc) ? 'checked' : '' }}
                                    class="w-5 h-5 text-[#034E7A] border-gray-300 rounded focus:ring-[#034E7A] focus:ring-2">
                                <span class="text-sm font-semibold text-gray-700">Enable ISLRC</span>
                            </label>
                            <label class="flex items-center gap-3 px-4 py-3 bg-purple-50 border-2 border-purple-200 rounded-lg hover:bg-purple-100 cursor-pointer transition">
                                <input type="checkbox" name="translation_4line" value="1" {{ old('translation_4line', $Post->translation_4line) ? 'checked' : '' }}
                                    class="w-5 h-5 text-[#034E7A] border-gray-300 rounded focus:ring-[#034E7A] focus:ring-2">
                                <span class="text-sm font-semibold text-gray-700">4-line format</span>
                            </label>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Translation Audio URL</label>
                            <input type="url" name="translation_audio_url" value="{{ old('translation_audio_url', $Post->translation_audio_url) }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#034E7A] focus:border-transparent transition">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Translation Content</label>
                            <textarea name="translation_content" rows="5"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#034E7A] focus:border-transparent transition">{{ old('translation_content', $Post->translation_content) }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Simple Translation</label>
                            <textarea id="simple_translation" class="tinymce-editor" name="simple_translation" rows="5"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#034E7A] focus:border-transparent transition">{{ old('simple_translation', $Post->simple_translation) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- word meanning for surah only -->
                @if($postType == 'surah')
                <!-- Word Meaning Section -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="bg-gradient-to-r from-[#034E7A] to-[#02629B] px-6 py-4">
                        <h2 class="text-xl font-semibold text-white flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Word Meanings
                        </h2>
                    </div>

                    <div class="p-6 space-y-6">
                        <div class="overflow-x-auto overflow-y-auto max-h-[300px]">
                            <table class="min-w-full border border-gray-200 rounded-lg" id="wordMeaningTable">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-gray-700 font-semibold border">Word</th>
                                        <th class="px-4 py-2 text-left text-gray-700 font-semibold border">Transliteration</th>
                                        <th class="px-4 py-2 text-left text-gray-700 font-semibold border">Translation</th>
                                        <th class="px-4 py-2 text-center text-gray-700 font-semibold border">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="wordMeaningBody">
                                    @if(!empty($wordMeanings))
                                    @foreach($wordMeanings as $index => $meaning)
                                    <tr>
                                        <td class="border px-4 py-2">
                                            <input type="text" name="word_meanings[{{ $index }}][word]"
                                                value="{{ $meaning['word'] ?? '' }}"
                                                class="w-full border-gray-300 rounded px-2 py-1 focus:ring-[#034E7A]" placeholder="Word">
                                        </td>
                                        <td class="border px-4 py-2">
                                            <input type="text" name="word_meanings[{{ $index }}][transliteration]"
                                                value="{{ $meaning['transliteration'] ?? '' }}"
                                                class="w-full border-gray-300 rounded px-2 py-1 focus:ring-[#034E7A]" placeholder="Transliteration">
                                        </td>
                                        <td class="border px-4 py-2">
                                            <input type="text" name="word_meanings[{{ $index }}][translation]"
                                                value="{{ $meaning['translation'] ?? '' }}"
                                                class="w-full border-gray-300 rounded px-2 py-1 focus:ring-[#034E7A]" placeholder="Translation">
                                        </td>
                                        <td class="border px-4 py-2 text-center">
                                            <button type="button" class="text-red-600 hover:text-red-800 remove-row">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr>
                                        <td class="border px-4 py-2">
                                            <input type="text" name="word_meanings[0][word]"
                                                class="w-full border-gray-300 rounded px-2 py-1 focus:ring-[#034E7A]" placeholder="Word">
                                        </td>
                                        <td class="border px-4 py-2">
                                            <input type="text" name="word_meanings[0][transliteration]"
                                                class="w-full border-gray-300 rounded px-2 py-1 focus:ring-[#034E7A]" placeholder="Transliteration">
                                        </td>
                                        <td class="border px-4 py-2">
                                            <input type="text" name="word_meanings[0][translation]"
                                                class="w-full border-gray-300 rounded px-2 py-1 focus:ring-[#034E7A]" placeholder="Translation">
                                        </td>
                                        <td class="border px-4 py-2 text-center">
                                            <button type="button" class="text-red-600 hover:text-red-800 remove-row">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endif
                                </tbody>

                            </table>
                        </div>

                        <button type="button" id="addWordRow"
                            class="mt-4 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center gap-2">
                            <i class="fa fa-plus"></i> Add Row
                        </button>
                    </div>
                </div>
                @endif
                @php
                $baseUrl = rtrim(config('app.url'), '/') . '/';
                @endphp

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
                                <input type="text" name="next_post_title" value="{{ old('next_post_title', $Post->next_post_title) }}"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#034E7A] focus:border-transparent transition">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Next Post URL</label>
                                <div class="flex items-center rounded-lg overflow-hidden border border-gray-300 focus-within:ring-2 focus-within:ring-[#034E7A] bg-white transition">
                                    <span class="bg-gray-100 text-gray-600 px-3 py-3 text-sm whitespace-nowrap select-none">
                                        {{ $baseUrl }}
                                    </span>
                                    <input type="text" name="next_post_url" value="{{ old('next_post_url', $Post->next_post_url) }}" placeholder="enter-slug-here"
                                        class="w-full px-3 py-3 text-gray-800 text-sm focus:outline-none" />
                                </div>
                            </div>
                            <div class="mb-6">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Internal Link
                                </label>
                                <div class="flex items-center rounded-lg overflow-hidden border border-gray-300 focus-within:ring-2 focus-within:ring-[#034E7A] bg-white transition">
                                    <span class="bg-gray-100 text-gray-600 px-3 py-3 text-sm whitespace-nowrap select-none">
                                        {{ $baseUrl }}
                                    </span>
                                    <input type="text" name="internal_link" value="{{ old('internal_link', $Post->internal_link) }}" placeholder="enter-slug-here"
                                        class="w-full px-3 py-3 text-gray-800 text-sm focus:outline-none" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-wrap gap-4 justify-end bg-white rounded-xl shadow-md p-6">
                    <a href="{{ route('admin.urdu.post.index' , ['post_type' => $postType]) }}"
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
                        Update Post
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
@if($postType == 'surah')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        let existingRows = document.querySelectorAll('#wordMeaningBody tr').length;
        let rowIndex = existingRows > 0 ? existingRows : 1;

        const addBtn = document.getElementById('addWordRow');
        const tableBody = document.getElementById('wordMeaningBody');

        if (addBtn) {
            addBtn.addEventListener('click', function() {
                const newRow = document.createElement('tr');
                newRow.innerHTML = `
                                    <td class="border px-4 py-2">
                                        <input type="text" name="word_meanings[${rowIndex}][word]" 
                                            class="w-full border-gray-300 rounded px-2 py-1 focus:ring-[#034E7A]" 
                                            placeholder="Word">
                                    </td>
                                    <td class="border px-4 py-2">
                                        <input type="text" name="word_meanings[${rowIndex}][transliteration]" 
                                            class="w-full border-gray-300 rounded px-2 py-1 focus:ring-[#034E7A]" 
                                            placeholder="Transliteration">
                                    </td>
                                    <td class="border px-4 py-2">
                                        <input type="text" name="word_meanings[${rowIndex}][translation]" 
                                            class="w-full border-gray-300 rounded px-2 py-1 focus:ring-[#034E7A]" 
                                            placeholder="Translation">
                                    </td>
                                    <td class="border px-4 py-2 text-center">
                                        <button type="button" class="text-red-600 hover:text-red-800 remove-row">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                `;
                tableBody.appendChild(newRow);
                rowIndex++;
            });
        }

        // Remove row functionality
        tableBody.addEventListener('click', function(e) {
            if (e.target.closest('.remove-row')) {
                e.target.closest('tr').remove();
            }
        });
    });
</script>
@endif
@endsection