<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Film Önerileri</title>
    <style>
        /* Basic Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Lato', sans-serif;
            background-color: #000;
            color: #fff;
            text-align: center;
            padding: 20px;
        }

        header {
            margin-bottom: 50px;
            position: relative;
        }

        header h1 {
            font-size: 3em;
            margin-bottom: 20px;
            letter-spacing: 2px;
        }

        header p {
            font-size: 1.2em;
            color: #aaa;
        }

        .profile-pic {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            overflow: hidden;
            border: 2px solid #fff;
            cursor: pointer;
        }

        .profile-pic img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-menu {
            display: none;
            position: absolute;
            top: 100px;
            right: 20px;
            width: 200px;
            background-color: #333;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }

        .profile-menu a {
            display: block;
            padding: 10px;
            color: white;
            text-decoration: none;
            font-size: 16px;
        }

        .profile-menu a:hover {
            background-color: #575757;
        }
        
        /* Make profile menu visible */
        .show-profile-menu {
            display: block;
        }

        .search-section {
            margin-bottom: 40px;
        }

        .search-section input {
            padding: 10px;
            font-size: 1em;
            border: none;
            border-radius: 5px;
            width: 300px;
            margin-right: 10px;
        }

        .search-section button {
            padding: 10px 20px;
            font-size: 1em;
            border: none;
            background-color: #ff0000;
            color: #fff;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .search-section button:hover {
            background-color: #d40000;
        }

        .recommendations {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .movie-item {
            margin: 20px;
            text-align: center;
            transition: transform 0.3s ease;
        }

        .movie-item img {
            width: 200px;
            border-radius: 10px;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .movie-item:hover {
            transform: scale(1.05);
        }

        .movie-item h3 {
            margin-top: 10px;
            font-size: 1.2em;
        }
        /* Üç nokta menüsü */
        .menu {
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
            z-index: 10;
        }

        .menu-items {
            display: none;
            position: absolute;
            top: 30px;
            right: 0;
            background-color: #333;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }

        .menu-items a {
            display: block;
            padding: 10px;
            color: white;
            text-decoration: none;
            font-size: 14px;
        }

        .menu-items a:hover {
            background-color: #575757;
        }

        .show-menu {
            display: block;
        }
    </style>
</head>
<body>
<!-- Header -->
<header>
    <h1>Film Öneri Sistemi</h1>
    <p>Sevdiğiniz filmlere benzer filmleri bulun</p>

    <!-- Profil Fotoğrafı -->
    <div class="profile-pic" onclick="toggleProfileMenu()">
        <img src="{{ Auth::user()->profile_photo ? Storage::url(Auth::user()->profile_photo) : asset('default-avatar.png') }}" alt="Profil Fotoğrafı">
    </div>

    <!-- Profil Menü -->
    <div class="profile-menu" id="profileMenu">
        <a href="{{ route('profile.edit') }}">Profil Düzenle</a>
        <a href="/watched">İzlediklerim</a>
        <a href="/to-watch">İzlemek İstediklerim</a>
        <a href="/logout">Çıkış Yap</a>
    </div>
</header>

<!-- Search Section -->
<section class="search-section">
    <form method="POST" action="/get-recommendations">
        @csrf
        <input type="text" id="movie_name" name="movie_name" placeholder="Film Adını Girin..." required>
        <button type="submit">Önerileri Al</button>
    </form>
</section>

<!-- Recommendations Section -->
<section class="recommendations" id="recommendations">
    @if(isset($movies))
        @foreach($movies as $movie)
            <div class="movie-item">
                <a href="https://www.imdb.com/title/{{ $movie['imdb_id'] }}" target="_blank">
                    <img src="{{ $movie['poster'] }}" alt="{{ $movie['title'] }} Poster">
                </a>
                <h3>{{ $movie['title'] }}</h3>
            </div>
        @endforeach
    @else
        <p>Henüz bir film önerisi almadınız.</p>
    @endif
</section>

<script>
function toggleProfileMenu() {
    var menu = document.getElementById('profileMenu');
    var isMenuVisible = menu.classList.contains('show-profile-menu');
    if (isMenuVisible) {
        menu.classList.remove('show-profile-menu');
    } else {
        menu.classList.add('show-profile-menu');
    }
}

// Hide menu when clicking outside
document.addEventListener('click', function(event) {
    var menu = document.getElementById('profileMenu');
    var profilePic = document.querySelector('.profile-pic');
    if (!profilePic.contains(event.target) && !menu.contains(event.target)) {
        menu.classList.remove('show-profile-menu');
    }
});

function toggleMenu(movieId) {
    var menu = document.getElementById('menu-' + movieId);
    menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
}

function addToCategory(movieId, category) {
    fetch(`/add-to-category`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ movie_id: movieId, category: category })
    }).then(response => {
        return response.json();
    }).then(data => {
        if (data.success) {
            alert('Film başarıyla ' + category + ' kategorisine eklendi.');
            // Optionally, you can reload the page or update the UI here
        }
    });
}
</script>
</body>
</html>
