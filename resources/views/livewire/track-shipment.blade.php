<div class="container max-w-6xl mx-auto space-y-8" x-data>
    {{-- Enhanced Search Card --}}
    <div class="card animate-fade-in-up">
        <div class="p-8">
            <!-- <div class="text-center mb-8">
                <div class="inline-flex items-center px-4 py-2 rounded-full bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 text-sm font-semibold mb-4">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    {{ t('Shipment Tracking') }}
                </div>
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-3">
                    {{ t('Track Your Shipment') }}
                </h1>
                <p class="text-gray-600 dark:text-gray-400 text-lg">
                    {{ t('Enter your tracking number to get real-time updates on your shipment') }}
                </p>
            </div> -->
            
            <!-- <form wire:submit.prevent="search" class="max-w-2xl mx-auto">
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                            {{ t('Tracking Number') }}
                        </label>
                        <div class="relative">
                            <input type="text" 
                                   wire:model.defer="query" 
                                   placeholder="{{ t('Example: ASK-XXXXXX') }}"
                                   autofocus
                                   class="form-input pl-12 py-4 text-lg font-mono tracking-wider">
                            <div class="absolute left-4 top-1/2 transform -translate-y-1/2">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                                </svg>
                            </div>
                        </div>
                        @error('query') 
                            <div class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="shrink-0 flex items-end">
                        <button type="submit"
                                class="btn btn-primary px-8 py-4 text-lg h-fit"
                                wire:loading.attr="disabled" 
                                wire:target="search">
                            <svg wire:loading.class="hidden" wire:target="search" 
                                 class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <svg wire:loading wire:target="search" 
                                 class="w-5 h-5 mr-2 animate-spin" viewBox="0 0 24 24" fill="none">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4A4 4 0 004 12z"/>
                            </svg>
                            <span wire:loading.class="hidden" wire:target="search">{{ t('Track') }}</span>
                            <span wire:loading wire:target="search">{{ t('Searching...') }}</span>
                        </button>
                    </div>
                </div>
            </form> -->
        </div>
    </div>

    {{-- Enhanced Empty State --}}
    @if(!$searched)
        <div wire:loading.class.add="hidden" class="text-center py-16 animate-fade-in-up animate-delay-200">
            <div class="max-w-md mx-auto">
                <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-primary-100 to-primary-200 dark:from-primary-900 dark:to-primary-800 rounded-2xl flex items-center justify-center">
                    <svg class="w-12 h-12 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">
                    {{ t('Ready to Track') }}
                </h3>
                <p class="text-gray-600 dark:text-gray-400">
                    {{ t('Search by shipment number to view the shipment\'s step-by-step path') }}
                </p>
            </div>
        </div>
    @endif

    {{-- Enhanced Loading State --}}
    <div wire:target="search" wire:loading.class.remove="hidden" 
         class="hidden card animate-pulse">
        <div class="p-8">
            <div class="flex items-center justify-center mb-6">
                <div class="w-8 h-8 border-4 border-primary-600 border-t-transparent rounded-full animate-spin"></div>
                <span class="ml-3 text-gray-600 dark:text-gray-400">{{ t('Searching for your shipment...') }}</span>
            </div>
            <div class="space-y-4">
                <div class="h-6 bg-gray-200 dark:bg-gray-700 rounded-lg animate-pulse"></div>
                <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                    @for($i=0; $i < 6; $i++)
                        <div class="h-24 bg-gray-100 dark:bg-gray-700 rounded-xl animate-pulse"></div>
                    @endfor
                </div>
            </div>
        </div>
    </div>

    {{-- Enhanced Error States --}}
    @if($searched && $error === 'not_found')
        <div wire:loading.class.add="hidden" class="card text-center animate-fade-in-up">
            <div class="p-12">
                <div class="w-20 h-20 mx-auto mb-6 bg-red-100 dark:bg-red-900/30 rounded-2xl flex items-center justify-center">
                    <svg class="w-10 h-10 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.664-.833-2.464 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">
                    {{ t('Shipment Not Found') }}
                </h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6 max-w-md mx-auto">
                    {{ t('We couldn\'t find a shipment with this tracking number. Please check the number and try again.') }}
                </p>
                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <button wire:click="resetForm" class="btn btn-outline-primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        {{ t('Try Again') }}
                    </button>
                    <a href="{{ route('contact.index') }}" class="btn btn-primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        {{ t('Contact Support') }}
                    </a>
                </div>
            </div>
        </div>
    @endif

    @if($searched && $error === 'server')
        <div class="card text-center animate-fade-in-up">
            <div class="p-12">
                <div class="w-20 h-20 mx-auto mb-6 bg-yellow-100 dark:bg-yellow-900/30 rounded-2xl flex items-center justify-center">
                    <svg class="w-10 h-10 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">
                    {{ t('Service Temporarily Unavailable') }}
                </h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6 max-w-md mx-auto">
                    {{ t('We\'re experiencing technical difficulties. Please try again in a few moments.') }}
                </p>
                <button wire:click="search" class="btn btn-primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    {{ t('Retry') }}
                </button>
            </div>
        </div>
    @endif

    {{-- عرض النتيجة (خط الحالات فقط) --}}
    @if($searched && !$error && $steps)
        {{-- ملخص صغير --}}
        <div wire:loading.class.add="hidden" class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-800 p-5">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                <div class="text-base md:text-lg font-semibold">{{ t('Shipment number') }}:
                    <span class="border border-dashed border-primary-500 px-3 rounded-full ">{{ $summary['tracking_number'] ?? '' }}</span>
                </div>
                <div>
                    @php
                        $status = $summary['current_status'] ?? null;
                        $label  = $summary['current_status_label'] ?? null;
                        $chip = match($status) {
                            'created'                      => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200',
                            'received_at_branch'           => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200',
                            'in_transit'                   => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/40 dark:text-yellow-200',
                            'arrived_jed_warehouse'        => 'bg-sky-100 text-sky-800 dark:bg-sky-900/40 dark:text-sky-200',
                            'shipped_jed_port'             => 'bg-primary-100 text-primary-800 dark:bg-primary-900/40 dark:text-primary-200',
                            'arrived_sudan_port'           => 'bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-200',
                            'arrived_destination_branch'   => 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-200',
                            'ready_for_delivery'           => 'bg-orange-100 text-orange-800 dark:bg-orange-900/40 dark:text-orange-200',
                            'delivered'                    => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-200',
                            default                        => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200',
                        };
                    @endphp
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm {{ $chip }}">
                        {{ $label ?? '—' }}
                    </span>
                </div>
            </div>
        </div>

        {{-- الخط الستّي (Timeline مبسّط وأنيق) --}}
        <div wire:loading.class.add="hidden" class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-800 p-6"
             aria-live="polite">
            {{-- أفقي على الشاشات الكبيرة --}}
            {{-- أفقي على الشاشات الكبيرة (نسخة مرتبة بدون تداخل) --}}
                @php
                $total = count($steps);
                $lastActive = -1;
                foreach ($steps as $i => $s) {
                    if (!empty($s['reached']) || !empty($s['is_current'])) $lastActive = $i;
                }
                $progress = $total > 1 ? max(0, $lastActive) / ($total - 1) * 100 : 0;
                @endphp

                <div class="relative hidden md:block pt-10">
                    {{-- خط الأساس --}}
                    <div class="absolute start-0 end-0 top-5 h-0.5 bg-gray-200 dark:bg-gray-800"></div>
                    {{-- شريط التقدّم (حتى آخر خطوة reached/current) --}}
                    <div class="absolute start-0 top-5 h-0.5 bg-primary-600 transition-all duration-500"
                        style="width: {{ $progress }}%;"></div>

                    {{-- شبكة الخطوات (أعمدة مرِنة بعدد الخطوات) --}}
                    <div class="grid gap-6"
                        style="grid-template-columns: repeat({{ $total }}, minmax(0, 1fr));">
                        @foreach($steps as $step)
                            @php
                                $dot = $step['is_current'] ? 'ring-2 ring-primary-500 bg-white dark:bg-gray-900'
                                    : ($step['reached'] ? 'bg-primary-600' : 'bg-gray-300 dark:bg-gray-700');
                                $card = $step['is_current'] ? 'border-primary-400/60'
                                    : ($step['reached'] ? 'border-gray-300 dark:border-gray-700'
                                                        : 'border-gray-200 dark:border-gray-800 opacity-60');
                            @endphp

                            <div class="relative text-center">
                                {{-- النقطة (متمركزة على الخط) --}}
                                <span class="absolute -top-5 left-1/2 -translate-x-1/2 -translate-y-1/2
                                            inline-block h-3.5 w-3.5 rounded-full z-10 {{ $dot }}"></span>

                                {{-- البطاقة أسفل النقطة --}}
                                <div class="mt-8 p-3 rounded-xl border {{ $card }}">
                                    <div class="text-[11px] text-gray-500">{{ $step['changed_at_human']??'---' }}</div>
                                    <div class="text-sm font-semibold">{{app()->getLocale()=='ar'?$step['label']: t($step['label'])}}</div>
                                </div>

                            </div>
                        @endforeach
                    </div>
                </div>

            {{-- <div class="hidden md:grid md:grid-cols-6 gap-4">
                @foreach($steps as $step)
                    @php
                        $dot = $step['is_current'] ? 'ring-2 ring-primary-500'
                             : ($step['reached'] ? 'bg-primary-600' : 'bg-gray-300 dark:bg-gray-700');
                        $card = $step['is_current'] ? 'border-primary-400/60'
                             : ($step['reached'] ? 'border-gray-300 dark:border-gray-700' : 'border-gray-200 dark:border-gray-800 opacity-60');
                    @endphp
                    <div class="relative">
                      <!-- الخط الواصل -->
                        @if(!$loop->first)
                            <div class="absolute -left-4 top-5 h-0.5 w-4 {{ $step['reached'] ? 'bg-primary-600' : 'bg-gray-300 dark:bg-gray-700' }}"></div>
                        @endif

                        <div class="flex items-start gap-3">
                            <span class="mt-1 inline-block h-3.5 w-3.5 rounded-full {{ $dot }}"></span>
                            <div class="flex-1 p-3 rounded-xl border {{ $card }}">
                                <div class="text-[11px] text-gray-500">{{ $step['status'] }}</div>
                                <div class="text-sm font-semibold">{{ $step['label'] }}</div>
                                <!--بدون تفاصيل إضافية -->
                            </div>
                        </div>
                    </div>
                @endforeach
            </div> --}}

            {{-- عمودي على الموبايل --}}
            <ol class="md:hidden relative border-s border-gray-200 dark:border-gray-800">
                @foreach($steps as $step)
                    @php
                        $dot = $step['is_current'] ? 'ring-2 ring-primary-500'
                             : ($step['reached'] ? 'bg-primary-600' : 'bg-gray-300 dark:bg-gray-700');
                        $txt = $step['is_current'] ? '' : ($step['reached'] ? '' : 'opacity-60');
                    @endphp
                    <li class="mb-6 ms-6 {{ $txt }}">
                        <span class="absolute -start-3 flex h-6 w-6 items-center justify-center rounded-full bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700">
                            <span class="h-2.5 w-2.5 rounded-full {{ $dot }}"></span>
                        </span>
                        <h4 class="text-sm font-semibold">{{app()->getLocale()=='ar'?$step['label']: t($step['label']) }}</h4>
                        <div class="text-[11px] text-gray-500">{{ $step['changed_at_human']??'---' }}</div>
                    </li>
                @endforeach
            </ol>
        </div>
    @endif
</div>
