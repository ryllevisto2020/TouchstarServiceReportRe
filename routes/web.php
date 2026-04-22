<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MachineController;

use App\Http\Controllers\ServiceController;
use App\Http\Middleware\isAuthEmployee;
use App\Http\Middleware\isLogin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

#Login Routes
Route::get('/login', [AuthController::class, 'LoginForm'])->name('login')->middleware(isLogin::class);
Route::post('/login/auth',[AuthController::class, 'LoginAuth'])->name('login.auth');

#Logout Route
Route::post("/logout",function(Request $request){
    if(Auth::guard('touchstaraccount')->check()){
        Auth::guard('touchstaraccount')->logout();
        return redirect()->route('login');
    }else{
        return Response('Unauthorized', 401);
    }
})->name('logout');
    
#Employee Routes
Route::get('/employee/register', [EmployeeController::class, 'index'])->name('employee.register')->middleware([isAuthEmployee::class]);
Route::post("/employee/add",[EmployeeController::class, 'addData'])->name('employee.add')->middleware([isAuthEmployee::class]);

#Client Routes
Route::get('/client/register', [AuthController::class, 'client'])->name('client.register');

#Machine Routes
Route::get('/machine', [MachineController::class, 'index'])->name('machines.index')->middleware([isAuthEmployee::class]);
Route::post('/machine', [MachineController::class, 'store'])->name('machines.store')->middleware([isAuthEmployee::class]);
Route::get('/machine/{machine}/details', [MachineController::class, 'getMachineDetails'])->name('machines.details')->middleware([isAuthEmployee::class]);
Route::get('/machine/{machine}/edit', [MachineController::class, 'edit'])->name('machines.edit')->middleware([isAuthEmployee::class]);
Route::put('/machine/{machine}', [MachineController::class, 'update'])->name('machines.update')->middleware([isAuthEmployee::class]);
Route::delete('/machine/{machine}', [MachineController::class, 'destroy'])->name('machines.destroy')->middleware([isAuthEmployee::class]);

#Service Report Routes
Route::get('/service', [ServiceController::class, 'report'])->name('service.report')->middleware([isAuthEmployee::class]);
Route::post('/service/add',[ServiceController::class, 'addReport'])->name('service.add')->middleware([isAuthEmployee::class]);

#History Routes
Route::get('/service/history', [ServiceController::class, 'history'])->name('service.history');
    