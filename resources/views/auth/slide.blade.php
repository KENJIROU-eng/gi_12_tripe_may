<x-guest-layout>
    <div class="relative mx-auto my-12 w-full max-w-[800px] min-h-[600px]">
        <!-- 背景の左右ボックス -->
        <div class="absolute w-full h-full flex flex-col sm:flex-row">
            <!-- 左ボックス -->
            <div class="sm:w-1/2 w-full h-1/2 sm:h-full flex flex-col items-center justify-center bg-orange-400 text-white z-0">
                <h2 class="text-3xl font-semibold tracking-widest mb-2">BLOOM &amp; BOUQUET</h2>
                <p class="text-sm mb-4">Pick your perfect <span class="text-pink-200">bouquet</span></p>
                <p class="text-xs mb-2">Have an account?</p>
                <button id="switchToLogin" class="bg-white text-pink-600 px-4 py-2 rounded hover:bg-pink-100 transition">Login</button>
            </div>

            <!-- 右ボックス -->
            <div class="sm:w-1/2 w-full h-1/2 sm:h-full flex flex-col items-center justify-center bg-orange-200 text-pink-700 z-0">
                <h2 class="text-3xl font-semibold tracking-widest mb-2">BLOOM &amp; BOUQUET</h2>
                <p class="text-sm mb-4">Pick your perfect <span class="text-pink-400">bouquet</span></p>
                <p class="text-xs mb-2">Don't have an account?</p>
                <button id="switchToRegister" class="bg-white text-pink-600 px-4 py-2 rounded hover:bg-pink-100 transition">Sign up</button>
            </div>
        </div>

        <!-- フォーム切り替え用：モバイル表示専用 -->
        <div class="sm:hidden absolute top-2 right-2 z-[9999]">
            <button id="mobileSwitchToLogin" class="bg-pink-500 text-white text-sm px-3 py-1 rounded mr-2">Login</button>
            <button id="mobileSwitchToRegister" class="bg-pink-500 text-white text-sm px-3 py-1 rounded">Register</button>
        </div>

        <!-- スライドボックス -->
        <div id="formSlider" class="absolute top-0 sm:top-[-5%] left-0 sm:w-[50%] w-full sm:h-[110%] h-full bg-white z-50 shadow-2xl transition-transform duration-700 ease-in-out transform translate-x-[0%] sm:rounded-xl flex items-center ">

            <!-- ログイン -->
            <div id="loginForm" class=" transition-all duration-500 ease-in-out opacity-100 translate-y-0 px-4 w-full">
                <h1 class="text-2xl font-bold text-center text-pink-600 uppercase tracking-widest mb-12">Login</h1>
                <form method="POST" action="{{ route('login') }}" class="space-y-4 mt-6">
                    @csrf
                    <div>
                        <x-auth-session-status class="mb-4" :status="session('status')" />
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" class="block mt-1 w-full" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="password" :value="__('Password')" />
                        <x-text-input id="password" type="password" name="password" required autocomplete="current-password" class="block mt-1 w-full" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-between mt-4">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="remember" class="rounded border-gray-300 text-pink-600 shadow-sm focus:ring-pink-500">
                            <span class="ml-2 text-sm text-gray-600">Remember me</span>
                        </label>
                    </div>

                    <div class="flex justify-end">
                        @if (Route::has('password.request'))
                        <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                        @endif
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <x-primary-button class="ml-3">{{ __('Log in') }}</x-primary-button>
                    </div>
                </form>
            </div>

            <!-- 登録 -->
            <div id="registerForm" class=" transition-all duration-500 ease-in-out opacity-0 -translate-y-4 hidden w-full px-4">
                <h1 class="text-2xl font-bold text-center text-pink-600 uppercase tracking-widest">Register</h1>
                <form method="POST" action="{{ route('register') }}" class="space-y-4 mt-6">
                    @csrf
                    <div>
                        <x-input-label for="name" :value="__('Name')" />
                        <x-text-input id="name" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" class="block mt-1 w-full" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" type="email" name="email" :value="old('email')" required autocomplete="username" class="block mt-1 w-full" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="password" :value="__('Password')" />
                        <x-text-input id="password" type="password" name="password" required autocomplete="new-password" class="block mt-1 w-full" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                        <x-text-input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" class="block mt-1 w-full" />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <x-primary-button class=" mt-4">{{ __('Register') }}</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const slider = document.getElementById('formSlider');
            const loginForm = document.getElementById('loginForm');
            const registerForm = document.getElementById('registerForm');

            function showForm(showEl, hideEl) {
                hideEl.classList.add('opacity-0', '-translate-y-4');
                hideEl.classList.remove('opacity-100', 'translate-y-0');

                setTimeout(() => {
                    hideEl.classList.add('hidden');
                    showEl.classList.remove('hidden');

                    // アニメーション前の初期状態
                    showEl.classList.add('opacity-0', '-translate-y-4');
                    showEl.classList.remove('opacity-100', 'translate-y-0');

                    // 次のフレームで滑らかに表示
                    requestAnimationFrame(() => {
                        showEl.classList.remove('opacity-0', '-translate-y-4');
                        showEl.classList.add('opacity-100', 'translate-y-0');
                    });
                }, 300);
            }


            function switchToRegister() {
                if (window.innerWidth < 640) {
                    showForm(registerForm, loginForm);
                } else {
                    slider.classList.remove('translate-x-0');
                    slider.classList.add('translate-x-full');

                    loginForm.classList.add('hidden');
                    registerForm.classList.remove('hidden');
                    registerForm.classList.remove('opacity-0', '-translate-y-4');
                    registerForm.classList.add('opacity-100', 'translate-y-0');
                }
            }

            function switchToLogin() {
                if (window.innerWidth < 640) {
                    showForm(loginForm, registerForm);
                } else {
                    slider.classList.remove('translate-x-full');
                    slider.classList.add('translate-x-0');

                    registerForm.classList.add('hidden');
                    loginForm.classList.remove('hidden');
                    loginForm.classList.remove('opacity-0', '-translate-y-4');
                    loginForm.classList.add('opacity-100', 'translate-y-0');
                }
            }

            const urlParams = new URLSearchParams(window.location.search);
            const view = urlParams.get('view');

            if (view === 'register') {
                switchToRegister();
            } else {
                switchToLogin(); // デフォルトはログイン
            }

            document.getElementById('switchToRegister').addEventListener('click', switchToRegister);
            document.getElementById('switchToLogin').addEventListener('click', switchToLogin);
            document.getElementById('mobileSwitchToRegister').addEventListener('click', switchToRegister);
            document.getElementById('mobileSwitchToLogin').addEventListener('click', switchToLogin);
        });
    </script>

</x-guest-layout>
