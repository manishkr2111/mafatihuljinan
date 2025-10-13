@extends('layouts.admin')

@section('title', 'Users')

@section('content')
<div class="max-w-6xl mx-auto mt-4 bg-white p-6 rounded-xl shadow-lg">

    <!-- Header -->
    <h2 class="text-2xl font-bold text-[#034E7A] mb-6">Users List</h2>

    <!-- Users Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-200 divide-y divide-gray-200">
            <thead class="bg-[#034E7A] text-white">
                <tr>
                    <th class="px-4 py-2 text-left w-16">#</th>
                    <th class="px-4 py-2 text-left">Name</th>
                    <th class="px-4 py-2 text-left">Email</th>
                    <th class="px-4 py-2 text-left">Email Verified</th>
                    <th class="px-4 py-2 text-left">Created At</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($users as $index => $user)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2">{{ $index + 1 }}</td>
                    <td class="px-4 py-2 font-medium text-gray-800">{{ $user->name }}</td>
                    <td class="px-4 py-2 text-gray-700">{{ $user->email }}</td>
                    <td class="px-4 py-2">
                        @if($user->email_verified_at)
                            <span class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">Verified</span>
                        @else
                            <span class="px-2 py-1 text-xs font-semibold text-red-700 bg-red-100 rounded-full">Not Verified</span>
                        @endif
                    </td>
                    <td class="px-4 py-2 text-gray-600">{{ $user->created_at ? $user->created_at->format('Y-m-d') : '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-4 text-center text-gray-500">
                        No users found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
