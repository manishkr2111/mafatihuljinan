@extends('layouts.admin')

@section('title', 'Create Category')

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

    <form action="{{ route('admin.gujarati.category.store') }}" method="POST" class="space-y-4">
        @csrf

        <!-- Sort Number -->
        <div>
            <label class="block font-medium mb-1 text-[#034E7A]">Sort Number:</label>
            <input type="number" name="sort_number" value="{{ old('sort_number', 0) }}"
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#034E7A]">
        </div>

        <!-- Category Name -->
        <div>
            <label class="block font-medium mb-1 text-[#034E7A]">Category Name:</label>
            <input type="text" name="name" value="{{ old('name') }}"
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#034E7A]" required>
        </div>

        <!-- Slug -->
        <div>
            <label class="block font-medium mb-1 text-[#034E7A]">Slug:</label>
            <input type="text" name="slug" value="{{ old('slug') }}"
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#034E7A]" required>
        </div>

        <!-- Post Type -->
        <div>
            <label class="block font-medium mb-1 text-[#034E7A]">Post Type:</label>
            <select name="post_type" id="post_type" class="w-full border rounded px-3 py-2">
                <option value="">-- Select Post Type --</option>
                @foreach(GujaratiPostTypeOptions() as $value => $label)
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
                <option value="{{ $id }}" {{ old('parent_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit"
            class="bg-[#034E7A] text-white px-4 py-2 rounded hover:bg-[#02629B] transition">
            Create Category
        </button>
    </form>
</div>

<!-- JS to dynamically load parent categories -->
<script>
    document.getElementById('post_type').addEventListener('change', function() {
        const postType = this.value;
        const parentSelect = document.getElementById('parent_id');
        parentSelect.innerHTML = '<option value="">Loading...</option>';

        fetch(`{{ route('admin.gujarati.category.parents') }}?post_type=${postType}`)
            .then(response => response.json())
            .then(data => {
                parentSelect.innerHTML = '<option value="">-- Select Parent --</option>';
                for (const id in data) {
                    parentSelect.innerHTML += `<option value="${id}">${data[id]}</option>`;
                }
            });
    });
</script>
@endsection