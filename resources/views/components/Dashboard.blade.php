<html>
    <head>
        <title>SACLI Queue</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        @vite('resources/js/app.js')
        @vite('resources/css/app.css')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
            {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"> --}}
        </head>
    <body>        
        <x-NavigationBar></x-NavigationBar>
        <x-Sidebar></x-Sidebar>
        {{ $content }}        
        <x-ErrorAlert></x-ErrorAlert>
        <x-SuccessAlert></x-SuccessAlert>
    </body>
</html>