<?php

namespace App\Classes;

use DateTime;

class PeselGenerator
{
    public function __construct(
        private int $year = 0,
        private int $month = 0,
        private int $day = 0,
        private string $gender = 'M'
    )
    {
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

    /**
     * [RRMMDD][SSSG][C]
     * @link: https://kalkulatory.gofin.pl/kalkulatory/sprawdzanie-pesel-weryfikacja-pesel
     */
    public function generate(): string
    {
        $this->setAllDataIfPossible();

        $year = $this->generateYearPart();
        $month = $this->getMonthPart();
        $day = $this->generateDayPart();
        $serialNumber = $this->generateSerialNumberPart();
        $genderDigit = $this->generateGenderDigit();
        $peselWithoutControlDigit = $year . $month . $day . $serialNumber . $genderDigit;

        return $peselWithoutControlDigit . $this->calculateControlDigit(str_split($peselWithoutControlDigit));
    }

    private function setAllDataIfPossible(): void
    {
        if ($this->year === 0) {
            $this->year = rand(1900, 2299);
        } else {
            $this->year = rand($this->year, $this->year + 99);
        }

        if ($this->month === 0) {
            $this->month = rand(1, 12);
        }

        if ($this->day === 0) {
            $daysInMonth = (int) (new DateTime("$this->year-$this->month-01"))->format('t');
            $this->day = rand(1, $daysInMonth);
        }

        if ($this->gender === 0) {
            $this->gender = rand(0, 1) === 0 ? 'M' : 'F';
        }
    }

    private function generateYearPart(): string
    {
        return str_pad((string) ($this->year % 100), 2, '0', STR_PAD_LEFT);
    }

    private function getMonthPart(): string
    {
        $month = $this->month;
        if ($this->year >= 1800 && $this->year <= 1899) {
            $month += 80;
        } elseif ($this->year >= 2000 && $this->year <= 2099) {
            $month += 20;
        } elseif ($this->year >= 2100 && $this->year <= 2199) {
            $month += 40;
        } elseif ($this->year >= 2200 && $this->year <= 2299) {
            $month += 60;
        }
        return str_pad((string) $month, 2, '0', STR_PAD_LEFT);
    }

    private  function generateSerialNumberPart(): string
    {
        $serialNumber = rand(0, 999);
        return str_pad((string) $serialNumber, 3, '0', STR_PAD_LEFT);
    }

    private function generateGenderDigit(): string
    {
        return $this->gender === 'M' ? rand(0, 4) * 2 + 1 : rand(0, 4) * 2;
    }

    private function generateDayPart(): string
    {
        return str_pad((string) $this->day, 2, '0', STR_PAD_LEFT);
    }

    private function calculateControlDigit(array $peselDigits): int
    {
        $weights = [1, 3, 7, 9, 1, 3, 7, 9, 1, 3];
        $sum = 0;

        foreach ($peselDigits as $index => $digit) {
            $sum += $digit * $weights[$index];
        }

        $modulo = $sum % 10;
        return $modulo === 0 ? 0 : 10 - $modulo;
    }

    private function validPesel(string $pesel): bool
    {
        if (strlen($pesel) !== 11 || !ctype_digit($pesel)) {
            return false;
        }

        $peselDigits = str_split($pesel);
        $controlDigit = (int) array_pop($peselDigits);
        $calculatedControlDigit = $this->calculateControlDigit($peselDigits);

        return $controlDigit === $calculatedControlDigit;
    }
}