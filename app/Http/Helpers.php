<?php

if (!function_exists('format_idr')) {
    function format_idr($amount, $decimal = 0)
    {
        return "Rp " . number_format($amount, $decimal, ',', '.');
    }
}

