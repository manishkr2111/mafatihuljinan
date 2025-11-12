@extends('layouts.admin')

@section('title', ucfirst($language) . ' ' . ucfirst($postType) . ' - ' . ucfirst($lrcType) . ' LRC Enabled Posts')

@section('content')
<div class="max-w-6xl mt-6 bg-white p-6 rounded shadow">
    <!-- <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold text-[#034E7A]">
            {{ ucfirst($language) }} / {{ ucfirst(str_replace('-', ' ', $postType)) }} - {{ ucfirst($lrcType) }} LRC Enabled Posts
        </h2>
    </div> -->

    @if($posts->count())
    <div class="overflow-x-auto">
        <table class="w-full border border-gray-200">
            <thead class="bg-gray-100 text-left">
                <tr>
                    <th class="p-3 border max-w-[80px]">#Sr. (ID)</th>
                    <th class="p-3 border">Title</th>
                    <th class="p-3 border">Status</th>
                    <th class="p-3 border">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($posts as $post)
                <tr class="hover:bg-gray-50">
                    <td class="p-3 border">
                        {{ $posts->firstItem() + $loop->index }} ({{ $post->id }})
                    </td>
                    <td class="p-3 border font-medium text-gray-800">{{ $post->title }}</td>
                    <td class="p-3 border">
                        <span class="px-2 py-1 rounded 
                                {{ $post->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ ucfirst($post->status) }}
                        </span>
                    </td>
                    <td class="p-3 border">
                        <a href="{{ route('admin.english.post.edit', ['postId' => $post->id, 'post_type' => $postType]) }}"
                            class="bg-[#034E7A] text-white px-3 py-1 rounded hover:bg-[#02629B] transition text-sm">
                            Edit
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $posts->appends(request()->except('page'))->links() }}
    </div>
    @else
    <p class="text-gray-500">No posts found with LRC enabled for {{ ucfirst($lrcType) }}.</p>
    @endif
</div>
@endsection