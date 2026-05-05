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
                    slidesPerView: 'auto',
                    spaceBetween: 25,
                    centeredSlides: false,
                    loop: totalSlides > 3,
                    grabCursor: true,
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
                        320: { slidesPerView: 1, spaceBetween: 10 },
                        700: { slidesPerView: 2, spaceBetween: 20 },
                        1100: { slidesPerView: 3, spaceBetween: 25 }
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