@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')
<div class="h-screen bg-gray-100 flex items-start">
    <div class="bg-white rounded-xl shadow-lg w-full p-6 space-y-6">

        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <a href="{{ route('admin.users.show', $user->id) }}" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 font-semibold"><i class="fas fa-arrow-left"></i> Back</a>
        </div>

        <!-- Edit Form -->
        <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-2 gap-6">

                <!-- Name -->
                <div>
                    <label class="block text-gray-500 font-semibold mb-1" for="name">Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                           class="w-full px-4 py-2 border rounded shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('name')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-gray-500 font-semibold mb-1" for="email">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                           class="w-full px-4 py-2 border rounded shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('email')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-gray-500 font-semibold mb-1" for="password">Password (leave blank to keep current)</label>
                    <input type="password" name="password" id="password"
                           class="w-full px-4 py-2 border rounded shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('password')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Email Verified -->
                <div class="flex items-center mt-6">
                    <input type="checkbox" name="email_verified" id="email_verified" value="1"
                        {{ $user->email_verified_at ? 'checked' : '' }}
                        class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                    <label for="email_verified" class="ml-2 text-gray-700 font-medium">Email Verified</label>
                </div>
            </div>

            <!-- Created / Updated Info -->
            <div class="grid grid-cols-2 gap-6 mt-4">
                <div>
                    <label class="block text-gray-500 font-semibold mb-1">Created At</label>
                    <p class="text-gray-800">{{ $user->created_at ? $user->created_at->format('Y-m-d H:i') : '-' }}</p>
                </div>
                <div>
                    <label class="block text-gray-500 font-semibold mb-1">Updated At</label>
                    <p class="text-gray-800">{{ $user->updated_at ? $user->updated_at->format('Y-m-d H:i') : '-' }}</p>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end space-x-2 mt-4">
                <a href="{{ route('admin.users.show', $user->id) }}" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Update User</button>
            </div>

        </form>

    </div>
</div>
@endsection
