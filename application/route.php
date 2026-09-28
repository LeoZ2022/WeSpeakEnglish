<?php
use think\facade\Route;

// PayPal IPN (legacy) — keep temporarily
Route::post('index/payment/notify', 'index/payment/notify')->ext('');

// PayPal Webhook (new)
Route::post('index/payment/webhook', 'index/payment/webhook')->ext('');
