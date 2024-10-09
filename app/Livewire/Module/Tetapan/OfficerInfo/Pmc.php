<?php

namespace App\Livewire\Module\Tetapan\OfficerInfo;

use App\Models\SettOfficerInfoFile;
use Livewire\Component;
use Livewire\WithFileUploads;
use WireUi\Traits\Actions;

class Pmc extends Component
{
    use Actions, WithFileUploads;

    public $savedFile;
    public $file;

    public function mount()
    {
        $this->savedFile = SettOfficerInfoFile::where('OFFICER_LVL', 'PMC')->first();
    }

    public function save()
    {
        $extension = $this->file->getClientOriginalExtension();
        $filename = 'info_pmc_' . now()->format('YmdHis') . '.' . $extension;
        $store_path = 'public/officer_info';
        $db_path = 'officer_info/' . $filename;
        $this->file->storeAs($store_path, $filename);

        SettOfficerInfoFile::updateOrCreate(
            [ 'OFFICER_LVL' => 'PMC' ],
            [ 'FILENAME' => $db_path ]
        );

        $this->reset('file');
        $this->redirect('/tetapan/info-pegawai');
    }

    public function render()
    {
        return view('livewire.module.tetapan.officer-info.pmc', [
            'savedFile' => $this->savedFile,
        ]);
    }
}
