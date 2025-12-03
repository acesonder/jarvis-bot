/**
 * Tweak Easy - Landing Page JavaScript
 */

// Animate elements on scroll
const AnimateOnScroll = {
    init() {
        this.elements = document.querySelectorAll('.feature-card, .platform-card, .supply-item');
        this.observer = new IntersectionObserver(
            (entries) => this.handleIntersect(entries),
            { threshold: 0.1, rootMargin: '0px 0px -50px 0px' }
        );
        
        this.elements.forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(30px)';
            this.observer.observe(el);
        });
    },

    handleIntersect(entries) {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.style.transition = 'all 0.6s ease';
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }, index * 100);
                this.observer.unobserve(entry.target);
            }
        });
    }
};

// Counter Animation
const CounterAnimation = {
    init() {
        const counters = document.querySelectorAll('.stat-number');
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    this.animateCounter(entry.target);
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });
        
        counters.forEach(counter => observer.observe(counter));
    },

    animateCounter(element) {
        const text = element.textContent;
        const match = text.match(/(\d+)/);
        if (!match) return;
        
        const target = parseInt(match[0]);
        const suffix = text.replace(match[0], '');
        const duration = 2000;
        const steps = 60;
        const increment = target / steps;
        let current = 0;
        const stepDuration = duration / steps;
        
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                element.textContent = target + suffix;
                clearInterval(timer);
            } else {
                element.textContent = Math.floor(current) + suffix;
            }
        }, stepDuration);
    }
};

// Parallax Effect
const ParallaxEffect = {
    init() {
        this.shapes = document.querySelectorAll('.shape');
        if (this.shapes.length === 0) return;
        
        window.addEventListener('scroll', () => this.handleScroll());
        window.addEventListener('mousemove', (e) => this.handleMouseMove(e));
    },

    handleScroll() {
        const scrolled = window.pageYOffset;
        this.shapes.forEach((shape, index) => {
            const speed = 0.1 + (index * 0.05);
            shape.style.transform = `translateY(${scrolled * speed}px)`;
        });
    },

    handleMouseMove(e) {
        const x = (e.clientX - window.innerWidth / 2) / 50;
        const y = (e.clientY - window.innerHeight / 2) / 50;
        
        this.shapes.forEach((shape, index) => {
            const multiplier = 1 + (index * 0.5);
            shape.style.transform += ` translate(${x * multiplier}px, ${y * multiplier}px)`;
        });
    }
};

// Header scroll effect
const HeaderScroll = {
    init() {
        this.header = document.querySelector('.header-3d');
        if (!this.header) return;
        
        window.addEventListener('scroll', () => this.handleScroll());
    },

    handleScroll() {
        if (window.scrollY > 50) {
            this.header.style.boxShadow = '0 4px 30px rgba(0, 0, 0, 0.15)';
            this.header.style.padding = '0';
        } else {
            this.header.style.boxShadow = '0 4px 30px rgba(0, 0, 0, 0.1)';
            this.header.style.padding = '';
        }
    }
};

// Contact Form Handler
const ContactForm = {
    init() {
        this.form = document.getElementById('contactForm');
        if (!this.form) return;
        
        this.form.addEventListener('submit', (e) => this.handleSubmit(e));
    },

    async handleSubmit(e) {
        e.preventDefault();
        
        if (!window.TweakEasy.FormValidator.validate(this.form)) {
            return;
        }
        
        const formData = new FormData(this.form);
        const data = Object.fromEntries(formData.entries());
        
        try {
            window.TweakEasy.Loading.show();
            
            // Simulate API call
            await new Promise(resolve => setTimeout(resolve, 1500));
            
            window.TweakEasy.Notifications.success('Message sent successfully! We\'ll get back to you soon.');
            this.form.reset();
        } catch (error) {
            window.TweakEasy.Notifications.error('Failed to send message. Please try again.');
        } finally {
            window.TweakEasy.Loading.hide();
        }
    }
};

// Supply Item Hover Effects
const SupplyHoverEffects = {
    init() {
        const items = document.querySelectorAll('.supply-item');
        
        items.forEach(item => {
            item.addEventListener('mouseenter', () => {
                item.style.zIndex = '10';
            });
            
            item.addEventListener('mouseleave', () => {
                item.style.zIndex = '';
            });
        });
    }
};

// Dashboard Preview Animation
const DashboardPreview = {
    init() {
        this.preview = document.querySelector('.dashboard-preview');
        if (!this.preview) return;
        
        window.addEventListener('mousemove', (e) => this.handleMouseMove(e));
    },

    handleMouseMove(e) {
        const x = (e.clientX - window.innerWidth / 2) / 100;
        const y = (e.clientY - window.innerHeight / 2) / 100;
        
        this.preview.style.transform = `perspective(1000px) rotateY(${-10 + x}deg) rotateX(${5 - y}deg)`;
    }
};

// Initialize all landing page features
document.addEventListener('DOMContentLoaded', () => {
    AnimateOnScroll.init();
    CounterAnimation.init();
    ParallaxEffect.init();
    HeaderScroll.init();
    ContactForm.init();
    SupplyHoverEffects.init();
    DashboardPreview.init();
});
