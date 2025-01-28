<x-App class="w-80">
    <x-slot name="content" x-init="console.log({{session('error')}})">

    <section class="bg-gray-50">
        <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
            <div class="w-full bg-white rounded-lg shadow border md:mt-0 sm:max-w-lg xl:p-1 min-h-[500px]">
                <div class="p-8 space-y-4 md:space-y-6 sm:p-8">
                    <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-3xl">
                        Sign in to your account
                    </h1>

                    <form method="POST" action="{{ route('login') }}" class="space-y-4 md:space-y-6">
                        @csrf

                        <div class="mt-1">
                            <label for="account_id" class="block mb-2 text-sm font-medium text-gray-900">Your Account ID</label>
                            <input type="text" name="account_id" id="account_id" value="{{ old('account_id') }}" required autocomplete="account_id" autofocus placeholder="Your Account ID" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-[#b8f500] focus:border-[#b8f500] block w-full p-2.5 @error('account_id') border-red-500 @enderror">
                            @error('account_id')
                                <div class="mt-2 text-sm text-red-500">{{ $message }}</div>
                            @enderror
                        </div>
                            
                         <div>
                            <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Password</label>
                            <input type="password" name="password" id="password" required autocomplete="current-password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-[#b8f500] focus:border-[#b8f500] block w-full p-2.5 @error('password') border-red-500 @enderror">
                            @error('password')
                                <div class="mt-2 text-sm text-red-500">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="flex items-center justify-between">
                             <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="remember" name="remember" type="checkbox" class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-[#b8f500]">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="remember" class="text-gray-500">Remember me</label>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="w-full text-white bg-[#b8f500] hover:bg-[#a3e000] hover:transition-all duration-700 ease-in-out focus:ring-4 focus:outline-none focus:ring-[#b8f500] font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                            Sign in
                        </button>

                        </form>

                        <p class="pt-6 text-center text-gray-400 text-xs">
                            Manage your workflow with ease—seamlessly track, organize, and streamline your tasks to maximize productivity and efficiency.
                        </p>
                        
                    </div>
                </div>
            </div>
        </section>

        <x-ErrorAlert></x-ErrorAlert>
    </x-slot>
</x-App>

<!-- Include Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">