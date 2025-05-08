<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\{
    CardRequest
};

class CardController extends Controller
{
    public function check(CardRequest $request)
    {
        $number = $request->number;
        return response()->json(["is_valid" => $this->luhn($number)], 200);
    }

    private function luhn(string $number): bool
    {
        $digits = str_split($number);
        $reversedDigits = [];
        foreach (array_reverse($digits) as $digit) {
            $reversedDigits[] = intval($digit);
        }
        $processedDigits = [];
        foreach ($reversedDigits as $i => $digit) {
            if ($i % 2 != 0) {
                $double = $digit * 2;
                $processedDigits[] = strval($double);
            } else {
                $processedDigits[] = strval($digit);
            }
        }
        $adjustedDigits = [];
        foreach ($processedDigits as $digit) {
            if (strlen($digit) == 2) {
                $sum = 0;
                foreach (str_split($digit) as $char) {
                    $sum += intval($char);
                }
                $adjustedDigits[] = $sum;
            } else {
                $adjustedDigits[] = intval($digit);
            }
        }
        $amount = array_sum($adjustedDigits);
        if ($amount % 10 == 0) {
            return true;
        }
        return false;
    }
}
