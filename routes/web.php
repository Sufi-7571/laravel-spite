<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Homepage redirects to products (which will redirect to login if not authenticated)
Route::get('/', function () {
    return redirect()->route('products.index');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// User Management Routes - Only for Admins
Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::resource('users', UserController::class);
});

// ALL Product Routes require authentication and email verification
Route::middleware(['auth', 'verified'])->group(function () {
    // List all products
    Route::get('/products', [ProductController::class, 'index'])
        ->name('products.index');
    
    // Trash routes (for viewing deleted products)
    // Route::middleware(['permission:delete products'])->group(function () {
    //     Route::get('/products/trash', [ProductController::class, 'trash'])
    //         ->name('products.trash');
    //     Route::post('/products/{id}/restore', [ProductController::class, 'restore'])
    //         ->name('products.restore');
    //     Route::delete('/products/{id}/force-delete', [ProductController::class, 'forceDelete'])
    //         ->name('products.forceDelete');
    // });
    
    // CREATE routes MUST come before {product} routes
    Route::middleware(['permission:create products'])->group(function () {
        Route::get('/products/create', [ProductController::class, 'create'])
            ->name('products.create');
        Route::post('/products', [ProductController::class, 'store'])
            ->name('products.store');
    });
    
    // Show single product (AFTER create route)
    Route::get('/products/{product}', [ProductController::class, 'show'])
        ->name('products.show');
    
    // EDIT routes (AFTER show route or use /products/{product}/edit pattern)
    Route::middleware(['permission:edit products'])->group(function () {
        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])
            ->name('products.edit');
        Route::put('/products/{product}', [ProductController::class, 'update'])
            ->name('products.update');
    });
    
    // DELETE route (now soft delete)
    Route::middleware(['permission:delete products'])->group(function () {
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])
            ->name('products.destroy');
    });
});

require __DIR__.'/auth.php';