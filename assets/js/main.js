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

    const initGoogleAuth = () => {
        console.log("[GoogleAuth] ══════════════════════════════════════════");
        console.log("[GoogleAuth] 🚀 INICIO del flujo de verificación silenciosa");
        console.log("[GoogleAuth] 📍 Ruta actual:", window.location.pathname);
        console.log("[GoogleAuth] ══════════════════════════════════════════");

        const statusMsg    = document.getElementById('google-status-msg');
        const inputCodigo  = document.getElementById('inputCodigo');

        console.log("[GoogleAuth] 🔎 Buscando elementos del DOM...");
        console.log("[GoogleAuth]    → #google-status-msg:", statusMsg  ? "✅ encontrado" : "❌ NO encontrado");
        console.log("[GoogleAuth]    → #inputCodigo:",       inputCodigo ? "✅ encontrado" : "❌ NO encontrado");

        if (!window.location.pathname.includes('register-confirm')) {
            console.log("[GoogleAuth] ⛔ Ruta no es 'register-confirm'. Saliendo sin hacer nada.");
            return;
        }
        console.log("[GoogleAuth] ✅ Ruta válida. Continuando...");

        if (!window.google || !window.google.accounts || !window.google.accounts.id) {
            console.error("[GoogleAuth] ❌ SDK de Google no disponible en window.google.accounts.id");
            console.error("[GoogleAuth]    → ¿Está cargado el script de GSI antes de main.js?");
            if (statusMsg) {
                statusMsg.style.display = 'block';
                console.log("[GoogleAuth] 📢 Mostrando badge (sin SDK).");
            }
            return;
        }
        console.log("[GoogleAuth] ✅ SDK de Google detectado correctamente.");

        // Leemos el email esperado desde el DOM (debe estar en un data-attribute o input hidden)
        const emailEsperado = document.body.dataset.userEmail || null;
        console.log("[GoogleAuth] 📧 Email esperado (data-user-email en <body>):", emailEsperado ?? "⚠️ NO definido — no podremos validar el email");

        let sessionDetected = false;
        let callbackFired   = false;

        console.log("[GoogleAuth] ──────────────────────────────────────────");
        console.log("[GoogleAuth] ⚙️  Llamando a google.accounts.id.initialize()...");
        console.log("[GoogleAuth]    → client_id: TU_GOOGLE_CLIENT_ID.apps.googleusercontent.com");
        console.log("[GoogleAuth]    → auto_select: true  (solo dispara callback si ya hay sesión, SIN mostrar UI)");
        console.log("[GoogleAuth]    → use_fedcm_for_prompt: false  (evitamos FedCM que falla con NetworkError)");
        console.log("[GoogleAuth]    → itp_support: true  (soporte para Safari ITP)");
        console.log("[GoogleAuth] ──────────────────────────────────────────");

        try {
            google.accounts.id.initialize({
                client_id: "TU_GOOGLE_CLIENT_ID.apps.googleusercontent.com",
                auto_select: true,
                use_fedcm_for_prompt: false, // ← CLAVE: desactiva FedCM que genera el NetworkError
                itp_support: true,
                callback: (response) => {
                    callbackFired   = true;
                    sessionDetected = true;

                    console.log("[GoogleAuth] ══════════════════════════════════════════");
                    console.log("[GoogleAuth] 🎉 CALLBACK DISPARADO — sesión detectada");
                    console.log("[GoogleAuth] ══════════════════════════════════════════");
                    console.log("[GoogleAuth] 📦 Tipo de respuesta:", typeof response);
                    console.log("[GoogleAuth] 🔑 credential presente:", !!response.credential);

                    if (!response.credential) {
                        console.error("[GoogleAuth] ❌ Callback disparado pero sin credential. Respuesta completa:", response);
                        if (statusMsg) statusMsg.style.display = 'block';
                        return;
                    }

                    const fragmento = response.credential.substring(0, 30);
                    console.log("[GoogleAuth] 🔑 Token (primeros 30 chars):", fragmento + "...");

                    // Decodificamos el payload del JWT para leer el email
                    try {
                        const payloadB64  = response.credential.split('.')[1];
                        const payloadJSON = atob(payloadB64.replace(/-/g, '+').replace(/_/g, '/'));
                        const payload     = JSON.parse(payloadJSON);

                        console.log("[GoogleAuth] 👤 Datos del usuario en el token:");
                        console.log("[GoogleAuth]    → email:",          payload.email     ?? "no presente");
                        console.log("[GoogleAuth]    → email_verified:", payload.email_verified ?? "no presente");
                        console.log("[GoogleAuth]    → name:",           payload.name      ?? "no presente");
                        console.log("[GoogleAuth]    → sub (Google ID):", payload.sub      ?? "no presente");
                        console.log("[GoogleAuth]    → aud (client_id):", payload.aud      ?? "no presente");
                        console.log("[GoogleAuth]    → iat:",            payload.iat       ?? "no presente");
                        console.log("[GoogleAuth]    → exp:",            payload.exp       ?? "no presente");

                        if (emailEsperado) {
                            const coincide = payload.email?.toLowerCase() === emailEsperado.toLowerCase();
                            console.log("[GoogleAuth] 🔍 Comparando emails:");
                            console.log("[GoogleAuth]    → Email en token:    ", payload.email);
                            console.log("[GoogleAuth]    → Email esperado:    ", emailEsperado);
                            console.log("[GoogleAuth]    → ¿Coinciden?:       ", coincide ? "✅ SÍ" : "❌ NO");

                            if (!coincide) {
                                console.warn("[GoogleAuth] ⚠️ El email del token NO coincide con el del registro. Mostrando badge.");
                                if (statusMsg) {
                                    statusMsg.style.display = 'block';
                                    console.log("[GoogleAuth] 📢 Badge visible.");
                                }
                                return;
                            }
                        } else {
                            console.warn("[GoogleAuth] ⚠️ No hay emailEsperado definido — se omite la comparación de email.");
                        }

                        // Todo OK: escribimos el código en el input
                        if (inputCodigo) {
                            // El "código" aquí es el credential token completo;
                            // ajusta este valor a lo que tu backend espere realmente
                            inputCodigo.value = response.credential;
                            console.log("[GoogleAuth] ✅ inputCodigo rellenado automáticamente.");
                            console.log("[GoogleAuth]    → Longitud del valor insertado:", inputCodigo.value.length, "chars");
                        } else {
                            console.error("[GoogleAuth] ❌ inputCodigo no encontrado en el DOM. No se pudo rellenar.");
                        }

                        if (statusMsg) {
                            statusMsg.style.display = 'none';
                            console.log("[GoogleAuth] 🙈 Badge informativo ocultado (sesión OK).");
                        }

                    } catch (decodeErr) {
                        console.error("[GoogleAuth] ❌ Error al decodificar el JWT:", decodeErr);
                        console.error("[GoogleAuth]    → Token raw:", response.credential.substring(0, 50) + "...");
                        if (statusMsg) statusMsg.style.display = 'block';
                    }
                }
            });
            console.log("[GoogleAuth] ✅ initialize() completado sin errores síncronos.");
        } catch (initErr) {
            console.error("[GoogleAuth] ❌ Error al llamar a initialize():", initErr);
            if (statusMsg) statusMsg.style.display = 'block';
            return;
        }

        // NO llamamos a .prompt() — eso es lo que abría la ventanita.
        // Con auto_select:true y use_fedcm_for_prompt:false, el SDK intentará
        // resolver la sesión silenciosamente via cookies/token de Google si las hay.
        console.log("[GoogleAuth] 🔕 .prompt() NO será llamado — flujo 100% silencioso.");
        console.log("[GoogleAuth] ⏳ Esperando callback automático durante 4 segundos...");

        setTimeout(() => {
            console.log("[GoogleAuth] ──────────────────────────────────────────");
            console.log("[GoogleAuth] ⏱️  TIMEOUT de 4s alcanzado.");
            console.log("[GoogleAuth]    → callbackFired:",   callbackFired);
            console.log("[GoogleAuth]    → sessionDetected:", sessionDetected);

            if (!sessionDetected) {
                console.log("[GoogleAuth] ℹ️  No se detectó sesión automática.");
                console.log("[GoogleAuth]    Causas posibles:");
                console.log("[GoogleAuth]    1. El usuario NO tiene sesión activa de Google en este navegador.");
                console.log("[GoogleAuth]    2. Las cookies de Google están bloqueadas (Safari ITP, Firefox ETP, etc.).");
                console.log("[GoogleAuth]    3. El dominio no está autorizado en Google Cloud Console.");
                console.log("[GoogleAuth]    4. El client_id no coincide con el origen de la página.");
                console.log("[GoogleAuth] 📢 Acción: Mostrando badge informativo para acción manual.");
                if (statusMsg) {
                    statusMsg.style.display = 'block';
                    console.log("[GoogleAuth]    → Badge visible:", statusMsg.style.display);
                }
            } else {
                console.log("[GoogleAuth] ✅ Sesión ya fue detectada y procesada antes del timeout.");
            }
            console.log("[GoogleAuth] ══════════════════════════════════════════");
            console.log("[GoogleAuth] 🏁 Flujo finalizado.");
            console.log("[GoogleAuth] ══════════════════════════════════════════");
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
        initAuth();
        initGoogleAuth();
        initCarousels();
    };

    document.readyState === "loading" ? document.addEventListener("DOMContentLoaded", init) : init();
})();