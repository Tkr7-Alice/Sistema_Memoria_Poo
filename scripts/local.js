document.addEventListener("DOMContentLoaded", () => {
    const temaSalvo = localStorage.getItem("tema");
    const idiomaSalvo = localStorage.getItem("idioma");

    // Aplica tema nos radios de todos os formulários
    if (temaSalvo) {
        document.body.classList.add("tema-" + temaSalvo);
        document.querySelectorAll(`input[name="tema"][value="${temaSalvo}"]`).forEach(radio => {
            radio.checked = true;
        });
    }

    // Aplica idioma em todos os selects nomeados "idioma"
    if (idiomaSalvo) {
        document.querySelectorAll('select[name="idioma"]').forEach(select => {
            select.value = idiomaSalvo;
        });
    }

    // Para cada formulário na página, adicionar o evento submit
    document.querySelectorAll("form").forEach(form => {
        form.addEventListener("submit", () => {
            const tema = form.querySelector('input[name="tema"]:checked')?.value;
            const idioma = form.querySelector('select[name="idioma"]')?.value;

            if (tema) localStorage.setItem("tema", tema);
            if (idioma) localStorage.setItem("idioma", idioma);
        });
    });
});
