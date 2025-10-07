@extends('layouts.admin')

@section('content')
<style>
    .content-scrollable {
        height: calc(100vh - 70px); /* Adjust for header */
        overflow-y: auto;
        padding: 20px;
    }

    /* Table sorting arrows */
    th.asc::after {
        content: " ▲";
    }
    th.desc::after {
        content: " ▼";
    }

    /* Card headers */
    .card-header-custom {
        font-weight: 600;
        padding: 10px 15px;
        background-color: #3C1D71;
        color: #fff;
        border-top-left-radius: 20px;
        border-top-right-radius: 20px;
    }

    .table-responsive {
        max-height: 70vh;
        overflow-y: auto;
    }

    .btn-view {
        border-radius: 50px;
        padding: 0.25rem 0.75rem;
    }

    @media screen and (max-width: 500px) {
        .table-responsive {
            overflow-x: auto;
        }
    }
</style>

<div class="content-scrollable">
    <div class="container-fluid">

        <!-- Search Form -->
        <form method="GET" action="{{ route('users') }}" class="mb-4">
            <div class="input-group">
                <input type="text" name="search" class="form-control" style="max-width: 90vh;"
                    placeholder="Search users by Name or Email" value="{{ $search ?? '' }}">
                <button class="btn btn-primary" type="submit">Search</button>
                @if(!empty($search))
                <a href="{{ route('users') }}" class="btn btn-secondary ms-2">Clear Filter</a>
                @endif
            </div>
        </form>

        <!-- Users Card -->
        <div class="card shadow-sm rounded-4">
            <div class="card-header card-header-custom d-flex justify-content-between align-items-center">
                <span>All Users</span>
                <a href="" class="btn btn-light btn-sm">Add New</a>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered mb-0 align-middle">
                        <thead class="table-primary sticky-top">
                            <tr>
                                <th scope="col">S.No</th>
                                <th scope="col" data-sort="string">Name</th>
                                <th scope="col" data-sort="string">Email</th>
                                <th scope="col" data-sort="date">Registered At</th>
                                <th scope="col" data-sort="string">Status</th>
                                <th scope="col" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                            <tr>
                                <td>{{ $loop->iteration + ($users->firstItem() - 1) }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    <span class="badge bg-{{ $user->status == 'active' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($user->status) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('users.editOrUpdate', $user->id) }}"
                                        class="btn btn-outline-primary btn-sm btn-view">
                                        View
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox fs-4"></i> <br>
                                    No users found.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer bg-light border-0 d-flex justify-content-center">
                {{ $users->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const getCellValue = (tr, idx, type) => {
        const val = tr.children[idx].innerText || tr.children[idx].textContent;
        if(type === 'date') return new Date(val);
        return val.toLowerCase();
    };

    const comparer = (idx, type, asc) => (a, b) => {
        const v1 = getCellValue(a, idx, type);
        const v2 = getCellValue(b, idx, type);
        if(v1 > v2) return asc ? 1 : -1;
        if(v1 < v2) return asc ? -1 : 1;
        return 0;
    };

    document.querySelectorAll('th[data-sort]').forEach(th => {
        th.addEventListener('click', () => {
            const table = th.closest('table');
            const tbody = table.querySelector('tbody');
            const type = th.dataset.sort;
            const idx = Array.from(th.parentNode.children).indexOf(th);
            const asc = !th.classList.contains('asc');

            Array.from(tbody.querySelectorAll('tr'))
                .sort(comparer(idx, type, asc))
                .forEach(tr => tbody.appendChild(tr));

            th.parentNode.querySelectorAll('th').forEach(th2 => th2.classList.remove('asc', 'desc'));
            th.classList.toggle('asc', asc);
            th.classList.toggle('desc', !asc);
        });
    });
});
</script>
@endsection
