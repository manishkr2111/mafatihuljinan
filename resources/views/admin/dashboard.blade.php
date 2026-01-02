@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
<!-- Dashboard Stats Section -->
<div class="mt-1">
    @if(Auth::user()->role == 'admin')
    <!-- API Token Section -->
    <div class="bg-white rounded shadow p-5 mb-6">
        <h2 class="text-lg font-semibold text-[#034E7A] mb-3">API Token</h2>
        <form method="POST" action="{{ route('admin.regenerateToken') }}" onsubmit="return confirm('Are you sure you want to regenerate the API token?');">
            @csrf
            <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-3 mb-2 space-y-2 sm:space-y-0">
                <input
                    type="text"
                    class="flex-1 border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#034E7A]"
                    value="{{ $apiToken }}"
                    readonly>
                <button
                    type="submit"
                    class="bg-[#034E7A] text-white px-4 py-2 rounded hover:bg-[#02629B] transition w-full sm:w-auto">
                    Regenerate
                </button>
            </div>

            <p class="text-gray-500 text-sm">Keep this token safe. You can regenerate it if compromised.</p>
        </form>
    </div>
    @endif
</div>
<!-- Users Section -->
<div class="bg-white rounded shadow p-5 mt-6 overflow-y-auto max-h-[400px]">
    <h2 class="text-lg font-bold text-[#034E7A] mb-3">User Counts by Language</h2>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-2">
        <div class="bg-white rounded shadow p-2 text-center">
            <h3 class="text-sm font-medium text-[#034E7A]"><a href="{{ route('admin.users') }}">Total Users</a></h3>
            <p class="text-2xl font-bold text-[#034E7A] mt-2">{{$totalUsers}}</p>
        </div>

        @foreach ($userCount as $language => $count)
        <div class="bg-white rounded shadow p-2 text-center">
            <h3 class="text-sm font-medium text-[#034E7A]">{{ ucfirst($language) }}</h3>
            <p class="text-2xl font-bold text-[#034E7A] mt-2">{{$count}}</p>
        </div>
        @endforeach
    </div>
</div>
<!-- Post Counts Section -->
<div class="bg-white rounded shadow p-5 mt-6 ">
    <h2 class="text-lg font-bold text-[#034E7A] mb-3">Post Counts by Language</h2>
    <div class="overflow-y-auto max-h-[600px]">
        @foreach ($postCounts as $language => $posts)
        <h3 class="text-md font-bold text-[#034E7A] mt-4 mb-2">{{ ucfirst($language) }}</h3>

        <!-- Responsive grid: 2 cols (mobile), 3 cols (tablet), 4 cols (desktop) -->
        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
            @foreach ($posts as $type => $counts)
            <div class="bg-gray-50 rounded p-3 text-center shadow hover:shadow-md transition">
                <h4 class="text-sm font-medium text-[#034E7A]">
                    {{ str_replace('-', ' ', ucfirst($type)) }}
                </h4>
                <p class="text-xl font-bold text-[#034E7A] mt-1">
                    Total: {{ $counts['total'] }}
                </p>
                <hr>
                <p class="text-sm text-green-700 mt-1">
                <div class="mt-2 text-sm text-green-700">
                    <h5 class="font-semibold text-[#034E7A] mb-1">LRC Enabled For:</h5>
                    <ul class="space-y-1">
                        <li>
                            <a href="{{ route('admin.lrc.posts', ['language' => $language, 'postType' => $type, 'lrcType' => 'arabic']) }}"
                                target="_blank" class="text-green-700 hover:underline">
                                Arabic: {{ $counts['arabic_lrc_enabled_count'] ?? 0 }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.lrc.posts', ['language' => $language, 'postType' => $type, 'lrcType' => 'transliteration']) }}"
                                target="_blank" class="text-green-700 hover:underline">
                                Transliteration: {{ $counts['transliteration_lrc_enabled_count'] ?? 0 }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.lrc.posts', ['language' => $language, 'postType' => $type, 'lrcType' => 'translation']) }}"
                                target="_blank" class="text-green-700 hover:underline">
                                Translation: {{ $counts['translation_lrc_enabled_count'] ?? 0 }}
                            </a>
                        </li>
                    </ul>

                </div>
                </p>
            </div>
            @endforeach
        </div>
        @endforeach
    </div>
</div>

@endsection