<?php
$title = "Registrazione";
include "header.php";
?>

<div class="container mt-5">

    <div class="row justify-content-center">

        <div class="col-md-6">

            <div class="card shadow">

                <div class="card-header bg-primary text-white">
                    <h3 class="text-center">Registrazione</h3>
                </div>

                <div class="card-body">

                    <form action="handle_register.php" method="POST">

                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome</label>
                            <input
                                type="text"
                                class="form-control"
                                id="nome"
                                name="nome"
                                required>
                        </div>

                        <div class="mb-3">
                            <label for="cognome" class="form-label">Cognome</label>
                            <input
                                type="text"
                                class="form-control"
                                id="cognome"
                                name="cognome"
                                required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input
                                type="email"
                                class="form-control"
                                id="email"
                                name="email"
                                required>
                        </div>

                        <div class="mb-3">
                            <label for="telefono" class="form-label">Telefono</label>
                            <input
                                type="text"
                                class="form-control"
                                id="telefono"
                                name="telefono"
                                required>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input
                                type="password"
                                class="form-control"
                                id="password"
                                name="password"
                                required>
                        </div>

                        <div class="mb-3">
                            <label for="conferma_password" class="form-label">
                                Conferma Password
                            </label>

                            <input
                                type="password"
                                class="form-control"
                                id="conferma_password"
                                name="conferma_password"
                                required>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-success">
                                Registrati
                            </button>
                        </div>

                    </form>

                    <hr>

                    <p class="text-center">
                        Hai già un account?
                        <a href="login.php">Accedi</a>
                    </p>

                </div>

            </div>

        </div>

    </div>

</div>

<?php
include "footer.php";
?>