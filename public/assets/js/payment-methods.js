/**
 * Payment Methods Handler
 * Handles payment method selection and form validation
 */

class PaymentMethodsHandler {
    constructor() {
        this.init();
    }

    init() {
        this.bindEvents();
        this.setupFormValidation();
    }

    bindEvents() {
        // Payment method selection
        document.addEventListener('click', (e) => {
            if (e.target.closest('.payment-method-card')) {
                const method = e.target.closest('.payment-method-card').querySelector('input[type="radio"]').value;
                this.selectPaymentMethod(method);
            }
        });

        // Form submission
        const form = document.querySelector('form[action*="choosePayment"]');
        if (form) {
            form.addEventListener('submit', (e) => this.handleFormSubmit(e));
        }
    }

    selectPaymentMethod(method) {
        // Remove selection from all cards
        document.querySelectorAll('.payment-method-card').forEach(card => {
            card.classList.remove('selected');
        });
        
        // Add selection to chosen card
        const selectedCard = document.querySelector(`input[value="${method}"]`).closest('.payment-method-card');
        selectedCard.classList.add('selected');
        
        // Check the radio button
        document.querySelector(`input[value="${method}"]`).checked = true;
        
        // Add visual effect
        this.addSelectionEffect(selectedCard);
        
        // Show method-specific information
        this.showMethodInfo(method);
    }

    addSelectionEffect(element) {
        element.style.transform = 'scale(1.02)';
        element.style.boxShadow = '0 10px 25px rgba(59, 130, 246, 0.2)';
        
        setTimeout(() => {
            element.style.transform = '';
            element.style.boxShadow = '';
        }, 200);
    }

    showMethodInfo(method) {
        // Remove existing info
        const existingInfo = document.querySelector('.payment-method-info');
        if (existingInfo) {
            existingInfo.remove();
        }

        // Create info element
        const info = document.createElement('div');
        info.className = 'payment-method-info mt-4 p-4 rounded-lg';
        
        if (method === 'paypal') {
            info.className += ' bg-blue-50 border border-blue-200';
            info.innerHTML = `
                <div class="flex items-center gap-2 text-blue-800">
                    <i class="fas fa-info-circle"></i>
                    <span class="font-semibold">الدفع عبر PayPal</span>
                </div>
                <p class="text-blue-700 text-sm mt-2">
                    ستتم إعادة توجيهك إلى PayPal لإتمام عملية الدفع بأمان
                </p>
            `;
        } else if (method === 'credit_card') {
            info.className += ' bg-purple-50 border border-purple-200';
            info.innerHTML = `
                <div class="flex items-center gap-2 text-purple-800">
                    <i class="fas fa-credit-card"></i>
                    <span class="font-semibold">الدفع بالبطاقة الائتمانية</span>
                </div>
                <p class="text-purple-700 text-sm mt-2">
                    الدفع الآمن بالبطاقة الائتمانية باستخدام Stripe
                </p>
            `;
        } else if (method === 'whatsapp') {
            info.className += ' bg-green-50 border border-green-200';
            info.innerHTML = `
                <div class="flex items-center gap-2 text-green-800">
                    <i class="fab fa-whatsapp"></i>
                    <span class="font-semibold">الدفع عبر الواتساب</span>
                </div>
                <p class="text-green-700 text-sm mt-2">
                    سيتم إنشاء الحجز وإرسالك إلى الواتساب لإتمام عملية الدفع وتأكيد الحجز
                </p>
            `;
        }

        // Insert after payment methods
        const paymentMethods = document.querySelector('.payment-method-card').closest('.md\\:col-span-2');
        paymentMethods.appendChild(info);
    }

    handleFormSubmit(e) {
        const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
        
        if (!paymentMethod) {
            e.preventDefault();
            this.showError('يرجى اختيار طريقة الدفع');
            return false;
        }
        
        // Add loading effect
        this.addLoadingEffect();
        
        return true;
    }

    addLoadingEffect() {
        const submitButton = document.querySelector('button[type="submit"]');
        if (submitButton) {
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin ml-2"></i>جاري المعالجة...';
            submitButton.disabled = true;
            submitButton.classList.add('payment-processing');
        }
    }

    showError(message) {
        // Remove existing alerts
        const existingAlert = document.querySelector('.payment-error-alert');
        if (existingAlert) {
            existingAlert.remove();
        }

        // Create error alert
        const alert = document.createElement('div');
        alert.className = 'payment-error-alert bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-4 error-animation';
        alert.innerHTML = `
            <div class="flex items-center gap-2">
                <i class="fas fa-exclamation-circle"></i>
                <span class="font-semibold">${message}</span>
            </div>
        `;

        // Insert before form
        const form = document.querySelector('form');
        form.parentNode.insertBefore(alert, form);

        // Auto remove after 5 seconds
        setTimeout(() => {
            if (alert.parentNode) {
                alert.remove();
            }
        }, 5000);
    }

    setupFormValidation() {
        // Real-time validation
        const form = document.querySelector('form[action*="choosePayment"]');
        if (!form) return;

        const requiredFields = form.querySelectorAll('input[required], select[required]');
        
        requiredFields.forEach(field => {
            field.addEventListener('blur', () => {
                this.validateField(field);
            });
        });
    }

    validateField(field) {
        const value = field.value.trim();
        const isValid = value !== '';
        
        if (!isValid) {
            field.classList.add('border-red-500');
            field.classList.remove('border-green-500');
        } else {
            field.classList.remove('border-red-500');
            field.classList.add('border-green-500');
        }
        
        return isValid;
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new PaymentMethodsHandler();
});

// Export for potential use in other scripts
window.PaymentMethodsHandler = PaymentMethodsHandler;
