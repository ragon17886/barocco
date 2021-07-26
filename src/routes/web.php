<?php

use App\Models\Url;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\Shop\OrderController;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\DebugController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\InfoPageController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Shop\CartController;
use App\Http\Controllers\Shop\CatalogController;
use App\Http\Controllers\Shop\ProductController;

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

// Route::domain(env('APP_NAME') . '.{domain}')->group(function () {
//     Route::get('/domain', function ($domain) {
//         dd($domain);
//     });
// });


// дописать права только для админа
// вообще в админку перенести !!!
Route::prefix('clear-cache')->group(function () {
    Route::get('/app', function () {
        Artisan::call('cache:clear');
        return 'App cache is cleared';
    });
    // ...
    Route::get('/all', function () {
        Artisan::call('cache:clear');
        Artisan::call('route:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');
        Cache::flush();
        return 'All cache is cleared';
    });
});
Route::view('/test', 'test');
Route::get('debug', [DebugController::class, 'index']);
Route::get('debug-sentry', function () {
    throw new Exception('Debug Sentry error!');
});


Route::get('/', [IndexController::class, 'index'])->name('index-page');

Route::get('online-shopping/{slug?}', [InfoPageController::class, 'index'])->name('info');

Route::view('shops', 'static.shops')->name('static-shops');

Auth::routes(['verify' => true]);

Route::get('feedbacks/{type?}', [FeedbackController::class, 'index'])->name('feedbacks');
Route::post('feedbacks', [FeedbackController::class, 'store'])->name('feedbacks.store');

// dashboard
Route::prefix('dashboard')->middleware('auth')->group(function () {
    Route::view('saved', 'dashboard.saved')->name('dashboard-saved');
    Route::get('profile', [DashboardController::class, 'getProfileData'])->name('dashboard-profile');
    Route::patch('profile/{user}/update', [DashboardController::class, 'updateProfileData'])->name('dashboard-profile-update');
    Route::view('card', 'dashboard.card')->name('dashboard-card');
    Route::get('{orders?}', function () { return redirect()->route('orders.index'); });
});

Route::post('currency/switch', [CurrencyController::class, 'switch'])->name('currency-switcher');

Route::group(['namespace' => 'Shop'], function () {
    Route::post('/quick/{id}', [ProductController::class, 'quickView'])->name('product.quick');

    Route::get('catalog/{path?}', function () {
        $request = Route::getCurrentRequest();
        $slug = Str::of($request->path())->explode('/')->last();
        $url = Url::search($slug);

        if (isset($url) && (new $url['model_type']) instanceof App\Models\Product) {
            return (new ProductController())->show($url, $request->input());
        }
        return (new CatalogController())->show($request);
    })
        ->where('path', '[a-zA-Z0-9/_-]+')
        ->name('shop');

    Route::prefix('cart')->group(function () { // routes for cart pages
        Route::get('/', [CartController::class, 'index'])->name('cart');
        Route::post('add', [CartController::class, 'addToCart'])->name('cart-add');
        Route::get('delete/{item}', [CartController::class, 'delete'])->name('cart-delete');
        Route::post('buy-one-click', [CartController::class, 'buyOneClick'])->name('cart-buy-one-click');
        Route::get('final', [CartController::class, 'final'])->name('cart-final');
    });
    Route::resource('orders', OrderController::class)->only('store');
    Route::resource('orders', OrderController::class)->only('index')->middleware('auth');
});

// sitemap
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap.index');
Route::get('/sitemap.static.xml', [SitemapController::class, 'static'])->name('sitemap.static');
Route::get('/sitemap.products.xml', [SitemapController::class, 'products'])->name('sitemap.products');
Route::get('/sitemap.catalog.categories.xml', [SitemapController::class, 'categories'])->name('sitemap.catalog.categories');
Route::get('/sitemap.catalog.brands.xml', [SitemapController::class, 'brands'])->name('sitemap.catalog.brands');
Route::get('/sitemap.catalog.categories_and_{another}_and_{another2}.xml', [SitemapController::class, 'catalog3'])->name('sitemap.catalog.catalog3');
Route::get('/sitemap.catalog.categories_and_{another}.xml', [SitemapController::class, 'catalog2'])->name('sitemap.catalog.catalog2');

Route::fallback(function () {
    return 'Хм… Почему ты оказался здесь?';
});