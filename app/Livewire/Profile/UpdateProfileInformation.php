<?php

namespace App\Livewire\Profile;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;


class UpdateProfileInformation extends Component
{
    use WithFileUploads;

    public string $name = '';
    public string $email = '';
    public ?string $phone = null;

    #[Validate('nullable|image|mimes:jpg,jpeg,png,webp|max:2048')]
    public $photo; // Livewire TemporaryUploadedFile

    public function mount(): void
    {
        $u = Auth::user();

        $this->name  = (string) $u->name;
        $this->email = (string) $u->email;
        $this->phone = $u->phone;
    }

    protected function rules(): array
    {
        $id = Auth::id();

        return [
            'name'  => ['required','string','max:255'],
            'email' => ['required','email','max:255', Rule::unique('users','email')->ignore($id)],
            'phone' => ['nullable','string','max:20', Rule::unique('users','phone')->ignore($id)],
        ];
    }

    public function saveProfile(): void
    {
        $this->validate(); // name/email/phone

        $u = Auth::user();
        $u->update([
            'name'  => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
        ]);
        // dd($this->name);

        $this->dispatch('toast', body: [
            'type'=>'success',
            'message'=>t('Profile updated successfully.')
        ]);
    }

    public function savePhoto(): void
    {
        $this->validateOnly('photo');

        if (! $this->photo) {
            return;
        }

        $u = Auth::user();

        // حذف القديم إن وجد
        if ($u->image && Storage::disk('public')->exists($u->image)) {
            Storage::disk('public')->delete($u->image);
        }

        // حفظ الجديد
        $filename = 'user-'.$u->id.'-'.now()->timestamp.'.'.$this->photo->getClientOriginalExtension();
        $path = $this->photo->storePubliclyAs('avatars', $filename, 'public');

        $u->forceFill(['image' => $path])->save();

        // تنظيف الملف المؤقت + تحديث الواجهة
        $this->reset('photo');
        $this->dispatch('toast', body: t('Profile photo updated.'));
        $this->dispatch('$refresh'); // يحدّث Auth::user() في الواجهة عند إعادة التحميل
    }

    public function removePhoto(): void
    {
        $u = Auth::user();
        if ($u->image && Storage::disk('public')->exists($u->image)) {
            Storage::disk('public')->delete($u->image);
        }
        $u->forceFill(['image' => null])->save();

        $this->dispatch('notify', body: t('Profile photo removed.'));
    }

    public function sendEmailVerification(): void
    {
        // Auth::user()?->sendEmailVerificationNotification();
        // $this->dispatch('notify', body: t('A new verification link has been sent to your email address.'));
    }

    public function render()
    {
        return view('livewire.profile.update-profile-information');
    }
}
