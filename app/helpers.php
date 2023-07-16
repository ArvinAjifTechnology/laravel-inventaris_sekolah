<?php

if (!function_exists('convertToRupiah')) {
    function convertToRupiah($amount)
    {
        // if (is_array($amount)) {
        //     return array_map(function ($value) {
        //         if (is_numeric($value)) {
        //             return 'Rp ' . number_format($value, 0, ',', '.');
        //         }
        //         return $value;
        //     }, $amount);
        // }

        // if (is_numeric($amount)) {
        //     return 'Rp ' . number_format($amount, 0, ',', '.');
        // }

        // return $amount;

        // if (is_array($amount)) {
        //     return array_map(function ($value) {
        //         if (is_numeric($value)) {
        //             return 'Rp ' . number_format($value, 0, ',', '.');
        //         }
        //         return $value;
        //     }, $amount);
        // }

        // if (is_numeric($amount)) {
        //     return 'Rp ' . number_format($amount, 0, ',', '.');
        // }

        // return $amount;

        if (is_array($amount)) {
            return array_map(function ($value) {
                if (is_numeric($value)) {
                    return 'Rp ' . number_format($value, 0, ',', '.');
                }
                return $value;
            }, $amount);
        }

        if (is_numeric($amount)) {
            return 'Rp ' . number_format($amount, 0, ',', '.');
        }

        // Remove currency symbol and thousand separators
        $numericValue = str_replace(['Rp ', ','], ['', ''], $amount);

        return $numericValue;

        // if (is_array($amount)) {
        //     return array_map(function ($value) {
        //         number_format(floatval($value), 0, ',', '.');
        //     }, $amount);
        // }
        // // return 'Rp 0';
        // return 'Rp ' . number_format($amount, 0, ',', '.');
    }
}
