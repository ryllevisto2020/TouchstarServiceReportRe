<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MachineController;

use App\Http\Controllers\ServiceController;

use App\Http\Middleware\isLogin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

    Route::get('/login', [AuthController::class, 'LoginForm'])->name('login')->middleware(isLogin::class);
    Route::post('/login/auth',[AuthController::class, 'LoginAuth'])->name('login.auth');

    Route::post("/logout",function(Request $request){
        if(Auth::guard('touchstaraccount')->check()){
            Auth::guard('touchstaraccount')->logout();
            return redirect()->route('login');
        }else{
            return Response('Unauthorized', 401);
        }
    })->name('logout');
    

    Route::get('/employee/register', [AuthController::class, 'register'])->name('employee.register');

    Route::get('/client/register', [AuthController::class, 'client'])->name('client.register');


    Route::get('/machine', [MachineController::class, 'index'])->name('machines.index')->middleware();
    Route::post('/machine', [MachineController::class, 'store'])->name('machines.store');
    Route::get('/machine/{machine}/details', [MachineController::class, 'getMachineDetails'])->name('machines.details');
    Route::get('/machine/{machine}/edit', [MachineController::class, 'edit'])->name('machines.edit');
    Route::put('/machine/{machine}', [MachineController::class, 'update'])->name('machines.update');
    Route::delete('/machine/{machine}', [MachineController::class, 'destroy'])->name('machines.destroy');


    Route::get('/service', [ServiceController::class, 'report'])->name('service.report');
    