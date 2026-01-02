<aside class="w-64 pl-4 pb-[60px] md:pb-0 max-h-full bg-white shadow h-screen flex flex-col h-full">
    <!-- Sidebar Header -->
    <div class="flex items-center justify-between px-4 py-3 border-b">
        <div class="flex items-center space-x-2">
            <img src="{{ asset('storage/website/mafa-logo.jpg') }}" alt="Logo" class="w-8 h-8 rounded">
            <span class="font-semibold text-lg">Mafatihuljinan</span>
        </div>
    </div>

    <!-- Sidebar Content -->
    <div class="flex-1 overflow-y-auto mt-2 px-2">
        <!-- Main Section -->
        <div class="mb-4">
            <div class="text-gray-500 uppercase text-xs font-bold mb-2">Main</div>
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('admin.dashboard') }}"
                        class="flex items-center px-2 py-2 rounded hover:bg-[#034E7A] hover:text-[#fff] {{ Route::is('admin.dashboard') ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                        <i class="fas fa-home mr-2"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.uploadAudioPage', ['language' => 'english']) }}"
                        class="flex items-center px-2 py-2 rounded hover:bg-[#034E7A] hover:text-[#fff] {{ Route::is('admin.uploadAudioPage', ['language' => 'english']) ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                        <i class="fas fa-microphone mr-2"></i> Upload Audio
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.menus.index') }}"
                        class="flex items-center px-2 py-2 rounded hover:bg-[#034E7A] hover:text-[#fff] {{ Route::is('admin.menus.index') ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                        <i class="fa fa-bars mr-2"></i> Menu Item
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.hijri.date.event') }}"
                        class="flex items-center px-2 py-2 rounded hover:bg-[#034E7A] hover:text-[#fff] {{ Route::is('admin.hijri.date.event') ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                        <i class="fa fa-calendar mr-2"></i> Hijri Date/Event
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.eventpopup') }}"
                        class="flex items-center px-2 py-2 rounded hover:bg-[#034E7A] hover:text-[#fff] {{ Route::is('admin.eventpopup') ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                        <i class="fa fa-file-text mr-2"></i> Event Pop Up
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.marquee.index') }}"
                        class="flex items-center px-2 py-2 rounded hover:bg-[#034E7A] hover:text-[#fff] {{ Route::is('admin.marquee.index') ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                        <i class="fa fa-file-text mr-2"></i> Marquee Text
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.notifications.schedule.index') }}"
                        class="flex items-center px-2 py-2 rounded hover:bg-[#034E7A] hover:text-[#fff] {{ Route::is('admin.notifications.schedule.*') ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                        <i class="fa fa-file-text mr-2"></i> English Notification
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.tafsir.index') }}"
                        class="flex items-center px-2 py-2 rounded hover:bg-[#034E7A] hover:text-[#fff] {{ Route::is('admin.tafsir.index') ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                        <i class="fa fa-file-text mr-2"></i> Tafsir
                    </a>
                </li>
            </ul>
        </div>

        <!-- All Dua's Section -->
        <div class="mb-4">
            <div class="text-gray-500 uppercase text-xs font-bold mb-2">All Post / Dua</div>
            <ul class="space-y-1">
                <!-- English -->
                <li>
                    <details class="group" {{ Route::is('admin.english.*') ? 'open' : '' }}>
                        <summary class="flex justify-between items-center px-2 py-2 cursor-pointer rounded hover:bg-[#034E7A] hover:text-[#fff]">
                            <span><i class="fa fa-language mr-2"></i> English</span>
                            <i class="fas fa-chevron-down transition-transform duration-200 group-open:rotate-180"></i>
                        </summary>
                        <ul class="pl-6 mt-1 space-y-1">
                            <li>
                                <a href="{{ route('admin.english.category.index') }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] {{ Route::is('admin.english.category.*') ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-list-alt mr-2"></i> Categories
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.english.post.index', ['post_type' => 'sahifas-ahlulbayt']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff]  
                                    {{ Route::is('admin.english.post.*') && request('post_type') === 'sahifas-ahlulbayt' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Sahifas Ahlulbayt
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.english.post.index', ['post_type' => 'dua']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.english.post.*') && request('post_type') === 'dua' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Dua
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.english.post.index', ['post_type' => 'daily-dua']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.english.post.*') && request('post_type') === 'daily-dua' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Daily Dua
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.english.post.index', ['post_type' => 'surah']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.english.post.*') && request('post_type') === 'surah' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Surah (Holy Quran)
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.english.post.index', ['post_type' => 'amaal']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.english.post.*') && request('post_type') === 'amaal' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Amaal
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.english.post.index', ['post_type' => 'amaal-namaz']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.english.post.*') && request('post_type') === 'amaal-namaz' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Amaal Namaz
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.english.post.index', ['post_type' => 'munajat']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.english.post.*') && request('post_type') === 'munajat' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Munajat
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.english.post.index', ['post_type' => 'salwaat']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.english.post.*') && request('post_type') === 'salwaat' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Salwaat
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.english.post.index', ['post_type' => 'salaat-namaz']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.english.post.*') && request('post_type') === 'salaat-namaz' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Salaat Namaz
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.english.post.index', ['post_type' => 'tasbih']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.english.post.*') && request('post_type') === 'tasbih' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Tasbih
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.english.post.index', ['post_type' => 'ziyarat']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.english.post.*') && request('post_type') === 'ziyarat' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Ziyarat
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.english.post.index', ['post_type' => 'travel-ziyarat']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.english.post.*') && request('post_type') === 'travel-ziyarat' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Travel Ziyarat
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.english.post.index', ['post_type' => 'essential-supplications']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.english.post.*') && request('post_type') === 'essential-supplications' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Essential Supplications
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.english.post.index', ['post_type' => 'burial-acts-prayers']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.english.post.*') && request('post_type') === 'burial-acts-prayers' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Burial Acts Prayers
                                </a>
                            </li>
                        </ul>
                    </details>
                </li>
                <!-- Gujarati -->
                <li>
                    <details class="group" {{ Route::is('admin.gujarati.*') ? 'open' : '' }}>
                        <summary class="flex justify-between items-center px-2 py-2 cursor-pointer rounded hover:bg-[#034E7A] hover:text-[#fff]">
                            <span><i class="fa fa-language mr-2"></i> Gujarati</span>
                            <i class="fas fa-chevron-down transition-transform duration-200 group-open:rotate-180"></i>
                        </summary>
                        <ul class="pl-6 mt-1 space-y-1">
                            <li>
                                <a href="{{ route('admin.gujarati.category.index') }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] {{ Route::is('admin.gujarati.category.*') ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-list-alt mr-2"></i> Categories
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.gujarati.post.index', ['post_type' => 'sahifas-ahlulbayt']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff]  
                                    {{ Route::is('admin.gujarati.post.*') && request('post_type') === 'sahifas-ahlulbayt' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Sahifas Ahlulbayt
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.gujarati.post.index', ['post_type' => 'dua']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.gujarati.post.*') && request('post_type') === 'dua' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Dua
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.gujarati.post.index', ['post_type' => 'daily-dua']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.gujarati.post.*') && request('post_type') === 'daily-dua' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Daily Dua
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.gujarati.post.index', ['post_type' => 'surah']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.gujarati.post.*') && request('post_type') === 'surah' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Surah (Holy Quran)
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.gujarati.post.index', ['post_type' => 'amaal']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.gujarati.post.*') && request('post_type') === 'amaal' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Amaal
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.gujarati.post.index', ['post_type' => 'amaal-namaz']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.gujarati.post.*') && request('post_type') === 'amaal-namaz' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Amaal Namaz
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.gujarati.post.index', ['post_type' => 'munajat']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.gujarati.post.*') && request('post_type') === 'munajat' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Munajat
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.gujarati.post.index', ['post_type' => 'salwaat']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.gujarati.post.*') && request('post_type') === 'salwaat' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Salwaat
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.gujarati.post.index', ['post_type' => 'salaat-namaz']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.gujarati.post.index') && request('post_type') === 'salaat-namaz' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Salaat Namaz
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.gujarati.post.index', ['post_type' => 'tasbih']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.gujarati.post.*') && request('post_type') === 'tasbih' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Tasbih
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.gujarati.post.index', ['post_type' => 'ziyarat']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.gujarati.post.*') && request('post_type') === 'ziyarat' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Ziyarat
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.gujarati.post.index', ['post_type' => 'travel-ziyarat']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.gujarati.post.*') && request('post_type') === 'travel-ziyarat' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Travel Ziyarat
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.gujarati.post.index', ['post_type' => 'essential-supplications']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.gujarati.post.*') && request('post_type') === 'essential-supplications' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Essential Supplications
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.gujarati.post.index', ['post_type' => 'burial-acts-prayers']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.gujarati.post.*') && request('post_type') === 'burial-acts-prayers' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Burial Acts Prayers
                                </a>
                            </li>
                        </ul>
                    </details>
                </li>

                <!-- Hindi -->
                <li>
                    <details class="group" {{ Route::is('admin.hindi.*') ? 'open' : '' }}>
                        <summary class="flex justify-between items-center px-2 py-2 cursor-pointer rounded hover:bg-[#034E7A] hover:text-[#fff]">
                            <span><i class="fa fa-language mr-2"></i> Hindi</span>
                            <i class="fas fa-chevron-down transition-transform duration-200 group-open:rotate-180"></i>
                        </summary>
                        <ul class="pl-6 mt-1 space-y-1">
                            <li>
                                <a href="{{ route('admin.hindi.category.index') }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] {{ Route::is('admin.hindi.category.*') ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-list-alt mr-2"></i> Categories
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.hindi.post.index', ['post_type' => 'sahifas-ahlulbayt']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff]  
                                    {{ Route::is('admin.hindi.post.*') && request('post_type') === 'sahifas-ahlulbayt' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Sahifas Ahlulbayt
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.hindi.post.index', ['post_type' => 'dua']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.hindi.post.*') && request('post_type') === 'dua' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Dua
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.hindi.post.index', ['post_type' => 'daily-dua']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.hindi.post.*') && request('post_type') === 'daily-dua' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Daily Dua
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.hindi.post.index', ['post_type' => 'surah']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.hindi.post.*') && request('post_type') === 'surah' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Surah (Holy Quran)
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.hindi.post.index', ['post_type' => 'amaal']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.hindi.post.*') && request('post_type') === 'amaal' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Amaal
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.hindi.post.index', ['post_type' => 'amaal-namaz']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.hindi.post.*') && request('post_type') === 'amaal-namaz' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Amaal Namaz
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.hindi.post.index', ['post_type' => 'munajat']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.hindi.post.*') && request('post_type') === 'munajat' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Munajat
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.hindi.post.index', ['post_type' => 'salwaat']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.hindi.post.*') && request('post_type') === 'salwaat' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Salwaat
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.hindi.post.index', ['post_type' => 'salaat-namaz']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.hindi.post.index') && request('post_type') === 'salaat-namaz' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Salaat Namaz
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.hindi.post.index', ['post_type' => 'tasbih']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.hindi.post.*') && request('post_type') === 'tasbih' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Tasbih
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.hindi.post.index', ['post_type' => 'ziyarat']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.hindi.post.*') && request('post_type') === 'ziyarat' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Ziyarat
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.hindi.post.index', ['post_type' => 'travel-ziyarat']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.hindi.post.*') && request('post_type') === 'travel-ziyarat' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Travel Ziyarat
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.hindi.post.index', ['post_type' => 'essential-supplications']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.hindi.post.*') && request('post_type') === 'essential-supplications' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Essential Supplications
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.hindi.post.index', ['post_type' => 'burial-acts-prayers']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.hindi.post.*') && request('post_type') === 'burial-acts-prayers' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Burial Acts Prayers
                                </a>
                            </li>
                        </ul>
                    </details>
                </li>

                <!-- Urdu -->
                <li>
                    <details class="group" {{ Route::is('admin.urdu.*') ? 'open' : '' }}>
                        <summary class="flex justify-between items-center px-2 py-2 cursor-pointer rounded hover:bg-[#034E7A] hover:text-[#fff]">
                            <span><i class="fa fa-language mr-2"></i> Urdu</span>
                            <i class="fas fa-chevron-down transition-transform duration-200 group-open:rotate-180"></i>
                        </summary>
                        <ul class="pl-6 mt-1 space-y-1">
                            <li>
                                <a href="{{ route('admin.urdu.category.index') }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] {{ Route::is('admin.urdu.category.*') ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-list-alt mr-2"></i> Categories
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.urdu.post.index', ['post_type' => 'sahifas-ahlulbayt']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff]  
                                    {{ Route::is('admin.urdu.post.*') && request('post_type') === 'sahifas-ahlulbayt' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Sahifas Ahlulbayt
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.urdu.post.index', ['post_type' => 'dua']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.urdu.post.*') && request('post_type') === 'dua' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Dua
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.urdu.post.index', ['post_type' => 'daily-dua']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.urdu.post.*') && request('post_type') === 'daily-dua' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Daily Dua
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.urdu.post.index', ['post_type' => 'surah']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.urdu.post.*') && request('post_type') === 'surah' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Surah (Holy Quran)
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.urdu.post.index', ['post_type' => 'amaal']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.urdu.post.*') && request('post_type') === 'amaal' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Amaal
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.urdu.post.index', ['post_type' => 'amaal-namaz']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.urdu.post.*') && request('post_type') === 'amaal-namaz' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Amaal Namaz
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.urdu.post.index', ['post_type' => 'munajat']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.urdu.post.*') && request('post_type') === 'munajat' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Munajat
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.urdu.post.index', ['post_type' => 'salwaat']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.urdu.post.*') && request('post_type') === 'salwaat' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Salwaat
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.urdu.post.index', ['post_type' => 'salaat-namaz']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.urdu.post.index') && request('post_type') === 'salaat-namaz' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Salaat Namaz
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.urdu.post.index', ['post_type' => 'tasbih']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.urdu.post.*') && request('post_type') === 'tasbih' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Tasbih
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.urdu.post.index', ['post_type' => 'ziyarat']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.urdu.post.*') && request('post_type') === 'ziyarat' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Ziyarat
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.urdu.post.index', ['post_type' => 'travel-ziyarat']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.urdu.post.*') && request('post_type') === 'travel-ziyarat' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Travel Ziyarat
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.urdu.post.index', ['post_type' => 'essential-supplications']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.urdu.post.*') && request('post_type') === 'essential-supplications' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Essential Supplications
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.urdu.post.index', ['post_type' => 'burial-acts-prayers']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.urdu.post.*') && request('post_type') === 'burial-acts-prayers' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Burial Acts Prayers
                                </a>
                            </li>
                        </ul>
                    </details>
                </li>
                <!-- Roman Urdu -->
                <li>
                    <details class="group" {{ Route::is('admin.roman-urdu.*') ? 'open' : '' }}>
                        <summary class="flex justify-between items-center px-2 py-2 cursor-pointer rounded hover:bg-[#034E7A] hover:text-[#fff]">
                            <span><i class="fa fa-language mr-2"></i> Roman Urdu</span>
                            <i class="fas fa-chevron-down transition-transform duration-200 group-open:rotate-180"></i>
                        </summary>
                        <ul class="pl-6 mt-1 space-y-1">
                            <li>
                                <a href="{{ route('admin.roman-urdu.category.index') }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] {{ Route::is('admin.roman-urdu.category.*') ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-list-alt mr-2"></i> Categories
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.roman-urdu.post.index', ['post_type' => 'sahifas-ahlulbayt']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff]  
                                    {{ Route::is('admin.roman-urdu.post.*') && request('post_type') === 'sahifas-ahlulbayt' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Sahifas Ahlulbayt
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.roman-urdu.post.index', ['post_type' => 'dua']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.roman-urdu.post.*') && request('post_type') === 'dua' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Dua
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.roman-urdu.post.index', ['post_type' => 'daily-dua']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.roman-urdu.post.*') && request('post_type') === 'daily-dua' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Daily Dua
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.roman-urdu.post.index', ['post_type' => 'surah']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.roman-urdu.post.*') && request('post_type') === 'surah' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Surah (Holy Quran)
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.roman-urdu.post.index', ['post_type' => 'amaal']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.roman-urdu.post.*') && request('post_type') === 'amaal' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Amaal
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.roman-urdu.post.index', ['post_type' => 'amaal-namaz']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.roman-urdu.post.*') && request('post_type') === 'amaal-namaz' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Amaal Namaz
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.roman-urdu.post.index', ['post_type' => 'munajat']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.roman-urdu.post.*') && request('post_type') === 'munajat' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Munajat
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.roman-urdu.post.index', ['post_type' => 'salwaat']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.roman-urdu.post.*') && request('post_type') === 'salwaat' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Salwaat
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.roman-urdu.post.index', ['post_type' => 'salaat-namaz']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.roman-urdu.post.index') && request('post_type') === 'salaat-namaz' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Salaat Namaz
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.roman-urdu.post.index', ['post_type' => 'tasbih']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.roman-urdu.post.*') && request('post_type') === 'tasbih' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Tasbih
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.roman-urdu.post.index', ['post_type' => 'ziyarat']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.roman-urdu.post.*') && request('post_type') === 'ziyarat' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Ziyarat
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.roman-urdu.post.index', ['post_type' => 'travel-ziyarat']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.roman-urdu.post.*') && request('post_type') === 'travel-ziyarat' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Travel Ziyarat
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.roman-urdu.post.index', ['post_type' => 'essential-supplications']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.roman-urdu.post.*') && request('post_type') === 'essential-supplications' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Essential Supplications
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.roman-urdu.post.index', ['post_type' => 'burial-acts-prayers']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.roman-urdu.post.*') && request('post_type') === 'burial-acts-prayers' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Burial Acts Prayers
                                </a>
                            </li>
                        </ul>
                    </details>
                </li>
                <!-- French -->
                <li>
                    <details class="group" {{ Route::is('admin.french.*') ? 'open' : '' }}>
                        <summary class="flex justify-between items-center px-2 py-2 cursor-pointer rounded hover:bg-[#034E7A] hover:text-[#fff]">
                            <span><i class="fa fa-language mr-2"></i> French</span>
                            <i class="fas fa-chevron-down transition-transform duration-200 group-open:rotate-180"></i>
                        </summary>
                        <ul class="pl-6 mt-1 space-y-1">
                            <li>
                                <a href="{{ route('admin.french.category.index') }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] {{ Route::is('admin.french.category.*') ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-list-alt mr-2"></i> Categories
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.french.post.index', ['post_type' => 'sahifas-ahlulbayt']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff]  
                                    {{ Route::is('admin.french.post.*') && request('post_type') === 'sahifas-ahlulbayt' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Sahifas Ahlulbayt
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.french.post.index', ['post_type' => 'dua']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.french.post.*') && request('post_type') === 'dua' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Dua
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.french.post.index', ['post_type' => 'daily-dua']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.french.post.*') && request('post_type') === 'daily-dua' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Daily Dua
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.french.post.index', ['post_type' => 'surah']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.french.post.*') && request('post_type') === 'surah' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Surah (Holy Quran)
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.french.post.index', ['post_type' => 'amaal']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.french.post.*') && request('post_type') === 'amaal' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Amaal
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.french.post.index', ['post_type' => 'amaal-namaz']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.french.post.*') && request('post_type') === 'amaal-namaz' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Amaal Namaz
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.french.post.index', ['post_type' => 'munajat']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.french.post.*') && request('post_type') === 'munajat' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Munajat
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.french.post.index', ['post_type' => 'salwaat']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.french.post.*') && request('post_type') === 'salwaat' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Salwaat
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.french.post.index', ['post_type' => 'salaat-namaz']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.french.post.index') && request('post_type') === 'salaat-namaz' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Salaat Namaz
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.french.post.index', ['post_type' => 'tasbih']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.french.post.*') && request('post_type') === 'tasbih' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Tasbih
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.french.post.index', ['post_type' => 'ziyarat']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.french.post.*') && request('post_type') === 'ziyarat' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Ziyarat
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.french.post.index', ['post_type' => 'travel-ziyarat']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.french.post.*') && request('post_type') === 'travel-ziyarat' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Travel Ziyarat
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.french.post.index', ['post_type' => 'essential-supplications']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.french.post.*') && request('post_type') === 'essential-supplications' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Essential Supplications
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.french.post.index', ['post_type' => 'burial-acts-prayers']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.french.post.*') && request('post_type') === 'burial-acts-prayers' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Burial Acts Prayers
                                </a>
                            </li>
                        </ul>
                    </details>
                </li>
                <!-- Swahili -->
                <li>
                    <details class="group" {{ Route::is('admin.swahili.*') ? 'open' : '' }}>
                        <summary class="flex justify-between items-center px-2 py-2 cursor-pointer rounded hover:bg-[#034E7A] hover:text-[#fff]">
                            <span><i class="fa fa-language mr-2"></i> Swahili</span>
                            <i class="fas fa-chevron-down transition-transform duration-200 group-open:rotate-180"></i>
                        </summary>
                        <ul class="pl-6 mt-1 space-y-1">
                            <li>
                                <a href="{{ route('admin.swahili.category.index') }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] {{ Route::is('admin.swahili.category.*') ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-list-alt mr-2"></i> Categories
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.swahili.post.index', ['post_type' => 'sahifas-ahlulbayt']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff]  
                                    {{ Route::is('admin.swahili.post.*') && request('post_type') === 'sahifas-ahlulbayt' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Sahifas Ahlulbayt
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.swahili.post.index', ['post_type' => 'dua']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.swahili.post.*') && request('post_type') === 'dua' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Dua
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.swahili.post.index', ['post_type' => 'daily-dua']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.swahili.post.*') && request('post_type') === 'daily-dua' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Daily Dua
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.swahili.post.index', ['post_type' => 'surah']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.swahili.post.*') && request('post_type') === 'surah' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Surah (Holy Quran)
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.swahili.post.index', ['post_type' => 'amaal']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.swahili.post.*') && request('post_type') === 'amaal' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Amaal
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.swahili.post.index', ['post_type' => 'amaal-namaz']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.swahili.post.*') && request('post_type') === 'amaal-namaz' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Amaal Namaz
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.swahili.post.index', ['post_type' => 'munajat']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.swahili.post.*') && request('post_type') === 'munajat' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Munajat
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.swahili.post.index', ['post_type' => 'salwaat']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.swahili.post.*') && request('post_type') === 'salwaat' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Salwaat
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.swahili.post.index', ['post_type' => 'salaat-namaz']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.swahili.post.index') && request('post_type') === 'salaat-namaz' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Salaat Namaz
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.swahili.post.index', ['post_type' => 'tasbih']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.swahili.post.*') && request('post_type') === 'tasbih' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Tasbih
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.swahili.post.index', ['post_type' => 'ziyarat']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.swahili.post.*') && request('post_type') === 'ziyarat' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Ziyarat
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.swahili.post.index', ['post_type' => 'travel-ziyarat']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.swahili.post.*') && request('post_type') === 'travel-ziyarat' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Travel Ziyarat
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.swahili.post.index', ['post_type' => 'essential-supplications']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.swahili.post.*') && request('post_type') === 'essential-supplications' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Essential Supplications
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.swahili.post.index', ['post_type' => 'burial-acts-prayers']) }}"
                                    class="flex items-center px-2 py-1 rounded hover:bg-[#034E7A] hover:text-[#fff] 
                                    {{ Route::is('admin.swahili.post.*') && request('post_type') === 'burial-acts-prayers' ? 'bg-[#034E7A] text-[#fff] font-semibold' : '' }}">
                                    <i class="fa fa-pencil-alt mr-2"></i> Burial Acts Prayers
                                </a>
                            </li>
                        </ul>
                    </details>
                </li>
            </ul>
        </div>

        <!-- Management Section -->
        <div class="mb-4">
            <div class="text-gray-500 uppercase text-xs font-bold mb-2">Management</div>
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('admin.post.search') }}"
                        class="flex items-center px-2 py-1 rounded 
                      hover:bg-[#034E7A] hover:text-white 
                      {{ Route::is('admin.post.search') ? 'bg-[#034E7A] text-white font-semibold' : '' }}">
                        <i class="fa fa-search mr-2"></i> Search Posts
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.users') }}"
                        class="flex items-center px-2 py-1 rounded 
                      hover:bg-[#034E7A] hover:text-white 
                      {{ Route::is('admin.users') || Route::is('admin.users.*') ? 'bg-[#034E7A] text-white font-semibold' : '' }}">
                        <i class="fas fa-users mr-2"></i> Users
                    </a>
                </li>

                <li>
                    <a href="#"
                        class="flex items-center px-2 py-1 rounded 
                      hover:bg-[#034E7A] hover:text-white">
                        <i class="fas fa-bell mr-2"></i> Notifications
                    </a>
                </li>
            </ul>
        </div>

    </div>

    <!-- Sidebar Footer -->
    <div class="mt- border-t px-4 py-1">
        <div class="flex items-center space-x-2">
            <div class="w-8 h-8 bg-[#02629B] text-white rounded-full flex items-center justify-center">
                {{ ucwords(Str::substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div>
                <div class="font-semibold">{{ ucwords(Auth::user()->name) }}</div>
                <div class="text-sm text-gray-500">Admin</div>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}" class="mt-2">
            @csrf
            <button type="submit" class="w-full bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">Logout</button>
        </form>
    </div>
</aside>

<script>
    document.querySelectorAll("#sidebar details").forEach(details => {
        details.addEventListener("toggle", function() {
            if (this.open) {
                document.querySelectorAll("#sidebar details").forEach(other => {
                    if (other !== this) other.removeAttribute("open");
                });
            }
        });
    });
</script>