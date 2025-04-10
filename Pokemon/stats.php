<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pokemon Stats - Pokemon Collection</title>
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
                    <a href="stats.php" class="active">Stats</a>
                    <a href="about.php">About</a>
                </div>
            </div>
        </nav>

        <div class="container">
            <h1>Pokemon Statistics</h1>
            
            <div class="stats-container">
                <?php
                // Fetch all Generation 1 Pokemon in one call
                $url = "https://pokeapi.co/api/v2/pokemon?limit=151&offset=0";
                $response = file_get_contents($url);
                $data = json_decode($response, true);
                
                $totalPokemon = count($data['results']);
                $typeCounts = [];
                $totalHeight = 0;
                $totalWeight = 0;
                $totalExp = 0;
                $expCount = 0;
                
                // Process Pokemon in batches
                $batchSize = 20;
                for ($i = 0; $i < count($data['results']); $i += $batchSize) {
                    $batch = array_slice($data['results'], $i, $batchSize);
                    foreach ($batch as $result) {
                        $pokemonId = (int)basename($result['url']);
                        $pokemon = getCachedPokemon($pokemonId);
                        
                        if (!$pokemon) {
                            $pokemon = json_decode(file_get_contents($result['url']), true);
                            cachePokemon($pokemonId, $pokemon);
                        }
                        
                        // Count types
                        foreach ($pokemon['types'] as $type) {
                            $typeName = $type['type']['name'];
                            $typeCounts[$typeName] = ($typeCounts[$typeName] ?? 0) + 1;
                        }
                        
                        // Calculate averages
                        $totalHeight += $pokemon['height'];
                        $totalWeight += $pokemon['weight'];
                        if ($pokemon['base_experience']) {
                            $totalExp += $pokemon['base_experience'];
                            $expCount++;
                        }
                    }
                    
                    // Add a small delay between batches
                    if ($i + $batchSize < count($data['results'])) {
                        usleep(100000); // 100ms delay
                    }
                }
                
                // Calculate averages
                $avgHeight = $totalHeight / $totalPokemon / 10; // Convert to meters
                $avgWeight = $totalWeight / $totalPokemon / 10; // Convert to kg
                $avgExp = $expCount > 0 ? $totalExp / $expCount : 0;
                
                // Sort types by count
                arsort($typeCounts);
                ?>
                
                <!-- Basic Stats -->
                <div class="stats-card">
                    <h2>Basic Statistics</h2>
                    <div class="stats-grid">
                        <div class="stat-item">
                            <span class="stat-label">Total Pokemon</span>
                            <span class="stat-value"><?php echo $totalPokemon; ?></span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Average Height</span>
                            <span class="stat-value"><?php echo number_format($avgHeight, 1); ?>m</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Average Weight</span>
                            <span class="stat-value"><?php echo number_format($avgWeight, 1); ?>kg</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Average Base Exp</span>
                            <span class="stat-value"><?php echo number_format($avgExp, 0); ?></span>
                        </div>
                    </div>
                </div>
                
                <!-- Type Distribution -->
                <div class="stats-card">
                    <h2>Type Distribution</h2>
                    <div class="type-stats">
                        <?php foreach ($typeCounts as $type => $count): ?>
                            <div class="type-stat">
                                <span class="type-badge <?php echo $type; ?>"><?php echo ucfirst($type); ?></span>
                                <div class="type-bar">
                                    <div class="type-bar-fill" style="width: <?php echo ($count / $totalPokemon) * 100; ?>%"></div>
                                </div>
                                <span class="type-count"><?php echo $count; ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
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