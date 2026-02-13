
<aside id="default-sidebar" class="fixed shadow-lg top-0 left-0 rtl:right-0 z-40 w-64 h-screen transition-transform
    -translate-x-full sm:translate-x-0" aria-label="Sidenav">
  <div class="overflow-y-auto py-5 px-3 h-full bg-white border-r border-gray-200 dark:bg-gray-800 dark:border-gray-700">

    <a href="#" class="flex  justify-center items-center mb-8">
        <img src="{{ asset('assets/images/logo/dark.png') }}" class="me-3 h-20 dark:hidden" alt="Askila Logo" />
        <img src="{{ asset('assets/images/logo/light.png') }}" class="me-3 h-20 hidden dark:block" alt="Askila Logo" />
        {{-- <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">Askila</span> --}}
    </a>
    <ul class="space-y-2">

        @can('view users')
        <x-dashboard.sidebar.item icon="users" title="المستخدمون" href="{{ route('dashboard.users.index') }}"/>
        @endcan

        @can('view shipments')
        <x-dashboard.sidebar.item icon="shipment" title="الشحنات" href="{{ route('dashboard.shipments.index') }}"/>
        @endcan

        @can('manage flights')
        <x-dashboard.sidebar.item icon="plane" title="الرحلات" href="{{ route('dashboard.flights.index') }}"/>
        @endcan

        @can('manage bookings')
        <x-dashboard.sidebar.item icon="ticket" title="الحجوزات" href="{{ route('dashboard.bookings.index') }}"/>
        @endcan

        @can('view customers')
        <x-dashboard.sidebar.item icon="users" title="العملاء" href="{{ route('dashboard.customers.index') }}"/>
        @endcan

        @can('view branches')
        <x-dashboard.sidebar.item icon="building" title="الفروع" href="{{ route('dashboard.branches.index') }}"/>
        @endcan

        @can('view reports')
        <x-dashboard.sidebar.item icon="chart" title="التقارير" href="{{ route('dashboard.reports.index') }}"/>
        @endcan

        @can('manage settings')
        <x-dashboard.sidebar.item icon="settings" title="الإعدادات" href="{{ route('dashboard.settings.index') }}"/>
        @endcan

        {{-- <x-dashboard.sidebar.item icon="incoming-messages" title="Messages" href="#" badge="6" /> --}}

        {{-- <x-dashboard.sidebar.dropdown icon="basket" title="Sales">
            <x-dashboard.sidebar.subitem href="#" title="Products"/>
            <x-dashboard.sidebar.subitem href="#" title="Billing"/>
            <x-dashboard.sidebar.subitem href="#" title="Invoice"/>
        </x-dashboard.sidebar.dropdown> --}}

        {{-- <li>
            <button type="button" class="flex items-center p-2 w-full text-base font-normal text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700" aria-controls="dropdown-authentication" data-collapse-toggle="dropdown-authentication">
                <svg aria-hidden="true" class="flex-shrink-0 w-6 h-6 text-gray-400 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path></svg>
                <span class="flex-1 ml-3 text-left whitespace-nowrap">Authentication</span>
                <svg aria-hidden="true" class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
            </button>
            <ul id="dropdown-authentication" class="hidden py-2 space-y-2">
                <li>
                    <a href="#" class="flex items-center p-2 pl-11 w-full text-base font-normal text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">Sign In</a>
                </li>
                <li>
                    <a href="#" class="flex items-center p-2 pl-11 w-full text-base font-normal text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">Sign Up</a>
                </li>
                <li>
                    <a href="#" class="flex items-center p-2 pl-11 w-full text-base font-normal text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">Forgot Password</a>
                </li>
            </ul>
        </li> --}}

      </ul>
      {{-- <ul class="pt-5 mt-5 space-y-2 border-t border-gray-200 dark:border-gray-700">
        <x-dashboard.sidebar.item icon="docs" title="Docs" href="#"/>
        <x-dashboard.sidebar.item icon="components" title="Components" href="#"/>

          <li>
              <a href="#" class="flex items-center p-2 text-base font-normal text-gray-900 rounded-lg transition duration-75 hover:bg-gray-100 dark:hover:bg-gray-700 dark:text-white group">
                  <svg aria-hidden="true" class="flex-shrink-0 w-6 h-6 text-gray-400 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-2 0c0 .993-.241 1.929-.668 2.754l-1.524-1.525a3.997 3.997 0 00.078-2.183l1.562-1.562C15.802 8.249 16 9.1 16 10zm-5.165 3.913l1.58 1.58A5.98 5.98 0 0110 16a5.976 5.976 0 01-2.516-.552l1.562-1.562a4.006 4.006 0 001.789.027zm-4.677-2.796a4.002 4.002 0 01-.041-2.08l-.08.08-1.53-1.533A5.98 5.98 0 004 10c0 .954.223 1.856.619 2.657l1.54-1.54zm1.088-6.45A5.974 5.974 0 0110 4c.954 0 1.856.223 2.657.619l-1.54 1.54a4.002 4.002 0 00-2.346.033L7.246 4.668zM12 10a2 2 0 11-4 0 2 2 0 014 0z" clip-rule="evenodd"></path></svg>
                  <span class="ml-3">Help</span>
              </a>
          </li>

      </ul> --}}


      {{-- Alert Padge  --}}
        {{-- <div id="alert-update" class="p-4 mb-3 rounded-lg bg-primary-50 dark:bg-primary-900" role="alert">
            <div class="flex justify-between items-center mb-3">
                <span class="bg-purple-100 text-purple-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded">Beta</span>
                <button type="button" class="inline-flex p-1 w-6 h-6 rounded-lg text-primary-700 bg-primary-50 focus:ring-2 focus:ring-primary-400 hover:bg-primary-100 dark:bg-primary-900 dark:text-primary-300 dark:hover:bg-primary-800 dark:hover:text-primary-200" data-dismiss-target="#alert-update" aria-label="Close">
                    <span class="sr-only">Dismiss</span>
                    <svg aria-hidden="true" class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                </button>
            </div>
            <div class="mb-3 text-sm font-light text-primary-700 dark:text-primary-300">
                Preview the new Flowbite v2.0! You can turn the new features off for a limited time in your settings page.
            </div>
            <a href="#" class="text-sm font-medium underline text-primary-700 dark:text-primary-300 hover:no-underline">
                Turn new features off
            </a>
        </div> --}}
  </div>
  <div class="hidden absolute bottom-0 left-0 justify-center p-4 space-x-4 w-full lg:flex bg-white dark:bg-gray-800 z-20 border-r border-gray-200 dark:border-gray-700">
    <!-- Without tooltip -->
    <x-dashboard.action-icon icon="filter"  />

    <!-- With tooltip -->
    <x-dashboard.action-icon icon="settings" tooltip="Settings page" />


    <x-dashboard.lang-switch />

  </div>
</aside>
