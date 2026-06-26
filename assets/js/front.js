document.addEventListener('DOMContentLoaded', function () {

    const mother = document.querySelector('.max-button-mother');
    const bubble = document.querySelector('.max-button-bubble');
    const hint   = document.querySelector('.max-button-hint');

    if (!mother) return;

    let hintShown = false;

    // toggle меню + скрытие hint
    if (bubble) {
        mother.addEventListener('click', function () {
            bubble.classList.toggle('open');

            if (hint) {
                hint.classList.remove('visible');
            }
        });
    }

    // hint показывается 1 раз через 5 секунд
    if (hint && !hintShown) {
        hintShown = true;

        setTimeout(() => {
            hint.classList.add('visible');
        }, 5000);
    }

});