<?php

namespace App\Livewire\Admin\Maintenance;

use Livewire\Component;
use App\Models\SoalanLazimFile;
use Livewire\WithFileUploads;
use WireUi\Traits\Actions;

class PydFaq extends Component
{

    use Actions, WithFileUploads;

    public $savedFile;
    public $file;

    public function mount()
    {
        $this->savedFile = SoalanLazimFile::where('officer_lvl', 'PYD')->latest()->first();
    }

    public function save()
    {
        $extension = $this->file->getClientOriginalExtension();
        $filename = 'soalan_lazim_pyd_' . now()->format('YmdHis') . '.' . $extension;
        $store_path = $this->file->storeAs('soalan-lazim',$filename,'public');
        $db_path = 'soalan-lazim/' . $filename;
        // $this->file->storeAs($store_path, $filename);

        SoalanLazimFile::updateOrCreate(
            // [ 'officer_lvl' => 'PYD' ],
            [   'officer_lvl' => 'PYD',
                'file_name'   => $filename,
                'file_path'   => $db_path,
                'uploaded_by' => auth()->user()?->USERID,
            ]
        );


        $this->reset('file');
        $this->redirect('/admin-maintenance/soalan-lazim');
    }

    public function render()
    {
        return view('livewire.admin.maintenance.pyd-faq');
    }
}
