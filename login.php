<?php

require_once "conn.php";

// Se l'utente è già autenticato lo reindirizza alla dashboard
if (isset($_SESSION["user_id"])) {

    header("Location: dashboard.php");
    exit();

}

$title = "Login";

include "header.php";

?>

<div class="container mt-5 mb-5">

    <div class="row justify-content-center">

        <div class="col-md-6 col-lg-5">

            <div class="material-card">

                <div class="material-card-body">

                    <h2 class="material-title text-center">Bentornato</h2>

                    <form action="handle_login.php" method="POST">

                        <div class="form-floating mb-3">
                            <input
                                type="email"
                                class="form-control"
                                id="email"
                                name="email"
                                placeholder="Email"
                                required>
                            <label for="email">Email</label>
                        </div>

                        <div class="form-floating mb-4">
                            <input
                                type="password"
                                class="form-control"
                                id="password"
                                name="password"
                                placeholder="Password"
                                required>
                            <label for="password">Password</label>
                        </div>

                        <button
                            type="submit"
                            class="btn btn-material w-100">

                            Accedi

                        </button>

                    </form>

                    <div class="material-divider">oppure</div>

                    <div class="d-grid gap-2">

                        <a href="auth_google.php" class="btn-material-outline">
                            <i class="bi bi-google"></i> Accedi con Google
                        </a>

                        <a href="auth_facebook.php" class="btn-material-outline">
                            <i class="bi bi-facebook"></i> Accedi con Facebook
                        </a>

                    </div>

                    <p class="text-center mt-4 mb-0">

                        Non hai ancora un account?

                        <a href="register.php" class="material-link">

                            Registrati

                        </a>

                    </p>

                </div>

            </div>

        </div>

    </div>

</div>

<?php

include "footer.php";

?>