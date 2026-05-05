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
            
            if (swiperEl && window.Swiper && !container.dataset.swiperReady) {
                const totalSlides = swiperEl.querySelectorAll('.swiper-slide').length;

                new Swiper(swiperEl, {
                    slidesPerView: 1,
                    spaceBetween: 20,
                    loop: totalSlides > 3,
                    navigation: {
                        nextEl: container.querySelector('.swiper-button-next'),
                        prevEl: container.querySelector('.swiper-button-prev'),
                    },
                    pagination: {
                        el: container.querySelector('.swiper-pagination-custom'),
                        clickable: true,
                    },
                    breakpoints: {
                        768: {
                            slidesPerView: 2,
                            spaceBetween: 20
                        },
                        1100: {
                            slidesPerView: 3,
                            spaceBetween: 30
                        }
                    }
                });
                container.dataset.swiperReady = "true";
            }
        });
    };

    const init = () => { 
        initAuth(); 
        initCarousels(); 
    };

    document.readyState === "loading" ? document.addEventListener("DOMContentLoaded", init) : init();
})();