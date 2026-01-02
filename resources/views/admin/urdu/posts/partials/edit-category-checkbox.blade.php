@foreach($categories as $category)
    @php
        $children = $category->allChildren()->get(); // fetch children collection
    @endphp
    <div class="ml-{{ $level ?? 0 }}">
        <label class="flex items-center gap-2 p-2 border-2 border-gray-200 rounded-lg hover:border-[#034E7A] hover:bg-blue-50 cursor-pointer transition">
            <input type="checkbox" name="category_ids[]" value="{{ $category->id }}"
                {{ in_array($category->id, $selectedIds) ? 'checked' : '' }}
                class="w-4 h-4 text-[#034E7A] border-gray-300 rounded focus:ring-[#034E7A] focus:ring-2">
            <span class="text-sm text-gray-700 font-medium">{{ $category->name }}</span>
        </label>

        @if($children->count())
            @include('admin.urdu.posts.partials.edit-category-checkbox', [
                'categories' => $children,
                'level' => ($level ?? 0) + 4,
                'selectedIds' => $selectedIds
            ])
        @endif
    </div>
@endforeach
