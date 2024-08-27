<?php

namespace App\Livewire\Module\Tetapan\UserAccessLevel;

use App\Models\SettUalRole;
use App\Models\SettUalUserHasRole;
use App\Models\User;
use Livewire\Component;
use WireUi\Traits\Actions;

class UserAccess extends Component
{
    use Actions;

    public $user;
    public $name;
    public $results = [];
    public $option;
    public $role = [];
    public $userAccessModal = false;

    public function mount()
    {
        $this->option = SettUalRole::orderBy('id', 'ASC')->get();
    }

    public function find(): void
    {
        $this->results = User::where('username', 'like', '%' . strtoupper($this->name) . '%')
                                ->where('userstatus', 1)
                                ->get();
    }

    public function showModal($userId)
    {
        $this->user = User::where('userid', $userId)
                            ->where('userstatus', 1)
                            ->first();
        $this->name = $this->user->username;
        $this->role = $this->user->roles->pluck('id')->toArray();
        $this->userAccessModal = true;
    }

    public function save()
    {
        // Delete existing role relationships
        SettUalUserHasRole::where('userid', $this->user->userid)->delete();

        // Attach new roles
        foreach ($this->role as $roleId) {
            SettUalUserHasRole::create([
                'USERID' => $this->user->userid,
                'ROLE_ID' => $roleId
            ]);
        }

        // Get role info for notification
        $roleNames = SettUalRole::whereIn('id', $this->role)->pluck('name')->implode(', ');

        // Show success notification
        $this->dialog()->success(
            $title = 'Berjaya dikemaskini',
            $description = '"' . $this->user->username . '" telah diberi akses sebagai "' . $roleNames . '"'
        );

        // Close modal and reset properties
        $this->userAccessModal = false;
        $this->reset('user', 'name', 'role');
    }

    public function render()
    {
        return view('livewire.module.tetapan.user-access-level.user-access', [
            'results' => $this->results,
            'options' => $this->option
        ]);
    }
}
