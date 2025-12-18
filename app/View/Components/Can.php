<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Helpers\PermissionHelper;

class Can extends Component
{
    public $permission;
    public $any;
    public $all;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($permission = null, $any = null, $all = null)
    {
        $this->permission = $permission;
        $this->any = $any;
        $this->all = $all;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        // If specific permission is provided
        if ($this->permission) {
            if (PermissionHelper::hasPermission($this->permission)) {
                return view('components.can');
            }
        }
        
        // If any permissions are provided
        if ($this->any) {
            $permissions = is_array($this->any) ? $this->any : explode(',', $this->any);
            if (PermissionHelper::hasAnyPermission($permissions)) {
                return view('components.can');
            }
        }
        
        // If all permissions are required
        if ($this->all) {
            $permissions = is_array($this->all) ? $this->all : explode(',', $this->all);
            if (PermissionHelper::hasAllPermissions($permissions)) {
                return view('components.can');
            }
        }
        
        // If no conditions matched, return empty view
        return '';
    }
}