@extends('layouts.admin')

@php $FrenchPostTypeOptions = FrenchPostTypeOptions() @endphp

@section('title', 'All ' . $FrenchPostTypeOptions[$postType] . ' Post')

@section('content')
@if ($errors->any())
<div class="bg-red-100 text-red-700 p-2 rounded mb-4">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="max-w-6xl mt-6 bg-white p-6 rounded shadow">
    <div class="flex justify-between items-center mb-6">
        <a href="{{ route('admin.french.post.create' , ['post_type' => $postType]) }}"
            class="bg-[#034E7A] text-white px-4 py-2 rounded hover:bg-[#02629B] transition">
            Add New Post
        </a>
    </div>

    <div class="mb-4">
        <form method="GET" action="{{ route('admin.french.post.index') }}" class="flex flex-col md:flex-row gap-4">
            <input type="hidden" name="post_type" value="{{ $postType }}">

            <!-- Search by title -->
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search by Title or Category..."
                class="border p-2 rounded w-full md:w-1/2">

            <!-- Filter by category -->
            <select name="category" class="border p-2 rounded w-full md:w-1/2">
                <option value="">All Categories</option>
                @foreach($allCategories as $category)
                <option value="{{ strtolower($category->name) }}"
                    {{ request('category') == strtolower($category->name) ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
                @endforeach
            </select>
            <!-- Sort Order -->
            <select name="sort_order" class="border p-2 rounded w-full md:w-1/3">
                <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Sort : Ascending</option>
                <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Sort : Descending</option>
            </select>

            <button type="submit" class="bg-[#034E7A] text-white px-4 py-2 rounded hover:bg-[#02629B] transition">
                Filter
            </button>
        </form>
    </div>




    <div class="overflow-x-auto">
        <table class="w-full border border-gray-200">
            <thead class="bg-gray-100 text-left">
                <tr>
                    <th class="p-3 border max-w-[80px]">#Sr. (ID)</th>
                    <th class="p-3 border max-w-[50px]">Sort</th>
                    <th class="p-3 border">Title</th>
                    <th class="p-3 border">Categories</th>
                    <th class="p-3 border">Status</th>
                    <th class="p-3 border">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($posts as $post)
                <tr class="hover:bg-gray-50">
                    <td class="p-3 border max-w-[30px]">{{ $posts->firstItem() + $loop->index }} ({{$post->id}})</td>
                    <td class="p-3 border max-w-[30px]">{{$post->sort_number}}</td>
                    <td class="p-3 border font-medium text-gray-800 max-w-[250px]">{{ $post->title }}</td>
                    <td class="p-3 border min-w-[200px] max-w-[300px] max-h-[80px] overflow-y-auto">
                        <div class="relative max-h-[200px] overflow-y-auto">
                            @foreach($post->categories() as $category)
                            <span class="inline-block bg-[#034E7A] text-white text-xs px-2 py-1 rounded mr-1 mb-1">
                                {{ $category->name }}
                            </span>
                            @endforeach
                        </div>
                    </td>
                    <td class="p-3 border">
                        <span class="px-2 py-1 rounded 
                            {{ $post->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ ucfirst($post->status) }}
                        </span>
                    </td>
                    <td class="p-3 border">
                        <a href="{{ route('admin.french.post.edit', ['postId' => $post->id, 'post_type' => $postType] ) }}"
                            class="bg-[#034E7A] text-white px-3 py-1 rounded hover:bg-[#02629B] transition text-sm">
                            Edit
                        </a>
                        <form action="{{ route('admin.french.post.destroy', ['postId' => $post->id, 'post_type' => $postType]) }}" method="POST" class="inline-block"
                            onsubmit="return confirm('Are you sure you want to delete this post?');">
                            @csrf
                            <input type="hidden" name="post_type" value="{{ $postType ?? 'sahifa' }}">

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
        {{ $posts->appends(request()->except('page'))->links() }}
    </div>

</div>
@endsection