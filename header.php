<header class="container main-header">
    <div class="logo-holder">
        <a href="index.php">
        <img src="img/logo.png" height="40">
        </a>
    </div>
      <nav class="main-nav">
        <ul class="main-menu" id="main-menu">
            <li><a href="index.php">Domov</a></li>
            <li><a href="portfolio.php">Portfólio</a></li>
            <li><a href="qna.php">Q&A</a></li>
            <li><a href="kontakt.php">Kontakt</a></li>
        </ul>
        <a class="hamburger" id="hamburger">
            <i class="fa fa-bars"></i>
        </a>
      </nav>
      <!-- Tlačidlo na prepínanie dark mode -->
      <form method="get" style="display: inline;">
          <button type="submit" name="dark_mode" value="<?php echo $darkModeClass === 'dark-mode' ? 'off' : 'on'; ?>">
              <?php echo $darkModeClass === 'dark-mode' ? 'Vypnúť Dark Mode' : 'Zapnúť Dark Mode'; ?>
          </button>
      </form>
</header>