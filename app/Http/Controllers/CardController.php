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

    public function generate(Request $request)
    {
        $amount = $request->input("amount", 1);

        if (is_null($amount)) {
            return response()->json(["message" => "Please provide a value to amount."], 400);
        }

        $amount = intval($amount);
        if ($amount < 1 || $amount > 100) {
            return response()->json(["message" => "Amount must be between 1 and 100."], 400);
        }

        $numbers = [];
        while (count($numbers) < $amount) {
            $number = $this->generateRandomCardNumber();
            if ($this->luhn($number)) {
                $numbers[] = $number;
            }
        }
        return response()->json([
            "amount" => $amount,
            "numbers" => $numbers,
        ], 200);

    }

    private function generateRandomCardNumber(): string
    {
        $length = rand(13, 19);
        $number = "";
        for ($i = 1; $i <= $length; $i++) {
            $number .= rand(0, 9);
        }
        return $number;
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
