<?php
    // Path to the JSON files
    $gamesFile   = __DIR__ . '/DB/games.json';
    $ratingsFile = __DIR__ . '/DB/ratings.json';

    // Max rating value (x/10)
    const MAX_RATING = 10;

    // Reads the JSON
    function loadJson(string $path): array
    {
        if (!is_file($path)) {
            return [];
        }
        $data = json_decode(file_get_contents($path), true);
        return is_array($data) ? $data : [];
    }

    $games   = loadJson($gamesFile);
    $ratings = loadJson($ratingsFile);

    // Regroupe les notes par idGame : [idGame => [valeurs...]]
    $ratingsByGame = [];
    foreach ($ratings as $rating) {
        $ratingsByGame[$rating['idGame']][] = $rating['value'];
    }

    // Ajoute la moyenne à chaque jeu (null si aucune note)
    foreach ($games as &$game) {
        $values = $ratingsByGame[$game['idGame']] ?? [];
        $game['averageRating'] = count($values) > 0
            ? array_sum($values) / count($values)
            : null;
    }
    unset($game);

    // Tri par défaut : note la plus haute -> la plus basse (jeux sans note à la fin)
    usort($games, function ($a, $b) {
        $ra = $a['averageRating'] ?? -1;
        $rb = $b['averageRating'] ?? -1;
        return $rb <=> $ra;
    });
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Lobby</title>
    </head>
    <body>
        <header>
            <h1>Chiara's Romhacks Sanctuary</h1>
        </header>
        <main>
            <div class="game-list">
                <?php foreach ($games as $game): ?>
                    <a href="php/gameInfos.php?id=<?= (int) $game['idGame'] ?>" class="game">
                        <div class="gameTitle"><?= htmlspecialchars($game['name']) ?></div>
                        <div class="gameRating">
                            <?php if ($game['averageRating'] !== null): ?>
                                <?= number_format($game['averageRating'], 2) ?>/<?= MAX_RATING ?>
                            <?php else: ?>
                                No ratings
                            <?php endif; ?>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </main>
    </body>
</html>