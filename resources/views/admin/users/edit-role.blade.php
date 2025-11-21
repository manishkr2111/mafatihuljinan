@extends('layouts.admin')

@section('title', 'Edit User Role')

@section('content')
<div class="max-w-md mt-10 bg-white p-6 rounded shadow">
    <h1 class="text-xl font-bold mb-4 text-[#034E7A]">Assign Role to {{ $user->name }}</h1>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.users.update-role', $user->id) }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label class="block font-medium mb-1 text-[#034E7A]">Role:</label>
            <select name="role" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#034E7A]" required>
                @foreach($roles as $key => $label)
                    <option value="{{ $key }}" {{ $user->role == $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="bg-[#034E7A] text-white px-4 py-2 rounded hover:bg-[#02629B] transition">Update Role</button>
    </form>
</div>
@endsection
