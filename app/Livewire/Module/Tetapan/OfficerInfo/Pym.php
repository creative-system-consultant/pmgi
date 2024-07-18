<?php

namespace App\Livewire\Module\Tetapan\OfficerInfo;

use App\Models\SettOfficerInfoFile;
use Livewire\Component;
use Livewire\WithFileUploads;
use WireUi\Traits\Actions;

class Pym extends Component
{
    use Actions, WithFileUploads;

    public $savedFile;
    public $file;

    public function mount()
    {
        $this->savedFile = SettOfficerInfoFile::where('OFFICER_LVL', 'PYM')->first();
    }

    public function save()
    {
        $extension = $this->file->getClientOriginalExtension();
        $filename = 'info_pym_' . now()->format('YmdHis') . '.' . $extension;
        $store_path = 'public/officer_info';
        $db_path = 'officer_info/' . $filename;
        $this->file->storeAs($store_path, $filename);

        SettOfficerInfoFile::updateOrCreate(
            [ 'OFFICER_LVL' => 'PYM' ],
            [ 'FILENAME' => $db_path ]
        );

        $this->reset('file');
        $this->redirect('/tetapan/info-pegawai', true);
    }

    public function render()
    {
        return view('livewire.module.tetapan.officer-info.pym', [
            'savedFile' => $this->savedFile,
        ]);
    }
}
