<footer>
    <p>&copy; <?php echo date("Y"); ?> W3S - Tutti i diritti riservati.</p>
</footer>

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

<!-- Auto-nascondi alert dopo 5 secondi e rimuovi parametri dall'URL per il tasto Indietro -->
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

    // Pulisce i parametri di notifica dall'URL senza ricaricare la pagina.
    // In questo modo, tornando indietro con la cronologia del browser, l'alert non verrà riproposto.
    if (window.location.search) {
        const urlParams = new URLSearchParams(window.location.search);
        const alertKeys = ["successo", "success", "errore", "error", "registered", "updated", "reset", "login", "sent"];
        let hasAlertParam = false;
        
        alertKeys.forEach(key => {
            if (urlParams.has(key)) {
                hasAlertParam = true;
                urlParams.delete(key);
            }
        });

        if (hasAlertParam) {
            const newSearch = urlParams.toString();
            const newUrl = window.location.pathname + (newSearch ? "?" + newSearch : "") + window.location.hash;
            window.history.replaceState({}, document.title, newUrl);
        }
    }
});

// Gestione del ripristino pagina da cache del browser (bfcache / tasto indietro)
window.addEventListener("pageshow", function (event) {
    const isBackNavigation = event.persisted || (window.performance && window.performance.getEntriesByType && window.performance.getEntriesByType("navigation")[0]?.type === "back_forward");
    if (isBackNavigation) {
        const alerts = document.querySelectorAll(".alert");
        alerts.forEach(alert => {
            // Non rimuovere i messaggi informativi fissi (es. "Nessun articolo...")
            if (!alert.classList.contains("small") && !alert.textContent.includes("Nessun")) {
                alert.remove();
            }
        });
    }
});
</script>

</body>
</html>