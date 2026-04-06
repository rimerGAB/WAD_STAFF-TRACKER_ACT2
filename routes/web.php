<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use App\Models\Employee;
use Inertia\Inertia;

Route::inertia('/', 'welcome', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';


Route::get('/employees', function () {
    $employees = Employee::with(['department', 'profile', 'projects'])->get();
    
    return Inertia::render('Employees/Index', [
        'employees' => $employees
    ]);
})->name('employees.index');