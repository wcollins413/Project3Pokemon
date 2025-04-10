<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pokemon Collection - About</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="pokemon-page">
        <nav class="main-nav">
            <div class="nav-container">
                <a href="index.php" class="nav-logo">Pokemon Collection</a>
                <div class="nav-links">
                    <a href="index.php">Search</a>
                    <a href="stats.php">Stats</a>
                    <a href="about.php" class="active">About</a>
                </div>
            </div>
        </nav>

        <div class="container">
            <h1>About This Collection</h1>
            
            <div class="about-content">
                <section class="about-section">
                    <h2>Data Source</h2>
                    <p>This Pokemon collection application uses the PokeAPI, a free and open-source RESTful API that provides comprehensive data about Pokemon. The API is based on the data from the Pokemon video games and includes information about:</p>
                    <ul>
                        <li>Pokemon names, types, and abilities</li>
                        <li>Base stats and characteristics</li>
                        <li>Physical attributes (height, weight)</li>
                        <li>Sprites and artwork</li>
                        <li>Evolution chains</li>
                        <li>And much more!</li>
                    </ul>
                </section>
                
                <section class="about-section">
                    <h2>API Details</h2>
                    <p>The PokeAPI is built using:</p>
                    <ul>
                        <li>RESTful architecture</li>
                        <li>JSON data format</li>
                        <li>HTTPS protocol</li>
                        <li>Rate limiting for fair usage</li>
                    </ul>
                    <p>API Documentation: <a href="https://pokeapi.co/docs/v2" target="_blank">https://pokeapi.co/docs/v2</a></p>
                </section>
                
                <section class="about-section">
                    <h2>Features</h2>
                    <p>This application provides several ways to explore the Pokemon collection:</p>
                    <ul>
                        <li><strong>Search:</strong> Find Pokemon by name or ID</li>
                        <li><strong>Filter:</strong> Narrow down results by type and generation</li>
                        <li><strong>List View:</strong> See Pokemon in a compact table format</li>
                        <li><strong>Detailed View:</strong> Explore comprehensive information about each Pokemon</li>
                        <li><strong>Statistics:</strong> View collection-wide statistics and distributions</li>
                    </ul>
                </section>
                <section class="about-section">
                    <h2>Credits</h2>
                    <p>All Pokemon data is provided by the PokeAPI. Pokemon and all related names are trademarks of Nintendo.</p>
                </section>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 