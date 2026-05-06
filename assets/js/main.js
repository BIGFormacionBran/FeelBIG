(function() {
    "use strict";

    const initAuth = () => {
        const passInput = document.getElementById('passInput');
        const toggleBtn = document.getElementById('toggleBtn');
        if (passInput && toggleBtn) {
            toggleBtn.addEventListener('click', () => {
                const isPass = passInput.type === "password";
                passInput.type = isPass ? "text" : "password";
                toggleBtn.querySelector('.icon-open').style.display = isPass ? 'none' : 'block';
                toggleBtn.querySelector('.icon-closed').style.display = isPass ? 'block' : 'none';
            });
        }
    };

    // NUEVA LÓGICA: Detección de sesión de navegador
    const initGoogleAuth = () => {
        const statusMsg = document.getElementById('google-status-msg');
        const inputCodigo = document.getElementById('inputCodigo');

        if (window.google && window.location.pathname.includes('register-confirm')) {
            google.accounts.id.initialize({
                client_id: "TU_GOOGLE_CLIENT_ID.apps.googleusercontent.com", 
                callback: (response) => {
                    // Si el navegador entrega la credencial, el usuario está logueado.
                    // Aquí podrías disparar una validación fetch si fuera necesario.
                    console.log("Usuario autenticado en el navegador.");
                },
                auto_select: true
            });

            google.accounts.id.prompt((notification) => {
                if (notification.isNotDisplayed() || notification.isSkippedMoment()) {
                    if (statusMsg) statusMsg.style.display = 'block';
                }
            });
        }
    };

    const initCarousels = () => {
        const sections = document.querySelectorAll('.feelbig-swiper-section');
        sections.forEach((container) => {
            const swiperEl = container.querySelector('.swiper-feelbig-generic');
            if (swiperEl && window.Swiper && !swiperEl.classList.contains('swiper-initialized')) {
                new Swiper(swiperEl, {
                    slidesPerView: 'auto',
                    spaceBetween: 30,
                    centeredSlides: true,
                    loop: true,
                    loopedSlides: 5,
                    speed: 800,
                    autoplay: { delay: 3000, disableOnInteraction: false },
                    navigation: {
                        nextEl: container.querySelector('.swiper-button-next'),
                        prevEl: container.querySelector('.swiper-button-prev'),
                    },
                    pagination: {
                        el: container.querySelector('.swiper-pagination-custom'),
                        clickable: true,
                    },
                    observer: true,
                    observeParents: true
                });
            }
        });
    };

    const init = () => { 
        initAuth(); 
        initGoogleAuth(); // Lanzamos la comprobación de sesión
        initCarousels(); 
    };
    
    document.readyState === "loading" ? document.addEventListener("DOMContentLoaded", init) : init();
})();