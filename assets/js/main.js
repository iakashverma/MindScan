document.addEventListener('DOMContentLoaded', () => {
    const loadingScreen = document.getElementById('loading-screen');
    setTimeout(() => {
        if (loadingScreen) {
            loadingScreen.classList.add('hidden');
        }
    }, 600);

    const particleLayer = document.getElementById('particle-layer');
    if (particleLayer) {
        for (let i = 0; i < 40; i += 1) {
            const particle = document.createElement('span');
            particle.className = 'particle';
            particle.style.left = `${Math.random() * 100}%`;
            particle.style.top = `${100 + Math.random() * 40}%`;
            particle.style.animationDelay = `${Math.random() * 6}s`;
            particle.style.animationDuration = `${8 + Math.random() * 8}s`;
            particleLayer.appendChild(particle);
        }
    }

    if (window.AOS) {
        AOS.init({
            duration: 900,
            once: true,
            offset: 80,
        });
    }

    const themeToggle = document.getElementById('theme-toggle');
    const toastEl = document.getElementById('themeToast');
    const toast = toastEl && window.bootstrap ? new bootstrap.Toast(toastEl) : null;

    const applyTheme = (theme) => {
        document.documentElement.setAttribute('data-theme', theme);
        localStorage.setItem('mindscan-theme', theme);
        if (themeToggle) {
            themeToggle.innerHTML = theme === 'light' ? '<i class="fa-solid fa-moon"></i>' : '<i class="fa-solid fa-sun"></i>';
        }
    };

    const savedTheme = localStorage.getItem('mindscan-theme') || 'dark';
    applyTheme(savedTheme);

    if (themeToggle) {
        themeToggle.addEventListener('click', () => {
            const current = document.documentElement.getAttribute('data-theme') === 'light' ? 'dark' : 'light';
            applyTheme(current);
            if (toast) {
                toast.show();
            }
        });
    }

    const chatbotToggle = document.getElementById('chatbot-toggle');
    const chatbotClose = document.getElementById('chatbot-close');
    const chatbotWindow = document.getElementById('chatbot-window');

    if (chatbotToggle && chatbotWindow) {
        chatbotToggle.addEventListener('click', () => {
            chatbotWindow.classList.toggle('active');
        });
    }

    if (chatbotClose && chatbotWindow) {
        chatbotClose.addEventListener('click', () => {
            chatbotWindow.classList.remove('active');
        });
    }
});
