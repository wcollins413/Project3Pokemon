<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pokemon Collection</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for Grid/List icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">


    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="pokemon-page">
        <nav class="main-nav">
            <div class="nav-container">
                <a href="index.php" class="nav-logo">Pokemon Collection</a>
                <div class="nav-links">
                    <a href="index.php" class="active">Search</a>
                    <a href="stats.php">Stats</a>
                    <a href="pokedex.php">Pokédex</a>
                    <a href="about.php">About</a>
                    <a href="logout.php" class="logout-btn">Logout</a>
                </div>
        </div>
    </nav>
    <div class="user-greeting">
    👋 Welcome, <strong><?php echo htmlspecialchars($username); ?></strong>
    </div>

        <div class="container">
            <h1>Pokemon Collection</h1>
            
            <form method="GET">
                <div class="search-options">
                    <div class="search-group">
                        <input type="text" name="search" placeholder="Search by name or ID" 
                               value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                        <button type="submit">Search</button>
                    </div>
                    
                    <div class="filter-grid">
            <div class="filter-group">
                            <label>Type</label>
                            <select name="type" onchange="this.form.submit()">
                    <option value="">All Types</option>
                                <?php
                                $types = ['normal', 'fire', 'water', 'electric', 'grass', 'ice', 
                                         'fighting', 'poison', 'ground', 'flying', 'psychic', 
                                         'bug', 'rock', 'ghost', 'dragon'];
                                foreach ($types as $t) {
                                    $selected = (isset($_GET['type']) && $_GET['type'] === $t) ? 'selected' : '';
                                    echo "<option value='$t' $selected>" . ucfirst($t) . "</option>";
                                }
                                ?>
                </select>
            </div>

            <div class="filter-group">
                            <label>Height Range (m)</label>
                            <div class="range-inputs">
                                <input type="number" name="height_min" placeholder="Min" step="0.1" min="0" max="10"
                                       value="<?php echo isset($_GET['height_min']) ? htmlspecialchars($_GET['height_min']) : ''; ?>">
                                <span>to</span>
                                <input type="number" name="height_max" placeholder="Max" step="0.1" min="0" max="10"
                                       value="<?php echo isset($_GET['height_max']) ? htmlspecialchars($_GET['height_max']) : ''; ?>">
                            </div>
                        </div>

                        <div class="filter-group">
                            <label>Weight Range (kg)</label>
                            <div class="range-inputs">
                                <input type="number" name="weight_min" placeholder="Min" step="0.1" min="0" max="1000"
                                       value="<?php echo isset($_GET['weight_min']) ? htmlspecialchars($_GET['weight_min']) : ''; ?>">
                                <span>to</span>
                                <input type="number" name="weight_max" placeholder="Max" step="0.1" min="0" max="1000"
                                       value="<?php echo isset($_GET['weight_max']) ? htmlspecialchars($_GET['weight_max']) : ''; ?>">
                            </div>
                        </div>

                        <div class="filter-group">
                            <label>Base Experience</label>
                            <div class="range-inputs">
                                <input type="number" name="exp_min" placeholder="Min" min="0" max="1000"
                                       value="<?php echo isset($_GET['exp_min']) ? htmlspecialchars($_GET['exp_min']) : ''; ?>">
                                <span>to</span>
                                <input type="number" name="exp_max" placeholder="Max" min="0" max="1000"
                                       value="<?php echo isset($_GET['exp_max']) ? htmlspecialchars($_GET['exp_max']) : ''; ?>">
                            </div>
            </div>

            <div class="filter-group">
                            <label>Sort By</label>
                            <select name="sort" onchange="this.form.submit()">
                                <option value="">Default</option>
                                <option value="id" <?php echo isset($_GET['sort']) && $_GET['sort'] === 'id' ? 'selected' : ''; ?>>ID</option>
                                <option value="name" <?php echo isset($_GET['sort']) && $_GET['sort'] === 'name' ? 'selected' : ''; ?>>Name</option>
                                <option value="height" <?php echo isset($_GET['sort']) && $_GET['sort'] === 'height' ? 'selected' : ''; ?>>Height</option>
                                <option value="weight" <?php echo isset($_GET['sort']) && $_GET['sort'] === 'weight' ? 'selected' : ''; ?>>Weight</option>
                                <option value="exp" <?php echo isset($_GET['sort']) && $_GET['sort'] === 'exp' ? 'selected' : ''; ?>>Base Experience</option>
                            </select>
            </div>

            <div class="filter-group">
                            <label>Sort Order</label>
                            <select name="order" onchange="this.form.submit()">
                                <option value="asc" <?php echo isset($_GET['order']) && $_GET['order'] === 'asc' ? 'selected' : ''; ?>>Ascending</option>
                                <option value="desc" <?php echo isset($_GET['order']) && $_GET['order'] === 'desc' ? 'selected' : ''; ?>>Descending</option>
                            </select>
            </div>

                        <div class="filter-group view-toggle">
                            <label>View</label>
                            <div class="toggle-buttons">
                                <a href="?<?php echo http_build_query(array_merge($_GET, ['view' => 'grid'])); ?>" 
                                   class="toggle-btn <?php echo (!isset($_GET['view']) || $_GET['view'] === 'grid') ? 'active' : ''; ?>">
                                    <i class="fas fa-th"></i> Grid
                                </a>
                                <a href="?<?php echo http_build_query(array_merge($_GET, ['view' => 'list'])); ?>" 
                                   class="toggle-btn <?php echo (isset($_GET['view']) && $_GET['view'] === 'list') ? 'active' : ''; ?>">
                                    <i class="fas fa-list"></i> List
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
        </form>

            <div id="search-loading" class="loading-indicator" style="display: none;">
                <div class="loading-spinner"></div>
                <p>Loading Pokemon...</p>
            </div>

            <?php
            if (isset($_GET['search']) || isset($_GET['type']) || isset($_GET['height_min']) || isset($_GET['height_max']) || 
                isset($_GET['weight_min']) || isset($_GET['weight_max']) || isset($_GET['exp_min']) || isset($_GET['exp_max'])) {
                $search = isset($_GET['search']) ? strtolower($_GET['search']) : '';
                $type = isset($_GET['type']) ? strtolower($_GET['type']) : '';
                $heightMin = isset($_GET['height_min']) ? (float)$_GET['height_min'] : null;
                $heightMax = isset($_GET['height_max']) ? (float)$_GET['height_max'] : null;
                $weightMin = isset($_GET['weight_min']) ? (float)$_GET['weight_min'] : null;
                $weightMax = isset($_GET['weight_max']) ? (float)$_GET['weight_max'] : null;
                $expMin = isset($_GET['exp_min']) ? (int)$_GET['exp_min'] : null;
                $expMax = isset($_GET['exp_max']) ? (int)$_GET['exp_max'] : null;
                $sort = isset($_GET['sort']) ? $_GET['sort'] : 'id';
                $order = isset($_GET['order']) ? $_GET['order'] : 'asc';
                $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                
                // Set fixed limits for Generation 1
                $startId = 1;
                $endId = 151;
                
                echo "<div class='pokemon-container'>";
                
                // If searching by name or ID, show that specific Pokemon
                if ($search) {
                    $pokemonId = is_numeric($search) ? (int)$search : null;
                    $pokemon = null;
                    
                    if ($pokemonId) {
                        $pokemon = getCachedPokemon($pokemonId);
                    }
                    
                    if (!$pokemon) {
                        $url = "https://pokeapi.co/api/v2/pokemon/" . urlencode($search);
                        $response = file_get_contents($url);
                        $pokemon = json_decode($response, true);
                        
                        if ($pokemon && $pokemonId) {
                            cachePokemon($pokemonId, $pokemon);
                        }
                    }
                    
                    if ($pokemon) {
                        // Check if the Pokemon matches the type filter and is from Generation 1
                        $pokemonTypes = array_map(fn($t) => $t['type']['name'], $pokemon['types']);
                        if (!$type || in_array($type, $pokemonTypes)) {
                            if ($pokemon['id'] >= $startId && $pokemon['id'] <= $endId) {
                                displayPokemon($pokemon);
                            } else {
                                echo "<div class='error'>This Pokemon is not from Generation 1!</div>";
                            }
                        } else {
                            echo "<div class='error'>This Pokemon doesn't match the selected type!</div>";
                        }
                    } else {
                        echo "<div class='error'>Pokemon not found!</div>";
                    }
                } else {
                    // If no specific search, show multiple Pokemon matching the filters
                    $limit = 9; // Number of Pokemon per page
                    $offset = ($page - 1) * $limit;
                    
                    // Fetch all Generation 1 Pokemon in one call
                    $url = "https://pokeapi.co/api/v2/pokemon?limit=151&offset=0";
                    $response = file_get_contents($url);
                    $data = json_decode($response, true);
                    
                    $matchingPokemon = [];
                    $batchSize = 20; // Process Pokemon in batches
                    
                    // Process Pokemon in batches to avoid memory issues
                    for ($i = 0; $i < count($data['results']); $i += $batchSize) {
                        $batch = array_slice($data['results'], $i, $batchSize);
                        foreach ($batch as $result) {
                            $pokemonId = (int)basename($result['url']);
                            
                            // Only include Pokemon from Generation 1
                            if ($pokemonId >= $startId && $pokemonId <= $endId) {
                                $pokemon = getCachedPokemon($pokemonId);
                                
                                if (!$pokemon) {
                                    $pokemon = json_decode(file_get_contents($result['url']), true);
                                    cachePokemon($pokemonId, $pokemon);
                                }
                                
                                // Apply filters
                                $pokemonTypes = array_map(fn($t) => $t['type']['name'], $pokemon['types']);
                                $pokemonHeight = $pokemon['height'] / 10; // Convert to meters
                                $pokemonWeight = $pokemon['weight'] / 10; // Convert to kg
                                $pokemonExp = $pokemon['base_experience'];
                                
                                $matchesType = !$type || in_array($type, $pokemonTypes);
                                $matchesHeight = (!$heightMin || $pokemonHeight >= $heightMin) && (!$heightMax || $pokemonHeight <= $heightMax);
                                $matchesWeight = (!$weightMin || $pokemonWeight >= $weightMin) && (!$weightMax || $pokemonWeight <= $weightMax);
                                $matchesExp = (!$expMin || $pokemonExp >= $expMin) && (!$expMax || $pokemonExp <= $expMax);
                                
                                if ($matchesType && $matchesHeight && $matchesWeight && $matchesExp) {
                                    $matchingPokemon[] = $pokemon;
                                }
                            }
                        }
                        
                        // Add a small delay between batches to avoid rate limiting
                        if ($i + $batchSize < count($data['results'])) {
                            usleep(100000); // 100ms delay
                        }
                    }
                    
                    // Sort Pokemon based on selected criteria
                    usort($matchingPokemon, function($a, $b) use ($sort, $order) {
                        $comparison = 0;
                        switch ($sort) {
                            case 'name':
                                $comparison = strcmp($a['name'], $b['name']);
                                break;
                            case 'height':
                                $comparison = $a['height'] - $b['height'];
                                break;
                            case 'weight':
                                $comparison = $a['weight'] - $b['weight'];
                                break;
                            case 'exp':
                                $comparison = ($a['base_experience'] ?? 0) - ($b['base_experience'] ?? 0);
                                break;
                            default: // id
                                $comparison = $a['id'] - $b['id'];
                        }
                        return $order === 'asc' ? $comparison : -$comparison;
                    });
                    
                    // Paginate the results
                    $totalPokemon = count($matchingPokemon);
                    $startIndex = ($page - 1) * $limit;
                    $endIndex = min($startIndex + $limit, $totalPokemon);
                    $paginatedPokemon = array_slice($matchingPokemon, $startIndex, $limit);
                    
                    if (empty($paginatedPokemon)) {
                        echo "<div class='error'>No Pokemon found matching your criteria!</div>";
                    } else {
                        // Show view based on user selection
                        if (isset($_GET['view']) && $_GET['view'] === 'list') {
                            // List view
                            echo "<div class='pokemon-list'>";
                            echo "<table class='pokemon-table'>";
                            echo "<thead><tr><th>ID</th><th>Name</th><th>Type</th><th>Height</th><th>Weight</th><th>Actions</th></tr></thead>";
                            echo "<tbody>";
                            
                            foreach ($paginatedPokemon as $pokemon) {
                                $types = array_map(fn($t) => $t['type']['name'], $pokemon['types']);
                                echo "<tr>";
                                echo "<td>#" . str_pad($pokemon['id'], 3, '0', STR_PAD_LEFT) . "</td>";
                                echo "<td>" . ucfirst($pokemon['name']) . "</td>";
                                echo "<td>" . implode(", ", array_map('ucfirst', $types)) . "</td>";
                                echo "<td>" . ($pokemon['height'] / 10) . "m</td>";
                                echo "<td>" . ($pokemon['weight'] / 10) . "kg</td>";
                                echo "<td><button class='view-btn' onclick='showPokemonDetails({$pokemon['id']})'>View Details</button></td>";
                                echo "</tr>";
                            }
                            
                            echo "</tbody></table>";
                            echo "</div>";
                        } else {
                            // Grid view
                            echo "<div class='pokemon-grid'>";
                            foreach ($paginatedPokemon as $pokemon) {
                                displayPokemon($pokemon);
                            }
                            echo "</div>";
                        }
                        
                        // Show total count if more than 9 Pokemon
                        if ($totalPokemon > $limit) {
                            echo "<div class='info'>Showing " . ($startIndex + 1) . " to " . $endIndex . " of $totalPokemon Pokemon</div>";
                            
                            // Add Load More button if there are more Pokemon
                            if ($endIndex < $totalPokemon) {
                                $nextPage = $page + 1;
                                echo "<div class='load-more-container'>";
                                echo "<a href='?page={$nextPage}&type={$type}&height_min={$heightMin}&height_max={$heightMax}&weight_min={$weightMin}&weight_max={$weightMax}&exp_min={$expMin}&exp_max={$expMax}&sort={$sort}&order={$order}&view=" . (isset($_GET['view']) ? $_GET['view'] : 'grid') . "' class='load-more'>Load More</a>";
                                echo "</div>";
                            }
                        }
                    }
                }
                
                echo "</div>";
            }
            ?>
        </div>
    </div>

    <div id="pokemon-modal" class="pokemon-popup">
        <div id="loading-indicator" class="loading-indicator">
            <div class="loading-spinner"></div>
            <p>Loading Pokemon details...</p>
        </div>
        <div id="modal-content"></div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('pokemon-modal');
            const modalContent = document.getElementById('modal-content');
        const loadingIndicator = document.getElementById('loading-indicator');
        const searchLoading = document.getElementById('search-loading');
        const searchForm = document.querySelector('form');

        // Show loading indicator when form is submitted
        searchForm.addEventListener('submit', function() {
            searchLoading.style.display = 'block';
        });

        // Function to show Pokemon details
        function showPokemonDetails(pokemonId) {
            // Show modal and loading indicator
            modal.style.display = "block";
            loadingIndicator.style.display = "block";
            modalContent.style.display = "none";
            
            // Fetch Pokemon details
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

                    // Add event listeners for the new close button
                    const closeBtn = modal.querySelector('.close-popup');
                    closeBtn.addEventListener('click', () => {
                        modal.style.display = 'none';
                    });
                })
                .catch(error => {
                    // Handle errors
                    loadingIndicator.style.display = "none";
                        modalContent.innerHTML = `
                        <div class="popup-content">
                            <span class="close-popup">&times;</span>
                            <div class="error">
                                Failed to load Pokemon details. Please try again.
                            </div>
                            </div>
                        `;
                    modalContent.style.display = "block";
                });
        }

        // Add click event listeners to grid view cards
        document.querySelectorAll('.card').forEach(card => {
            card.addEventListener('click', function(e) {
            // If the click came from a form or button inside the card, ignore it
            if (e.target.tagName === 'BUTTON' || e.target.closest('form')) {
                return;
            }
                const pokemonId = this.dataset.pokemonId;
                showPokemonDetails(pokemonId);
            });
        });

        // Add click event listeners to list view buttons
        document.querySelectorAll('.view-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const pokemonId = this.getAttribute('onclick').match(/\d+/)[0];
                showPokemonDetails(pokemonId);
            });
        });

        // Close modal when clicking outside
        window.addEventListener('click', function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        });
        });
    </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 

<?php
function displayPokemon($pokemon, $detailed = false) {
    $types = array_map(fn($t) => $t['type']['name'], $pokemon['types']);
    $abilities = array_map(fn($a) => $a['ability']['name'], $pokemon['abilities']);
    $stats = array_map(fn($s) => [
        'name' => $s['stat']['name'],
        'value' => $s['base_stat']
    ], $pokemon['stats']);

    $pokemonName = $pokemon['name'];
    $pokemonId = $pokemon['id'];
    $imgSrc = $pokemon['sprites']['front_default'];

    // Check if user is logged in
    $alreadyCaught = false;
    if (isset($_SESSION['user_id'])) {
        $conn = new mysqli('localhost', 'root', '', 'Pokemon');
        if (!$conn->connect_error) {
            $stmt = $conn->prepare("SELECT 1 FROM caught_pokemon WHERE user_id = ? AND pokemon_name = ?");
            $stmt->bind_param("is", $_SESSION['user_id'], $pokemonName);
            $stmt->execute();
            $stmt->store_result();
            $alreadyCaught = $stmt->num_rows > 0;
            $stmt->close();
            $conn->close();
        }
    }

    echo "<div class='card' data-pokemon-id='{$pokemonId}'>";
    echo "<div class='card-header'>";
    echo "<h2>" . ucfirst($pokemonName) . "</h2>";
    echo "<img src='{$imgSrc}' alt='{$pokemonName}'>";
    echo "<div class='types'>";
    foreach ($types as $t) {
        echo "<span class='type-badge {$t}'>" . ucfirst($t) . "</span>";
    }
    echo "</div>";
    echo "</div>";

    // Catch Button
    echo "<div class='catch-button'>";
    if ($alreadyCaught) {
        echo "<button class='caught' disabled>Caught</button>";
    } else {
        echo "<form method='POST' action='catch.php' class='catch-form'>";
        echo "<input type='hidden' name='pokemon_name' value='" . htmlspecialchars($pokemonName) . "'>";
        echo "<button type='submit' class='catch-btn'>Catch</button>";
        echo "<div class='pokeball-animation' style='display: none;'>";
        echo "<img src='https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/items/poke-ball.png' alt='Pokeball'>";
        echo "</div>";
        echo "</form>";
    }
    echo "</div>";

    echo "</div>";
}


function getCachedPokemon($id) {
    $cacheFile = "cache/pokemon_{$id}.json";
    if (file_exists($cacheFile)) {
        return json_decode(file_get_contents($cacheFile), true);
    }
    return null;
}

function cachePokemon($id, $data) {
    if (!is_dir('cache')) {
        mkdir('cache', 0777, true);
    }
    $cacheFile = "cache/pokemon_{$id}.json";
    file_put_contents($cacheFile, json_encode($data));
}
?> 
<script>
document.querySelectorAll('.catch-form').forEach(form => {
    form.addEventListener('submit', function(e) {
        const button = form.querySelector('.catch-btn');
        const pokeball = form.querySelector('.pokeball-animation');

        // Show Pokéball, hide button
        pokeball.style.display = 'block';
        button.style.display = 'none';

        // Wait 1.5 seconds, then submit
        e.preventDefault();
        setTimeout(() => {
            form.submit();
        }, 1500);
    });
});
</script>
