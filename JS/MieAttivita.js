const modaleSegnala = document.getElementById('exampleModal');
modaleSegnala.addEventListener('show.bs.modal', event => {
    const button = event.relatedTarget;
    const eventoId = button.getAttribute('data-evento-id');
    const inputEvento = document.getElementById('eventoIdModal');
    if (inputEvento) {
        inputEvento.value = eventoId;
    }
});