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

</body>
</html>