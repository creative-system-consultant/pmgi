<?php

namespace App\Livewire\Module\Lantikan\StateCommittee;

use App\Jobs\CleanupTemporaryFiles;
use App\Jobs\SendLantikanUrusetiaNegeriEmail;
use App\Models\BankOfficer;
use App\Models\BnmStatecode;
use App\Models\SettStateCommittee;
use App\Models\User;
use App\Services\HtmlToImageService;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use PDO;
use WireUi\Traits\Actions;

class Index extends Component
{
    use Actions;

    private $htmlToImageService;

    public $options;
    public $selectedUsers = [];
    public $filteredOptions = [];
    protected $originalSelectedUsers = [];

    protected $rules = [
        'selectedUsers.*' => 'nullable|exists:FMS_USERS,userid',
    ];


    public function __construct()
    {
        $this->htmlToImageService = new HtmlToImageService();
    }

    public function mount()
    {
        $this->options = User::with([
                                'bankOfficer' => function ($query) {
                                    $query->select('officer_id', 'branch_code', 'officer_position');
                                },
                                'bankOfficer.branch' => function ($query) {
                                    $query->select('branch_code', 'branch_name');
                                },
                            ])
                            ->whereHas('bankOfficer', function ($query) {
                                $query->whereIn('roles', ['542', '634']);
                            })
                            ->where('userstatus', 1)
                            ->get(['userid', 'username']);

        $this->initializeSelectedUsers();
        $this->initializeFilteredOptions();
        $this->originalSelectedUsers = $this->selectedUsers;
    }

    protected function initializeSelectedUsers()
    {
        $states = BnmStatecode::whereNotIn('code', ['00', '15', '16', '99'])->get();
        $existingAssignments = SettStateCommittee::whereIn('statecode', $states->pluck('code'))->get();

        foreach ($states as $state) {
            $assignment = $existingAssignments->firstWhere('statecode', $state->code);
            $this->selectedUsers[$state->code] = $assignment ? $assignment->userid : null;
        }

        // Ensure all states have an entry in selectedUsers, even if it's null
        foreach ($states as $state) {
            if (!array_key_exists($state->code, $this->selectedUsers)) {
                $this->selectedUsers[$state->code] = null;
            }
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
            // Only send an email if the userId has changed compared to the original state
            if ($userId && (!isset($this->originalSelectedUsers[$stateCode]) || $this->originalSelectedUsers[$stateCode] !== $userId)) {
                // Generate emails image and send email only for updated entries
                $path = $this->generateImageFromHtml($stateCode, $userId);
                $email = BankOfficer::where('officer_id', $userId)->value('email');
                $this->sendEmail($email, $path['image'], $path['html']);

                // Add to the update list
                $dataToUpdate[] = ['statecode' => $stateCode, 'userid' => $userId];

                // Update the originalSelectedUsers array to reflect the new state after saving
                $this->originalSelectedUsers[$stateCode] = $userId;
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

    private function generateImageFromHtml($stateCode, $userId)
    {
        $stateName = BnmStatecode::whereCode($stateCode)->value('description');

        return $this->htmlToImageService->generate(
            'emails.lantikan_urusetia_negeri',
            ['state' => $stateName],
            'emails/urusetia_negeri/',
            "email_urusetia_negeri_{$userId}"
        );
    }

    private function sendEmail($email, $imagePath, $htmlPath)
    {
        $jobs = [];

        if ($email) {
            $jobs[] = new SendLantikanUrusetiaNegeriEmail($email, $imagePath);
        }

        // Chain the cleanup job after the email jobs
        $jobs[] = new CleanupTemporaryFiles([$imagePath], [$htmlPath]);

        // Dispatch the jobs as a chain
        Bus::chain($jobs)->dispatch();
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

        return view('livewire.module.lantikan.state-committee.index', [
            'datas' => $data,
            'options' => $this->options,
            'filteredOptions' => $this->filteredOptions,
        ])->extends('layouts.main');
    }
}
