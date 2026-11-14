<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class CardGenerator
{
    // Card BINs (Bank Identification Numbers)
    private static $bins = [
        'Visa' => ['400000', '410000', '420000', '430000'],
        'Mastercard' => ['510000', '520000', '530000', '540000', '550000'],
        'American Express' => ['340000', '370000'],
        'Discover' => ['601100', '622126', '644000', '645000', '646000', '647000', '648000', '649000', '650000'],
    ];

    /**
     * Generate a valid card number using Luhn algorithm
     */
    public static function generateCardNumber($type = 'Visa')
    {
        $bins = self::$bins[$type] ?? self::$bins['Visa'];
        $bin = $bins[array_rand($bins)];
        
        // Generate 9 random digits (16 total - 6 BIN - 1 check digit)
        $baseNumber = $bin . str_pad(mt_rand(0, 999999999), 9, '0', STR_PAD_LEFT);
        
        // Calculate and append check digit using Luhn algorithm
        $checkDigit = self::calculateLuhnCheckDigit($baseNumber);
        
        return $baseNumber . $checkDigit;
    }

    /**
     * Calculate Luhn check digit
     */
    private static function calculateLuhnCheckDigit($number)
    {
        $sum = 0;
        $length = strlen($number);
        
        for ($i = 0; $i < $length; $i++) {
            $digit = (int) $number[$length - $i - 1];
            
            if ($i % 2 === 1) {
                $digit *= 2;
                if ($digit > 9) {
                    $digit -= 9;
                }
            }
            
            $sum += $digit;
        }
        
        return (10 - ($sum % 10)) % 10;
    }

    /**
     * Validate card number using Luhn algorithm
     */
    public static function validateCardNumber($number)
    {
        $number = preg_replace('/\D/', '', $number);
        $sum = 0;
        $length = strlen($number);
        
        for ($i = 0; $i < $length; $i++) {
            $digit = (int) $number[$length - $i - 1];
            
            if ($i % 2 === 1) {
                $digit *= 2;
                if ($digit > 9) {
                    $digit -= 9;
                }
            }
            
            $sum += $digit;
        }
        
        return $sum % 10 === 0;
    }

    /**
     * Generate CVV
     */
    public static function generateCVV($type = 'Visa')
    {
        $length = $type === 'American Express' ? 4 : 3;
        return str_pad(mt_rand(0, pow(10, $length) - 1), $length, '0', STR_PAD_LEFT);
    }

    /**
     * Generate expiry date (3 years from now)
     */
    public static function generateExpiry()
    {
        $month = str_pad(mt_rand(1, 12), 2, '0', STR_PAD_LEFT);
        $year = date('Y') + 3;
        
        return [
            'month' => $month,
            'year' => $year,
            'formatted' => $month . '/' . substr($year, -2)
        ];
    }

    /**
     * Generate unique card token
     */
    public static function generateToken()
    {
        return 'tok_' . strtoupper(Str::random(24));
    }

    /**
     * Get BIN from card number
     */
    public static function getBIN($cardNumber)
    {
        return substr($cardNumber, 0, 6);
    }

    /**
     * Get last four digits
     */
    public static function getLastFour($cardNumber)
    {
        return substr($cardNumber, -4);
    }

    /**
     * Encrypt PAN (Primary Account Number)
     */
    public static function encryptPAN($cardNumber)
    {
        return base64_encode(encrypt($cardNumber));
    }

    /**
     * Decrypt PAN
     */
    public static function decryptPAN($encryptedPAN)
    {
        try {
            return decrypt(base64_decode($encryptedPAN));
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Format card number with spaces
     */
    public static function formatCardNumber($cardNumber)
    {
        return trim(chunk_split($cardNumber, 4, ' '));
    }

    /**
     * Mask card number
     */
    public static function maskCardNumber($cardNumber)
    {
        return '**** **** **** ' . substr($cardNumber, -4);
    }

    /**
     * Get card type from BIN
     */
    public static function getCardTypeFromBIN($bin)
    {
        $bin = substr($bin, 0, 6);
        
        foreach (self::$bins as $type => $bins) {
            foreach ($bins as $typeBin) {
                if (substr($bin, 0, strlen($typeBin)) === $typeBin) {
                    return $type;
                }
            }
        }
        
        return 'Unknown';
    }
}
