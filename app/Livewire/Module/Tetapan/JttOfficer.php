<?php

namespace App\Livewire\Module\Tetapan;

use App\Models\SettJtt;
use App\Models\User;
use Livewire\Component;
use WireUi\Traits\Actions;

class JttOfficer extends Component
{
    use Actions;

    public $name;
    public $results = [];

    public function find(): void
    {
        $this->results = User::where('username', 'like', '%' . strtoupper($this->name) . '%')
                                ->where('userstatus', 1)
                                ->get();
    }

    public function addJtt($id)
    {
        $this->dialog()->confirm([
            'title'       => 'Andakah anda pasti?',
            'description' => 'Tambah sebagai ahli JTT?',
            'icon'        => 'question',
            'accept'      => [
                'label'  => 'Ya, pasti',
                'method' => 'confirmAdd',
                'params' => $id
            ],
            'reject' => [
                'label'  => 'Tidak, batalkan',
            ],
        ]);
    }

    public function confirmAdd($id)
    {
        SettJtt::create([
            'officer_id' => $id,
            'created_by' => auth()->user()->userid
        ]);

        $this->dialog()->success(
            $title = 'Berjaya!',
            $description = 'Ahli JTT berjaya ditambah'
        );
    }

    public function removeJtt($id)
    {
        $this->dialog()->confirm([
            'title'       => 'Andakah anda pasti?',
            'description' => 'Padam ahli JTT?',
            'icon'        => 'question',
            'accept'      => [
                'label'  => 'Ya, pasti',
                'method' => 'confirmRemove',
                'params' => $id
            ],
            'reject' => [
                'label'  => 'Tidak, batalkan',
            ],
        ]);
    }

    public function confirmRemove($id)
    {
        SettJtt::whereOfficerId($id)->delete();

        $this->dialog()->success(
            $title = 'Berjaya!',
            $description = 'Ahli JTT berjaya dipadam'
        );
    }

    public function render()
    {
        $jtt = SettJtt::all();

        return view('livewire.module.tetapan.jtt-officer', [
            'results' => $this->results,
            'jtts' => $jtt,
        ])->extends('layouts.main');
    }
}
