import './bootstrap';
import Alpine from 'alpinejs';
import $ from 'jquery'; // Import jQuery
window.$ = $; // Make $ available globally

window.$ = window.jQuery = $;
window.Alpine = Alpine;

Alpine.start();