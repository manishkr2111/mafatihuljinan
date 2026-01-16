@extends('layouts.admin')

@section('title', 'Fujarati Categories')

@section('content')
<div class="mt-10 bg-white p-6 rounded shadow">
    <div class="flex justify-between items-center mb-6">
        <a href="{{ route('admin.hindi.category.create') }}"
            class="bg-[#034E7A] text-white px-4 py-2 rounded hover:bg-[#02629B] transition">Create Category</a>
    </div>

    <!-- Filter Form -->
    <form method="GET" action="{{ route('admin.hindi.category.index') }}" class="mb-6 flex items-center space-x-3">
        <label class="font-medium text-[#034E7A]">Filter by Post:</label>
        <select name="post_type" onchange="this.form.submit()" class="border rounded px-3 py-2">
            <option value="">-- All Types --</option>
            @foreach(HindiPostTypeOptions() as $value => $label)
            <option value="{{ $value }}" {{ $postType == $value ? 'selected' : '' }}>
                {{ $label }}
            </option>
            @endforeach
        </select>


    </form>
    <div class="overflow-x-auto">
        <table class="w-full border border-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 border">ID</th>
                    <th class="p-3 border">Name</th>
                    <th class="p-3 border">Slug</th>
                    <th class="p-3 border">Post Type</th>
                    <th class="p-3 border">Parent</th>
                    <th class="p-3 border">Sort</th>
                    <th class="p-3 border">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                @include('admin.hindi.category.partials.category-row', ['category' => $category, 'level' => 0])
                @empty
                <tr>
                    <td colspan="7" class="text-center p-4 text-gray-500">No categories found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection