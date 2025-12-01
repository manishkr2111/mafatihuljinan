@extends('layouts.admin')

@section('title', 'Search and Replace Posts')

@section('content')
<div class="bg-white p-4 rounded shadow">

    {{-- Search Form --}}
    <form action="{{ route('admin.post.search.replace') }}" method="POST" class="space-y-4">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            {{-- Language --}}
            <div>
                <label class="font-medium text-[#034E7A]">Language</label>
                <select name="language" class="w-full border rounded px-3 py-2" required>
                    <option value="">-- Select --</option>
                    @foreach (\validLanguages() as $lang)
                    <option value="{{ $lang }}" {{ (isset($language) && $language == $lang) ? 'selected' : '' }}>
                        {{ ucfirst($lang) }}
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- Search --}}
            <div>
                <label class="font-medium text-[#034E7A]">Search For (Case Sensitive)</label>
                <input type="text" name="search_text" placeholder="Text to search..."
                    value="{{ $search ?? '' }}"
                    class="w-full border rounded px-3 py-2" required>
            </div>

            {{-- Replace --}}
            <div>
                <label class="font-medium text-[#034E7A]">Replace With</label>
                <input type="text" name="replace_text" placeholder="Replace with..."
                    value="{{ $replace ?? '' }}"
                    class="w-full border rounded px-3 py-2" required>
            </div>

        </div>

        <button type="submit"
            class="bg-[#034E7A] text-white px-5 py-2 rounded hover:bg-[#02629B] transition">
            Search & Preview
        </button>
    </form>

    {{-- Results --}}
    @if(!empty($results))
    <h2 class="text-xl font-semibold mt-5 text-[#034E7A]">Matched Results</h2>

    @foreach($results as $postType => $items)
    <div class="border rounded mt-4 p-4">

        <h3 class="font-bold text-lg text-[#034E7A]">
            {{ ucwords(str_replace('-', ' ', $postType)) }}
        </h3>

        {{-- MAIN REPLACE FORM (Only ONE per postType) --}}
        <form action="{{ route('admin.post.perform.replace') }}" method="POST" class="mt-3">
            @csrf
            <input type="hidden" name="language" value="{{ $language }}">
            <input type="hidden" name="search_text" value="{{ $search }}">
            <input type="hidden" name="replace_text" value="{{ $replace }}">
            <input type="hidden" name="post_type" value="{{ $postType }}">

            @foreach($items as $item)
            @php
            $post = $item['post'];
            $matches = $item['matches'];
            @endphp

            <div class="p-4 rounded mb-4 bg-gray-50">

                {{-- Post title + Edit --}}
                <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-3">

                    <h4 class="font-bold text-[#034E7A] text-lg">
                        {{ $post->title }}
                        <span class="text-sm text-gray-500">(ID: {{ $post->id }})</span>
                    </h4>

                    {{-- Edit --}}
                    <a href="{{ route('admin.' . strtolower($language) . '.post.edit', [
                            'post_type' => $postType,
                            'postId' => $post->id
                        ]) }}">
                        <button type="button"
                            class="bg-[#034E7A] text-white px-4 py-2 rounded hover:bg-[#02629B] transition w-full">
                            Edit
                        </button>
                    </a>

                </div>

                {{-- Table --}}
                <div class="overflow-x-auto mt-4">
                    <table class="w-full border text-sm min-w-[700px]">
                        <thead>
                            <tr class="bg-gray-100 border-b">
                                <th class="p-2 w-1/4">Field</th>
                                <th class="p-2">Matched Text</th>
                                <th class="p-2">After Replace</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($matches as $field => $content)
                            <tr class="border-b align-top">
                                <td class="p-2 font-semibold capitalize">
                                    {{ str_replace('_',' ', $field) }}
                                </td>

                                {{-- Before --}}
                                <td class="p-2">
                                    {!! preg_replace(
                                    "/" . preg_quote($search, '/') . "/i",
                                    "<mark>$search</mark>",
                                    $content
                                    ) !!}
                                </td>

                                {{-- After --}}
                                <td class="p-2 text-green-700">
                                    @php
                                    $afterReplace = preg_replace(
                                    "/" . preg_quote($search, '/') . "/i",
                                    $replace,
                                    $content
                                    );
                                    $afterHighlight = preg_replace(
                                    "/" . preg_quote($replace, '/') . "/i",
                                    "<mark class='bg-green-300'>$replace</mark>",
                                    $afterReplace
                                    );
                                    @endphp

                                    {!! $afterHighlight !!}
                                </td>

                            </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>

            </div>
            @endforeach

            <button class="mt-3 bg-[#034E7A] text-white px-5 py-2 rounded hover:bg-green-700">
                Replace in {{ count($items) }} Posts
            </button>

        </form>

    </div>
    @endforeach

    @elseif(isset($results))
    <p class="mt-4 text-red-500 font-medium">
        No matches found for "{{ $search }}"
    </p>
    @endif

</div>
@endsection