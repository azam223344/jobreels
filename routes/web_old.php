<?php

use App\Http\Livewire\Adoptions\AdoptionsIndexComponent;
use App\Http\Livewire\Authentication\LoginComponent;
use App\Http\Livewire\Breeds\BreedsEditComponent;
use App\Http\Livewire\Breeds\BreedsIndexComponent;
use App\Http\Livewire\Colors\ColorsEditComponent;
use App\Http\Livewire\Colors\ColorsIndexComponent;
use App\Http\Livewire\Complaints\ComplaintsIndexComponent;
use App\Http\Livewire\Dashboard\DashboardComponent;
use App\Http\Livewire\FlaggedContent\FlaggedContentDetailComponent;
use App\Http\Livewire\FlaggedContent\FlaggedContentIndexComponent;
use App\Http\Livewire\Posts\PostsIndexComponent;
use App\Http\Livewire\Posts\PostViewComponent;
use App\Http\Livewire\States\StatesEditComponent;
use App\Http\Livewire\States\StatesIndexComponent;
use App\Http\Livewire\FreelancerList;
use App\Http\Livewire\Freelancers\PendingFreelancersComponent;
use App\Http\Livewire\Freelancers\FreelancerDetailsComponent;
use App\Http\Livewire\Freelancers\FreelancersIndexComponent;
use App\Http\Livewire\HirerList;
use App\Http\Livewire\Hirers\HirerDetailsComponent;
use App\Http\Livewire\Hirers\HirersIndexComponent;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BreedsController;
use App\Http\Controllers\Api\StatesController;

use App\Http\Livewire\Packages\PackageEditComponent;
use App\Http\Livewire\Packages\PackageIndexComponent;
use App\Http\Controllers\Api\PackageController;
use App\Http\Controllers\WebviewController;
/*

|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', LoginComponent::class)->name('login');
Route::middleware(['auth'])->group(function () {
Route::get('/dashboard', DashboardComponent::class);
});

Route::get('/login', function () {
    return view('login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/freelancers/list', FreelancersIndexComponent::class);
    Route::get('/freelancers/detail', FreelancerDetailsComponent::class);

    Route::get('/freelancers/pending-approval', PendingFreelancersComponent::class);

    Route::get('/hirers/list', HirersIndexComponent::class);
    Route::get('/hirers/detail', HirerDetailsComponent::class);

    // Breed Routes
    Route::get('/breeds/list', BreedsIndexComponent::class);
    Route::get('/breeds/edit', BreedsEditComponent::class);
    Route::get('/breeds/delete', [BreedsController::class, 'delete']);

    // Colors Routes
    Route::get('/adoptions/list', AdoptionsIndexComponent::class);

    // Colors Routes
    Route::get('/colors/list', ColorsIndexComponent::class);
    Route::get('/colors/edit', ColorsEditComponent::class);

    // States Routes
    Route::get('/states/list', StatesIndexComponent::class);
    Route::get('/states/edit', StatesEditComponent::class);
    Route::get('/states/delete', [StatesController::class, 'delete']);

    Route::get('/packages/list', PackageIndexComponent::class);
    Route::get('/packages/edit', PackageEditComponent::class);
    Route::get('/packages/delete', [PackageController::class, 'delete']);

    // Posts Routes
    Route::get('/posts/list', PostsIndexComponent::class);
    Route::get('/posts/view', PostViewComponent::class);

    // Flagged Content Routes
    Route::get('/posts/flagged', FlaggedContentIndexComponent::class);
    Route::get('/posts/flagged/detail', FlaggedContentDetailComponent::class);

    Route::get('/complaints/list', ComplaintsIndexComponent::class);
});

Route::post('paddle/webhook1', '\App\Http\Controllers\WebhookController');
Route::get('/webview-privacy-policy', [WebviewController::class, 'privacy_policy']);
Route::get('/webview-feedback', [WebviewController::class, 'feedback']);
Route::get('/terms-of-use', [WebviewController::class, 'term_of_use']);
