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

    // LÓGICA SILENCIOSA: Comprobación de sesión sin popups
    const initGoogleAuth = () => {
        console.log("[GoogleAuth] Comprobando sesión de forma silenciosa...");
        
        const statusMsg = document.getElementById('google-status-msg');
        const inputCodigo = document.getElementById('inputCodigo');

        if (!window.location.pathname.includes('register-confirm')) return;
        if (!window.google) return;

        // Inicializamos el SDK
        google.accounts.id.initialize({
            client_id: "TU_GOOGLE_CLIENT_ID.apps.googleusercontent.com", 
            auto_select: true, // Intenta seleccionar la cuenta automáticamente si hay sesión
            use_fedcm_for_prompt: true,
            callback: (response) => {
                // Si entra aquí, es que hay una sesión activa y Google la ha validado
                console.log("[GoogleAuth] Sesión detectada.");
                if (statusMsg) statusMsg.style.display = 'none';
                
                // Nota: Aquí podrías realizar una llamada al servidor para obtener el código 
                // si el email de Google coincide con el del registro, pero por ahora 
                // cumplimos con la lógica de detección.
            }
        });

        // IMPORTANTE: NO LLAMAMOS A google.accounts.id.prompt()
        // En su lugar, si tras 2 segundos no ha habido callback, mostramos el aviso (badge)
        setTimeout(() => {
            if (statusMsg && statusMsg.style.display === 'none') {
                console.log("[GoogleAuth] No se detectó sesión automática. Mostrando aviso.");
                statusMsg.style.display = 'block';
            }
        }, 2000);
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
        initGoogleAuth(); 
        initCarousels(); 
    };
    
    document.readyState === "loading" ? document.addEventListener("DOMContentLoaded", init) : init();
})();