<?php

namespace App\Constants\PMGI;

class PmgiCancelReason
{
    const TUKAR_PYD = 1;
    const TERSALAH_PYD = 2;

    public static function getReasonList()
    {
        return [
            self::TUKAR_PYD => 'TUKAR PYD',
            self::TERSALAH_PYD => 'TERSALAH PILIH PYD',
        ];
    }
}