<?php

namespace App\Traits;

use App\Models\Branch;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait BelongsToBranch
{
    public static function bootBelongsToBranch()
    {
        static::creating(function ($model) {
            if (Auth::hasUser()) {
                $user = Auth::user();
                if (!$model->branch_id) {
                    // إذا كان المستخدم مرتبط بفرع وليس مدير عام، نقوم بتعيين الفرع تلقائياً
                    if ($user->branch_id && !$user->hasAnyRole(['super_admin', 'manager', 'المشرف الاعلى', 'مدير'])) {
                        $model->branch_id = $user->branch_id;
                    }
                }
                
                // تعيين من أنشأ السجل إذا كان الحقل موجوداً
                if (in_array('created_by', $model->getFillable()) && !$model->created_by) {
                    $model->created_by = $user->id;
                }
            }
        });

        static::addGlobalScope('branch_isolation', function (Builder $builder) {
            // نستخدم hasUser() لتجنب التكرار اللانهائي (infinite recursion) عند استخدامه في موديل User
            if (Auth::hasUser()) {
                $user = Auth::user();
                // تطبيق العزل فقط إذا كان المستخدم مرتبط بفرع وليس مديراً عاماً
                if ($user->branch_id && !$user->hasAnyRole(['super_admin', 'manager', 'المشرف الاعلى', 'مدير'])) {
                    $builder->where(function ($query) use ($user) {
                        $query->where($query->getModel()->getTable() . '.branch_id', $user->branch_id);
                    });
                }
            }
        });
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
