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
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.0/dist/trix.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="{{ env('VITE_SERVER_IMAGE_API').'/uploads/image/edsparkLogo.png' }}">
    @vite(['resources/js/app.ts'])
    @vite(['tinymce/tinymce.min.js'])

</head>
<body class="relative w-full lg:w-auto">
<div id="app" class="flex flex-col container mx-auto shadow-lg min-h-screen "></div>

</body>
</html>
