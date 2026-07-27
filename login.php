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

<div class="container mt-5">

    <div class="row justify-content-center">

        <div class="col-md-6">

            <div class="card shadow">

                <div class="card-header bg-primary text-white">

                    <h2 class="text-center">

                        Login

                    </h2>

                </div>

                <div class="card-body">

                    <form action="handle_login.php" method="POST">

                        <div class="mb-3">

                            <label class="form-label">

                                Email

                            </label>

                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                required>

                        </div>

                        <div class="mb-4">

                            <label class="form-label">

                                Password

                            </label>

                            <input
                                type="password"
                                name="password"
                                class="form-control"
                                required>

                        </div>

                        <button
                            type="submit"
                            class="btn btn-success w-100">

                            Accedi

                        </button>

                    </form>

                    <hr>

                    <p class="text-center">

                        Non hai ancora un account?

                        <a href="register.php">

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