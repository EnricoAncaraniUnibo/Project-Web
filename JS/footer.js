function toggleAdmin(el) {
    const expand = el.nextElementSibling;

    // sicurezza: controlla che sia quello giusto
    if (!expand || !expand.classList.contains("admin-expand")) return;

        expand.classList.toggle("open");

        const arrow = el.querySelector(".arrow");
        arrow.classList.toggle("rotate");
}

