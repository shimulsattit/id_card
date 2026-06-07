@extends('layouts.guest')

@section('content')
<div class="w-full max-w-[420px]">
    <div class="bg-white rounded-[2.5rem] shadow-2xl overflow-hidden relative pb-10">
        <!-- Top Banner/Logo Area -->
        <div class="pt-10 pb-6 flex flex-col items-center">
            <div class="w-24 h-24 rounded-full border-4 border-yellow-400 p-1 bg-white mb-4 shadow-md">
                <div class="w-full h-full rounded-full bg-gray-100 flex items-center justify-center overflow-hidden">
                    {{-- Using a placeholder for logo, you can update this path --}}
                    <i class="fas fa-school text-4xl text-emerald-800"></i>
                </div>
            </div>

            <h1 class="text-3xl font-bold text-emerald-900 mb-1" style="font-family: 'Noto Serif Bengali', serif;">
                بِسْمِ اللهِ الرَّحْمٰنِ الرَّحِيْمِ
            </h1>
            <h2 class="text-xl font-bold text-gray-800 tracking-wide uppercase">
                ID GENERATION SYSTEM
            </h2>

            <div class="mt-4 text-center">
                <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">
                    লাইসেন্স মেয়াদ: 11 May, 2026
                </p>
                <p class="text-xs text-emerald-600 font-bold">
                    বাকি আছে <span class="text-emerald-700">৩৬৫</span> দিন
                </p>
            </div>

            <!-- Progress Bar -->
            <div class="w-48 h-1.5 bg-gray-100 rounded-full mt-4 overflow-hidden">
                <div class="h-full bg-gradient-to-r from-yellow-400 to-emerald-600" style="width: 75%"></div>
            </div>
        </div>

        <!-- Login Form -->
        <div class="px-10">
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Field -->
                <div class="mb-4 relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fas fa-user text-emerald-800"></i>
                    </div>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="block w-full pl-11 pr-4 py-3 bg-gray-50 border-transparent rounded-xl text-gray-900 placeholder-gray-400 focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition duration-200 @error('email') border-red-500 @enderror"
                        placeholder="আপনার ইমেইল দিন">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1 ml-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Field -->
                <div class="mb-6 relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fas fa-lock text-emerald-800"></i>
                    </div>
                    <input id="password" type="password" name="password" required
                        class="block w-full pl-11 pr-4 py-3 bg-gray-50 border-transparent rounded-xl text-gray-900 placeholder-gray-400 focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition duration-200 @error('password') border-red-500 @enderror"
                        placeholder="************">
                    @error('password')
                        <p class="text-red-500 text-xs mt-1 ml-2">{{ $message }}</p>
                    @enderror
                </div>


                <!-- Submit Button -->
                <button type="submit"
                    class="w-full flex items-center justify-center space-x-3 bg-[#064e3b] hover:bg-[#065f46] text-white font-bold py-4 px-6 rounded-2xl shadow-lg transform active:scale-95 transition duration-150">
                    <i class="fas fa-sign-in-alt"></i>
                    <span class="text-xl" style="font-family: 'Noto Serif Bengali', serif;">প্রবেশ করুন</span>
                </button>
            </form>
        </div>

        <!-- Footer Decoration -->
        <div class="mt-8 flex justify-center">
            <div class="h-1 w-20 bg-gray-200 rounded-full"></div>
        </div>
    </div>

    <!-- External Link -->
    <div class="mt-6 text-center">
        <a href="#" class="text-white opacity-80 hover:opacity-100 text-sm font-medium transition duration-200">
            সহায়তার জন্য যোগাযোগ করুন
        </a>
    </div>
</div>
@endsection