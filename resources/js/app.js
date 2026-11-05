import './bootstrap';

import Alpine from 'alpinejs';
import Chart from 'chart.js/auto';

window.Alpine = Alpine;
window.Chart = Chart;

Alpine.start();

document.addEventListener('submit', (event) => {
    if (event.defaultPrevented || !(event.target instanceof HTMLFormElement)) {
        return;
    }

    const button = event.submitter;
    if (!(button instanceof HTMLButtonElement) || button.disabled) {
        return;
    }

    button.disabled = true;
    button.setAttribute('aria-busy', 'true');
    button.textContent = 'Memproses…';
});
