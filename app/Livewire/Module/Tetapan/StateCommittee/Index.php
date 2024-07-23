<?php

namespace App\Livewire\Module\Tetapan\StateCommittee;

use App\Models\BnmStatecode;
use App\Models\SettStateCommittee;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use PDO;
use WireUi\Traits\Actions;

class Index extends Component
{
    use Actions;

    public $options;
    public $selectedUsers = [];
    public $filteredOptions = [];

    protected $rules = [
        'selectedUsers.*' => 'nullable|exists:FMS_USERS,userid',
    ];

    public function mount()
    {
        $this->options = Cache::remember('user_options', 60 * 60, function () {
            return User::with(['bankOfficer' => function ($query) {
                $query->select('officer_id', 'branch_code');
            }, 'bankOfficer.branch' => function ($query) {
                $query->select('branch_code', 'branch_name');
            }])
            ->where('userstatus', 1)
            ->get(['userid', 'username']);
        });

        $this->initializeSelectedUsers();
        $this->initializeFilteredOptions();
    }

    protected function initializeSelectedUsers()
    {
        $states = BnmStatecode::whereNotIn('code', ['00', '15', '16', '99'])->get();
        $existingAssignments = SettStateCommittee::whereIn('statecode', $states->pluck('code'))->get();

        foreach ($states as $state) {
            $assignment = $existingAssignments->firstWhere('statecode', $state->code);
            $this->selectedUsers[$state->code] = $assignment ? $assignment->userid : null;
        }
    }

    protected function initializeFilteredOptions()
    {
        $states = BnmStatecode::whereNotIn('code', ['00', '99'])->get();

        foreach ($states as $state) {
            $this->filteredOptions[$state->code] = $this->getFilteredOptions($state->code);
        }
    }

    public function getFilteredOptions($stateCode)
    {
        return $this->options->filter(function ($option) use ($stateCode) {
            return optional($option->bankOfficer)->branch_code && substr($option->bankOfficer->branch_code, 0, 2) == $stateCode;
        })->map(function ($option) {
            return [
                'userid' => $option->userid,
                'username' => $option->username,
            ];
        })->values()->toArray();
    }

    public function save()
    {
        $dataToUpdate = [];

        foreach ($this->selectedUsers as $stateCode => $userId) {
            if ($userId) { // Only save if userId is not null
                $dataToUpdate[] = ['statecode' => $stateCode, 'userid' => $userId];
            }
        }

        // Using batch insert/update
        SettStateCommittee::upsert(
            $dataToUpdate,
            ['statecode'],
            ['userid']
        );

        $this->dialog()->success(
            $title = 'Berjaya disimpan',
            $description = 'Lantikan Urusetia berjaya disimpan.'
        );
    }

    public function test()
    {
        // Define the output parameter for the OUT parameter
        $output = '';

        // Use DB::executeProcedure for calling stored procedures
        $procedureName = 'dbo.UP_PMGI_UPD_MNTR_SESSION';

        $bindings = [
            'pi_reportdt'    => '2024-01-31',
            'pi_state_code'  => '01',
            'pi_branch_code' => '0112',
            'pi_officer_id'  => 'FARID',
            'pi_pmgi_result' => 'RSG',
            'pi_wait_period' => 0,
            'pi_operated_by' => 'CR7',
            'pi_ret_msg'     => [
                'value' => &$output,
                'type'  => PDO::PARAM_STR,
                'length' => 4000,
            ],
        ];

        // Execute the procedure
        $result = DB::executeProcedure($procedureName, $bindings);

        // Display the output message
        dd($output);
    }

    public function render()
    {
        $data = BnmStatecode::with('committee')
            ->whereNotIn('code', ['00', '15', '16', '99'])
            ->get();

        return view('livewire.module.tetapan.state-committee.index', [
            'datas' => $data,
            'options' => $this->options,
            'filteredOptions' => $this->filteredOptions,
        ])->extends('layouts.main');
    }
}
