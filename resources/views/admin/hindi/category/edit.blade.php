@extends('layouts.admin')

@section('title', 'Edit Category')

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

    <form action="{{ route('admin.hindi.category.update', $category->id) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <!-- Sort Number -->
        <div>
            <label class="block font-medium mb-1 text-[#034E7A]">Sort Number:</label>
            <input type="number" name="sort_number" value="{{ old('sort_number', $category->sort_number) }}"
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#034E7A]">
        </div>

        <!-- Category Name -->
        <div>
            <label class="block font-medium mb-1 text-[#034E7A]">Category Name:</label>
            <input type="text" name="name" value="{{ old('name', $category->name) }}"
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#034E7A]" required>
        </div>

        <!-- Slug -->
        @php
        $baseUrl = rtrim(config('app.url'), '/') . '/';
        @endphp
        <div>
            <label class="block font-medium mb-1 text-[#034E7A]">Slug:</label>
            <div class="flex items-center rounded-lg overflow-hidden border border-gray-300 focus-within:ring-2 focus-within:ring-[#034E7A] bg-white transition">
                <span class="bg-gray-100 text-gray-600 px-3 py-3 text-sm whitespace-nowrap select-none">
                    {{ $baseUrl }}
                </span>
                <input type="text" name="slug" value="{{ old('slug', $category->slug) }}"
                    class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#034E7A]" required>
            </div>
        </div>

        <!-- Post Type -->
        <div>
            <label class="block font-medium mb-1 text-[#034E7A]">Post Type:</label>
            <select name="post_type" id="post_type" class="w-full border rounded px-3 py-2">
                <option value="">-- Select Post Type --</option>
                @foreach(HindiPostTypeOptions() as $value => $label)
                <option value="{{ $value }}" {{ old('post_type', $category->post_type ?? 'blog') == $value ? 'selected' : '' }}>
                    {{ $label }}
                </option>
                @endforeach
            </select>

        </div>

        <!-- Parent Category -->
        <div>
            <label class="block font-medium mb-1 text-[#034E7A]">Parent Category:</label>
            <select name="parent_id" id="parent_id" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#034E7A]">
                <option value="">-- Select Parent --</option>
                @foreach($categoryOptions as $id => $name)
                <option value="{{ $id }}" {{ (old('parent_id', $category->parent_id) == $id) ? 'selected' : '' }}>{{ $name }}</option>
                @endforeach
            </select>
        </div>

        <!-- JS to handle dynamic loading -->
        <script>
            document.getElementById('post_type').addEventListener('change', function() {
                const postType = this.value;
                const parentSelect = document.getElementById('parent_id');
                parentSelect.innerHTML = '<option value="">Loading...</option>';

                fetch(`{{ route('admin.hindi.category.parents') }}?post_type=${postType}`)
                    .then(response => response.json())
                    .then(data => {
                        parentSelect.innerHTML = '<option value="">-- Select Parent --</option>';
                        for (const id in data) {
                            parentSelect.innerHTML += `<option value="${id}">${data[id]}</option>`;
                        }
                    });
            });
        </script>


        <button type="submit"
            class="bg-[#034E7A] text-white px-4 py-2 rounded hover:bg-[#02629B] transition">
            Update Category
        </button>
    </form>
</div>
@endsection