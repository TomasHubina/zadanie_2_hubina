<?php
// Spustenie session
session_start();

// Kontrola, či bola odoslaná požiadavka na prepnutie dark mode
if (isset($_GET['dark_mode'])) {
    $_SESSION['dark_mode'] = $_GET['dark_mode'] === 'on' ? 'on' : 'off';
}

// Nastavenie triedy pre dark mode
$darkModeClass = isset($_SESSION['dark_mode']) && $_SESSION['dark_mode'] === 'on' ? 'dark-mode' : '';
?>
<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moja stránka</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/accordion.css">
    <link rel="stylesheet" href="css/banner.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body class="<?php echo $darkModeClass; ?>">
<?php
  $file_path = "header.php";
    if(!require($file_path)) {
      echo"Failed to include $file_path";
}
?>
  <main>
    <section class="banner">
      <div class="container text-white">
        <h1>Q&A</h1>
      </div>
    </section>
    <?php
    // Načítanie triedy QnA
    require_once(__DIR__ . '/classes/QnA.php');
    use otazkyodpovede\QnA;

    // Vytvorenie inštancie triedy QnA
    $qna = new QnA();

    // Zavolanie metódy na načítanie otázok a odpovedí z databázy
    $otazkyOdpovede = $qna->getQnA();
    ?>

    <section class="container">
        <div class="row">
            <div class="col-100 text-center">
                <h2>Otázky a odpovede</h2>
            </div>
        </div>
        <?php if (!empty($otazkyOdpovede)): ?>
            <?php foreach ($otazkyOdpovede as $qna): ?>
                <div class="accordion">
                    <div class="question"><?php echo htmlspecialchars($qna['otazka']); ?></div>
                    <div class="answer"><?php echo htmlspecialchars($qna['odpoved']); ?></div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center">Žiadne otázky a odpovede neboli nájdené.</p>
        <?php endif; ?>
    </section>
  </footer>
<script src="js/accordion.js"></script>
<script src="js/menu.js"></script>
</body>
</html>