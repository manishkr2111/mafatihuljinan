@extends('layouts.admin')

@section('title', 'Edit Deeplink Category')

@section('content')
<div class="max-w-lg mt-1 bg-white p-6 rounded shadow">
    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.english.category.deeplink.update', $category->id) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        @php
            $baseUrl = rtrim(config('app.url'), '/') . '/';
        @endphp

        <!-- Sort Number -->
        <div>
            <label class="block font-medium mb-1 text-[#034E7A]">Sort Number</label>
            <input type="number" name="sort_number"
                value="{{ old('sort_number', $category->sort_number) }}"
                class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-[#034E7A]">
        </div>

        <!-- Category Name (optional) -->
        <div>
            <label class="block font-medium mb-1 text-[#034E7A]">Category Name</label>
            <input type="text" name="name"
                value="{{ old('name', $category->name) }}"
                class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-[#034E7A]">
        </div>

        <!-- Deeplink URL (readonly) -->
        <div>
            <label class="block font-medium mb-1 text-[#034E7A]">Deeplink URL</label>
            <div class="flex items-center border rounded overflow-hidden">
                <span class="bg-gray-100 px-3 py-3 text-sm text-gray-600">
                    {{ $baseUrl }}
                </span>
                <input type="text"
                    name="deeplink_url"
                    value="{{ old('deeplink_url', $category->deeplink_url) }}"
                    class="w-full px-3 py-2 bg-gray-50">
            </div>
        </div>

        <!-- Slug (readonly) -->
        <div>
            <label class="block font-medium mb-1 text-[#034E7A]">Slug</label>
            <div class="flex items-center border rounded overflow-hidden">
                <span class="bg-gray-100 px-3 py-3 text-sm text-gray-600">
                    {{ $baseUrl }}
                </span>
                <input type="text"
                    value="{{ $category->slug }}"
                    readonly
                    class="w-full px-3 py-2 bg-gray-50 cursor-not-allowed">
            </div>
        </div>

        <!-- Post Type -->
        <div>
            <label class="block font-medium mb-1 text-[#034E7A]">Post Type</label>
            <select name="post_type" id="post_type" class="w-full border rounded px-3 py-2">
                @foreach(EnglishPostTypeOptions() as $value => $label)
                    <option value="{{ $value }}" {{ $category->post_type == $value ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Parent Category -->
        <div>
            <label class="block font-medium mb-1 text-[#034E7A]">Parent Category</label>
            <select name="parent_id" id="parent_id" class="w-full border rounded px-3 py-2">
                <option value="">-- Select Parent --</option>
                @foreach($categoryOptions as $id => $name)
                    <option value="{{ $id }}" {{ $category->parent_id == $id ? 'selected' : '' }}>
                        {{ $name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit"
            class="bg-[#034E7A] text-white px-4 py-2 rounded hover:bg-[#02629B] transition">
            Update Deeplink Category
        </button>
    </form>
</div>

<script>
document.getElementById('post_type').addEventListener('change', function () {
    fetch(`{{ route('admin.english.category.parents') }}?post_type=${this.value}`)
        .then(res => res.json())
        .then(data => {
            const parent = document.getElementById('parent_id');
            parent.innerHTML = '<option value="">-- Select Parent --</option>';
            for (const id in data) {
                parent.innerHTML += `<option value="${id}">${data[id]}</option>`;
            }
        });
});
</script>
@endsection
