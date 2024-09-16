<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kullanıcı Profili</title>
    <style>
        /* CSS kodları burada */
    </style>
</head>
<body>
    <h1>{{ $user->name }}'ın Profili</h1>
    <div class="watched-movies">
        <h2>İzlediklerim</h2>
        @foreach ($user->movies()->where('category', 'Watched')->get() as $movie)
            <div>{{ $movie->title }}</div>
        @endforeach
    </div>

    <div class="to-watch-movies">
        <h2>İzleyeceklerim</h2>
        @foreach ($user->movies()->where('category', 'To-Watch')->get() as $movie)
            <div>{{ $movie->title }}</div>
        @endforeach
    </div>
</body>
</html>
