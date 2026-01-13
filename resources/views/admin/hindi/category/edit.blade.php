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

    <form action="{{ route('admin.hindi.category.update', $category->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
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
        <!-- Popup Image (Only for Amaal & Namaz) -->
        <div id="popupImageWrapper" class="hidden">
            <div class="flex gap-2 justify-between">
                <label class="block font-medium mb-1 text-[#034E7A]">
                    Popup Image (JPG / PNG):
                </label>
                @if($category->popup_image)
                <div class="mb-2 bg-[#034E7A] text-white px-4 py-1 rounded">
                    <a href="{{ asset('storage/' . $category->popup_image) }}" target="_blank">
                        View Popup Image
                    </a>
                </div>
                @endif
            </div>
            <input type="file" name="popup_image"
                accept="image/png,image/jpeg"
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#034E7A]">
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
        <button type="submit"
            class="bg-[#034E7A] text-white px-4 py-2 rounded hover:bg-[#02629B] transition">
            Update Category
        </button>
    </form>
</div>
<!-- JS to handle dynamic loading -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const postTypeSelect = document.getElementById('post_type');
        const popupImageWrapper = document.getElementById('popupImageWrapper');
        const parentSelect = document.getElementById('parent_id');

        function handlePostTypeChange() {
            const postType = postTypeSelect.value;

            // Show / hide popup image
            if (postType === 'amaal-namaz') {
                popupImageWrapper.classList.remove('hidden');
            } else {
                popupImageWrapper.classList.add('hidden');
            }

            // Load parent categories
            parentSelect.innerHTML = '<option value="">Loading...</option>';

            fetch(`{{ route('admin.hindi.category.parents') }}?post_type=${postType}`)
                .then(response => response.json())
                .then(data => {
                    parentSelect.innerHTML = '<option value="">-- Select Parent --</option>';

                    for (const id in data) {
                        const selected = id == "{{ old('parent_id', $category->parent_id) }}" ? 'selected' : '';
                        parentSelect.innerHTML += `<option value="${id}" ${selected}>${data[id]}</option>`;
                    }
                });
        }

        // On change
        postTypeSelect.addEventListener('change', handlePostTypeChange);

        // On page load (edit + validation error)
        if (postTypeSelect.value) {
            handlePostTypeChange();
        }
    });
</script>
@endsection