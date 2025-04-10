<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
$userId = $_SESSION['user_id'];

$conn = new mysqli('localhost', 'root', '', 'Pokemon');
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("SELECT pokemon_name FROM caught_pokemon WHERE user_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$caughtPokemon = [];
while ($row = $result->fetch_assoc()) {
    $caughtPokemon[] = $row['pokemon_name'];
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Pokédex</title>
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
                <a href="pokedex.php" class="active">My Pokédex</a>
                <a href="about.php">About</a>
                <a href="logout.php">Logout</a>
            </div>
        </div>
    </nav>
    
    <div class="container">
        <h1><?php echo htmlspecialchars($username); ?>'s Pokédex</h1>

        <?php if (empty($caughtPokemon)): ?>
            <div class="error">You haven’t caught any Pokémon yet!</div>
        <?php else: ?>
            <div class="pokemon-grid">
                <?php
                foreach ($caughtPokemon as $pokeName) {
                    $cacheFile = "cache/pokemon_{$pokeName}.json";

                    if (!file_exists($cacheFile)) {
                        $url = "https://pokeapi.co/api/v2/pokemon/" . urlencode($pokeName);
                        $response = file_get_contents($url);
                        if ($response) {
                            file_put_contents($cacheFile, $response);
                        }
                    }

                    $pokemon = json_decode(file_get_contents($cacheFile), true);
                    if ($pokemon) {
                        echo "<div class='card' data-pokemon-id='{$pokemon['id']}'>";
                        echo "<div class='card-header'>";
                        echo "<h2>" . ucfirst($pokemon['name']) . "</h2>";
                        echo "<img src='{$pokemon['sprites']['front_default']}' alt='{$pokemon['name']}'>";
                        echo "<div class='types'>";
                        foreach ($pokemon['types'] as $t) {
                            $typeName = $t['type']['name'];
                            echo "<span class='type-badge {$typeName}'>" . ucfirst($typeName) . "</span>";
                        }
                        echo "</div>";
                        echo "</div>";
                        echo "</div>";
                    }
                }
                ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<div id="pokemon-modal" class="pokemon-popup">
    <div id="loading-indicator" class="loading-indicator">
        <div class="loading-spinner"></div>
        <p>Loading Pokemon details...</p>
    </div>
    <div id="modal-content"></div>
    <script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('pokemon-modal');
    const modalContent = document.getElementById('modal-content');
    const loadingIndicator = document.getElementById('loading-indicator');

    // Show modal and loading indicator
    function showPokemonDetails(pokemonId) {
        modal.style.display = "block";
        loadingIndicator.style.display = "block";
        modalContent.style.display = "none";

        fetch(`https://pokeapi.co/api/v2/pokemon/${pokemonId}`)
            .then(response => response.json())
            .then(pokemon => {
                const types = pokemon.types.map(t => t.type.name);
                const abilities = pokemon.abilities.map(a => a.ability.name);
                const stats = pokemon.stats.map(s => ({
                    name: s.stat.name,
                    value: s.base_stat
                }));

                let html = `
                    <div class="popup-content">
                        <span class="close-popup">&times;</span>
                        <div class="popup-header">
                            <h2>${pokemon.name}</h2>
                            <img src="${pokemon.sprites.front_default}" alt="${pokemon.name}">
                            <div class="types">
                                ${types.map(t => `<span class="type-badge ${t}">${t}</span>`).join('')}
                            </div>
                        </div>
                        <div class="popup-details">
                            <div class="info-section">
                                <h3>Details</h3>
                                <div class="info-grid">
                                    <div class="info-item">
                                        <span class="info-label">Height</span>
                                        <span class="info-value">${pokemon.height / 10}m</span>
                                    </div>
                                    <div class="info-item">
                                        <span class="info-label">Weight</span>
                                        <span class="info-value">${pokemon.weight / 10}kg</span>
                                    </div>
                                    <div class="info-item">
                                        <span class="info-label">Base Experience</span>
                                        <span class="info-value">${pokemon.base_experience}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="info-section">
                                <h3>Abilities</h3>
                                <div class="abilities-list">
                                    ${abilities.map(a => `<span class="ability-item">${a}</span>`).join('')}
                                </div>
                            </div>

                            <div class="info-section">
                                <h3>Stats</h3>
                                <div class="stats-grid">
                                    ${stats.map(stat => `
                                        <span class="stat-label">${stat.name.replace('-', ' ')}</span>
                                        <div class="stat-bar">
                                            <div class="stat-bar-fill" style="width: ${(stat.value / 255) * 100}%"></div>
                                        </div>
                                        <span class="stat-value">${stat.value}</span>
                                    `).join('')}
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                modal.innerHTML = html;
                modal.style.display = 'block';

                // Close modal logic
                const closeBtn = modal.querySelector('.close-popup');
                closeBtn.addEventListener('click', () => {
                    modal.style.display = 'none';
                });
            })
            .catch(error => {
                loadingIndicator.style.display = "none";
                modalContent.innerHTML = `
                    <div class="popup-content">
                        <span class="close-popup">&times;</span>
                        <div class="error">Failed to load Pokemon details. Please try again.</div>
                    </div>
                `;
                modalContent.style.display = "block";
            });
    }

    // Add event listener to each card
    document.querySelectorAll('.card').forEach(card => {
        card.addEventListener('click', function () {
            const pokemonId = this.dataset.pokemonId;
            showPokemonDetails(pokemonId);
        });
    });

    // Close modal when clicking outside
    window.addEventListener('click', function (event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    });
});
</script>

</div>
</body>
</html>
