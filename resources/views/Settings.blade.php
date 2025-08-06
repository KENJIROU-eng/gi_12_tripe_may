<x-app-layout>
    <div class="py-12 min-h-screen flex items-center justify-center">
        <div class="w-full max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6 sm:p-10">

                {{-- タイトル --}}
                <div class="relative text-center mb-10">
                    <h1 class="text-3xl sm:text-5xl font-bold text-gray-800 dark:text-gray-100 tracking-widest space-x-1">
                        <i class="fa-solid fa-s"></i>
                        <i class="fa-solid fa-e"></i>
                        <i class="fa-solid fa-t"></i>
                        <i class="fa-solid fa-t"></i>
                        <i class="fa-solid fa-i"></i>
                        <i class="fa-solid fa-n"></i>
                        <i class="fa-solid fa-g"></i>
                    </h1>

                    {{-- fixed position with margin-top --}}
                    <a href="{{ route('dashboard') }}"
                    class="absolute right-4 sm:right-10 top-16 inline-flex items-center px-3 py-1.5 bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-semibold rounded-lg shadow transition">
                        <i class="fa-solid fa-house mr-2"></i>
                        Go to Dashboard
                    </a>
                </div>

                {{-- 説明カード --}}
                <div class="bg-white dark:bg-gray-700 rounded-lg p-6 max-w-md mx-auto text-center shadow-lg space-y-4">
                    <p class="text-gray-800 dark:text-gray-100 text-base sm:text-lg font-medium">
                        Do you permit notification sounds?
                    </p>
                    <p id="complete" class="text-sm text-yellow-600 hidden">
                        Sound setting has been updated.
                    </p>

                    <div class="flex flex-col sm:flex-row justify-center items-center gap-4">
                        <button id="enable-sound" class="w-full sm:w-auto px-6 py-2 bg-blue-400 text-white font-semibold rounded hover:bg-blue-600 transition">
                            Permit
                        </button>
                        <button id="cancel-sound" class="w-full sm:w-auto px-6 py-2 bg-gray-400 text-white font-semibold rounded hover:bg-gray-600 transition">
                            Decline
                        </button>
                    </div>
                </div>

                {{-- スクリプト --}}
                <script>
                    window.addEventListener('DOMContentLoaded', () => {
                        const userId = document.body.dataset.userId || 'default';

                        function unlock() {
                            const dummy = new Audio('/sounds/maou_se_onepoint23.mp3');
                            dummy.volume = 0;
                            dummy.play().then(() => {
                                localStorage.setItem(`audioUnlocked_user_${userId}`, '1');
                                console.log('✅ Sound permission granted and saved.');
                            }).catch(err => {
                                console.warn('❌ Sound permission failed:', err);
                            });
                        }

                        function enable() {
                            document.getElementById('enable-sound').classList.replace('bg-blue-400', 'bg-blue-600');
                        }

                        function complete() {
                            document.getElementById('complete').classList.remove('hidden');
                        }

                        function resetEnable() {
                            document.getElementById('enable-sound').classList.replace('bg-blue-600', 'bg-blue-400');
                        }

                        function cancel() {
                            document.getElementById('cancel-sound').classList.replace('bg-gray-400', 'bg-gray-600');
                        }

                        function resetCancel() {
                            document.getElementById('cancel-sound').classList.replace('bg-gray-600', 'bg-gray-400');
                        }

                        document.getElementById('enable-sound').addEventListener('click', () => {
                            unlock();
                            resetCancel();
                            enable();
                            complete();
                        });

                        document.getElementById('cancel-sound').addEventListener('click', () => {
                            console.log('🔕 User declined sound permission.');
                            localStorage.setItem(`audioUnlocked_user_${userId}`, '0');
                            resetEnable();
                            cancel();
                            complete();
                        });
                    });
                </script>
            </div>
        </div>
    </div>
</x-app-layout>
