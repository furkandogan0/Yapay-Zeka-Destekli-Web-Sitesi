<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Film Öneri Sistemi</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .background {
            background-image: url('C:/Users/RUMEYSA/Desktop/indir.jpg');
            background-size: cover;
            background-position: center;
            filter: brightness(0.5);
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }
        .content {
            position: relative;
            z-index: 1;
            color: white;
            padding-top: 15%;
        }
    </style>
</head>
<body>
    <div class="background"></div>
    <div class="container mx-auto p-6 content">
        <header class="text-center mb-8">
            <h1 class="text-4xl font-bold">Film Öneri Sistemi</h1>
            <p class="text-lg">Film adı girerek benzer filmleri keşfedin!</p>
        </header>
        
        <div class="max-w-lg mx-auto bg-black bg-opacity-50 p-6 rounded-lg shadow-lg">
            <form action="{{ route('recommendations.get') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="movie_name" class="block text-white font-medium mb-2">Film Adı</label>
                    <input type="text" id="movie_name" name="movie_name" placeholder="Film adını buraya yazın..." 
                           class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500" 
                           required>
                </div>
                <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded-md hover:bg-blue-600">Önerileri Getir</button>
            </form>
        </div>
    </div>
</body>
</html>
