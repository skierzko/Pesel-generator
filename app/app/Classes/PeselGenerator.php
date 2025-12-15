<?php

namespace App\Classes;

use DateTime;

class PeselGenerator
{
    private int $year = 2000;
    private int $month;
    private int $day;
    private string $gender = 'M';

    public function __construct()
    {
        $this->month = (int) new DateTime()->format('n');
        $this->day = (int) (new DateTime()->format('j'));
    }

    public function setYear(int $year): void
    {
        $this->year = in_array($year, range(1800, 2200, step: 100)) ? $year : $this->year;
    }

    public function setMonth(int $month): void
    {
        $this->month = $month >= 1 && $month <= 12 ? $month : $this->month;
    }

    public function setDay(int $day): void
    {
        $daysInMonth = (int) (new DateTime("$this->year-$this->month-01"))->format('t');
        $this->day = $day >= 1 && $day <= $daysInMonth ? $day : $this->day;
    }

    public function setGender(string $gender): void
    {
        $this->gender = in_array($gender, ['M', 'F']) ? $gender : $this->gender;
    }

    public function generate(): string
    {
        return '111';
    }
}