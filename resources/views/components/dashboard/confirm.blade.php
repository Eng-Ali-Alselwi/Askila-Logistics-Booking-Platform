{{-- ضع هذا المكوّن مرة واحدة في الـ layout العام (أسفل الصفحة) --}}
<style>[x-cloak]{ display:none !important; }</style>

<div
    x-data
    x-init="
        if (!Alpine.store('confirm')) {
            Alpine.store('confirm', {
                open: false,
                title: 'تأكيد العملية',
                message: 'هل أنت متأكد؟',
                // سنخزّن دالة التأكيد هنا (قادمة من الزر)
                _fn: null,
                ask(fn, t = null, m = null) {
                    this._fn = fn;
                    this.title = t ?? 'تأكيد العملية';
                    this.message = m ?? 'هل أنت متأكد؟';
                    this.open = true;
                },
                confirm() {
                    try { this._fn && this._fn(); }
                    finally { this.open = false; this._fn = null; }
                },
                cancel() {
                    this.open = false; this._fn = null;
                }
            })
        }
    "
    class="relative z-[60]"
>
    <!-- Overlay -->
    <div x-cloak x-show="$store.confirm.open" x-transition.opacity class="fixed inset-0 bg-black/40"></div>

    <!-- Modal -->
    <div x-cloak x-show="$store.confirm.open" x-transition
         class="fixed inset-0 flex items-center justify-center p-4">
        <div class="w-full max-w-md rounded-2xl bg-white dark:bg-gray-900 shadow-xl border border-gray-200 dark:border-gray-800">
            <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-800">
                <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100" x-text="$store.confirm.title"></h3>
            </div>
            <div class="px-5 py-4">
                <p class="text-sm text-gray-600 dark:text-gray-300" x-text="$store.confirm.message"></p>
            </div>
            <div class="px-5 py-4 flex items-center justify-end gap-2 border-t border-gray-200 dark:border-gray-800">
                <button type="button"
                        class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700"
                        @click="$store.confirm.cancel()">إلغاء</button>
                <button type="button"
                        class="px-4 py-2 rounded-lg bg-rose-600 text-white hover:bg-rose-700"
                        @click="$store.confirm.confirm()">تأكيد</button>
            </div>
        </div>
    </div>
</div>
