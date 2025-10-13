@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
<div class="mt-1">
    <!-- API Token Section -->
    <div class="bg-white rounded shadow p-5 mb-6">
        <h2 class="text-lg font-semibold text-[#034E7A] mb-3">API Token</h2>
        <form method="POST" action="{{ route('admin.regenerateToken') }}">
            @csrf
            <div class="flex space-x-3 items-center mb-2">
                <input type="text" class="flex-1 border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#034E7A]"
                    value="{{ $apiToken }}" readonly>
                <button type="submit" class="bg-[#034E7A] text-white px-4 py-2 rounded hover:bg-[#02629B] transition">
                    Regenerate
                </button>
            </div>
            <p class="text-gray-500 text-sm">Keep this token safe. You can regenerate it if compromised.</p>
        </form>
    </div>

    <!-- Dashboard Stats Section -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-2">
        <div class="bg-white rounded shadow p-2 text-center">
            <h3 class="text-sm font-medium text-[#034E7A]">Total Users</h3>
            <p class="text-2xl font-bold text-[#034E7A] mt-2">120</p>
        </div>

        <div class="bg-white rounded shadow p-2 text-center">
            <h3 class="text-sm font-medium text-[#034E7A]">English User</h3>
            <p class="text-2xl font-bold text-[#034E7A] mt-2">45</p>
        </div>

        <div class="bg-white rounded shadow p-2 text-center">
            <h3 class="text-sm font-medium text-[#034E7A]">Hindi User</h3>
            <p class="text-2xl font-bold text-[#034E7A] mt-2">30</p>
        </div>
        <div class="bg-white rounded shadow p-2 text-center">
            <h3 class="text-sm font-medium text-[#034E7A]">Gujarati User</h3>
            <p class="text-2xl font-bold text-[#034E7A] mt-2">30</p>
        </div>
    </div>
</div>
@endsection