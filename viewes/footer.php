<footer>
    <p>&copy; <?php echo date("Y"); ?> W3S - Tutti i diritti riservati.</p>
</footer>

<!-- Bootstrap Bundle JS (necessario per il menu a tendina dell'utente, il toggler mobile, ecc.) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<?php
// Dissolvi gli alert in tutte le pagine TRANNE nella home se non loggato
$is_home = basename($_SERVER["SCRIPT_NAME"]) === "index.php";
$is_logged_in = isset($_SESSION["user_id"]);
$should_dissolve_alerts = !($is_home && !$is_logged_in);
?>

<?php if ($should_dissolve_alerts) { ?>
<!-- Dissolvenza automatica degli alert -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    const alerts = document.querySelectorAll(".alert");
    alerts.forEach(alert => {
        // Non dissolvere alert "Nessun articolo disponibile"
        if (alert.textContent.includes("Nessun articolo")) {
            return;
        }

        setTimeout(() => {
            alert.style.transition = "opacity 0.5s ease-out";
            alert.style.opacity = "0";

            setTimeout(() => {
                alert.remove();
            }, 500);
        }, 5000);
    });
});
</script>
<?php } ?>

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
<!-- Script per il dragging/swipe del carosello con mouse e touch -->
<script>
(function() {
    const carousel = document.getElementById("demo");
    if (!carousel) return;

    const bootstrapCarousel = new bootstrap.Carousel(carousel, {
        interval: 25000,
        pause: false
    });
    const progressBar = carousel.querySelector(".carousel-progress-bar");
    const indicators = carousel.querySelectorAll(".indicator-thumb");

    let startX = 0;
    let startY = 0;
    let currentX = 0;
    let isDragging = false;
    const minDragDistance = 30;

    // Funzione per reinizializzare la barra di progresso
    const resetProgressBar = () => {
        if (progressBar) {
            progressBar.style.animation = "none";
            setTimeout(() => {
                progressBar.style.animation = "carousel-progress 25s linear forwards";
            }, 10);
        }
    };

    // Aggiorna gli indicatori quando cambia slide
    const updateIndicators = () => {
        const activeItem = carousel.querySelector(".carousel-item.active");
        const activeIndex = Array.from(carousel.querySelectorAll(".carousel-item")).indexOf(activeItem);
        indicators.forEach((indicator, index) => {
            indicator.classList.toggle("active", index === activeIndex);
        });
    };

    // Riavvia la barra e aggiorna indicatori ad ogni cambio slide
    carousel.addEventListener("slide.bs.carousel", () => {
        resetProgressBar();
    });
    carousel.addEventListener("slid.bs.carousel", () => {
        updateIndicators();
    });

    // Aggiungi click handler agli indicatori
    indicators.forEach((indicator, index) => {
        indicator.addEventListener("click", () => {
            bootstrapCarousel.to(index);
        });
    });

    // Gestione mouse e touch
    const startDrag = (e) => {
        startX = e.type.includes("touch") ? e.touches[0].clientX : e.clientX;
        startY = e.type.includes("touch") ? e.touches[0].clientY : e.clientY;
        isDragging = true;
        carousel.style.cursor = "grabbing";
    };

    const moveDrag = (e) => {
        if (!isDragging) return;
        currentX = e.type.includes("touch") ? e.touches[0].clientX : e.clientX;
    };

    const endDrag = () => {
        if (!isDragging) return;
        isDragging = false;
        carousel.style.cursor = "grab";

        const distance = startX - currentX;

        // Swipe verso sinistra -> slide successivo
        if (distance > minDragDistance) {
            bootstrapCarousel.next();
        }
        // Swipe verso destra -> slide precedente
        else if (distance < -minDragDistance) {
            bootstrapCarousel.prev();
        }
    };

    // Mouse events
    carousel.addEventListener("mousedown", startDrag);
    carousel.addEventListener("mousemove", moveDrag);
    carousel.addEventListener("mouseup", endDrag);
    carousel.addEventListener("mouseleave", endDrag);

    // Touch events
    carousel.addEventListener("touchstart", startDrag, { passive: true });
    carousel.addEventListener("touchmove", moveDrag, { passive: true });
    carousel.addEventListener("touchend", endDrag);

    // Cambio cursore in hover
    carousel.style.cursor = "grab";

    // Inizializza la barra di progresso e indicatori al caricamento
    resetProgressBar();
    updateIndicators();
})();
</script>

