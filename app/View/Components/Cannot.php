<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Helpers\PermissionHelper;

class Cannot extends Component
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
        $show = false;
        
        // If specific permission is provided
        if ($this->permission) {
            $show = !PermissionHelper::hasPermission($this->permission);
        }
        
        // If any permissions are provided
        else if ($this->any) {
            $permissions = is_array($this->any) ? $this->any : explode(',', $this->any);
            $show = !PermissionHelper::hasAnyPermission($permissions);
        }
        
        // If all permissions are required
        else if ($this->all) {
            $permissions = is_array($this->all) ? $this->all : explode(',', $this->all);
            $show = !PermissionHelper::hasAllPermissions($permissions);
        }
        
        // If conditions matched, return the content
        if ($show) {
            return view('components.can');
        }
        
        // Otherwise return empty
        return '';
    }
}