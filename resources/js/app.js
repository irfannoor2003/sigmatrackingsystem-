import Alpine from 'alpinejs';
import { createIcons, icons } from 'lucide';

window.Alpine = Alpine;
Alpine.start();

// Render Lucide icons
document.addEventListener('DOMContentLoaded', () => createIcons({ icons }));

document.addEventListener('alpine:init', () => {
    Alpine.effect(() => createIcons({ icons }));
});
