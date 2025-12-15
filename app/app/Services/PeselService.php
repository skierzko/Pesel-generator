<?php

namespace App\Services;

use App\Classes\PeselGenerator;
use Illuminate\Http\Request;

class PeselService
{
    private const array YEARS = [
        ['start' => 1800, 'end' => 1899],
        ['start' => 1900, 'end' => 1999],
        ['start' => 2000, 'end' => 2099],
        ['start' => 2100, 'end' => 2199],
        ['start' => 2200, 'end' => 2299],
    ];

    private const array MONTHS = [
        ['name' => 'January', 'value' => 1],
        ['name' => 'February', 'value' => 2],
        ['name' => 'March', 'value' => 3],
        ['name' => 'April', 'value' => 4],
        ['name' => 'May', 'value' => 5],
        ['name' => 'June', 'value' => 6],
        ['name' => 'July', 'value' => 7],
        ['name' => 'August', 'value' => 8],
        ['name' => 'September', 'value' => 9],
        ['name' => 'October', 'value' => 10],
        ['name' => 'November', 'value' => 11],
        ['name' => 'December', 'value' => 12],
    ];

    private const array GENDER = [
        ['name' => 'Male', 'value' => 'M'],
        ['name' => 'Female', 'value' => 'F'],
    ];

    public function getOptions(): array
    {
        return [
            'years' => self::YEARS,
            'months' => self::MONTHS,
            'gender' => self::GENDER,
            'days' => $this->getDays(),
        ];
    }

    private function getDays(): array
    {
        $currentMonthDays = (int) date('t');
        $days = [];
        for ($i = 1; $i <= $currentMonthDays; $i++) {
            $days[] = [
                'name' => $i,
                'value' => $i,
            ];
        }
        return $days;
    }

    public function generate(Request $request): string
    {
        $peselGenerator = new PeselGenerator();
        $peselGenerator->setYear($request->input('year'));
        $peselGenerator->setMonth($request->input('month'));
        
        $peselGenerator->setDay($request->input('day'));
        $peselGenerator->setGender($request->input('gender'));
        return $peselGenerator->generate();
    }
}