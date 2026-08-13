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

import { initCompanyForm, initCompanyPage } from './company';
import { initCrudPage } from './general';
import './city';

document.addEventListener('DOMContentLoaded', () => {

    initCompanyForm();
    initCompanyPage();
    initCrudPage();

});


