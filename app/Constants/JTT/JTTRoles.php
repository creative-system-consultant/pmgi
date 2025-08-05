<?php

namespace App\Constants\JTT;

class JTTRoles
{
    const PENGERUSI = 1;
    const PENGERUSI_GANTIAN = 2;
    const AHLI_AHLI = 3;
    const AHLI_AHLI_GANTIAN = 4;
    const PEMBENTANG = 5;
    const URUSETIA = 6;
    const PENGERUSI_BERSAMA = 7;
    const AHLI_BERSAMA = 8;

    public static function getRoleName()
    {
        return [
            self::PENGERUSI => 'PENGERUSI',
            self::PENGERUSI_GANTIAN => 'PENGERUSI GANTIAN',
            self::AHLI_AHLI => 'AHLI-AHLI',
            self::AHLI_AHLI_GANTIAN => 'AHLI-AHLI GANTIAN',
            self::PEMBENTANG => 'PEMBENTANG',
            self::URUSETIA => 'URUSETIA',
            self::PENGERUSI_BERSAMA => 'PENGERUSI BERSAMA (PERHEBAT)',
            self::AHLI_BERSAMA => 'AHLI BERSAMA (PERHEBAT)',
        ];
    }
}