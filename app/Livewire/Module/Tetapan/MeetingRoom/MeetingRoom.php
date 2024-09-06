<?php

namespace App\Livewire\Module\Tetapan\MeetingRoom;

use App\Models\SettMeetingRoom;
use Livewire\Component;
use WireUi\Traits\Actions;

class MeetingRoom extends Component
{
    use Actions;
    public $room_name;


    public function add()
    {
        SettMeetingRoom::create([
            'ROOM_NAME' => $this->room_name,
            'CREATED_BY' => auth()->user()->userid,
        ]);

        $this->reset('room_name');

        // show notification dialog
        $this->dialog()->success(
            $title = 'Berjaya ditambah',
            $description = 'Maklumat bilik mesyuarat telah berjaya ditambah'
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
        SettMeetingRoom::where('ID', $id)->delete();

        // show notification dialog
        $this->dialog()->success(
            $title = 'Berjaya dipadam',
            $description = 'Maklumat bilik mesyuarat telah berjaya dipadam'
        );
    }


    public function render()
    {
        $rooms = SettMeetingRoom::orderBy('ID', 'asc')->get();
        return view('livewire.module.tetapan.meeting-room.meeting-room', ['rooms' => $rooms])->extends('layouts.main');
    }
}
