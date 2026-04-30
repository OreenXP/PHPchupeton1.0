document.addEventListener("DOMContentLoaded", () => {
    const botonModo = document.getElementById("modo-oscuro");
    const modoGuardado = localStorage.getItem("modo");

    if (modoGuardado === "oscuro") {
        document.body.classList.add("dark-mode");
        if (botonModo) {
            botonModo.textContent = "☀";
        }
    }

    if (botonModo) {
        botonModo.addEventListener("click", () => {
            document.body.classList.toggle("dark-mode");

            if (document.body.classList.contains("dark-mode")) {
                localStorage.setItem("modo", "oscuro");
                botonModo.textContent = "☀";
            } else {
                localStorage.setItem("modo", "claro");
                botonModo.textContent = "☾";
            }
        });
    }
});
