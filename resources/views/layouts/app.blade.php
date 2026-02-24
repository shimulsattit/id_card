<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        @if(Auth::check())
            @if(Auth::user()->hasRole('school_admin') && Auth::user()->school)
                {{ Auth::user()->school->name }}
            @elseif(Auth::user()->hasRole('super_admin'))
                Cyberhaven IT
            @else
                ID Card System
            @endif
        @else
            ID Card System
        @endif
    </title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        .font-sans {
            font-family: 'Poppins', sans-serif;
        }

        @media print {
            .no-print {
                display: none;
            }
        }

        .font-signature {
            font-family: 'Dancing Script', cursive;
        }
    </style>
</head>

<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen flex flex-col bg-gray-50">
        <!-- Navigation -->
        <nav class="bg-white shadow-md z-10 p-4">
            <div class="max-w-7xl mx-auto flex justify-between items-center">
                <div class="flex items-center gap-4">
                    <a href="{{ route('dashboard') }}" class="text-2xl font-bold text-blue-600 tracking-wide">
                        @if(Auth::user()->school)
                            {{ Auth::user()->school->name }}
                            <span class="text-xs font-normal text-gray-400 bg-gray-100 px-2 py-0.5 rounded ml-2">ID:
                                {{ Auth::user()->school->unique_id }}</span>
                        @else
                            ID Card System
                        @endif
                    </a>
                </div>
                <div class="flex items-center space-x-6">
                    <div class="flex flex-col text-right">
                        <span class="text-sm font-semibold text-gray-800">{{ Auth::user()->name }}</span>
                        <span class="text-xs text-gray-500 uppercase">{{ Auth::user()->roles->first()->name }}</span>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="bg-red-50 text-red-600 hover:bg-red-100 px-4 py-2 rounded-lg text-sm font-medium transition duration-200">Logout</button>
                    </form>
                </div>
            </div>
        </nav>

        <div class="flex flex-1">
            <!-- Sidebar -->
            <aside class="w-72 bg-slate-900 border-r border-slate-800 hidden md:flex flex-col text-slate-300">
                <div class="p-6">
                    <nav class="space-y-2">
                        <a href="{{ route('dashboard') }}"
                            class="flex items-center px-4 py-3 rounded-xl transition duration-200 {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50' : 'hover:bg-slate-800 hover:text-white' }}">
                            <span class="font-medium">Dashboard</span>
                        </a>

                        @role('super_admin')
                        <div class="pt-6 pb-2 px-4 text-xs font-bold text-slate-500 uppercase tracking-widest">
                            Administration</div>
                        <a href="{{ route('admin.schools.index') }}"
                            class="flex items-center px-4 py-3 rounded-xl transition duration-200 {{ request()->routeIs('admin.schools.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50' : 'hover:bg-slate-800 hover:text-white' }}">
                            <span>Manage Schools</span>
                        </a>
                        <a href="{{ route('admin.id-card-templates.index') }}"
                            class="flex items-center px-4 py-3 rounded-xl transition duration-200 {{ request()->routeIs('admin.id-card-templates.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50' : 'hover:bg-slate-800 hover:text-white' }}">
                            <span>ID Card Templates</span>
                        </a>
                        @endrole

                        @role('school_admin')
                        <!-- Student Management Dropdown -->
                        <div x-data="{ open: {{ request()->routeIs('school.students.*', 'school.academic.*') ? 'true' : 'false' }} }"
                            class="mb-2">
                            <button @click="open = !open"
                                class="w-full flex items-center justify-between px-4 py-3 rounded-xl transition duration-200 hover:bg-slate-800 hover:text-white text-slate-300 font-medium">
                                <div class="flex items-center">
                                    <span>Student Management</span>
                                </div>
                                <svg :class="{'rotate-180': open}" class="w-4 h-4 transition-transform duration-200"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="open" x-collapse class="pl-4 space-y-1 mt-1">
                                <a href="{{ route('school.students.index') }}"
                                    class="flex items-center px-4 py-2 rounded-lg text-sm transition duration-200 {{ request()->routeIs('school.students.index') ? 'bg-blue-600 text-white' : 'hover:bg-slate-800 text-slate-400 hover:text-white' }}">
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                        </path>
                                    </svg>
                                    <span>STD List</span>
                                </a>
                                <a href="{{ route('school.students.create') }}"
                                    class="flex items-center px-4 py-2 rounded-lg text-sm transition duration-200 {{ request()->routeIs('school.students.create') ? 'bg-blue-600 text-white' : 'hover:bg-slate-800 text-slate-400 hover:text-white' }}">
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z">
                                        </path>
                                    </svg>
                                    <span>Add STD</span>
                                </a>
                                <a href="{{ route('school.academic.index') }}"
                                    class="flex items-center px-4 py-2 rounded-lg text-sm transition duration-200 {{ request()->routeIs('school.academic.index') ? 'bg-blue-600 text-white' : 'hover:bg-slate-800 text-slate-400 hover:text-white' }}">
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                        </path>
                                    </svg>
                                    <span>Manage Classes</span>
                                </a>
                            </div>
                        </div>

                        <!-- Staff Management Dropdown -->
                        <div x-data="{ open: {{ request()->routeIs('school.teachers.*', 'school.designations.*') ? 'true' : 'false' }} }"
                            class="mb-2">
                            <button @click="open = !open"
                                class="w-full flex items-center justify-between px-4 py-3 rounded-xl transition duration-200 hover:bg-slate-800 hover:text-white text-slate-300 font-medium">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                        </path>
                                    </svg>
                                    <span>Staff Management</span>
                                </div>
                                <svg :class="{'rotate-180': open}" class="w-4 h-4 transition-transform duration-200"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="open" x-collapse class="pl-4 space-y-1 mt-1">
                                <a href="{{ route('school.teachers.index') }}"
                                    class="flex items-center px-4 py-2 rounded-lg text-sm transition duration-200 {{ request()->routeIs('school.teachers.index') ? 'bg-blue-600 text-white' : 'hover:bg-slate-800 text-slate-400 hover:text-white' }}">
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                        </path>
                                    </svg>
                                    <span>Teachers List</span>
                                </a>
                                <a href="{{ route('school.teachers.create') }}"
                                    class="flex items-center px-4 py-2 rounded-lg text-sm transition duration-200 {{ request()->routeIs('school.teachers.create') ? 'bg-blue-600 text-white' : 'hover:bg-slate-800 text-slate-400 hover:text-white' }}">
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z">
                                        </path>
                                    </svg>
                                    <span>Add Teacher</span>
                                </a>
                                <a href="{{ route('school.designations.index') }}"
                                    class="flex items-center px-4 py-2 rounded-lg text-sm transition duration-200 {{ request()->routeIs('school.designations.index') ? 'bg-blue-600 text-white' : 'hover:bg-slate-800 text-slate-400 hover:text-white' }}">
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>Add Designation</span>
                                </a>
                            </div>
                        </div>

                        <!-- ID Cards Dropdown -->
                        <div x-data="{ open: {{ request()->routeIs('school.idcards.*') ? 'true' : 'false' }} }"
                            class="mb-2">
                            <button @click="open = !open"
                                class="w-full flex items-center justify-between px-4 py-3 rounded-xl transition duration-200 hover:bg-slate-800 hover:text-white text-slate-300 font-medium">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0c0 .884.896 1.679 2 2 1.104-.321 2-1.116 2-2M15 7a1 1 0 011 1 1 1 0 001 1v1h-8V9a1 1 0 001-1 1 1 0 011-1h4z">
                                        </path>
                                    </svg>
                                    <span>ID Cards</span>
                                </div>
                                <svg :class="{'rotate-180': open}" class="w-4 h-4 transition-transform duration-200"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="open" x-collapse class="pl-4 space-y-1 mt-1">
                                <a href="{{ route('school.idcards.students') }}"
                                    class="flex items-center px-4 py-2 rounded-lg text-sm transition duration-200 {{ request()->routeIs('school.idcards.students') ? 'bg-blue-600 text-white' : 'hover:bg-slate-800 text-slate-400 hover:text-white' }}">
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                    <span>Student ID Cards</span>
                                </a>
                                <a href="{{ route('school.idcards.teachers') }}"
                                    class="flex items-center px-4 py-2 rounded-lg text-sm transition duration-200 {{ request()->routeIs('school.idcards.teachers') ? 'bg-blue-600 text-white' : 'hover:bg-slate-800 text-slate-400 hover:text-white' }}">
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    <span>Teacher ID Cards</span>
                                </a>
                            </div>
                        </div>
                        @endrole
                    </nav>
                </div>
            </aside>

            <!-- Page Content -->
            <div class="flex-1 flex flex-col">
                <main class="flex-1 p-6">
                    <!-- Flash Messages -->
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                            role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4"
                            role="alert">
                            {{ session('error') }}
                        </div>
                    @endif
                    @if($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4"
                            role="alert">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @yield('content')
                </main>

                <footer class="bg-white border-t border-gray-200 p-4 text-center">
                    <span class="text-sm text-gray-500">Developed by - </span>
                    <span class="font-signature text-xl text-blue-600 font-bold">Shimul Hossain</span>
                </footer>
            </div>
        </div>
    </div>
</body>

</html>