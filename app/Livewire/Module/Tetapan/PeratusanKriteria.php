<?php

namespace App\Livewire\Module\Tetapan;

use App\Models\BnmStatecode;
use App\Models\RefEvalPctg;
use Livewire\Component;
use Carbon\Carbon;
use WireUi\Traits\Actions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PeratusanKriteria extends Component
{
    use Actions;

    public $titles = [
        1 => 'Patut Kutip (RM) vs Dapat Kutip (RM)',
        2 => 'Patut Kutip (BIL) vs Dapat Kutip (BIL)',
        3 => 'Lawatan Seliaan',
        4 => 'Prestasi NPF (KAWALAN)',
        5 => 'Prestasi NPF (Pemulihan)'
    ];
    public $differentStateCodes = [];
    public $type;
    public $negeri;
    public $diff_pctgs;
    public $effective_date;
    public $result = false;
    public $resultMount = false;
    public $noPreviousData = false;
    public $evaluation_criteria_percentage = [
        1 => 0,
        2 => 0,
        3 => 0,
        4 => 0,
        5 => 0
    ];
    public $tableData = [];

    public function mount()
    {
        $this->effective_date = Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d');
        $this->retrieveInitialData();
    }

    public function retrieveInitialData()
    {
        $eval_pctgs = RefEvalPctg::where('effective_date', $this->effective_date)
            ->whereNotIn('state_code', ['00', '15', '16', '99'])
            ->with('bnmState')
            ->get();

        if ($eval_pctgs->isNotEmpty()) {
            $this->noPreviousData = false;
            $allSame = true;
            $differentStates = [];
            $differentStateCodes = [];
            $tableData = [];

            // Initialize the table header
            $tableData[] = array_merge(['No.', 'Negeri'], array_values($this->titles));

            foreach (range(1, 5) as $evaluationId) {
                $percentages = $eval_pctgs->where('evaluation_id', $evaluationId)
                    ->groupBy('state_code')
                    ->map(function ($group) {
                        return [
                            'state_code' => $group->first()->state_code,
                            'state' => $group->first()->bnmState->description,
                            'percentage' => $group->first()->evaluation_percentage
                        ];
                    })->values();

                $uniquePercentages = $percentages->pluck('percentage')->unique();
                if ($uniquePercentages->count() > 1) {
                    $allSame = false;
                    $differentStates[$evaluationId] = $percentages->toArray();

                    // Find the majority percentage
                    $percentageValues = $percentages->pluck('percentage')->toArray();
                    $percentageCounts = array_count_values($percentageValues);
                    arsort($percentageCounts);
                    $majorityPercentage = key($percentageCounts);

                    // Record state codes with different values
                    $differentCodes = $percentages->where('percentage', '!=', $majorityPercentage)
                        ->pluck('state_code')
                        ->toArray();
                    $differentStateCodes = array_merge($differentStateCodes, $differentCodes);
                }
            }

            $differentStateCodes = array_unique($differentStateCodes);

            if ($allSame) {
                // If all percentages are the same, just add one row for "Semua negeri"
                $stateData = ['No.' => 1, 'Negeri' => 'SEMUA NEGERI'];
                foreach (range(1, 5) as $evaluationId) {
                    $statePercentage = $eval_pctgs->where('evaluation_id', $evaluationId)->first();
                    $stateData[$this->titles[$evaluationId]] = $statePercentage ? $statePercentage->evaluation_percentage . '%' : 'N/A';
                }
                $tableData[] = $stateData;
            } else {
                // Add rows for states with different percentages
                $rowNumber = 1;
                foreach ($differentStateCodes as $stateCode) {
                    $stateData = ['No.' => $rowNumber++, 'Negeri' => ''];
                    foreach (range(1, 5) as $evaluationId) {
                        $statePercentage = $eval_pctgs->where('state_code', $stateCode)
                            ->where('evaluation_id', $evaluationId)
                            ->first();
                        $stateData[$this->titles[$evaluationId]] = $statePercentage ? $statePercentage->evaluation_percentage . '%' : 'N/A';
                        $stateData['Negeri'] = $statePercentage ? $statePercentage->bnmState->description : 'Unknown';
                    }
                    $tableData[] = $stateData;
                }

                // Add the last row for all other states
                $otherStatesData = ['No.' => $rowNumber, 'Negeri' => 'LAIN-LAIN NEGERI'];
                foreach (range(1, 5) as $evaluationId) {
                    $otherStatesPercentage = $eval_pctgs->whereNotIn('state_code', $differentStateCodes)
                        ->where('evaluation_id', $evaluationId)
                        ->first();
                    $otherStatesData[$this->titles[$evaluationId]] = $otherStatesPercentage ? $otherStatesPercentage->evaluation_percentage . '%' : 'N/A';
                }
                $tableData[] = $otherStatesData;
            }

            $this->tableData = $tableData;
            $this->resultMount = true;

            if (!$allSame) {
                $this->differentStateCodes = $differentStateCodes;
                $this->diff_pctgs = $eval_pctgs;
            }
        } else {
            $this->noPreviousData = true;
            $this->resultMount = true;
            $this->tableData = [array_merge(['No.', 'Negeri'], array_values($this->titles))];
        }
    }

    public function generate()
    {
        $nextMonth = Carbon::now()->addMonth()->startOfMonth();

        $this->validate([
            'type' => [
                'required',
                'in:1,2',
            ],
            'effective_date' => [
                'required',
                'date',
                function ($attribute, $value, $fail) use ($nextMonth) {
                    if (Carbon::parse($value)->lt($nextMonth)) {
                        $fail("Tarikh kuatkuasa mestilah " . $nextMonth->format('F Y') . " atau selepasnya.");
                    }
                },
            ],
        ], [
            'type.required' => 'Sila pilih jenis terlebih dahulu.',
            'type.in' => 'Jenis yang dipilih tidak sah.',
            'effective_date.required' => 'Sila pilih tarikh kuatkuasa.',
            'effective_date.date' => 'Tarikh kuatkuasa mestilah tarikh yang sah.',
        ]);

        if ($this->type == 2) {

            $this->validate([
                'negeri' => 'required',
            ], [
                'negeri.required' => 'Sila pilih negeri terlebih dahulu.',
            ]);
        }


        if ($this->effective_date) {
            $this->resultMount = false;
            $this->effective_date = Carbon::parse($this->effective_date)->endOfMonth()->format('Y-m-d');
            if ($this->type == 2) {
                if ($this->negeri) {
                    $eval_pctgs = RefEvalPctg::where('state_code', $this->negeri)
                        ->where('effective_date', $this->effective_date)
                        ->get();

                    if ($eval_pctgs->isNotEmpty()) {
                        $this->noPreviousData = false;
                        $this->evaluation_criteria_percentage = [
                            1 => $eval_pctgs->where('evaluation_id', 1)->first()->evaluation_percentage ?? 0,
                            2 => $eval_pctgs->where('evaluation_id', 2)->first()->evaluation_percentage ?? 0,
                            3 => $eval_pctgs->where('evaluation_id', 3)->first()->evaluation_percentage ?? 0,
                            4 => $eval_pctgs->where('evaluation_id', 4)->first()->evaluation_percentage ?? 0,
                            5 => $eval_pctgs->where('evaluation_id', 5)->first()->evaluation_percentage ?? 0
                        ];

                        // Log the retrieved data for debugging
                        Log::info("Retrieved percentages for state: {$this->negeri}, date: {$this->effective_date}", [
                            'percentages' => $this->evaluation_criteria_percentage
                        ]);
                    } else {
                        $this->evaluation_criteria_percentage = [
                            1 => 0,
                            2 => 0,
                            3 => 0,
                            4 => 0,
                            5 => 0
                        ];
                        $this->noPreviousData = true;
                    }
                    $this->result = true;
                }
            } else {
                $eval_pctgs = RefEvalPctg::where('effective_date', $this->effective_date)
                    ->whereNotIn('state_code', ['00', '15', '16', '99'])
                    ->with('bnmState')
                    ->get();

                if ($eval_pctgs->isNotEmpty()) {
                    $this->noPreviousData = false;
                    $allSame = true;
                    $firstPercentages = [];
                    $differentStates = [];

                    foreach (range(1, 5) as $evaluationId) {
                        $percentages = $eval_pctgs->where('evaluation_id', $evaluationId)
                            ->groupBy('state_code')
                            ->map(function ($group) {
                                return [
                                    'state' => $group->first()->bnmState->description,
                                    'percentage' => $group->first()->evaluation_percentage
                                ];
                            });

                        if ($percentages->unique('percentage')->count() > 1) {
                            $allSame = false;
                            $differentStates[$evaluationId] = $percentages->toArray();
                        }

                        $firstPercentages[$evaluationId] = $percentages->first()['percentage'];
                    }

                    if ($allSame) {
                        $this->evaluation_criteria_percentage = $firstPercentages;
                        $this->result = true;
                    } else {
                        $this->showConfirmationDialog($differentStates);
                    }
                } else {
                    $this->evaluation_criteria_percentage = [
                        1 => 0,
                        2 => 0,
                        3 => 0,
                        4 => 0,
                        5 => 0
                    ];
                    $this->noPreviousData = true;
                    $this->result = true;
                }
            }
        }
    }

    private function showConfirmationDialog($differentStates)
    {
        $message = "<p>Terdapat perbezaan peratusan kriteria antara negeri-negeri berikut:</p>";
        $message .= "<ul class='space-y-2 mt-2'>";

        foreach ($differentStates as $evaluationId => $states) {
            $title = $this->titles[$evaluationId] ?? "Kriteria $evaluationId";
            $message .= "<li>";
            $message .= "<strong>{$evaluationId}. {$title}:</strong>";
            $message .= "<ul class='list-disc list-inside pl-4 mt-1'>";

            // Find the most common percentage
            $percentages = array_column($states, 'percentage');
            $percentageCounts = array_count_values($percentages);
            arsort($percentageCounts);
            $majorityPercentage = key($percentageCounts);

            foreach ($states as $state) {
                $percentageDisplay = $state['percentage'] == $majorityPercentage
                    ? "{$state['percentage']}%"
                    : "<strong class='text-red-600'>{$state['percentage']}%</strong>";

                $message .= "<li>{$state['state']}: <span class='font-semibold'>{$percentageDisplay}</span></li>";
            }
            $message .= "</ul>";
            $message .= "</li>";
        }

        $message .= "</ul>";
        $message .= "<p class='mt-4'>Adakah anda ingin meneruskan?</p>";

        $this->dialog()->confirm([
            'title'       => 'Peratusan Tidak Sama',
            'description' => $message,
            'icon'        => 'question',
            'html'        => true,
            'accept'      => [
                'label'  => 'Ya',
                'method' => 'continueWithZeroPercentages',
            ],
            'reject' => [
                'label'  => 'Tidak',
                'method' => 'cancelOperation',
            ],
        ]);
    }

    public function continueWithZeroPercentages()
    {
        $this->evaluation_criteria_percentage = [
            1 => 0,
            2 => 0,
            3 => 0,
            4 => 0,
            5 => 0
        ];
        $this->result = true;
    }

    public function cancelOperation()
    {
        $this->reset(['evaluation_criteria_percentage', 'result']);
    }

    public function saveEvaluationCriteriaPercentage()
    {
        $this->validate([
            'type' => 'required|in:1,2',
            'effective_date' => 'required|date',
            'evaluation_criteria_percentage.*' => 'required|numeric|min:0|max:100',
        ]);

        if ($this->type == 2) {
            $this->validate(['negeri' => 'required']);
        }

        try {
            DB::beginTransaction();

            // Log the data before saving
            Log::info("Attempting to save percentages", [
                'type' => $this->type,
                'negeri' => $this->negeri ?? 'All',
                'effective_date' => $this->effective_date,
                'percentages' => $this->evaluation_criteria_percentage
            ]);

            if ($this->type == 1) {
                // For type 1, update or create records for all states
                $states = BnmStatecode::whereNotIn('code', ['00', '15', '16', '99'])->pluck('code');
                foreach ($states as $stateCode) {
                    foreach ($this->evaluation_criteria_percentage as $evaluationId => $percentage) {
                        RefEvalPctg::updateOrCreate(
                            [
                                'STATE_CODE' => $stateCode,
                                'EFFECTIVE_DATE' => $this->effective_date,
                                'EVALUATION_ID' => $evaluationId,
                            ],
                            [
                                'EVALUATION_PERCENTAGE' => $percentage,
                            ]
                        );
                    }
                }
            } else {
                // For type 2, update or create for the selected state
                foreach ($this->evaluation_criteria_percentage as $evaluationId => $percentage) {
                    RefEvalPctg::updateOrCreate(
                        [
                            'STATE_CODE' => $this->negeri,
                            'EFFECTIVE_DATE' => $this->effective_date,
                            'EVALUATION_ID' => $evaluationId,
                        ],
                        [
                            'EVALUATION_PERCENTAGE' => $percentage,
                        ]
                    );
                }
            }

            DB::commit();

            $this->dialog()->success(
                $title = 'Berjaya Disimpan',
                $description = 'Peratusan kriteria penilaian telah berjaya disimpan.'
            );

            // Log the data after saving
            Log::info("Percentages saved successfully", [
                'type' => $this->type,
                'negeri' => $this->negeri ?? 'All',
                'effective_date' => $this->effective_date,
                'percentages' => $this->evaluation_criteria_percentage
            ]);

            $this->reset(['result', 'noPreviousData']);
            $this->generate(); // Refresh the data

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error in saveEvaluationCriteriaPercentage: " . $e->getMessage(), [
                'type' => $this->type,
                'negeri' => $this->negeri ?? 'All',
                'effective_date' => $this->effective_date,
                'evaluation_criteria_percentage' => $this->evaluation_criteria_percentage,
            ]);

            $this->dialog()->error(
                $title = 'Ralat',
                $description = 'Terdapat ralat semasa menyimpan data. Sila cuba lagi.'
            );
        }
    }

    public function render()
    {
        $stateSelection = BnmStatecode::whereNotIn('code', ['00', '15', '16', '99'])
            ->orderBy('code', 'ASC')
            ->get();

        return view('livewire.module.tetapan.peratusan-kriteria', [
            'stateSelection' => $stateSelection
        ])->extends('layouts.main');
    }
}
