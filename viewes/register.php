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

                    <?php if (isset($_GET["error"])) {
                        $error = $_GET["error"];
                        $messages = [
                            'email_exists' => 'Utente già esistente.',
                            'password_mismatch' => 'Le password non coincidono.',
                            'registration_failed' => '❌ Registrazione non effettuata. Riprova più tardi.',
                            'missing_name' => '❌ Nome e cognome sono obbligatori.',
                            'invalid_email' => '❌ Email non valida.',
                            'weak_password' => '❌ Password troppo debole. Deve contenere maiuscole, minuscole, numeri e simboli.',
                            'short_password' => '❌ Password troppo corta. Minimo 8 caratteri.',
                        ];
                        $message = $messages[$error] ?? '❌ Errore durante la registrazione.';
                    ?>
                        <div class="alert alert-danger alert-register-danger" role="alert">
                            <?= htmlspecialchars($message) ?>
                        </div>
                    <?php } ?>

                        <form action="../controllers/handle_register.php" method="POST" id="registerForm">

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

<!-- Validazione email e password lato client -->
<script>
document.getElementById('registerForm').addEventListener('submit', function(e) {
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('conferma_password').value;
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    // Valida l'email
    if (!email || !emailRegex.test(email)) {
        e.preventDefault();
        alert('⚠️ Email non valida. Per favore, inserisci un\'email valida.');
        document.getElementById('email').focus();
        return false;
    }

    // Valida che le password coincidono
    if (password !== confirmPassword) {
        e.preventDefault();
        alert('⚠️ Le password non coincidono. Per favore, verifica la password di conferma.');
        document.getElementById('conferma_password').focus();
        return false;
    }

    // Valida che la password non sia troppo corta
    if (password.length < 8) {
        e.preventDefault();
        alert('⚠️ La password deve contenere almeno 8 caratteri.');
        document.getElementById('password').focus();
        return false;
    }
});
</script>