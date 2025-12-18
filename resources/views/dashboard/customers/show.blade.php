@extends('dashboard.layout.admin', ['title' => t('Show Customer').' | '.$customer->name])

@section('css')
@endsection

@section('content')
    <!-- Start Content-->
    @include('dashboard.layout.shared/page-title', [
        'subtitle' => t('Show Customer').' | '.$customer->name,
        'title' => 'Dashboard'
    ])

    <x-dashboard.outer-card :title="t('Show Customer').' | '.$customer->name">
        <x-slot:header>
            <div class="flex px-4 border-b-1 border-b-gray-500 flex-col items-stretch justify-between py-4 space-y-3 md:flex-row md:items-center md:space-y-0">
                <h1 class="text-xl font-semibold"> {{ t('Customer Details').': '.$customer->name }}</h1>
                <div class="flex items-center gap-3">
                    <x-inputs.button-secondary as="a" href="{{ route('dashboard.customers.edit', $customer) }}" class="inline-flex gap-2 justify-center items-center">
                        <x-heroicon-s-pencil-square class="w4 h-4"/> {{ t('Edit') }}
                    </x-inputs.button-secondary>

                    <x-inputs.button-outlined as="a" href="{{ route('dashboard.customers.index') }}" class="inline-flex gap-2 justify-center items-center">
                        <x-heroicon-s-arrow-up-right class="w4 h-4"/> {{ t('Back To Customers') }}
                    </x-inputs.button-outlined>
                </div>
            </div>
        </x-slot:header>

        <div class="p-5 space-y-6">
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800">
                <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ t('Name') }}</div>
                        <div class="font-medium">{{ $customer->name }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ t('Email') }}</div>
                        <div class="font-medium">{{ $customer->email ?: '—' }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ t('Phone') }}</div>
                        <div class="font-medium">{{ $customer->phone }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ t('Status') }}</div>
                        <div>
                            @if($customer->is_active)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-200">{{ t('Active') }}</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-200">{{ t('Inactive') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="md:col-span-2">
                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ t('Address') }}</div>
                        <div class="font-medium">{{ $customer->address ?: '—' }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ t('City') }}</div>
                        <div class="font-medium">{{ $customer->city ?: '—' }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ t('Country') }}</div>
                        <div class="font-medium">{{ $customer->country ?: '—' }}</div>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700">
                <div class="p-4 font-semibold">{{ t('Shipments for this Customer') }}</div>
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th class="px-4 py-2 text-start text-xs font-medium text-gray-500 uppercase">{{ t('Tracking Number') }}</th>
                            <th class="px-4 py-2 text-start text-xs font-medium text-gray-500 uppercase">{{ t('Status') }}</th>
                            <th class="px-4 py-2 text-start text-xs font-medium text-gray-500 uppercase">{{ t('Latest Event') }}</th>
                            <th class="px-4 py-2 text-start text-xs font-medium text-gray-500 uppercase">{{ t('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-800">
                        @forelse($customer->shipments as $s)
                            <tr>
                                <td class="px-4 py-2">{{ $s->tracking_number }}</td>
                                <td class="px-4 py-2">{{ $s->status->label() ?? '—' }}</td>
                                <td class="px-4 py-2">{{ optional($s->latestEvent)->description ?: '—' }}</td>
                                <td class="px-4 py-2">
                                    <a href="{{ route('dashboard.shipments.show', $s) }}" class="text-indigo-600 hover:underline">{{ t('View') }}</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-4 py-4 text-center text-sm text-gray-500" colspan="4">{{ t('No shipments found') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </x-dashboard.outer-card>
@endsection

@section('script')
@endsection


