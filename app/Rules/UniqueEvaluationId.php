<?php

namespace App\Rules;

use App\Models\RefEvalPctg;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueEvaluationId implements ValidationRule
{
    protected $stateCode, $effectiveDate;
    
    public function __construct($stateCode, $effectiveDate)
    {
        $this->stateCode = $stateCode;
        $this->effectiveDate = $effectiveDate;
    }
    
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $evaluationIdExist = RefEvalPctg::query()
        ->where([
            ['state_code', '=', $this->stateCode],
            ['effective_date', '=', $this->effectiveDate],
            [$attribute, '=', $value]
        ])
        ->exists();

        if($evaluationIdExist)
        {
            $fail('Penilaian telah wujud. Sila kemaskini penilaian sedia ada.');
        }
    }
}
