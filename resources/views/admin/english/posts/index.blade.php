@extends('layouts.admin')

@section('title', 'Sahifas Shlulbayt Post')

@section('content')
<div class="max-w-6xl mt-6 bg-white p-6 rounded shadow">
    <div class="flex justify-between items-center mb-6">
        <a href="{{ route('admin.english.post.create') }}"
            class="bg-[#034E7A] text-white px-4 py-2 rounded hover:bg-[#02629B] transition">
            Add New Post
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full border border-gray-200">
            <thead class="bg-gray-100 text-left">
                <tr>
                    <th class="p-3 border">ID</th>
                    <th class="p-3 border">Title</th>
                    <th class="p-3 border">Categories</th>
                    <th class="p-3 border">Status</th>
                    <th class="p-3 border">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($posts as $post)
                <tr class="hover:bg-gray-50">
                    <td class="p-3 border">{{ $post->id }}</td>
                    <td class="p-3 border font-medium text-gray-800">{{ $post->title }}</td>
                    <td class="p-3 border max-w-20">
                        @foreach($post->categories() as $category)
                        <span class="inline-block bg-[#034E7A] text-white text-xs px-2 py-1 rounded mr-1 mb-1">
                            {{ $category->name }}
                        </span>
                        @endforeach
                    </td>
                    <td class="p-3 border">
                        <span class="px-2 py-1 rounded 
                            {{ $post->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ ucfirst($post->status) }}
                        </span>
                    </td>
                    <td class="p-3 border">
                        <a href="{{ route('admin.english.post.edit', $post->id) }}"
                            class="bg-[#034E7A] text-white px-3 py-1 rounded hover:bg-[#02629B] transition text-sm">
                            Edit
                        </a>
                        <form action="{{ route('admin.english.post.destroy', $post->id) }}" method="POST" class="inline-block"
                            onsubmit="return confirm('Are you sure you want to delete this post?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline ml-3">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $posts->links() }} <!-- Pagination links -->
    </div>
</div>
@endsection