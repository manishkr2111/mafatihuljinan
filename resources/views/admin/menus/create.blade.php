@extends('layouts.admin')

@section('title', 'Create Menu')

@section('content')
<div class="max-w-lg mt-10 bg-white p-6 rounded shadow">
    @if ($errors->any())
    <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('admin.menus.store') }}" method="POST" class="space-y-4">
        @csrf
        <!-- Sort Number -->
        <div>
            <label class="block font-medium mb-1 text-[#034E7A]">Sort Number:</label>
            <input type="number" name="sort_number" value="{{ old('sort_number', 0) }}"
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#034E7A]"
                required>
        </div>

        <!-- Menu Name -->
        <div>
            <label class="block font-medium mb-1 text-[#034E7A]">Menu Name:</label>
            <input type="text" name="menu_name" value="{{ old('menu_name') }}"
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#034E7A]"
                required>
        </div>

        <!-- Language Dropdown -->
        <div>
            <label class="block font-medium mb-1 text-[#034E7A]">Language:</label>
            <select name="language"
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#034E7A]"
                required>
                <option value="">-- Select Language --</option>
                @foreach(validLanguages() as $language)
                <option value="{{ $language }}">{{ ucfirst($language) }}
                </option>
                @endforeach
                <!-- Add more languages here -->
            </select>
        </div>
        <div>
            <label class="block font-medium mb-1 text-[#034E7A]">Post Type:</label>
            <select name="post_type"
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#034E7A]"
                required>
                @foreach (commonPostTypeOptions() as $value => $label)
                <option value="{{ $value }}"
                    {{ old('post_type', $menu->post_type ?? null) == $value ? 'selected' : '' }}>
                    {{ $label }}
                </option>
                @endforeach
            </select>
        </div>

        <button type="submit"
            class="bg-[#034E7A] text-white px-4 py-2 rounded hover:bg-[#02629B] transition">
            Create Menu
        </button>
    </form>
</div>
@endsection