<?php

namespace App\Livewire\Module;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SoalanLazimView extends Component
{

    public $userRoleCode;

    public function render()
    {
        $user = Auth::user();

        $roleMap = [
            4 => 'pyd',
            5 => 'pym',
            6 => 'pmc',
        ];

        // Ambil role pertama sahaja (kalau 1 user = 1 role)
        $roleId = $user->roles()->pluck('role_id')->first();

        $this->userRoleCode = $roleMap[$roleId] ?? null;


        // Query dengan filter berdasarkan jawatan
        $latest = DB::table('AUDIT.pmgi_soalan_lazim')
            ->where('officer_lvl', $this->userRoleCode)
            ->latest('created_at')
            ->first();

        $documents = $latest ? collect([$latest]) : collect([]);

        
        return view('livewire.module.soalan-lazim-view', [
            'documents' => $documents,
            'userJawatan' => $this->userRoleCode,
        ])->extends('layouts.main');
    }
}
