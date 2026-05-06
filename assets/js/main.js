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

        const statusMsg   = document.getElementById('google-status-msg');
        const inputCodigo = document.getElementById('inputCodigo');

        console.log("[GoogleAuth] 🔎 Buscando elementos del DOM...");
        console.log("[GoogleAuth]    → #google-status-msg:", statusMsg   ? "✅ encontrado" : "❌ NO encontrado");
        console.log("[GoogleAuth]    → #inputCodigo:",       inputCodigo ? "✅ encontrado" : "❌ NO encontrado");

        if (!window.location.pathname.includes('register-confirm')) {
            console.log("[GoogleAuth] ⛔ Ruta no es 'register-confirm'. Saliendo sin hacer nada.");
            return;
        }
        console.log("[GoogleAuth] ✅ Ruta válida. Continuando...");

        if (!window.google || !window.google.accounts || !window.google.accounts.id) {
            console.error("[GoogleAuth] ❌ SDK de Google no disponible en window.google.accounts.id");
            console.error("[GoogleAuth]    → ¿Está cargado el script de GSI antes de main.js?");
            if (statusMsg) { statusMsg.style.display = 'block'; }
            return;
        }
        console.log("[GoogleAuth] ✅ SDK de Google detectado correctamente.");

        const emailEsperado = document.body.dataset.userEmail || null;
        console.log("[GoogleAuth] 📧 Email esperado (data-user-email en <body>):", emailEsperado ?? "⚠️ NO definido — se omitirá la comparación de email");

        let sessionDetected = false;
        let callbackFired   = false;

        console.log("[GoogleAuth] ──────────────────────────────────────────");
        console.log("[GoogleAuth] ⚙️  Llamando a google.accounts.id.initialize()...");
        console.log("[GoogleAuth]    → auto_select: true");
        console.log("[GoogleAuth]    → use_fedcm_for_prompt: false");
        console.log("[GoogleAuth]    → itp_support: true");
        console.log("[GoogleAuth] ──────────────────────────────────────────");

        try {
            google.accounts.id.initialize({
                client_id: "TU_GOOGLE_CLIENT_ID.apps.googleusercontent.com",
                auto_select: true,
                use_fedcm_for_prompt: false,
                itp_support: true,
                callback: (response) => {
                    callbackFired   = true;
                    sessionDetected = true;

                    console.log("[GoogleAuth] ══════════════════════════════════════════");
                    console.log("[GoogleAuth] 🎉 CALLBACK DISPARADO — sesión detectada");
                    console.log("[GoogleAuth] ══════════════════════════════════════════");
                    console.log("[GoogleAuth] 🔑 credential presente:", !!response.credential);

                    if (!response.credential) {
                        console.error("[GoogleAuth] ❌ Callback sin credential. Respuesta:", response);
                        if (statusMsg) statusMsg.style.display = 'block';
                        return;
                    }

                    console.log("[GoogleAuth] 🔑 Token (primeros 30 chars):", response.credential.substring(0, 30) + "...");

                    try {
                        const payloadB64  = response.credential.split('.')[1];
                        const payloadJSON = atob(payloadB64.replace(/-/g, '+').replace(/_/g, '/'));
                        const payload     = JSON.parse(payloadJSON);

                        console.log("[GoogleAuth] 👤 Datos del usuario en el token:");
                        console.log("[GoogleAuth]    → email:",           payload.email          ?? "no presente");
                        console.log("[GoogleAuth]    → email_verified:",  payload.email_verified ?? "no presente");
                        console.log("[GoogleAuth]    → name:",            payload.name           ?? "no presente");
                        console.log("[GoogleAuth]    → sub (Google ID):", payload.sub            ?? "no presente");
                        console.log("[GoogleAuth]    → aud (client_id):", payload.aud            ?? "no presente");
                        console.log("[GoogleAuth]    → exp:",             payload.exp            ?? "no presente");

                        if (emailEsperado) {
                            const coincide = payload.email?.toLowerCase() === emailEsperado.toLowerCase();
                            console.log("[GoogleAuth] 🔍 Comparando emails:");
                            console.log("[GoogleAuth]    → Email en token:  ", payload.email);
                            console.log("[GoogleAuth]    → Email esperado:  ", emailEsperado);
                            console.log("[GoogleAuth]    → ¿Coinciden?:     ", coincide ? "✅ SÍ" : "❌ NO");

                            if (!coincide) {
                                console.warn("[GoogleAuth] ⚠️ Emails NO coinciden. Mostrando badge.");
                                if (statusMsg) statusMsg.style.display = 'block';
                                return;
                            }
                        } else {
                            console.warn("[GoogleAuth] ⚠️ Sin emailEsperado — comparación omitida.");
                        }

                        if (inputCodigo) {
                            inputCodigo.value = response.credential;
                            console.log("[GoogleAuth] ✅ inputCodigo rellenado automáticamente.");
                            console.log("[GoogleAuth]    → Longitud del valor insertado:", inputCodigo.value.length, "chars");
                        } else {
                            console.error("[GoogleAuth] ❌ #inputCodigo no encontrado — no se pudo rellenar.");
                        }

                        if (statusMsg) {
                            statusMsg.style.display = 'none';
                            console.log("[GoogleAuth] 🙈 Badge ocultado — sesión OK.");
                        }

                    } catch (decodeErr) {
                        console.error("[GoogleAuth] ❌ Error al decodificar el JWT:", decodeErr);
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

        // ── PROMPT ──────────────────────────────────────────────────────────
        // Con auto_select:true y UNA sola cuenta Google en el navegador:
        //   → El prompt se resuelve SOLO, sin mostrar ninguna ventana.
        // Con múltiples cuentas o ninguna:
        //   → El prompt se omite (skipped) y el badge informativo aparecerá.
        // En NINGÚN caso se llama al prompt para mostrar UI de forma activa.
        // ────────────────────────────────────────────────────────────────────
        console.log("[GoogleAuth] 📡 Lanzando .prompt() con auto_select (resolución silenciosa si hay 1 cuenta)...");

        google.accounts.id.prompt((notification) => {
            const momentType = notification.getMomentType();
            console.log("[GoogleAuth] ──────────────────────────────────────────");
            console.log("[GoogleAuth] 🕒 Notificación del prompt recibida.");
            console.log("[GoogleAuth]    → MomentType:", momentType);

            if (notification.isDisplayMoment()) {
                // Esto significa que SÍ se mostró UI — no debería pasar con auto_select y 1 cuenta
                console.warn("[GoogleAuth] ⚠️  DISPLAY MOMENT — el prompt mostró UI al usuario.");
                console.warn("[GoogleAuth]    Causa probable: múltiples cuentas Google en el navegador.");
                console.warn("[GoogleAuth]    El usuario deberá seleccionar manualmente.");
            }

            if (notification.isNotDisplayed()) {
                const reason = notification.getNotDisplayedReason();
                console.warn("[GoogleAuth] 🚫 Prompt NO mostrado. Razón:", reason);
                console.warn("[GoogleAuth]    Interpretación según razón:");
                switch (reason) {
                    case 'browser_not_supported':
                        console.warn("[GoogleAuth]    → Navegador no compatible con One Tap.");
                        break;
                    case 'invalid_client':
                        console.warn("[GoogleAuth]    → client_id inválido o dominio no autorizado en Google Cloud Console.");
                        break;
                    case 'missing_client_id':
                        console.warn("[GoogleAuth]    → client_id no proporcionado en initialize().");
                        break;
                    case 'opt_out_or_no_session':
                        console.warn("[GoogleAuth]    → El usuario no tiene sesión Google activa O ha desactivado One Tap.");
                        break;
                    case 'secure_http_required':
                        console.warn("[GoogleAuth]    → La página no está en HTTPS. One Tap requiere HTTPS.");
                        break;
                    case 'suppressed_by_user':
                        console.warn("[GoogleAuth]    → El usuario cerró One Tap demasiadas veces y está suprimido temporalmente.");
                        break;
                    case 'unregistered_origin':
                        console.warn("[GoogleAuth]    → El origen (dominio) no está registrado en Google Cloud Console.");
                        break;
                    case 'unknown_reason':
                    default:
                        console.warn("[GoogleAuth]    → Razón desconocida. Comprueba: HTTPS, dominio autorizado, cookies.");
                        break;
                }
                if (!sessionDetected && statusMsg) {
                    statusMsg.style.display = 'block';
                    console.log("[GoogleAuth] 📢 Badge visible (prompt no mostrado).");
                }
            }

            if (notification.isSkippedMoment()) {
                const reason = notification.getSkippedReason();
                console.warn("[GoogleAuth] ⏭️  Prompt omitido (skipped). Razón:", reason);
                console.warn("[GoogleAuth]    Interpretación según razón:");
                switch (reason) {
                    case 'auto_cancel':
                        console.warn("[GoogleAuth]    → auto_select cancelado automáticamente (posiblemente múltiples cuentas).");
                        break;
                    case 'user_cancel':
                        console.warn("[GoogleAuth]    → El usuario cerró el prompt manualmente.");
                        break;
                    case 'tap_outside':
                        console.warn("[GoogleAuth]    → El usuario hizo clic fuera del prompt.");
                        break;
                    case 'issuing_failed':
                        console.warn("[GoogleAuth]    → Fallo interno al emitir el token.");
                        break;
                    case 'unknown_reason':
                    default:
                        console.warn("[GoogleAuth]    → Razón desconocida. Puede ser FedCM bloqueado o sin sesión.");
                        break;
                }
                if (!sessionDetected && statusMsg) {
                    statusMsg.style.display = 'block';
                    console.log("[GoogleAuth] 📢 Badge visible (prompt omitido).");
                }
            }

            if (notification.isDismissedMoment()) {
                const reason = notification.getDismissedReason();
                console.warn("[GoogleAuth] ❌ Prompt descartado (dismissed). Razón:", reason);
                if (!sessionDetected && statusMsg) {
                    statusMsg.style.display = 'block';
                    console.log("[GoogleAuth] 📢 Badge visible (prompt descartado).");
                }
            }

            console.log("[GoogleAuth] ──────────────────────────────────────────");
        });

        // Fallback de seguridad: si el callback nunca llegó tras 5s, mostramos el badge
        setTimeout(() => {
            console.log("[GoogleAuth] ══════════════════════════════════════════");
            console.log("[GoogleAuth] ⏱️  TIMEOUT de 5s alcanzado.");
            console.log("[GoogleAuth]    → callbackFired:",   callbackFired);
            console.log("[GoogleAuth]    → sessionDetected:", sessionDetected);
            if (!sessionDetected) {
                console.log("[GoogleAuth] ℹ️  Sin sesión confirmada tras 5s. Badge visible.");
                if (statusMsg) statusMsg.style.display = 'block';
            } else {
                console.log("[GoogleAuth] ✅ Sesión confirmada antes del timeout. Sin acción.");
            }
            console.log("[GoogleAuth] 🏁 Flujo finalizado.");
            console.log("[GoogleAuth] ══════════════════════════════════════════");
        }, 5000);
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