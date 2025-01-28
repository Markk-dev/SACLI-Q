<link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.2/dist/tailwind.min.css" rel="stylesheet">
<div>
    @if ($errors->any())
    <div 
        x-data="{ show: true }" 
        x-show="show" 
        x-init="setTimeout(() => show = false, 2000)" 
        x-transition:enter="transition ease-in-out duration-500"
        x-transition:enter-start="opacity-0 translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in-out duration-500"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-4"
        class="fixed bottom-4 right-8 z-50 max-w-lg bg-red-100 border border-red-300 text-red-800 font-medium rounded-md shadow-lg p-4 font-inter"
    >
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-red-700" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1.93-9.82l-.59.58a1 1 0 01-1.42-1.42l1.3-1.3a1 1 0 011.42 0l3.5 3.5a1 1 0 01-1.42 1.42l-2.8-2.8z" clip-rule="evenodd"></path>
            </svg>
            <span class="font-bold">Errors occurred:</span>
        </div>
        <ul class="list-disc list-inside mt-2 font-normal">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.12.0/dist/cdn.min.js" defer></script>