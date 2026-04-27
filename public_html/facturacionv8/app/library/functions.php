<?php
if (!function_exists('custom_money_format')) {
    function custom_money_format($amount) {
        return number_format($amount ?? 0, 2, '.', ',');
    }
}
?>