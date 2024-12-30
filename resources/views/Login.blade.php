<x-App>
    <x-slot name="content" x-init="console.log({{session('error')}})">        
        <div class="dark:bg-gray-700 h-full w-full flex flex-col justify-center items-center">
            <div class="flex justify-center">
                <div class="w-full max-w-md">

                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md w-96 p-4 md:p-6 lg:p-8 xl:p-10">
                        <div class="text-lg font-bold mb-4 text-gray-900 dark:text-white">{{ __('Login') }}</div>

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="mb-4">
                                <label for="account_id" class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">{{ __('Your Account ID') }}</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                        <i class="fas fa-user text-gray-400"></i>
                                    </span>
                                    <input id="account_id" type="text" class="block w-full p-2 pl-10 text-sm text-gray-700 dark:text-gray-300 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500 @error('account_id') border-red-500 @enderror" name="account_id" value="{{ old('account_id') }}" required autocomplete="account_id" autofocus>
                                </div>
                                @error('account_id')
                                    <div class="mt-2 text-sm text-red-500">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="password" class="block text-sm font-medium mb-2 text-gray-700 dark:text-gray-300">{{ __('Password') }}</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                        <i class="fas fa-lock text-gray-400"></i>
                                    </span>
                                    <input id="password" type="password" class="block w-full p-2 pl-10 text-sm text-gray-700 dark:text-gray-300 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-500 @enderror" name="password" required autocomplete="current-password">
                                </div>
                                @error('password')
                                    <div class="mt-2 text-sm text-red-500">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="flex justify-end mt-4">
                                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    {{ __('Login') }}
                                </button>

                            </div>
                            <x-ErrorAlert></x-ErrorAlert>
                            <x-SuccessAlert></x-SuccessAlert>
                        </form>
                    </div>
                
                </div>
            </div>
        </div>
    </x-slot>
</x-App>

<!-- Include Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">