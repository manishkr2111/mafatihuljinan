@extends('layouts.admin')

@section('title', 'View User')

@section('content')
<div class="h-screen bg-gray-100 p-4 flex items-start">
    <div class="bg-white rounded-xl shadow-lg w-full p-6 space-y-6">

        <!-- Header -->
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold text-[#034E7A]">User Details</h2>
            <a href="{{ route('admin.users') }}" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 font-semibold"><i class="fas fa-arrow-left"></i> Back</a>
        </div>

        <!-- Top Actions -->
        <div class="flex justify-end space-x-2">
            <a href="{{ route('admin.users.edit', $user->id) }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Edit User</a>
            <form action="" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Delete User</button>
            </form>
        </div>

        <!-- User Info -->
        <div class="grid grid-cols-2 gap-6 border border-gray-200 rounded p-4 bg-gray-50">
            <div>
                <h3 class="text-gray-500 font-semibold">Name</h3>
                <p class="text-gray-800">{{ $user->name }}</p>
            </div>
            <div>
                <h3 class="text-gray-500 font-semibold">Email</h3>
                <p class="text-gray-800">{{ $user->email }}</p>
            </div>
            <div>
                <h3 class="text-gray-500 font-semibold">Email Verified</h3>
                <p class="text-gray-800">
                    @if($user->email_verified_at)
                    <span class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">Verified</span>
                    @else
                    <span class="px-2 py-1 text-xs font-semibold text-red-700 bg-red-100 rounded-full">Not Verified</span>
                    @endif
                </p>
            </div>
            <div>
                <h3 class="text-gray-500 font-semibold">Created At</h3>
                <p class="text-gray-800">{{ $user->created_at ? $user->created_at->format('Y-m-d H:i') : '-' }}</p>
            </div>
            <div>
                <h3 class="text-gray-500 font-semibold">Updated At</h3>
                <p class="text-gray-800">{{ $user->updated_at ? $user->updated_at->format('Y-m-d H:i') : '-' }}</p>
            </div>
        </div>

        <!-- Bookmarked Posts -->
        <div class="border-t border-gray-200 pt-4">
            <h3 class="text-xl font-bold text-[#034E7A] mb-2">Bookmarked Posts</h3>
            @if($GroupedBookmarkPosts->isEmpty())
            <p class="text-gray-600">No favorite posts found.</p>
            @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($GroupedBookmarkPosts as $language => $bookmarks)
                <div class="bg-gray-50 rounded-lg p-4 shadow-sm border border-gray-200">
                    <h4 class="text-lg font-semibold text-[#026b9c] border-b border-gray-200 pb-1 mb-2">
                        {{ ucfirst($language) }}
                    </h4>
                    <ul class="list-disc list-inside text-gray-700 space-y-1">
                        @foreach($bookmarks as $bookmark)
                        <li>
                            {{ $bookmark->post->title ?? 'Untitled' }}
                            <span class="text-gray-500 text-sm">
                                ({{ str_replace('-', ' ', ucfirst($bookmark->post_type)) }})
                            </span>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        <!-- Favorite Posts -->
        <div class="border-t border-gray-200 pt-4">
            <h3 class="text-xl font-bold text-[#034E7A] mb-2">Favorite Posts</h3>
            @if($GroupedFavorites->isEmpty())
            <p class="text-gray-600">No favorite posts found.</p>
            @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($GroupedFavorites as $language => $favorites)
                <div class="bg-gray-50 rounded-lg p-4 shadow-sm border border-gray-200">
                    <h4 class="text-lg font-semibold text-[#026b9c] border-b border-gray-200 pb-1 mb-2">
                        {{ ucfirst($language) }}
                    </h4>
                    <ul class="list-disc list-inside text-gray-700 space-y-1">
                        @foreach($favorites as $favorite)
                        <li>
                            {{ $favorite->post->title ?? 'Untitled' }}
                            <span class="text-gray-500 text-sm">
                                ({{ str_replace('-', ' ', ucfirst($favorite->post_type)) }})
                            </span>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endforeach
            </div>
            @endif
        </div>
        <!-- Created Notes -->
        <div class="border-t border-gray-200 pt-4">
            <h3 class="text-xl font-bold text-[#034E7A] mb-2">Created Notes</h3>
            <ul class="list-disc list-inside text-gray-700 space-y-1">
                <li>Note 1: Lorem ipsum dolor sit amet.</li>
                <li>Note 2: Consectetur adipiscing elit.</li>
                <li>Note 3: Integer nec odio.</li>
            </ul>
        </div>

    </div>
</div>
@endsection