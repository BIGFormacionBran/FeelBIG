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

    // LÓGICA DE DETECCIÓN CORREGIDA PARA LOGIN AUTOMÁTICO (SILENT)
    const initGoogleAuth = () => {
        console.log("[GoogleAuth] 🚀 Iniciando flujo de verificación detallado...");
        
        const statusMsg = document.getElementById('google-status-msg');
        let sessionDetected = false;

        if (!window.location.pathname.includes('register-confirm')) {
            console.log("[GoogleAuth] 🛑 Ruta no válida para comprobación.");
            return;
        }

        if (!window.google) {
            console.error("[GoogleAuth] ❌ SDK de Google no encontrado.");
            return;
        }

        console.log("[GoogleAuth] ⚙️ Configurando google.accounts.id.initialize...");
        google.accounts.id.initialize({
            client_id: "TU_GOOGLE_CLIENT_ID.apps.googleusercontent.com", 
            auto_select: true, // ESTO fuerza que si ya entró una vez, entre solo
            use_fedcm_for_prompt: true, // Google obliga a esto ahora
            itp_support: true,
            callback: (response) => {
                sessionDetected = true;
                console.log("[GoogleAuth] ✅ ¡CALLBACK EXITOSO! Entrando sin intervención del usuario.");
                console.log("[GoogleAuth] 🔑 ID Token:", response.credential.substring(0, 30) + "...");
                
                if (statusMsg) {
                    console.log("[GoogleAuth] 🙈 Ocultando mensaje de estado.");
                    statusMsg.style.display = 'none';
                }
                
                // Aquí podrías redirigir o enviar el token a tu backend automáticamente
                // window.location.href = "/dashboard?token=" + response.credential;
            }
        });

        console.log("[GoogleAuth] 📡 Lanzando google.accounts.id.prompt() en modo automático...");
        
        google.accounts.id.prompt((notification) => {
            const moment = notification.getMomentType();
            console.log(`[GoogleAuth] 🕒 Momento actual: ${moment}`);
            
            if (notification.isNotDisplayed()) {
                console.warn("[GoogleAuth] ⚠️ Prompt NO mostrado. Razón:", notification.getNotDisplayedReason());
                console.log("[GoogleAuth] Detalle: Si la razón es 'skipped', Google cree que el usuario no quiere login automático o no hay sesión activa.");
            }
            
            if (notification.isSkippedMoment()) {
                console.warn("[GoogleAuth] ⏭️ Momento omitido. Razón:", notification.getSkippedReason());
            }

            if (notification.isDismissedMoment()) {
                console.warn("[GoogleAuth] ❌ El usuario cerró la ventana manualmente. Razón:", notification.getDismissedReason());
            }
            
            if (notification.isDisplayed()) {
                console.log("[GoogleAuth] 👀 El prompt visual se ha mostrado (el usuario NO entró solo).");
            }
        });

        // Verificación de seguridad tras 4 segundos
        setTimeout(() => {
            if (!sessionDetected) {
                console.error("[GoogleAuth] ⌛ TIMEOUT: 4 segundos sin detección automática.");
                console.log("[GoogleAuth] Acción: Mostrando aviso visual porque el 'auto_select' falló.");
                if (statusMsg) statusMsg.style.display = 'block';
            } else {
                console.log("[GoogleAuth] ✨ Verificación final: El usuario ya está dentro.");
            }
        }, 4000);
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
        console.log("[App] 🚀 Inicializando scripts...");
        initAuth(); 
        initGoogleAuth(); 
        initCarousels(); 
    };
    
    document.readyState === "loading" ? document.addEventListener("DOMContentLoaded", init) : init();
})();