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
					<thead><tr><th>ID</th><th>Nome</th></tr></thead>
					<tbody>
						<?php while ($row = $result->fetch_assoc()) { ?>
							<tr>
								<td><?= $row["id_argomento"] ?></td>
								<td><?= htmlspecialchars($row["nome"]) ?></td>
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