<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>edSpark</title>

    @vite(['resources/css/app.css'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    {{-- <script src="https://cdn.tailwindcss.com"></script> --}}
    {{-- <link rel="stylesheet" href="{{ mix('resources/css/app.css')}}" type="text/css"> --}}
    {{-- <link rel="stylesheet" href="{{ asset('css/app.css') }}" type="text/css"> --}}
    <link rel="icon" type="image/x-icon" href="{{ env('VITE_SERVER_IMAGE_API').'/uploads/image/edsparkLogo.png' }}">
    @vite(['resources/js/app.js'])

</head>
<body class="relative">
    <div id="app" class="flex flex-col container mx-auto shadow-lg min-h-screen font-['Poppins']"></div>
{{--    <script src="{{ mix('resources/js/app.js') }}" type="text/javascript"></script>--}}
</body>
</html>
