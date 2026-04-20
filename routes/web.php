<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MachineController;
use App\Http\Controllers\ServiceController;


    Route::get('/login', [AuthController::class, 'login'])->name('login');




    Route::get('/machine', [MachineController::class, 'index'])->name('machines.index');
    Route::post('/machine', [MachineController::class, 'store'])->name('machines.store');
    Route::get('/machine/{machine}/details', [MachineController::class, 'getMachineDetails'])->name('machines.details');
    Route::get('/machine/{machine}/edit', [MachineController::class, 'edit'])->name('machines.edit');
    Route::put('/machine/{machine}', [MachineController::class, 'update'])->name('machines.update');
    Route::delete('/machine/{machine}', [MachineController::class, 'destroy'])->name('machines.destroy');


    Route::get('/service', [ServiceController::class, 'report'])->name('service.report');