@extends('layouts.admin')

@section('title', 'Users')

@section('content')
<div class="h-screen bg-gray-100 p-4 flex flex-col">
    <div class="w-full max-w-7xl mx-auto bg-white rounded-xl shadow-lg flex flex-col h-full">

        <!-- Filters -->
        <div class="p-4 border-b border-gray-200 flex flex-col md:flex-row md:items-center md:space-x-4 space-y-2 md:space-y-0">
            <!-- Search Input -->
            <input
                type="text"
                id="userSearch"
                placeholder="Search by Name or Email..."
                class="w-full md:w-1/2 px-4 py-2 border rounded shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">

            <!-- Role Filter -->
            <select id="roleFilter" class="w-full md:w-1/4 px-4 py-2 border rounded shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">All Roles</option>
                <option value="admin">Admin</option>
                <option value="editor">Editor</option>
                <option value="subscriber">Subscriber</option>
                <!-- Add more roles as needed -->
            </select>
        </div>

        <!-- Table Header -->
        <div class="grid grid-cols-12 bg-[#034E7A] text-white sticky top-0 z-10 text-sm md:text-base w-full">
            <div class="col-span-1 px-2 py-2 font-semibold">#</div>
            <div class="col-span-2 px-2 py-2 font-semibold">Name</div>
            <div class="col-span-3 px-2 py-2 font-semibold">Email</div>
            <div class="col-span-1 px-2 py-2 font-semibold">Role</div>
            <div class="col-span-1 px-2 py-2 font-semibold">Verified</div>
            <div class="col-span-2 px-2 py-2 font-semibold">Created At</div>
            <div class="col-span-1 px-2 py-2 font-semibold text-center">Actions</div>
        </div>

        <!-- Table Body -->
        <div class="flex-1 overflow-y-auto w-full">
            @forelse($users as $index => $user)
            <div class="grid grid-cols-12 divide-y divide-gray-200 hover:bg-gray-50 items-center user-row w-full">
                <div class="col-span-1 px-2 py-2 text-sm"># {{ $index + 1 }}</div>
                <div class="col-span-2 px-2 py-2 font-medium text-gray-800 truncate">
                    <a href="{{ route('admin.users.show', $user->id) }}" class="hover:underline text-blue-600">
                        {{ $user->name }}
                    </a>
                </div>
                <div class="col-span-3 px-2 py-2 text-gray-700 truncate">{{ $user->email }}</div>
                <div class="col-span-1 px-2 py-2 text-gray-700 truncate">{{ $user->role }}</div>
                <div class="col-span-1 px-2 py-2">
                    @if($user->email_verified_at)
                    <span class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">Yes</span>
                    @else
                    <span class="px-2 py-1 text-xs font-semibold text-red-700 bg-red-100 rounded-full">No</span>
                    @endif
                </div>
                <div class="col-span-2 px-2 py-2 text-gray-600 text-sm">{{ $user->created_at ? $user->created_at->format('Y-m-d') : '-' }}</div>
                <div class="col-span-1 px-2 py-2 flex flex-col md:flex-row items-center justify-center space-y-1 md:space-y-0 md:space-x-2">
                    <a href="{{ route('admin.users.edit', $user->id) }}" class="text-blue-600 px-2 py-1 rounded bg-blue-50 hover:bg-blue-100 text-xs md:text-sm transition">Edit</a>
                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline text-xs md:text-sm">Delete</button>
                    </form>
                </div>
            </div>
            @empty
            <div class="px-4 py-4 text-center text-gray-500">
                No users found.
            </div>
            @endforelse
        </div>
    </div>
    <div class="px-4 py-4">
        {{ $users->links() }}
    </div>

</div>

<!-- JS Frontend Filter -->
<script>
    const searchInput = document.getElementById('userSearch');
    const roleFilter = document.getElementById('roleFilter');

    function filterUsers() {
        const filterText = searchInput.value.toLowerCase();
        const selectedRole = roleFilter.value.toLowerCase();

        document.querySelectorAll('.user-row').forEach(row => {
            const name = row.children[1].textContent.toLowerCase();
            const email = row.children[2].textContent.toLowerCase();
            const role = row.children[3].textContent.toLowerCase(); // role column

            const matchesSearch = name.includes(filterText) || email.includes(filterText);
            const matchesRole = selectedRole === "" || role === selectedRole;

            row.style.display = (matchesSearch && matchesRole) ? '' : 'none';
        });
    }

    searchInput.addEventListener('input', filterUsers);
    roleFilter.addEventListener('change', filterUsers);
</script>
@endsection