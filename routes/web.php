<?php

use Inertia\Inertia;
use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', function (Request $request) {
            $request->session()->flash('success', 'Login berhasil!');
            return Inertia::render('Dashboard');
        })->name('dashboard');

        Route::get('/photos', function () {
            return Inertia::render('Admin/Photos', [
                'photos' => Photo::all(),
            ]);
        })->name('photos');

        Route::get('/photos/create', function () {
            return Inertia::render('Admin/PhotosCreate');
        })->name('photos.create');

        Route::post('/photos', function (Request $request) {
            $validated_data = $request->validate([
                'path' => ['required', 'image', 'max:2500'],
                'description' => ['required'],
            ]);

            $path = Storage::disk('public')->put('photos', $request->file('path'));
            $validated_data['path'] = $path;

            Photo::create($validated_data);
            return to_route('admin.photos');
        })->name('photos.store');

        Route::get('/photos/{photo}/edit', function (Photo $photo) {
            return Inertia::render('Admin/PhotosEdit', [
                'photo' => $photo,
            ]);
        })->name('photos.edit');

        Route::put('/photos/{photo}', function (Request $request, Photo $photo) {
            $validated_data = $request->validate([
                'description' => ['required'],
            ]);

            if ($request->hasFile('path')) {
                $validated_data['path'] = $request->validate([
                    'path' => ['required', 'image', 'max:1500'],
                ]);

                $oldImage = $photo->path;
                Storage::delete($oldImage);

                $path = Storage::disk('public')->put('photos', $request->file('path'));
                $validated_data['path'] = $path;
            }

            $photo->update($validated_data);
            return to_route('admin.photos');
        })->name('photos.update');

        Route::delete('/photos/{photo}', function (Photo $photo) {
            Storage::delete($photo->path);
            $photo->delete();
            return to_route('admin.photos');
        })->name('photos.delete');
    });

Route::get('/photos', function () {
    return Inertia::render('Guest/Photos', [
        'photos' => Photo::all(),
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
});