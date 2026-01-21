function toggleAdmin(el) {
    const expand = el.nextElementSibling;

    // Controlla se l'elemento ha una classe che inizia con "admin-expand"
    if (!expand || !Array.from(expand.classList).some(className => 
        className.startsWith("admin-expand"))) return;

    expand.classList.toggle("open");

    const arrow = el.querySelector(".arrow");
    if (arrow) {
        arrow.classList.toggle("rotate");
    }
}