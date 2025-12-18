document.addEventListener('DOMContentLoaded', function() {
    const imageContainer = document.querySelector('.image-container');
    
    if (imageContainer) {
        // التحقق من دعم الجهاز للتأثيرات المتقدمة
        const supportsAdvancedEffects = window.innerWidth > 768 && 
                                      !window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        
        if (supportsAdvancedEffects) {
            // تأثير المتابعة بالماوس (للشاشات الكبيرة فقط)
            let isHovering = false;
            let animationFrameId = null;
            
            imageContainer.addEventListener('mouseenter', function() {
                isHovering = true;
            });
            
            imageContainer.addEventListener('mouseleave', function() {
                isHovering = false;
                
                // إلغاء أي إطار أنيميشن معلق
                if (animationFrameId) {
                    cancelAnimationFrame(animationFrameId);
                    animationFrameId = null;
                }
                
                const mainWrapper = this.querySelector('.main-image-wrapper');
                if (mainWrapper) {
                    mainWrapper.style.transform = 'scale(1.02)';
                }
            });
            
            imageContainer.addEventListener('mousemove', function(e) {
                if (!isHovering) return;
                
                // إلغاء الإطار السابق إذا كان موجوداً
                if (animationFrameId) {
                    cancelAnimationFrame(animationFrameId);
                }
                
                // استخدام requestAnimationFrame لتحسين الأداء
                animationFrameId = requestAnimationFrame(() => {
                    const rect = this.getBoundingClientRect();
                    const x = e.clientX - rect.left;
                    const y = e.clientY - rect.top;
                    const centerX = rect.width / 2;
                    const centerY = rect.height / 2;
                    
                    // تقليل شدة التأثير لجعله أكثر نعومة
                    const rotateX = (y - centerY) / 30;
                    const rotateY = (centerX - x) / 30;
                    
                    const mainWrapper = this.querySelector('.main-image-wrapper');
                    if (mainWrapper) {
                        mainWrapper.style.transform = `
                            scale(1.08) 
                            translateY(-5px) 
                            rotateX(${rotateX}deg) 
                            rotateY(${rotateY}deg)
                        `;
                    }
                });
            });
        }
        
        // تأثير النقر (لجميع الأجهزة)
        imageContainer.addEventListener('click', function() {
            // تأثير النقر مع تحسين الأداء
            this.style.transform = 'scale(0.98)';
            this.style.transition = 'transform 0.15s ease-out';
            
            setTimeout(() => {
                this.style.transform = 'scale(1)';
                // إزالة الانتقال المؤقت بعد انتهاء التأثير
                setTimeout(() => {
                    this.style.transition = '';
                }, 150);
            }, 150);
        });
        
        // تحسين الأداء: مراقبة الرؤية (Intersection Observer)
        let intersectionObserver;
        
        if ('IntersectionObserver' in window) {
            intersectionObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        // تفعيل التأثيرات عندما يكون العنصر مرئياً
                        entry.target.classList.add('animations-active');
                        
                        // تفعيل أنيميشن البطاقات العائمة
                        const floatingCards = entry.target.querySelectorAll('.floating-card');
                        floatingCards.forEach((card, index) => {
                            setTimeout(() => {
                                card.classList.add('loaded');
                            }, index * 200);
                        });
                    } else {
                        // إيقاف التأثيرات عندما يكون العنصر غير مرئي
                        entry.target.classList.remove('animations-active');
                    }
                });
            }, {
                threshold: 0.1,
                rootMargin: '50px'
            });
            
            intersectionObserver.observe(imageContainer);
        }
        
        // تحسين الأداء للأجهزة المحمولة
        if (window.innerWidth <= 768) {
            // تقليل معدل الإطارات للأجهزة المحمولة
            let throttleTimer = null;
            const throttleDelay = 32; // ~30fps بدلاً من 60fps
            
            // تطبيق throttling على أحداث الماوس
            const originalAddEventListener = imageContainer.addEventListener;
            imageContainer.addEventListener = function(event, handler, options) {
                if (event === 'mousemove') {
                    const throttledHandler = function(e) {
                        if (throttleTimer) return;
                        throttleTimer = setTimeout(() => {
                            handler.call(this, e);
                            throttleTimer = null;
                        }, throttleDelay);
                    };
                    originalAddEventListener.call(this, event, throttledHandler, options);
                } else {
                    originalAddEventListener.call(this, event, handler, options);
                }
            };
        }
        
        // تحسين الذاكرة: تنظيف المستمعين عند عدم الحاجة
        const cleanup = () => {
            if (animationFrameId) {
                cancelAnimationFrame(animationFrameId);
            }
            if (intersectionObserver) {
                intersectionObserver.disconnect();
            }
        };
        
        // تنظيف عند مغادرة الصفحة
        window.addEventListener('beforeunload', cleanup);
        
        // تنظيف عند إخفاء الصفحة (للتطبيقات أحادية الصفحة)
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                cleanup();
            }
        });
    }
    
    // تحسين عام للأداء: تأجيل تحميل التأثيرات غير الضرورية
    setTimeout(() => {
        const cards = document.querySelectorAll('.floating-card');
        cards.forEach((card, index) => {
            // إضافة تأخير تدريجي لظهور البطاقات
            setTimeout(() => {
                card.classList.add('loaded');
            }, index * 100);
        });
    }, 500);
    
    // تحسين الأداء: تحسين الرسوم المتحركة للبطاقات العائمة
    const optimizeFloatingCards = () => {
        const cards = document.querySelectorAll('.floating-card');
        
        cards.forEach(card => {
            // تحسين الأداء باستخدام transform بدلاً من تغيير الخصائص الأخرى
            card.addEventListener('mouseenter', function() {
                this.style.willChange = 'transform';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.willChange = 'auto';
            });
        });
    };
    
    // تطبيق التحسينات بعد تحميل الصفحة
    if (document.readyState === 'complete') {
        optimizeFloatingCards();
    } else {
        window.addEventListener('load', optimizeFloatingCards);
    }
    
    // تحسين الاستجابة: إعادة تقييم التأثيرات عند تغيير حجم الشاشة
    let resizeTimer;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => {
            // إعادة تقييم دعم التأثيرات المتقدمة
            const newSupportsAdvancedEffects = window.innerWidth > 768 && 
                                            !window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            
            if (!newSupportsAdvancedEffects && imageContainer) {
                // إيقاف التأثيرات المتقدمة على الشاشات الصغيرة
                const mainWrapper = imageContainer.querySelector('.main-image-wrapper');
                if (mainWrapper) {
                    mainWrapper.style.transform = 'scale(1.02)';
                }
            }
        }, 250);
    });
});