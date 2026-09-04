<?php
$title = "W3S - Home";
include __DIR__ . '/viewes/header.php';

// Controlla se l'utente è loggato (per decidere quali articoli mostrare)
$logged_in = isset($_SESSION["user_id"]);

// Recupera gli ultimi 6 articoli (visibili in base al login)
if ($logged_in) {
    // Utenti loggati vedono tutti gli articoli (pubblici e privati)
    $sql = "SELECT a.id_articolo, a.titolo, a.pubblico, ar.nome AS argomento
            FROM articoli a
            INNER JOIN argomenti ar ON a.id_argomento = ar.id_argomento
            ORDER BY a.id_articolo DESC
            LIMIT 6";
} else {
    // Utenti non loggati vedono solo articoli pubblici (pubblico = 1)
    $sql = "SELECT a.id_articolo, a.titolo, a.pubblico, ar.nome AS argomento
            FROM articoli a
            INNER JOIN argomenti ar ON a.id_argomento = ar.id_argomento
            WHERE a.pubblico = 1
            ORDER BY a.id_articolo DESC
            LIMIT 6";
}

$articoli = $conn->query($sql);

// Debug: verifica se la query ha errori
if (!$articoli) {
    die("Errore nella query: " . $conn->error);
}
?>

  <!-- Hero Carousel Premium - PlayStation Style -->
<div id="demo" class="carousel slide carousel-premium" data-bs-ride="carousel" data-bs-pause="false" data-bs-interval="25000" touch="true">

  <!-- Progress bar -->
  <div class="carousel-progress-bar"></div>

  <!-- The slideshow/carousel -->
   
  <div class="carousel-inner">


    <div class="carousel-item active">
      <img src="asset/image/Screenshot 2026-09-03 153756.png" alt="Hero Slide 1" class="d-block w-100 carousel-media">
      <div class="carousel-overlay"></div>
       <div id="content" class="carousel-content-left">
        <div id="caption" class="carousel-text-left">
          <h2>Benvenuto su W3S</h2>
          <p class="carousel-subtitle">Scopri i nostri contenuti</p>
          <p class="carousel-description">Accedi alle risorse esclusive e migliora le tue competenze con i nostri articoli e esercizi pratici.</p>
          <a href="#articoli" class="btn-cta-white">Scopri di più</a>
        </div>
      </div>
    </div>


    <div class="carousel-item">
      <img src="asset/image/chicago.jpg" alt="Hero Slide 2" class="d-block w-100 carousel-media">
      <div class="carousel-overlay"></div>
      <div class="carousel-content-left">
        <div id="caption" class="carousel-text-left">
          <h2>Impara con noi</h2>
          <p class="carousel-subtitle">Contenuti esclusivi</p>
          <p class="carousel-description">Articoli, esercizi e risorse per approfondire le tue conoscenze tecniche.</p>
          <a href="#articoli" class="btn-cta-white">Inizia ora</a>
        </div>
      </div>
    </div>


    <div class="carousel-item">
      <img src="asset/image/ny.jpg" alt="Hero Slide 3" class="d-block w-100 carousel-media">
      <div class="carousel-overlay"></div>
      <div class="carousel-content-left">
        <div id="caption" class="carousel-text-left">
          <h2>Migliora le tue competenze</h2>
          <p class="carousel-subtitle">Risorse premium</p>
          <p class="carousel-description">Accedi a una vasta collezione di materiali didattici e guide complete.</p>
          <a href="#articoli" class="btn-cta-white">Accedi</a>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Thumbnails Section - Separate from Carousel -->
<div class="carousel-thumbnails-section">
  <div class="carousel-indicators-thumbnails">
    <button type="button" data-bs-target="#demo" data-bs-slide-to="0" class="indicator-thumb active">
      <img src="asset/image/Screenshot 2026-09-03 153756.png" alt="Thumb 1">
    </button>
    <button type="button" data-bs-target="#demo" data-bs-slide-to="1" class="indicator-thumb">
      <img src="asset/image/chicago.jpg" alt="Thumb 2">
    </button>
    <button type="button" data-bs-target="#demo" data-bs-slide-to="2" class="indicator-thumb">
      <img src="asset/image/ny.jpg" alt="Thumb 3">
    </button>
  </div>
</div>


<main class="container mt-5">



    <h1 class="mb-4">Ultimi articoli</h1>


    <?php if ($articoli && $articoli->num_rows > 0) { ?>

        <div class="row g-4">

            <?php while ($articolo = $articoli->fetch_assoc()) { ?>

                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body d-flex flex-column">

                            <span class="badge <?php echo $articolo["pubblico"] ? "bg-success" : "bg-secondary"; ?> mb-2 align-self-start">
                                <?php echo $articolo["pubblico"] ? "Pubblico" : "Privato"; ?>
                            </span>

                            <h5 class="card-title"><?php echo htmlspecialchars($articolo["titolo"]); ?></h5>
                            <p class="card-text text-muted mb-3"><?php echo htmlspecialchars($articolo["argomento"]); ?></p>

                            <a
                                href="viewes/articolo.php?id=<?php echo $articolo["id_articolo"]; ?>"
                                class="btn btn-primary btn-sm mt-auto">
                                Leggi
                            </a>

                        </div>
                    </div>
                </div>

            <?php } ?>

        </div>

    <?php } else { ?>

        <div class="alert alert-warning">
            Nessun articolo disponibile al momento.
        </div>

    <?php } ?>

</main>

<?php
include __DIR__ . '/viewes/footer.php';
?>
