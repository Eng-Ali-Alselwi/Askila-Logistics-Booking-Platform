 <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
     <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
         <tr class=" border-gray-500 dark:border-gray-600">
             <th scope="col" class="px-4 py-3">User</th>
             <th scope="col" class="px-4 py-3">User Role</th>
             <th scope="col" class="px-4 py-3">Permissions</th>
             <th scope="col" class="px-4 py-3">Status</th>
             <th scope="col" class="px-4 py-3">Change Status</th>
             <th scope="col" class="px-4 py-3">
                 <span class="sr-only">Actions</span>
             </th>
         </tr>
     </thead>
     <tbody>
         @foreach ($users as $user)
             <tr class="border-b  border-gray-100 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700">
                 <th scope="row" class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                     <div class="flex items-center">
                         <img src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/avatars/avatar-10.png"
                             alt="iMac Front Image" class="w-auto h-8 me-3 rounded-full">
                         {{-- <img src="{{ $user->image ? asset('storage/' . $user->image) : asset('storage/users/default.png') }}" alt="{{ $user->name }} Profile Image" class="w-auto h-8 mr-3 rounded-full"> --}}
                         <div>
                            {{ $user->name }}
                         <br/>
                         <span class="font-light ">

                             {{ $user->email }}
                         </span>
                         </div>
                     </div>
                 </th>

                 <td class="px-4 py-2">
                     <livewire:user-roles-select :user-id="$user->id" :key="'roles-'.$user->id" />
                     {{-- @php
                         $role = $user->getRoleNames()[0];
                         $roleClasses = [
                             'super_admin' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                             'manager' => 'bg-primary-100 text-primary-800 dark:bg-primary-900 dark:text-primary-300',
                             'updater' => 'bg-primary-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-300',
                         ];
                         $roleClass =
                             $roleClasses[$role] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300';
                     @endphp
                     <div
                         class="inline-flex items-center text-xs font-medium px-2 py-0.5 rounded
                                   {{ $roleClass }} ">
                         @if ($user->getRoleNames()[0] == 'super_admin')
                             <x-heroicon-s-shield-check class="h-3.5 w-3.5 me-1" />
                         @elseif ($user->getRoleNames()[0] == 'manager')
                             <x-heroicon-s-cog class="h-3.5 w-3.5 me-1" />
                         @elseif ($user->getRoleNames()[0] == 'updater')
                             <x-heroicon-s-pencil class="h-3.5 w-3.5 me-1" />
                         @else
                             <x-heroicon-s-paper-airplane class="h-3.5 w-3.5 me-1" />
                         @endif
                         {{ $user->getRoleNames()[0] }}
                     </div> --}}
                 </td>

                 <td class="px-4 py-2 max-w-3xs inline-flex gap-1 flex-wrap justify-start">
                     @foreach ($user->getAllPermissions() as $permission)
                        <x-badge label="{{ $permission->name }}" type="dark"/>
                         {{-- <span
                             class="bg-gray-200  text-gray-700 dark:bg-gray-700 dark:text-gray-200 px-2 py-0.5 rounded text-xs">
                             {{ $permission->name }}
                         </span> --}}
                     @endforeach {{  $user->hasRole('super_admin').'  ' }}
                 </td>

                 {{-- <livewire:toggle-user-activation2/> --}}
                 {{-- <livewire:toggle-user-activation :user-id="$user->id"> --}}

                 <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                     <div class="flex items-center">
                         <div class="w-3 h-3 mr-2 {{ $user->is_active ? 'bg-green-500' : 'bg-red-500' }}  rounded-full">
                         </div>
                         {{ $user->is_active ? 'Active' : 'Inactive' }}
                     </div>
                 </td>

                 <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    <form action="{{ route('dashboard.users.toggle-activation',['user'=>$user]) }}" method="POST">
                        @csrf
                        @method("PATCH")
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" value="" class="sr-only peer"
                            @checked($user->is_active)
                                @disabled($user->isSuperAdmin())
                                onchange="this.form.submit()">
                            <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600 dark:peer-checked:bg-blue-600"></div>
                        </label>
                        {{-- <label class="relative inline-flex items-center cursor-pointer">

                            <input type="checkbox"
                                @checked($user->is_active)
                                @disabled($user->isSuperAdmin())
                                class="sr-only peer"
                                onchange="this.form.submit">
                            <div class="w-11 h-6 {{ $user->isSuperAdmin() ? 'cursor-not-allowed' : '' }} bg-gray-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-primary-300 dark:peer-focus:ring-primary-800 dark:bg-gray-600 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary-600">
                            </div>
                        </label> --}}
                    </form>
                    {{-- <livewire:toggle-user-activation :user="$user"> --}}
                 </td>

                 <td class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                     <x-dashboard.delete-action route="{{ route('dashboard.users.destroy', $user) }}"
                         message="Are you sure you want to delete this user?" />
                     <x-dashboard.action-icon route="{{ route('dashboard.users.edit', $user) }}" >
                        <x-heroicon-s-pencil-square class="w4 h-4"/>
                     </x-dashboard.action-icon>
                 </td>
             </tr>
         @endforeach
     </tbody>
 </table>
