<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('dashboard.index'))->name('dashboard');
Route::get('/members', fn () => view('members.index'))->name('members.index');
Route::get('/events', fn () => view('events.index'))->name('events.index');
Route::get('/finance', fn () => view('finance.index'))->name('finance.index');
Route::get('/procurement', fn () => view('procurement.index'))->name('procurement.index');
Route::get('/assets', fn () => view('assets.index'))->name('assets.index');
Route::get('/communication', fn () => view('communication.index'))->name('communication.index');
Route::get('/reports', fn () => view('reports.index'))->name('reports.index');
