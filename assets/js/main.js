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

    // LÓGICA DETALLADA: Detección e inyección de sesión
    const initGoogleAuth = () => {
        console.log("[GoogleAuth] Iniciando comprobación...");
        
        const statusMsg = document.getElementById('google-status-msg');
        const inputCodigo = document.getElementById('inputCodigo');

        if (!window.location.pathname.includes('register-confirm')) {
            console.log("[GoogleAuth] No estamos en la página de confirmación. Abortando.");
            return;
        }

        if (!window.google) {
            console.error("[GoogleAuth] Error: La librería 'google' no está cargada. Revisa la etiqueta script en auth_view.php.");
            return;
        }

        console.log("[GoogleAuth] Librería detectada. Inicializando SDK...");

        google.accounts.id.initialize({
            client_id: "TU_GOOGLE_CLIENT_ID.apps.googleusercontent.com", 
            callback: (response) => {
                console.log("[GoogleAuth] ¡Sesión detectada con éxito!");
                // Aquí el navegador nos da el ID Token, lo que confirma que hay sesión.
                // Intentamos extraer información o simplemente avisar.
                if (inputCodigo) {
                    console.log("[GoogleAuth] Intentando autocompletar input...");
                    // Nota: El "código" real está en el mail, pero si Google responde aquí 
                    // es que el usuario está logueado en Chrome con la misma cuenta.
                }
            },
            auto_select: true
        });

        google.accounts.id.prompt((notification) => {
            console.log("[GoogleAuth] Notificación de estado:", notification.getMomentType());
            
            if (notification.isNotDisplayed()) {
                console.warn("[GoogleAuth] El prompt no se mostró:", notification.getNotDisplayedReason());
                if (statusMsg) statusMsg.style.display = 'block';
            }
            
            if (notification.isSkippedMoment()) {
                console.warn("[GoogleAuth] El usuario omitió el prompt:", notification.getSkippedReason());
            }

            if (notification.isDismissedMoment()) {
                console.warn("[GoogleAuth] Prompt cerrado por el usuario.");
            }
        });
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