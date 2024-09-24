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
    public $nextDifferentStateCodes = [];
    public $type;
    public $negeri;
    public $diff_pctgs;
    public $nextDiff_pctgs;
    public $effective_date;
    public $next_effective_date;
    public $result = false;
    public $resultMount = false;
    public $nextResultMount = false;
    public $noPreviousData = false;
    public $evaluation_criteria_percentage = [
        1 => 0,
        2 => 0,
        3 => 0,
        4 => 0,
        5 => 0
    ];
    public $tableData = [];
    public $nextTableData = [];

    public function mount()
    {
        $this->retrieveInitialData();
        $this->retrieveNextEffectiveData();
    }

    public function retrieveInitialData()
    {
        $eval_pctgs = RefEvalPctg::whereNotIn('state_code', ['00', '15', '16', '99'])
            ->where('effective_date', function ($query) {
                $query->select('effective_date')
                    ->from('pmgi_ref_eval_pctg')
                    ->where('effective_date', '<=', now())
                    ->orderBy('effective_date', 'desc')
                    ->limit(1);
            })
            ->with('bnmState')
            ->orderBy('state_code', 'ASC')
            ->orderBy('evaluation_id', 'ASC')
            ->get();

        if ($eval_pctgs->isNotEmpty()) {
            $this->effective_date = $eval_pctgs->first()->effective_date;
            $this->noPreviousData = false;

            $statePercentages = []; // To store percentages per state
            $tableData = [];

            // Initialize the table header
            $tableData[] = array_merge(['No.', 'Negeri'], array_values($this->titles));

            // Collect percentages for each state
            foreach ($eval_pctgs->groupBy('state_code') as $stateCode => $stateData) {
                $percentages = [];
                foreach (range(1, 5) as $evaluationId) {
                    $percentage = $stateData->where('evaluation_id', $evaluationId)->first();
                    $percentages[$evaluationId] = $percentage ? $percentage->evaluation_percentage : null;
                }
                $statePercentages[$stateCode] = [
                    'state_code' => $stateCode,
                    'state_name' => $stateData->first()->bnmState->description,
                    'percentages' => $percentages,
                ];
            }

            $rowNumber = 1;
            $totalStates = count($statePercentages);

            // Calculate majority percentages for each evaluation
            $evaluationMajority = [];
            foreach (range(1, 5) as $evaluationId) {
                $percentages = array_column(array_column($statePercentages, 'percentages'), $evaluationId);
                $percentageCounts = array_count_values($percentages);
                arsort($percentageCounts);
                $majorityPercentage = key($percentageCounts);
                $evaluationMajority[$evaluationId] = $majorityPercentage;
            }

            // Identify states that match the majority percentages across all evaluations
            $statesMatchingMajority = [];
            $statesNotMatchingMajority = [];

            foreach ($statePercentages as $stateCode => $data) {
                $matchesMajority = true;
                foreach (range(1, 5) as $evaluationId) {
                    if ($data['percentages'][$evaluationId] != $evaluationMajority[$evaluationId]) {
                        $matchesMajority = false;
                        break;
                    }
                }
                if ($matchesMajority) {
                    $statesMatchingMajority[] = $data;
                } else {
                    $statesNotMatchingMajority[] = $data;
                }
            }

            // Only group under "LAIN-LAIN NEGERI" if totalStates == 14
            if ($totalStates == 14) {
                // If all states have the same percentages and total states is 14
                if (count($statesMatchingMajority) == $totalStates) {
                    // All states share the same percentages, display as "SEMUA NEGERI"
                    $stateData = ['No.' => $rowNumber++, 'Negeri' => 'SEMUA NEGERI'];
                    foreach (range(1, 5) as $evaluationId) {
                        $percentage = $evaluationMajority[$evaluationId];
                        $stateData[$this->titles[$evaluationId]] = $percentage !== null ? $percentage . '%' : 'N/A';
                    }
                    $tableData[] = $stateData;
                } else {
                    // Display individual states not matching the majority
                    foreach ($statesNotMatchingMajority as $data) {
                        $stateData = ['No.' => $rowNumber++, 'Negeri' => $data['state_name']];
                        foreach (range(1, 5) as $evaluationId) {
                            $percentage = $data['percentages'][$evaluationId];
                            $stateData[$this->titles[$evaluationId]] = $percentage !== null ? $percentage . '%' : 'N/A';
                        }
                        $tableData[] = $stateData;
                    }

                    // Group states matching the majority under "LAIN-LAIN NEGERI"
                    if (count($statesMatchingMajority) > 0) {
                        $stateData = ['No.' => $rowNumber++, 'Negeri' => 'LAIN-LAIN NEGERI'];
                        foreach (range(1, 5) as $evaluationId) {
                            $percentage = $evaluationMajority[$evaluationId];
                            $stateData[$this->titles[$evaluationId]] = $percentage !== null ? $percentage . '%' : 'N/A';
                        }
                        $tableData[] = $stateData;
                    }
                }
            } else {
                // Less than 14 states, display all states individually
                foreach ($statePercentages as $data) {
                    $stateData = ['No.' => $rowNumber++, 'Negeri' => $data['state_name']];
                    foreach (range(1, 5) as $evaluationId) {
                        $percentage = $data['percentages'][$evaluationId];
                        $stateData[$this->titles[$evaluationId]] = $percentage !== null ? $percentage . '%' : 'N/A';
                    }
                    $tableData[] = $stateData;
                }
            }

            $this->tableData = $tableData;
            $this->resultMount = true;
        } else {
            $this->noPreviousData = true;
            $this->resultMount = true;
            $this->tableData = [array_merge(['No.', 'Negeri'], array_values($this->titles))];
        }
    }

    public function retrieveNextEffectiveData()
    {
        $nextEffectiveData = RefEvalPctg::select('effective_date')
            ->where('effective_date', '>=', now())
            ->distinct()
            ->orderBy('effective_date', 'asc')
            ->first();

        if ($nextEffectiveData) {
            $nextEffectiveDate = $nextEffectiveData->effective_date;
            $eval_pctgs = RefEvalPctg::whereNotIn('state_code', ['00', '15', '16', '99'])
                ->where('effective_date', $nextEffectiveDate)
                ->with('bnmState')
                ->orderBy('state_code', 'ASC')
                ->orderBy('evaluation_id', 'ASC')
                ->get();

            if ($eval_pctgs->isNotEmpty()) {
                $this->next_effective_date = $eval_pctgs->first()->effective_date;

                $statePercentages = []; // To store percentages per state
                $tableData = [];

                // Initialize the table header
                $tableData[] = array_merge(['No.', 'Negeri'], array_values($this->titles));

                // Collect percentages for each state
                foreach ($eval_pctgs->groupBy('state_code') as $stateCode => $stateData) {
                    $percentages = [];
                    foreach (range(1, 5) as $evaluationId) {
                        $percentage = $stateData->where('evaluation_id', $evaluationId)->first();
                        $percentages[$evaluationId] = $percentage ? $percentage->evaluation_percentage : null;
                    }
                    $statePercentages[$stateCode] = [
                        'state_code' => $stateCode,
                        'state_name' => $stateData->first()->bnmState->description,
                        'percentages' => $percentages,
                    ];
                }

                $rowNumber = 1;
                $totalStates = count($statePercentages);

                // Calculate majority percentages for each evaluation
                $evaluationMajority = [];
                foreach (range(1, 5) as $evaluationId) {
                    $percentages = array_column(array_column($statePercentages, 'percentages'), $evaluationId);
                    $percentageCounts = array_count_values($percentages);
                    arsort($percentageCounts);
                    $majorityPercentage = key($percentageCounts);
                    $evaluationMajority[$evaluationId] = $majorityPercentage;
                }

                // Identify states that match the majority percentages across all evaluations
                $statesMatchingMajority = [];
                $statesNotMatchingMajority = [];

                foreach ($statePercentages as $stateCode => $data) {
                    $matchesMajority = true;
                    foreach (range(1, 5) as $evaluationId) {
                        if ($data['percentages'][$evaluationId] != $evaluationMajority[$evaluationId]) {
                            $matchesMajority = false;
                            break;
                        }
                    }
                    if ($matchesMajority) {
                        $statesMatchingMajority[] = $data;
                    } else {
                        $statesNotMatchingMajority[] = $data;
                    }
                }

                // Only group under "LAIN-LAIN NEGERI" if totalStates == 14
                if ($totalStates == 14) {
                    // If all states have the same percentages and total states is 14
                    if (count($statesMatchingMajority) == $totalStates) {
                        // All states share the same percentages, display as "SEMUA NEGERI"
                        $stateData = ['No.' => $rowNumber++, 'Negeri' => 'SEMUA NEGERI'];
                        foreach (range(1, 5) as $evaluationId) {
                            $percentage = $evaluationMajority[$evaluationId];
                            $stateData[$this->titles[$evaluationId]] = $percentage !== null ? $percentage . '%' : 'N/A';
                        }
                        $tableData[] = $stateData;
                    } else {
                        // Display individual states not matching the majority
                        foreach ($statesNotMatchingMajority as $data) {
                            $stateData = ['No.' => $rowNumber++, 'Negeri' => $data['state_name']];
                            foreach (range(1, 5) as $evaluationId) {
                                $percentage = $data['percentages'][$evaluationId];
                                $stateData[$this->titles[$evaluationId]] = $percentage !== null ? $percentage . '%' : 'N/A';
                            }
                            $tableData[] = $stateData;
                        }

                        // Group states matching the majority under "LAIN-LAIN NEGERI"
                        if (count($statesMatchingMajority) > 0) {
                            $stateData = ['No.' => $rowNumber++, 'Negeri' => 'LAIN-LAIN NEGERI'];
                            foreach (range(1, 5) as $evaluationId) {
                                $percentage = $evaluationMajority[$evaluationId];
                                $stateData[$this->titles[$evaluationId]] = $percentage !== null ? $percentage . '%' : 'N/A';
                            }
                            $tableData[] = $stateData;
                        }
                    }
                } else {
                    // Less than 14 states, display all states individually
                    foreach ($statePercentages as $data) {
                        $stateData = ['No.' => $rowNumber++, 'Negeri' => $data['state_name']];
                        foreach (range(1, 5) as $evaluationId) {
                            $percentage = $data['percentages'][$evaluationId];
                            $stateData[$this->titles[$evaluationId]] = $percentage !== null ? $percentage . '%' : 'N/A';
                        }
                        $tableData[] = $stateData;
                    }
                }

                $this->nextTableData = $tableData;
                $this->nextResultMount = true;
            }
        }
    }

    public function kemaskini()
    {
        $this->validate([
            'type' => [
                'required',
                'in:1,2',
            ],
        ], [
            'type.required' => 'Sila pilih jenis terlebih dahulu.',
            'type.in' => 'Jenis yang dipilih tidak sah.',
        ]);

        if ($this->type == 2) {
            $this->validate([
                'negeri' => 'required',
            ], [
                'negeri.required' => 'Sila pilih negeri terlebih dahulu.',
            ]);
        }

        $this->resultMount = false;
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

    public function saveEvaluationCriteriaPercentage()
    {
        $this->validate([
            'type' => 'required|in:1,2',
            'evaluation_criteria_percentage.*' => 'required|numeric|min:0|max:100',
        ]);

        if ($this->type == 2) {
            $this->validate(['negeri' => 'required']);
        }

        try {
            DB::beginTransaction();

            if ($this->type == 1) {
                // For type 1, update or create records for all states
                $states = BnmStatecode::whereNotIn('code', ['00', '15', '16', '99'])->pluck('code');
                foreach ($states as $stateCode) {
                    foreach ($this->evaluation_criteria_percentage as $evaluationId => $percentage) {
                        RefEvalPctg::updateOrCreate(
                            [
                                'STATE_CODE' => $stateCode,
                                'EFFECTIVE_DATE' => now()->addMonth()->endOfMonth(),
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
                            'EFFECTIVE_DATE' => now()->addMonth()->endOfMonth(),
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

            $this->reset(['type', 'negeri', 'result', 'noPreviousData']);
            $this->mount(); // Refresh the data

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error in saveEvaluationCriteriaPercentage: " . $e->getMessage(), [
                'type' => $this->type,
                'negeri' => $this->negeri ?? 'All',
                'effective_date' => now()->addMonth()->endOfMonth(),
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
