document.addEventListener("DOMContentLoaded", () => {
    const toggles = document.querySelectorAll("[data-password-toggle]");

    toggles.forEach(toggle => {
        toggle.addEventListener("click", () => {
            const target = document.querySelector(toggle.dataset.passwordToggle);

            if (!target) return;

            if (target.type === "password") {
                target.type = "text";
                toggle.innerHTML = '<i class="fa-solid fa-eye-slash"></i>';
            } else {
                target.type = "password";
                toggle.innerHTML = '<i class="fa-solid fa-eye"></i>';
            }
        });
    });
});

// import './bootstrap';

import initCompany from './company';
import './city';
import initPlatform from './platform';
import initGenerals from './general';

document.addEventListener('DOMContentLoaded', () => {

    initCompany();
    initPlatform();
    initGenerals();

});