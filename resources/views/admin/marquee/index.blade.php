@extends('layouts.admin')

@section('title', 'Marquee Texts')

@section('content')
<div class="max-w-5xl mt-2 bg-white p-6 rounded-xl shadow-lg">

    <!-- Add Marquee Text Form -->
    <h2 class="text-2xl font-bold text-[#034E7A] mb-6">Add Marquee Text</h2>

    <form action="{{ route('admin.marquee.store') }}" method="POST" class="space-y-4">
        @csrf
        <div class="mb-4">
            <label class="block font-medium text-[#034E7A] mb-1">Marquee Text</label>
            <textarea name="text" rows="3" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#034E7A]" required></textarea>
        </div>

        <div class="mb-4">
            <label class="block font-medium text-[#034E7A] mb-1">Language</label>
            <select name="language" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#034E7A]" required>
                @foreach(validLanguages() as $language)
                <option value="{{ $language }}">{{ ucfirst($language) }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="bg-[#034E7A] text-white px-6 py-2 rounded hover:bg-[#02629B] transition">Add Text</button>
    </form>

    <!-- Existing Marquee Texts -->
    <div class="mt-10">
        <h2 class="text-2xl font-bold text-[#034E7A] mb-4">Marquee Texts List</h2>

        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-200 divide-y divide-gray-200">
                <thead class="bg-[#034E7A] text-white">
                    <tr>
                        <th class="px-4 py-2 text-left">Text</th>
                        <th class="px-4 py-2 text-left">Language</th>
                        <th class="px-4 py-2 text-left">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($marquees as $marquee)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2">{!! $marquee->text !!}</td>
                        <td class="px-4 py-2">{{ ucfirst($marquee->language) }}</td>
                        <td class="px-4 py-2 flex gap-2">
                            <a href="{{ route('admin.marquee.edit', $marquee->id) }}" class="text-[#034E7A] hover:underline">Edit</a>

                            <form action="{{ route('admin.marquee.delete', $marquee->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this text?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach

                    @if($marquees->isEmpty())
                    <tr>
                        <td colspan="3" class="px-4 py-2 text-center text-gray-500">No marquee texts found.</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
