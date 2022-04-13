<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Site\IndexController as SiteIndexController;
use App\Http\Controllers\Site\BlogController as SiteBlogController;

use App\Http\Controllers\Admin\IndexController as AdminIndexController;
use App\Http\Controllers\Admin\BlogController as AdminBlogController;
use App\Http\Controllers\Admin\BannerController as AdminBannerController;
use App\Http\Controllers\Admin\GuideController as AdminGuideController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Auth\AccountController as AuthAccountController;
use App\Support\Message;

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

Route::get("/", [SiteIndexController::class, "index"])->name("site.index");
Route::get("/sobre", [SiteIndexController::class, "about"])->name("site.about");
Route::post("/contato", [SiteIndexController::class, "contact"])->name("site.contact");
Route::prefix('blog')->group(function () {
    Route::get("/", [SiteBlogController::class, "index"])->name("site.blog.index");
});
Route::get('/profile', [AuthAccountController::class, "profile"])->name("auth.account.profile");
Route::post('/profile/update', [AuthAccountController::class, "update"])->name("auth.profile.update");

Route::prefix("panel")->group(function () {
    Route::get("/", [AdminIndexController::class, "index"])->name("admin.index");
    Route::get("/profile", [AdminIndexController::class, "profile"])->name("admin.profile");
    Route::post("/notifications", [AdminIndexController::class, "notifications"])->name("admin.notifications");

    Route::get("/guide/icons", [AdminGuideController::class, "icons"])->name("admin.guide.icons");

    /**
     * Admin Users Controller
     */
    Route::get("/users", [AdminUserController::class, "index"])->name("admin.users.index");
    Route::get("/user/edit/{id}", [AdminUserController::class, "edit"])->name("admin.users.edit");
    Route::post("/user/update/{id}", [AdminUserController::class, "update"])->name("admin.users.update");
    Route::post("/user/delete/{id}", [AdminUserController::class, "destroy"])->name("admin.users.destroy");
    Route::post("/users/filter", [AdminUserController::class, "filter"])->name("admin.users.filter");
    Route::post("/user/promote/{id}", [AdminUserController::class, "promote"])->name("admin.users.promote");
    Route::post("/user/demote/{id}", [AdminUserController::class, "demote"])->name("admin.users.demote");
    Route::post("/user/ban/{id}", [AdminUserController::class, "ban"])->name("admin.users.ban");
    Route::post("/user/unban/{id}", [AdminUserController::class, "unban"])->name("admin.users.unban");
    Route::post("/user/bans/{id}", [AdminUserController::class, "bans"])->name("admin.users.bans");

    /**
     * Admin Banners Controller
     */
    Route::get("/banners", [AdminBannerController::class, "index"])->name("admin.banners.index");
    Route::post("/banners/filter", [AdminBannerController::class, "filter"])->name("admin.banners.filter");
    Route::get("/banner/new", [AdminBannerController::class, "create"])->name("admin.banners.create");
    Route::post("/banner/new", [AdminBannerController::class, "store"])->name("admin.banners.store");
    Route::get("/banner/edit/{banner}", [AdminBannerController::class, "edit"])->name("admin.banners.edit");
    Route::post("/banner/destroy/{banner}", [AdminBannerController::class, "destroy"])->name("admin.banners.destroy");

    Route::post("/banner/{banner}/new-element", [AdminBannerController::class, "storeElement"])
        ->name("admin.banners.storeElement");

    Route::get("/banner/edit/{banner}/{bannerElement}", [AdminBannerController::class, "editElement"])
        ->name("admin.banners.editElement");

    Route::post("/banner/update-element/{bannerElement}", [AdminBannerController::class, "updateElement"])
        ->name("admin.banners.updateElement");
    Route::post("/banner/destroy-element/{bannerElement}", [AdminBannerController::class, "destroyElement"])
        ->name("admin.banners.destroyElement");

    /**
     * Admin Blog Controller
     */
    Route::get("/blog/categories", [AdminBlogController::class, "categories"])->name("admin.blog.categories");
    Route::get("/blog/articles", [AdminBlogController::class, "articles"])->name("admin.blog.articles");
    Route::get("/blog/comments", [AdminBlogController::class, "comments"])->name("admin.blog.comments");

    Route::get("/pages", [AdminIndexController::class, "pages"])->name("admin.pages");
    Route::get("/faq", [AdminIndexController::class, "faq"])->name("admin.faq");
});

Auth::routes();

// Aviso de verificação de e-mail 
Route::get("/email/verificar", function (\Illuminate\Http\Request  $request) {
    if ($request->user()->email_verified_at) {
        Message::info(__("auth.emailAlreadyVerifiedMessage", [
            "firstName" => $request->user()->first_name,
            "date" => date("d/m/Y H:i", strtotime($request->user()->email_verified_at))
        ]), __("auth.emailVerifiedTitle"))->flash();
        return redirect()->route("site.index");
    }

    return view("auth.verify");
})->middleware("auth")->name("verification.notice");

// Verificação de e-mail 
Route::get("/email/verificar/{id}/{hash}", function (Illuminate\Foundation\Auth\EmailVerificationRequest $request) {
    $request->fulfill();

    Message::info(__("auth.emailVerifiedMessage", ["userName" => auth()->user()->first_name]), __("auth.emailVerifiedTitle"))->flash();

    return redirect("/");
})->middleware(["auth", "signed"])->name("verification.verify");

// Reenviando o e-mail de verificação 
Route::post("/email/verification-notification", function (Illuminate\Http\Request $request) {
    $request->user()->sendEmailVerificationNotification();

    Message::info(__("auth.verificationLinkResentMessage"), __("auth.verificationLinkResentTitle"))->flash();

    return back();
    // return back()->with("message", "Verification link sent!");
})->middleware(["auth", "throttle:6,1"])->name("verification.send");
