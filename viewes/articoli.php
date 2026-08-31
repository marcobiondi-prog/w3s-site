<?php

require_once __DIR__ . "/../controllers/conn.php";

if (!isset($_SESSION["user_id"])) {
	header("Location: login.php");
	exit();
}

$title = "Nuovo Articolo";
include __DIR__ . "/header.php";
$result = $conn->query("SELECT * FROM argomenti ORDER BY nome ASC");
?>

<div class="container mt-5">
	<h1 class="mb-4">Nuovo Articolo</h1>

	<?php if (isset($_GET["successo"]) && $_GET["successo"] === "1") { ?>
		<div class="alert alert-success alert-dismissible fade show" role="alert" style="background-color: #d4edda; border: 2px solid #28a745; padding: 1rem;">
			<i class="bi bi-check-circle-fill" style="color: #28a745; font-size: 1.2rem;"></i> 
			<strong style="color: #28a745; font-size: 1.1rem;">Successo!</strong> Articolo salvato con successo.
			<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
		</div>
	<?php } ?>

	<div class="card shadow">
		<div class="card-body">
			<form action="../controllers/handle_articoli.php" method="POST">
				<div class="mb-3">
					<label class="form-label" for="id_argomento">Argomento</label>
					<select name="id_argomento" id="id_argomento" class="form-select" required>
						<option value="">Seleziona un argomento</option>
						<?php while ($row = $result->fetch_assoc()) { ?>
							<option value="<?= $row["id_argomento"] ?>"><?= htmlspecialchars($row["nome"]) ?></option>
						<?php } ?>
					</select>
				</div>
				<div class="mb-3">
					<label class="form-label" for="titolo">Titolo</label>
					<input type="text" name="titolo" id="titolo" class="form-control" required>
				</div>
				<div class="mb-4">
					<label class="form-label" for="contenuto">Contenuto dell'articolo</label>
					<textarea name="contenuto" id="contenuto" class="form-control" rows="12" required></textarea>
				</div>
				<div class="mb-4 form-check">
					<input type="checkbox" name="pubblico" value="1" class="form-check-input" id="pubblico">
					<label class="form-check-label" for="pubblico">Rendi visibile pubblicamente</label>
				</div>
				<button type="submit" class="btn btn-primary">Pubblica articolo</button>
			</form>
		</div>
	</div>
</div>

<?php include __DIR__ . "/footer.php"; ?>