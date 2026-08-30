<?php

require_once __DIR__ . "/../controllers/conn.php";

if (!isset($_SESSION["user_id"])) {
	header("Location: login.php");
	exit();
}

$title = "Gestione Argomenti";
include __DIR__ . "/header.php";

$result = $conn->query("SELECT * FROM argomenti ORDER BY id_argomento DESC");
?>

<div class="container mt-5">
	<h1 class="mb-4">Gestione Argomenti</h1>

	<?php if (isset($_GET["errore"]) && $_GET["errore"] === "duplicato") { ?>
		<div class="alert alert-warning" role="alert">Questo argomento esiste già.</div>
	<?php } ?>

	<?php if (isset($_GET["successo"]) && $_GET["successo"] === "inserimento") { ?>
		<div class="alert alert-success" role="alert">Argomento inserito con successo.</div>
	<?php } ?>

	<?php if (isset($_GET["successo"]) && $_GET["successo"] === "eliminazione") { ?>
		<div class="alert alert-success" role="alert">Argomento eliminato con successo.</div>
	<?php } ?>

	<div class="card shadow mb-5">
		<div class="card-header bg-primary text-white"><h4>Nuovo Argomento</h4></div>
		<div class="card-body">
			<form action="../handle_argomenti.php" method="POST">
				<div class="mb-3">
					<label class="form-label" for="nome">Nome Argomento</label>
					<input type="text" id="nome" name="nome" class="form-control" required>
				</div>
				<button type="submit" class="btn btn-success">Aggiungi Argomento</button>
			</form>
		</div>
	</div>

	<div class="card shadow">
		<div class="card-header bg-dark text-white"><h4>Elenco Argomenti</h4></div>
		<div class="card-body">
			<?php if ($result->num_rows > 0) { ?>
				<table class="table table-striped">
					<thead><tr><th>ID</th><th>Nome</th><th>Azioni</th></tr></thead>
					<tbody>
						<?php while ($row = $result->fetch_assoc()) { ?>
							<tr>
								<td><?= $row["id_argomento"] ?></td>
								<td><?= htmlspecialchars($row["nome"]) ?></td>
								<td>
									<div class="d-flex gap-2">
										<a href="articoli_argomento.php?id=<?= $row["id_argomento"] ?>" class="btn btn-primary btn-sm">Leggi articoli</a>
										<form action="../controllers/handle_elimina_argomento.php" method="POST" onsubmit="return confirm('Eliminare questo argomento e tutti i contenuti collegati?');">
											<input type="hidden" name="id_argomento" value="<?= $row["id_argomento"] ?>">
											<button type="submit" class="btn btn-danger btn-sm">Elimina</button>
										</form>
									</div>
								</td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			<?php } else { ?>
				<div class="alert alert-warning">Nessun argomento presente.</div>
			<?php } ?>
		</div>
	</div>
</div>

<?php include __DIR__ . "/footer.php"; ?>