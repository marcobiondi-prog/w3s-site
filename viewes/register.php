<?php
$title = "Registrazione";
include __DIR__ . "/header.php";
?>

<div class="container mt-5 mb-5">

    <div class="row justify-content-center">

        <div class="col-md-7 col-lg-6">

            <div class="material-card">

                <div class="material-card-body">

                    <h2 class="material-title text-center">Crea un account</h2>

                    <?php if (isset($_GET["exists"]) && $_GET["exists"] == "1") { ?>
                        <div class="alert alert-danger alert-register-danger" role="alert">
                            Utente gia registrato con questa email.
                        </div>
                    <?php } ?>

                    <?php if (isset($_GET["error"]) && $_GET["error"] == "1") { ?>
                        <div class="alert alert-danger alert-register-danger" role="alert">
                            registrazione non effettuata
                        </div>
                    <?php } ?>

                        <form action="../controllers/handle_register.php" method="POST">

                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="nome"
                                        name="nome"
                                        placeholder="Nome"
                                        required>
                                    <label for="nome">Nome *</label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="cognome"
                                        name="cognome"
                                        placeholder="Cognome"
                                        required>
                                    <label for="cognome">Cognome *</label>
                                </div>
                            </div>

                        </div>

                        <div class="form-floating mb-3">
                            <input
                                type="email"
                                class="form-control"
                                id="email"
                                name="email"
                                placeholder="Email"
                                required>
                            <label for="email">Email *</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input
                                type="text"
                                class="form-control"
                                id="telefono"
                                name="telefono"
                                placeholder="Telefono"
                                >
                            <label for="telefono">Telefono</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input
                                type="password"
                                class="form-control"
                                id="password"
                                name="password"
                                placeholder="Password"
                                required>
                            <label for="password">Password *</label>
                        </div>

                        <div class="form-floating mb-4">
                            <input
                                type="password"
                                class="form-control"
                                id="conferma_password"
                                name="conferma_password"
                                placeholder="Conferma Password"
                                required>
                            <label for="conferma_password">Conferma Password *</label>
                        </div>

                        <button type="submit" class="btn btn-material w-100">
                            Registrati
                        </button>

                    </form>

                    <div class="material-divider">oppure</div>

                    <div class="d-grid gap-2">

                        <a href="../controllers/auth_google.php" class="btn-material-outline">
                            <i class="bi bi-google"></i> Registrati con Google
                        </a>

                        <a href="../controllers/auth_facebook.php" class="btn-material-outline">
                            <i class="bi bi-facebook"></i> Registrati con Facebook
                        </a>

                    </div>

                    <p class="text-center mt-4 mb-0">
                        Hai già un account?
                        <a href="login.php" class="material-link">Accedi</a>
                    </p>

                </div>

            </div>

        </div>

    </div>

</div>

<?php
include __DIR__ . "/footer.php";
?>