<?php

use Illuminate\Support\Facades\Route;

// Simple test route to check if form submission works
Route::post('/test-form', function(\Illuminate\Http\Request $request) {
    \Log::info('Test form submission received', [
        'data' => $request->all(),
        'user' => $request->user() ? $request->user()->id : 'not authenticated'
    ]);
    
    return response()->json([
        'status' => 'success',
        'message' => 'Form data received',
        'data' => $request->all()
    ]);
});

// Include this in web.php
