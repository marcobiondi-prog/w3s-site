<?php

require_once __DIR__ . "/../controllers/conn.php";
require_once __DIR__ . "/../models/argomenti.php";

if (!isset($_SESSION["user_id"])) {
	header("Location: login.php");
	exit();
}

$title = "Gestione Argomenti";
include __DIR__ . "/header.php";

// Usa il modello per recuperare gli argomenti
$argomenti_list = Argomenti::getAll("id_argomento DESC");
?>

<div class="container mt-5">
	<h1 class="mb-4">Gestione Argomenti</h1>

	<?php if (isset($_GET["errore"]) && $_GET["errore"] === "duplicato") { ?>
		<div class="alert alert-warning alert-dismissible fade show" role="alert">
			<i class="bi bi-exclamation-circle"></i> Questo argomento esiste già.
			<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
		</div>
	<?php } ?>

	<?php if (isset($_GET["successo"]) && $_GET["successo"] === "inserimento") { ?>
		<div class="alert alert-success alert-dismissible fade show" role="alert" style="background-color: #d4edda; border: 2px solid #28a745; padding: 1rem;">
			<i class="bi bi-check-circle-fill" style="color: #28a745; font-size: 1.2rem;"></i> 
			<strong style="color: #28a745; font-size: 1.1rem;">Successo!</strong> Argomento inserito con successo.
			<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
		</div>
	<?php } ?>

	<?php if (isset($_GET["successo"]) && $_GET["successo"] === "modifica") { ?>
		<div class="alert alert-success alert-dismissible fade show" role="alert" style="background-color: #d4edda; border: 2px solid #28a745; padding: 1rem;">
			<i class="bi bi-check-circle-fill" style="color: #28a745; font-size: 1.2rem;"></i> 
			<strong style="color: #28a745; font-size: 1.1rem;">Successo!</strong> Argomento modificato con successo.
			<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
		</div>
	<?php } ?>

	<?php if (isset($_GET["successo"]) && $_GET["successo"] === "eliminazione") { ?>
		<div class="alert alert-success alert-dismissible fade show" role="alert" style="background-color: #d4edda; border: 2px solid #28a745; padding: 1rem;">
			<i class="bi bi-check-circle-fill" style="color: #28a745; font-size: 1.2rem;"></i> 
			<strong style="color: #28a745; font-size: 1.1rem;">Successo!</strong> Argomento eliminato con successo.
			<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
		</div>
	<?php } ?>

	<div class="card shadow mb-5">
		<div class="card-header bg-primary text-white"><h4>Nuovo Argomento</h4></div>
		<div class="card-body">
			<form action="../controllers/handle_argomenti.php" method="POST">
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
			<?php if (!empty($argomenti_list)) { ?>
				<table class="table table-striped">
					<thead><tr><th>ID</th><th>Nome</th><th>Azioni</th></tr></thead>
					<tbody>
						<?php foreach ($argomenti_list as $row) { ?>
							<tr>
								<td><?= $row["id_argomento"] ?></td>
								<td><?= htmlspecialchars($row["nome"]) ?></td>
								<td>
									<div class="d-flex gap-2">
										<a href="articoli_argomento.php?id=<?= $row["id_argomento"] ?>" class="btn btn-primary btn-sm">Leggi articoli</a>
										<button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalModifica" data-id="<?= $row["id_argomento"] ?>" data-nome="<?= htmlspecialchars($row["nome"]) ?>">Modifica</button>
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

<!-- Modale per modificare argomento -->
<div class="modal fade" id="modalModifica" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Modifica Argomento</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
			</div>
			<form action="../controllers/handle_argomenti.php" method="POST">
				<div class="modal-body">
					<input type="hidden" id="modalIdArgomento" name="id_argomento">
					<div class="mb-3">
						<label class="form-label" for="modalNome">Nome Argomento</label>
						<input type="text" id="modalNome" name="nome" class="form-control" required>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
					<button type="submit" class="btn btn-primary">Salva Modifiche</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script>
	const modalModifica = document.getElementById("modalModifica");
	modalModifica.addEventListener("show.bs.modal", (event) => {
		const button = event.relatedTarget;
		document.getElementById("modalIdArgomento").value = button.dataset.id;
		document.getElementById("modalNome").value = button.dataset.nome;
	});
</script>

<?php include __DIR__ . "/footer.php"; ?>