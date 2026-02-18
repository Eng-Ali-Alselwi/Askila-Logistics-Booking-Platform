@extends('dashboard.layout.admin', ['title' => t('Show Branch').' | '.$branch->name])

@section('css')
@endsection

@section('content')
    <x-dashboard.outer-card :title="t('Show Branch').' | '.$branch->name">
        <x-slot:header>
            <div class="flex px-4 border-b-1 border-b-gray-500 flex-col items-stretch justify-between py-4 space-y-3 md:flex-row md:items-center md:space-y-0">
                <h1 class="text-xl font-semibold">{{ t('Branch Details').': '.$branch->name }}</h1>
                <div class="flex items-center gap-3">
                    <x-inputs.button-secondary as="a" href="{{ route('dashboard.branches.edit', $branch) }}" class="inline-flex gap-2 justify-center items-center">
                        <x-heroicon-s-pencil-square class="w4 h-4"/> 
                        {{ t('Edit') }}
                    </x-inputs.button-secondary>

                    <x-inputs.button-primary as="a" href="{{ route('dashboard.branches.index') }}" class="inline-flex gap-2 justify-center items-center">
                        {{ t('Back To Branches') }}
                        <x-heroicon-o-arrow-left class="w4 h-4"/> 
                    </x-inputs.button-primary>
                </div>
            </div>
        </x-slot:header>

        <div class="p-5 space-y-6">
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800">
                <div class="p-5 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ t('Name') }}</div>
                        <div class="font-medium">{{ $branch->name }}</div>
                    </div>

                    <div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ t('Code') }}</div>
                        <div class="font-medium">{{ $branch->code }}</div>
                    </div>

                    <div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ t('Manager Name') }}</div>
                        <div class="font-medium">{{ $branch->manager_name ?: '—' }}</div>
                    </div>

                    <div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ t('Phone') }}</div>
                        <div class="font-medium">{{ $branch->phone ?: '—' }}</div>
                    </div>

                    <div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ t('Email') }}</div>
                        <div class="font-medium">{{ $branch->email ?: '—' }}</div>
                    </div>

                    <div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ t('Status') }}</div>
                        <div>
                            @if($branch->is_active)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-200">{{ t('Active') }}</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-200">{{ t('Inactive') }}</span>
                            @endif
                        </div>
                    </div>

                    <div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ t('Address') }}</div>
                        <div class="font-medium">{{ $branch->address ?: '—' }}</div>
                    </div>

                    <div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ t('City') }}</div>
                        <div class="font-medium">{{ $branch->city ?: '—' }}</div>
                    </div>

                    <div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ t('Country') }}</div>
                        <div class="font-medium">{{ $branch->country ?: '—' }}</div>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700">
                <div class="p-4 font-semibold">{{ t('Recent Shipments') }}</div>
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th class="px-4 py-2 text-start text-xs font-medium text-gray-500 uppercase">{{ t('Tracking Number') }}</th>
                            <th class="px-4 py-2 text-start text-xs font-medium text-gray-500 uppercase">{{ t('Customer') }}</th>
                            <th class="px-4 py-2 text-start text-xs font-medium text-gray-500 uppercase">{{ t('Latest Event') }}</th>
                            <th class="px-4 py-2 text-start text-xs font-medium text-gray-500 uppercase">{{ t('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-800">
                        @forelse($branch->shipments as $s)
                            <tr>
                                <td class="px-4 py-2">{{ $s->tracking_number }}</td>
                                <td class="px-4 py-2">{{ optional($s->customer)->name ?: '—' }}</td>
                                <td class="px-4 py-2">{{ optional($s->latestEvent)->description ?: '—' }}</td>
                                <td class="px-4 py-2"><a href="{{ route('dashboard.shipments.show', $s) }}" class="text-indigo-600 hover:underline">{{ t('View') }}</a></td>
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


