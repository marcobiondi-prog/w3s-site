<footer>
    <p>&copy; <?php echo date("Y"); ?> W3S - Tutti i diritti riservati.</p>
</footer>

<?php
function renderBootstrapAlerts(): void
{
    if (isset($_GET["updated"]) && $_GET["updated"] == "1") {
        echo '<div class="alert alert-success" role="alert">Dati aggiornati con successo.</div>';
    }

    if (isset($_GET["registered"]) && $_GET["registered"] == "1") {
        echo '<div class="alert alert-success" role="alert">Registrazione avvenuta con successo!!</div>';
    }

    if (isset($_GET["error"])) {
        $error = $_GET["error"];

        if ($error === "email_not_found") {
            echo '<div class="alert alert-warning" role="alert"><i class="bi bi-exclamation-triangle"></i> Utente non registrato. Crea un account per poter accedere.</div>';
        } elseif ($error === "invalid_password") {
            echo '<div class="alert alert-danger" role="alert"><i class="bi bi-exclamation-triangle"></i> Password errata</div>';
        } elseif ($error === "missing_fields") {
            echo '<div class="alert alert-warning" role="alert"><i class="bi bi-exclamation-triangle"></i> Inserisci email e password</div>';
        } elseif (in_array($error, ["email_required", "email_invalid", "email_too_long"], true)) {
            echo '<div class="alert alert-warning" role="alert"><i class="bi bi-exclamation-triangle"></i> Inserisci un indirizzo email valido.</div>';
        } elseif ($error === "passwords_not_match") {
            echo '<div class="alert alert-danger" role="alert">Le password non coincidono. Per favore, verifica la password di conferma.</div>';
        } elseif ($error === "empty_password") {
            echo '<div class="alert alert-warning" role="alert">Inserisci una password valida.</div>';
        }
    }

    if (isset($_GET["reset"]) && $_GET["reset"] == "success") {
        echo '<div class="alert alert-success" role="alert"><i class="bi bi-check-circle"></i> Password reimpostata con successo! Accedi con la tua nuova password</div>';
    }

    if (isset($_GET["success"]) && $_GET["success"] == "1") {
        echo '<div class="alert alert-success" role="alert">Operazione completata con successo.</div>';
    }

    if (isset($_GET["successo"])) {
        $successo = $_GET["successo"];

        if ($successo === "inserimento") {
            echo '<div class="alert alert-success" role="alert">Elemento inserito con successo.</div>';
        } elseif ($successo === "modifica") {
            echo '<div class="alert alert-success" role="alert">Elemento modificato con successo.</div>';
        } elseif ($successo === "eliminazione") {
            echo '<div class="alert alert-success" role="alert">Elemento eliminato con successo.</div>';
        }
    }
}
?>

<!-- Bootstrap Bundle JS (necessario per il menu a tendina dell'utente, il toggler mobile, ecc.) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Effetto ripple in stile Material sui bottoni .btn-material / .btn-material-outline -->
<script>
document.addEventListener("click", function (event) {
    const button = event.target.closest(".btn-material, .btn-material-outline");
    if (!button) return;

    const rect = button.getBoundingClientRect();
    const size = Math.max(rect.width, rect.height);
    const ripple = document.createElement("span");

    ripple.className = "ripple-effect";
    ripple.style.width = ripple.style.height = size + "px";
    ripple.style.left = (event.clientX - rect.left - size / 2) + "px";
    ripple.style.top = (event.clientY - rect.top - size / 2) + "px";

    button.appendChild(ripple);
    ripple.addEventListener("animationend", () => ripple.remove());
});
</script>

<!-- Gestione backdrop e menu account mobile -->
<script>
const mobileUserMenu = document.querySelector(".mobile-user-menu");
const mobileUserPanel = document.querySelector(".mobile-user-panel");
const mobileUserTrigger = document.querySelector(".mobile-user-trigger");

if (mobileUserMenu && mobileUserPanel) {
    // Crea il backdrop
    const createBackdrop = () => {
        let backdrop = document.getElementById("menu-backdrop");
        if (!backdrop) {
            backdrop = document.createElement("div");
            backdrop.id = "menu-backdrop";
            backdrop.style.cssText = `
                position: fixed;
                inset: 0;
                background: rgba(10, 12, 30, 0.5);
                backdrop-filter: blur(6px);
                z-index: 100;
                opacity: 0;
                pointer-events: none;
                transition: opacity 0.18s;
            `;
            document.body.appendChild(backdrop);
        }
        return backdrop;
    };

    const backdrop = createBackdrop();

    // Osserva il menu per apriture/chiusure
    const observer = new MutationObserver(() => {
        if (mobileUserMenu.hasAttribute("open")) {
            backdrop.style.opacity = "1";
            backdrop.style.pointerEvents = "auto";
        } else {
            backdrop.style.opacity = "0";
            backdrop.style.pointerEvents = "none";
        }
    });

    observer.observe(mobileUserMenu, { attributes: true, attributeFilter: ["open"] });

    // Chiudi il menu quando clicchi sul backdrop
    backdrop.addEventListener("click", () => {
        mobileUserMenu.removeAttribute("open");
    });

    // Chiudi il menu quando clicchi fuori (anche se non sul backdrop)
    document.addEventListener("click", function (event) {
        if (!mobileUserMenu.hasAttribute("open")) return;

        const isClickInsideMenu = mobileUserPanel.contains(event.target);
        const isClickOnTrigger = mobileUserTrigger && mobileUserTrigger.contains(event.target);

        if (!isClickInsideMenu && !isClickOnTrigger) {
            mobileUserMenu.removeAttribute("open");
        }
    });
}
</script>

<!-- Auto-nascondi alert dopo 5 secondi -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    const alerts = document.querySelectorAll(".alert");
    alerts.forEach(alert => {
        setTimeout(() => {
            // Aggiunge animazione di scomparsa
            alert.style.transition = "opacity 0.5s ease-out";
            alert.style.opacity = "0";
            
            // Rimuove l'elemento dopo l'animazione
            setTimeout(() => {
                alert.remove();
            }, 500);
        }, 5000); // 5 secondi
    });
});
</script>

<?php renderBootstrapAlerts(); ?>

</body>
</html>