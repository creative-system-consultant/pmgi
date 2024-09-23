<?php

namespace App\Livewire\Module\Tetapan\OfficerInfo;

use App\Models\SettOfficerInfoFile;
use App\Models\SettPydProb;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use WireUi\Traits\Actions;

class Pyd extends Component
{
    use Actions, WithFileUploads;

    public $savedFile;

    #[Validate('required', message: 'Sila isikan masalah yang dihadapi.')]
    public $problem;
    public $file;

    public function mount()
    {
        $this->savedFile = SettOfficerInfoFile::where('OFFICER_LVL', 'PYD')->first();
    }

    public function add()
    {
        $this->validate();

        SettPydProb::create([
            'DESCRIPTION' => $this->problem
        ]);

        $this->reset('problem');

        // show notification dialog
        $this->dialog()->success(
            $title = 'Berjaya ditambah',
            $description = 'Masalah yang dihadapi PYD telah berjaya ditambah'
        );
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
        SettPydProb::where('ID', $id)->delete();

        // show notification dialog
        $this->dialog()->success(
            $title = 'Berjaya dipadam',
            $description = 'Masalah yang dihadapi telah berjaya dipadam'
        );
    }

    public function save()
    {
        $extension = $this->file->getClientOriginalExtension();
        $filename = 'info_pyd_' . now()->format('YmdHis') . '.' . $extension;
        $store_path = 'public/officer_info';
        $db_path = 'officer_info/' . $filename;
        $this->file->storeAs($store_path, $filename);

        SettOfficerInfoFile::updateOrCreate(
            [ 'OFFICER_LVL' => 'PYD' ],
            [ 'FILENAME' => $db_path ]
        );

        $this->reset('file');
        $this->redirect('/tetapan/info-pegawai', true);
    }

    public function render()
    {
        $problem = SettPydProb::orderBy('id')->get();

        return view('livewire.module.tetapan.officer-info.pyd', [
            'problems' => $problem,
            'savedFile' => $this->savedFile,
        ]);
    }
}
