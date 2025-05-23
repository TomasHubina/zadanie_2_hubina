<?php
function preparePortfolio(int $numberOfRows = 2, int $numberOfCols = 4): array {
    $portfolio = [];
    $colIndex = 1;
    for ($i = 1; $i <= $numberOfRows; $i++) {
        for ($j = 1; $j <= $numberOfCols; $j++) {
            $portfolio[$i][$j] = $colIndex;
            $colIndex++;
        }
    }
    return $portfolio;
}

function finishPortfolio() {
    $jsonData = file_get_contents(__DIR__ . '/data/data.json');
    $portfolioData = json_decode($jsonData, true);

    $portfolio = preparePortfolio();
    echo '<section class="container portfolio-section">';
    foreach ($portfolio as $row => $col) {
        echo '<div class="row">';
        foreach ($col as $index) {
            $fotoKey = 'portfolio_foto' . $index;
            $foto = $portfolioData[$fotoKey]['foto'] ?? 'img/default.jpg'; 
            $alt = $portfolioData[$fotoKey]['alt'] ?? 'Web stránka ' . $index;
            $url = $portfolioData[$fotoKey]['url'] ?? '#'; 

            echo '<a href="' . $url . '" class="col-25 portfolio" id="portfolio-' . $index . '" style="background-image: url(\'' . $foto . '\');">
                <div class="portfolio-content">
                    <p class="portfolio-text">' . $alt . '</p>
                </div>
            </a>';
        }
        echo '</div>';
    }
    echo '</section>';
}