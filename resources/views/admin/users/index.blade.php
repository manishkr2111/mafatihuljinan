@extends('layouts.admin')

@section('title', 'Users')

@section('content')
<div class="min-h-screen bg-gray-100">
    <div class="w-full max-w-7xl mx-auto bg-white rounded-xl ">

        <!-- Header Title -->
        <div class="p-4 border-b border-gray-200">
            <h1 class="text-lg sm:text-xl font-bold text-[#034E7A]">Users Management</h1>
        </div>

        <!-- Filters -->
        <div class="p-4 border-b border-gray-200 flex flex-col space-y-3 sm:flex-row sm:items-center sm:space-x-4 sm:space-y-0">
            <!-- Search Input -->
            <input
                type="text"
                id="userSearch"
                placeholder="Search by Name or Email..."
                class="w-full sm:flex-1 px-4 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">

            <!-- Role Filter -->
            <select id="roleFilter" class="w-full sm:w-auto px-4 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                <option value="">All Roles</option>
                <option value="admin">Admin</option>
                <option value="editor">Editor</option>
                <option value="subscriber">Subscriber</option>
            </select>
        </div>

        <!-- Horizontal Scroll Container -->
        <div class="overflow-x-auto">
            <div class="min-w-max">
                <!-- Table Header -->
                <!-- Table Header -->
                <div class="grid grid-cols-[40px_150px_200px_80px_80px_120px_1fr] gap-0 bg-[#034E7A] text-white sticky top-0 z-10 text-xs sm:text-sm">
                    <div class="px-1 py-2 font-semibold truncate">#</div>
                    <div class="px-1 py-2 font-semibold truncate">Name</div>
                    <div class="px-1 py-2 font-semibold truncate">Email</div>
                    <div class="px-1 py-2 font-semibold truncate">Role</div>
                    <div class="px-1 py-2 font-semibold truncate">Verified</div>
                    <div class="px-1 py-2 font-semibold truncate">Created At</div>
                    <div class="px-1 py-2 font-semibold text-center">Actions</div>
                </div>

                <!-- Table Body -->
                <div class="divide-y divide-gray-200">
                    @forelse($users as $index => $user)
                    <div class="grid grid-cols-[40px_150px_200px_80px_80px_120px_1fr] gap-0 hover:bg-gray-50 items-center user-row text-xs sm:text-sm">
                        <div class="px-1 py-2 truncate">{{ $index + 1 }}</div>
                        <div class="px-1 py-2 font-medium text-gray-800 truncate">
                            <a href="{{ route('admin.users.show', $user->id) }}" class="hover:underline text-blue-600">
                                {{ $user->name }}
                            </a>
                        </div>
                        <div class="px-1 py-2 text-gray-700 truncate break-all">
                            {{ $user->email }}
                        </div>
                        <div class="px-1 py-2 text-gray-700 truncate">{{ $user->role }}</div>
                        <div class="px-1 py-2">
                            @if($user->email_verified_at)
                            <span class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full whitespace-nowrap">Yes</span>
                            @else
                            <span class="px-2 py-1 text-xs font-semibold text-red-700 bg-red-100 rounded-full whitespace-nowrap">No</span>
                            @endif
                        </div>
                        <div class="px-1 py-2 text-gray-600 truncate whitespace-nowrap">{{ $user->created_at ? $user->created_at->format('Y-m-d') : '-' }}</div>
                        <div class="px-1 py-2 flex items-center justify-center space-x-1 sm:space-x-2">
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="text-blue-600 px-2 py-1 rounded bg-blue-50 hover:bg-blue-100 transition whitespace-nowrap">Edit</a>
                            <a href="{{ route('admin.users.edit-role', $user->id) }}" class="text-blue-600 px-2 py-1 rounded bg-blue-50 hover:bg-blue-100 transition whitespace-nowrap">Edit Role</a>
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 px-2 py-1 rounded bg-red-50 hover:bg-red-100 transition whitespace-nowrap">Delete</button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <div class="px-4 py-8 text-center text-gray-500">
                        No users found.
                    </div>
                    @endforelse
                </div>

            </div>
        </div>

        <!-- Pagination -->
        <div class="px-4 py-4 border-t border-gray-200">
            {{ $users->links() }}
        </div>
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
            const role = row.children[3].textContent.toLowerCase();

            const matchesSearch = name.includes(filterText) || email.includes(filterText);
            const matchesRole = selectedRole === "" || role === selectedRole;

            row.style.display = (matchesSearch && matchesRole) ? '' : 'none';
        });
    }

    searchInput.addEventListener('input', filterUsers);
    roleFilter.addEventListener('change', filterUsers);
</script>
@endsection