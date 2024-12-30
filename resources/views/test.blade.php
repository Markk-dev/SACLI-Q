<x-App>
    <x-slot name="main"> 
        <div x-init = "
        {{-- Go to channel Queue --}}
        {{-- Listen for eventUserQueued --}}
            Echo.channel('queue').
                listen('UserQueued', (event)=>{
                console.log(event)}
            )">
        </div>
    </x-slot>
</x-App>

