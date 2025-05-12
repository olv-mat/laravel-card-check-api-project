<?php

namespace App\Http\Controllers;

use App\HandleResponse;
use App\Events\ResourceConsumed;
use App\Http\Controllers\Controller;
use App\Http\Requests\CardRequest;
use Illuminate\Http\Request;

class CardController extends Controller
{
    use HandleResponse;

    public function check(CardRequest $request)
    {
        $userId = $request->user()->id;
        $resource = "card/check";

        $number = $request->number;
        $message = ["is_valid" => $this->luhn($number)];
        
        return $this->dispatchAndResponse($userId, $resource, 200, $message);
    }

    public function generate(Request $request)
    {
        $userId = $request->user()->id;
        $resource = "card/generate";

        $amount = $request->input("amount", 1);
    
        if (is_null($amount)) {
            $message = ["message" => "Please provide a value to amount."];
            return $this->dispatchAndResponse($userId, $resource, 400, $message);
        }

        $amount = intval($amount);
        if ($amount < 1 || $amount > 100) {
            $message = ["message" => "Amount must be between 1 and 100."];
            return $this->dispatchAndResponse($userId, $resource, 400, $message);
        }

        $numbers = [];
        while (count($numbers) < $amount) {
            $number = $this->generateRandomCardNumber();
            if ($this->luhn($number)) {
                $numbers[] = $number;
            }
        }

        $message = [
            "amount" => $amount,
            "numbers" => $numbers,
        ];

        return $this->dispatchAndResponse($userId, $resource, 200, $message); 
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
