@extends('layouts.admin')
@section('title', 'Menus')
@section('content')
<div class=" mt-10">

    <!-- Top controls: Create + Filter + Count -->
    <div class="flex justify-between items-center mb-6">
        <a href="{{ route('admin.menus.create') }}"
            class="bg-[#034E7A] text-white px-2 py-1 rounded hover:bg-[#02629B] transition">
            Create New Menu
        </a>

        <div class="flex items-center space-x-4">
            <!-- Language Filter -->
            <div>
                <label for="languageFilter" class="mr-2 font-medium text-[#034E7A]">Filter by Language:</label>
                <select id="languageFilter" class="border rounded px-2 py-1 focus:outline-none focus:ring-2 focus:ring-[#034E7A]">
                    <option value="">All</option>
                    <option value="english">English</option>
                    <option value="hindi">Hindi</option>
                    <option value="gujarati">Gujarati</option>
                    <option value="french">French</option>
                    <option value="spanish">Spanish</option>
                    <!-- Add more languages here -->
                </select>
            </div>

            <!-- Total Count -->
            <div class="font-medium text-[#034E7A]">
                Total: <span id="menuCount">{{ $menus->count() }}</span>
            </div>
        </div>
    </div>

    <div class="overflow-x-auto bg-white rounded shadow">
        <table class="min-w-full border-collapse" id="menusTable">
            <thead>
                <tr class="bg-[#034E7A] text-white text-left">
                    <th class="px-5 py-1 border-b border-gray-151">#</th>
                    <th class="px-2 py-1 border-b border-gray-151">Sort Number</th>
                    <th class="px-2 py-1 border-b border-gray-151">Menu Name</th>
                    <th class="px-2 py-1 border-b border-gray-151">Language</th>
                    <th class="px-2 py-1 border-b border-gray-151">Action</th>
                </tr>
            </thead>
            <tbody>
                @php $counter = 1; @endphp
                @foreach($menus as $menu)
                <tr class="border-b border-gray-200 hover:bg-[#E6F0F8]">
                    <!-- Serial Number -->
                    <td class="px-6 py-3 text-[#034E7A] font-medium">{{ $counter++ }}</td>

                    <td class="px-6 py-3 text-[#034E7A]">{{ $menu->sort_number }}</td>
                    <td class="px-6 py-3 text-[#034E7A]">{{ $menu->menu_name }}</td>
                    <td class="px-6 py-3 text-[#034E7A]">{{ $menu->language }}</td>
                    <td class="px-6 py-3">
                        <a href="{{ route('admin.menus.edit', $menu->id) }}"
                            class="text-white bg-[#034E7A] px-3 py-1 rounded hover:bg-[#02629B] transition">
                            Edit
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>


        </table>
    </div>
</div>

<!-- Filter + Count JS -->
<script>
    const languageFilter = document.getElementById('languageFilter');
    const menuCount = document.getElementById('menuCount');

    function updateTable() {
        const selected = languageFilter.value.toLowerCase();
        const rows = document.querySelectorAll('#menusTable tbody tr');
        let count = 0;

        rows.forEach(row => {
            const lang = row.querySelector('.menu-language').textContent.toLowerCase();
            if (!selected || lang === selected) {
                row.style.display = '';
                count++;
            } else {
                row.style.display = 'none';
            }
        });

        menuCount.textContent = count;
    }

    languageFilter.addEventListener('change', updateTable);
</script>
@endsection