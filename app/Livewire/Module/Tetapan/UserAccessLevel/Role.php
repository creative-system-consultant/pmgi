<?php

namespace App\Livewire\Module\Tetapan\UserAccessLevel;

use App\Models\SettUalPage;
use App\Models\SettUalRole;
use App\Models\SettUalRoleHasPage;
use Livewire\Component;
use WireUi\Traits\Actions;

class Role extends Component
{
    use Actions;

    public $data;
    public $name;
    public $page;
    public $result = [];
    public $roleModal = false;
    public $roleId = null;

    public function mount()
    {
        $this->data = SettUalPage::all()->toArray();
    }

    private function resetForm()
    {
        $this->roleId = null;
        $this->name = '';
        $this->page = [];
    }

    public function add()
    {
        $this->resetForm();
        $this->roleModal = true;
    }

    public function save()
    {
        if ($this->roleId) {
            // Update role
            $role = SettUalRole::findOrFail($this->roleId);
            $role->update(['NAME' => strtoupper($this->name)]);

            // Delete existing page relationships
            SettUalRoleHasPage::where('ROLE_ID', $this->roleId)->delete();

            // Attach new pages
            $role->pages()->attach($this->page);
        } else {
            // Save new role
            $role = SettUalRole::create(['NAME' => strtoupper($this->name)]);

            // Attach pages
            $role->pages()->attach($this->page);
        }

        // Close modal and refresh results
        $this->roleModal = false;
        $this->resetForm();
    }

    public function edit($id)
    {
        $role = SettUalRole::findOrFail($id);
        $this->roleId = $role->id;
        $this->name = $role->name;
        $this->page = $role->pages->pluck('id')->toArray();
        $this->roleModal = true;
    }

    public function remove($id)
    {
        $this->dialog()->confirm([
            'title'       => 'Adakah anda pasti?',
            'description' => 'Padam maklumat ini?',
            'icon'        => 'question',
            'accept'      => [
                'label'  => 'Ya, padam',
                'method' => 'confirmRemove',
                'color' => 'negative',
                'params' => $id,
            ],
            'reject' => [
                'label'  => 'Tidak, batal'
            ],
        ]);
    }

    public function confirmRemove($id)
    {
        // Delete the role and its relationships
        $role = SettUalRole::findOrFail($id);
        $roleName = $role->name;
        SettUalRoleHasPage::where('ROLE_ID', $role->id)->delete();
        $role->delete();

        // show notification dialog
        $this->dialog()->success(
            $title = 'Berjaya dipadam',
            $description = 'Kumpulan "'.$roleName.'" telah berjaya dipadam'
        );
    }

    public function render()
    {
        $this->result = SettUalRole::all();

        return view('livewire.module.tetapan.user-access-level.role', [
            'results' => $this->result,
            'options' => $this->data
        ]);
    }
}
