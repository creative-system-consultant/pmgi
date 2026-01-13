<?php

namespace App;

enum PmgiSessionStatus: int
{
    case Pending = 0;
    case Completed = 1;
    case Cancel = 2;
}
