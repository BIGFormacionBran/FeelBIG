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

    const initCarousels = () => {
        const sections = document.querySelectorAll('.feelbig-swiper-section');
        sections.forEach((container) => {
            const swiperEl = container.querySelector('.swiper-feelbig-generic');
            // Verificamos si existe el elemento y si no ha sido inicializado
            if (swiperEl && window.Swiper && !swiperEl.classList.contains('swiper-initialized')) {
                new Swiper(swiperEl, {
                    slidesPerView: 'auto',
                    spaceBetween: 30,
                    centeredSlides: true,
                    loop: true,
                    loopedSlides: 5, // Ayuda a que el loop sea fluido con slides 'auto'
                    speed: 800,
                    autoplay: { 
                        delay: 3000, 
                        disableOnInteraction: false 
                    },
                    // Usamos el contenedor padre para encontrar los botones específicos de esta sección
                    navigation: {
                        nextEl: container.querySelector('.swiper-button-next'),
                        prevEl: container.querySelector('.swiper-button-prev'),
                    },
                    pagination: {
                        el: container.querySelector('.swiper-pagination-custom'),
                        clickable: true,
                    },
                    // Observar cambios para evitar que se rompa al redimensionar
                    observer: true,
                    observeParents: true
                });
            }
        });
    };

    const init = () => { initAuth(); initCarousels(); };
    document.readyState === "loading" ? document.addEventListener("DOMContentLoaded", init) : init();
})();