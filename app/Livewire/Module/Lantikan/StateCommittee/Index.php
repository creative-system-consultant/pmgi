<?php

namespace App\Livewire\Module\Lantikan\StateCommittee;

use App\Jobs\CleanupTemporaryFiles;
use App\Jobs\SendLantikanUrusetiaNegeriEmail;
use App\Models\BankOfficer;
use App\Models\BnmStatecode;
use App\Models\SettStateCommittee;
use App\Models\SettUalRole;
use App\Models\SettUalUserHasRole;
use App\Models\User;
use App\Services\HtmlToImageService;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use PDO;
use WireUi\Traits\Actions;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\LantikanUrusetiaNegeri;

class Index extends Component
{
    use Actions;

    private $htmlToImageService;

    public $options;
    public $selectedUsers = [];
    public $filteredOptions = [];
    protected $originalSelectedUsers = [];

    protected $rules = [
        'selectedUsers.*' => 'nullable|exists:pmgi_fms_users,USERID',
    ];

    public function __construct()
    {
        $this->htmlToImageService = new HtmlToImageService();
    }

    public function mount()
    {
        // Step 1: Get user IDs from linked server
        $userIds = User::where('USERSTATUS', 1)->pluck('USERID');

        // Step 2: Get bank officers with branches from local DB
        $bankOfficers = BankOfficer::query()
            ->with(['branch' => function ($query) {
                $query->select('branch_code', 'branch_name');
            }])
            ->whereIn('officer_id', $userIds)
            ->whereIn('roles', ['542', '634'])
            ->get()
            ->keyBy('officer_id');

        // Step 3: Get linked server users and attach bank officer data
        $this->options = User::query()
            ->whereIn('USERID', $bankOfficers->keys())
            ->get(['USERID', 'USERNAME'])
            ->map(function ($user) use ($bankOfficers) {
                $user->bankOfficer = $bankOfficers->get($user->USERID);
                return $user;
            });

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
                'userid' => $option->USERID,
                'username' => $option->USERNAME,
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
                // $email = 'hafizah@tekun.gov.my'; //FAT purpose
                $email = 'nazirul@csc.net.my'; //FAT purpose
                // $email = BankOfficer::where('officer_id', $userId)->value('email');
                $this->sendEmail($email, $path['email_image_path'], $path['html_path']);

                // Add to the update list with zero-padded statecode
                $dataToUpdate[] = ['statecode' => str_pad($stateCode, 2, '0', STR_PAD_LEFT), 'userid' => $userId];

                // Update the originalSelectedUsers array to reflect the new state after saving
                $this->originalSelectedUsers[$stateCode] = $userId;
            }

            $stateCommitteeRoleId = SettUalRole::where('name', 'URUSETIA NEGERI')->value('id');

            if ($stateCommitteeRoleId && !empty($userId)) { // Explicit check for empty userId
                $existingRole = SettUalUserHasRole::where(DB::raw('UPPER(USERID)'), strtoupper($userId))
                    ->where('ROLE_ID', $stateCommitteeRoleId)
                    ->exists();

                if (!$existingRole) {
                    SettUalUserHasRole::create([
                        'USERID' => strtoupper($userId), // Ensure consistent case
                        'ROLE_ID' => $stateCommitteeRoleId,
                    ]);
                }
            }
        }

        // Also handle records that weren't changed but might need statecode padding
        foreach ($this->selectedUsers as $stateCode => $userId) {
            if ($userId && !in_array(['statecode' => str_pad($stateCode, 2, '0', STR_PAD_LEFT), 'userid' => $userId], $dataToUpdate)) {
                $dataToUpdate[] = ['statecode' => str_pad($stateCode, 2, '0', STR_PAD_LEFT), 'userid' => $userId];
            }
        }

        // Using batch insert/update with zero-padded statecodes
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
        try {
            // Read image data immediately
            $imageData = base64_encode(file_get_contents($imagePath));
            $imageName = basename($imagePath);
            
            // Explicitly set MIME type based on file extension for better compatibility
            $extension = strtolower(pathinfo($imagePath, PATHINFO_EXTENSION));
            if ($extension === 'jpg' || $extension === 'jpeg') {
                $imageMime = 'image/jpeg';
            } elseif ($extension === 'png') {
                $imageMime = 'image/png';
            } else {
                $imageMime = mime_content_type($imagePath) ?: 'image/jpeg'; // Default to JPEG
            }
            
            // Send email synchronously
            Mail::to($email)->send(new LantikanUrusetiaNegeri($imageData, $imageName, $imageMime));
            
            // Clean up files after successful email send
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
            
            // Also clean up original PNG if we used compressed version
            $originalPng = str_replace('_email.jpg', '.png', $imagePath);
            if (file_exists($originalPng) && $originalPng !== $imagePath) {
                unlink($originalPng);
            }
            
            if (file_exists($htmlPath)) {
                unlink($htmlPath);
            }
            
            Log::info("Email sent successfully to: {$email} (using compressed image)");
            
        } catch (\Exception $e) {
            Log::error("Failed to send email to {$email}: " . $e->getMessage());
            
            // Clean up files even if email failed
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
            
            // Also clean up original PNG if we used compressed version
            $originalPng = str_replace('_email.jpg', '.png', $imagePath);
            if (file_exists($originalPng) && $originalPng !== $imagePath) {
                unlink($originalPng);
            }
            
            if (file_exists($htmlPath)) {
                unlink($htmlPath);
            }
            
            throw $e;
        }
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
