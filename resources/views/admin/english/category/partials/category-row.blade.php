<tr class="border-t {{ $level > 0 ? 'bg-gray-50' : '' }}">
    <td class="p-3">{{ $category->id }}</td>
    <td class="p-3">{!! str_repeat('-', $level + 0) !!} {{ $category->name }}</td>
    <td class="p-3">{{ $category->slug }}</td>
    <td class="p-3">{{ $category->deeplink_url?? 'N/A' }}</td>
    <td class="p-3 border capitalize">{{ $category->post_type ?? '-' }}</td>
    <td class="p-3">{{ $category->parent ? $category->parent->name : '-' }}</td>
    <td class="p-3">{{ $category->sort_number ?? '-' }}</td>
    <td class="p-3 space-x-2">
        @if($category->deeplink_url)
        <a href="{{ route('admin.english.category.deeplink.edit', $category->id) }}" class="text-blue-600 hover:underline">Edit</a>
        @else
        <a href="{{ route('admin.english.category.edit', $category->id) }}" class="text-blue-600 hover:underline">Edit</a>
        @endif
        <form action="{{ route('admin.english.category.destroy', $category->id) }}" method="POST" class="inline-block"
            onsubmit="return confirm('Are you sure you want to delete this category?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-red-600 hover:underline">Delete</button>
        </form>
    </td>
</tr>

@if($category->children)
@foreach($category->children as $child)
@include('admin.english.category.partials.category-row', ['category' => $child, 'level' => $level + 1])
@endforeach
@endif