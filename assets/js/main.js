(function() {
    "use strict";

    // 1. Toggle de Password
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

    // 2. Motor Swiper para FeelBig
    const initCarousels = () => {
        const sections = document.querySelectorAll('.feelbig-swiper-section');
        
        sections.forEach((container) => {
            const swiperEl = container.querySelector('.swiper-feelbig-generic');
            
            if (swiperEl && window.Swiper && !container.dataset.swiperReady) {
                const totalSlides = swiperEl.querySelectorAll('.swiper-slide').length;
                
                new Swiper(swiperEl, {
                    loop: totalSlides > 3,
                    spaceBetween: 20,
                    slidesPerView: 1,
                    autoplay: { delay: 5000, disableOnInteraction: false },
                    navigation: {
                        nextEl: container.querySelector('.swiper-button-next'),
                        prevEl: container.querySelector('.swiper-button-prev'),
                    },
                    pagination: {
                        el: container.querySelector('.swiper-pagination-custom'),
                        clickable: true,
                    },
                    breakpoints: {
                        768: { slidesPerView: Math.min(totalSlides, 2) },
                        1024: { slidesPerView: Math.min(totalSlides, 3) }
                    },
                    on: {
                        init: function() {
                            container.dataset.swiperReady = 'true';
                        }
                    }
                });
            }
        });
    };

    const init = () => { 
        initAuth(); 
        initCarousels(); 
    };

    document.readyState === "loading" ? document.addEventListener("DOMContentLoaded", init) : init();
})();