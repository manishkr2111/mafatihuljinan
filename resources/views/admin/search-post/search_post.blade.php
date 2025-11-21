@extends('layouts.admin')

@section('title', 'Search Posts / Duas')

@section('content')
<div class=" bg-white p-3 rounded shadow">
    <form action="{{ route('admin.post.search.submit') }}" method="POST" class="space-y-4">
        @csrf
        <!-- Language & Title Inputs -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block font-medium mb-1 text-[#034E7A]">Language:</label>
                <select name="language"
                    class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#034E7A]"
                    required>
                    <option value="">-- Select Language --</option>
                    @foreach (\validLanguages() as $lang)
                    <option value="{{ $lang }}" {{ (isset($language) && $language == $lang) ? 'selected' : '' }}>
                        {{ ucfirst($lang) }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block font-medium mb-1 text-[#034E7A]">Title:</label>
                <input type="text" name="title" placeholder="Enter title..."
                    value="{{ $title ?? '' }}"
                    class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#034E7A]"
                    required>
            </div>
        </div>

        <button type="submit"
            class="bg-[#034E7A] text-white px-5 py-2 rounded hover:bg-[#02629B] transition font-medium">
            Search
        </button>
    </form>

    @if(!empty($results))
    <div class="mt-3">
        <h2 class="text-2xl font-semibold mb-4 mt-2 text-[#034E7A]">Results</h2>

        @foreach($results as $postType => $posts)
        @foreach($posts as $post)
        <div class="mb-2 border rounded shadow-sm p-4 hover:shadow-md transition flex flex-col md:flex-row md:justify-between md:items-center gap-4">
            <div>
                <h3 class="font-bold text-[#034E7A] text-lg">{{ ucwords(str_replace('-', ' ', $postType)) }}</h3>
                <ul class="mt-2 list-disc pl-5 space-y-1">
                    <li>{{ $post->title }} (ID: {{ $post->id }})</li>
                </ul>
            </div>
            <a href="{{ route('admin.' . strtolower($language) . '.post.edit', [
                'post_type' => $postType,
                'postId' => $post->id
            ]) }}">
                <div>
                    <button class="bg-[#034E7A] text-white px-5 py-1 rounded hover:bg-[#02629B] transition">
                        Edit
                    </button>
                    <!-- Delete Button -->
                    <form action="{{ route('admin.' . strtolower($language) . '.post.destroy', [
                            'postId' => $post->id,
                            'post_type' => $postType
                        ]) }}"
                        method="POST" class="inline-block"
                        onsubmit="return confirm('Are you sure you want to delete this post?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline px-3 py-1 rounded border border-red-600 hover:bg-red-100 transition">
                            Delete
                        </button>
                    </form>
                </div>
            </a>
        </div>
        @endforeach
        @endforeach

    </div>
    @elseif(isset($results))
    <p class="mt-6 text-red-500 font-medium">No posts found for "<span class="font-semibold">{{ $title }}</span>" in "<span class="font-semibold">{{ $language }}</span>"</p>
    @endif
</div>
@endsection