<?php

use App\Http\Controllers\Api\BreedsController;
use App\Http\Controllers\Api\ColorsController;
use App\Http\Controllers\Api\CommentsController;
use App\Http\Controllers\Api\ComplaintsController;
use App\Http\Controllers\Api\FlagController;
use App\Http\Controllers\Api\FlaggedContentController;
use App\Http\Controllers\Api\UserFlagController;
use App\Http\Controllers\Api\FlaggedUserController;
use App\Http\Controllers\Api\PostLikesController;
use App\Http\Controllers\Api\PostSavesController;
use App\Http\Controllers\Api\PostsController;
use App\Http\Controllers\Api\PostSearchController;
use App\Http\Controllers\Api\StatesController;
use App\Http\Controllers\Api\UsersController;
use App\Http\Controllers\Api\TransactionsController;
use App\Http\Controllers\Api\PackageController;
use App\Http\Controllers\Api\SubscriptionController;
use App\Http\Controllers\Api\ThreadsController;
use App\Http\Controllers\Api\MessagesController;
use App\Jobs\SendEmailJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Kutia\Larafirebase\Facades\Larafirebase;

// use Mail;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/create-job',[PostsController::class,'createJob']);
Route::get('/get-job/{id}',[PostsController::class,'getJob']);
Route::group(['middleware' => ['log_request']], function() {
    Route::middleware(['auth:sanctum'])->group(function () {
        // Threads
		Route::prefix('/thread')->group(function () {
			Route::post('/list', [ThreadsController::class,'list']);
            Route::post('/add', [ThreadsController::class, 'addThread']);
	        Route::post('/delete', [ThreadsController::class,'deleteThread']);
    	    Route::post('/clear', [ThreadsController::class,'clearThread']);
		});
        //Message controller
		Route::prefix('/message')->group(function () {
			Route::get('/', [MessagesController::class,'listMsg']);
    	    Route::post('/mark-read', [MessagesController::class,'markMessagesRead']);
            Route::post('/delete', [MessagesController::class,'deleteMsg']);
            Route::post('/notification-counter',[MessagesController::class,'notificationCounter']);

		});
        Route::get('/user', [UsersController::class, 'self']);
        Route::get('/posts/dropdowns', [PostsController::class, 'dropdowns']);
        Route::post('/posts/list', [PostsController::class, 'list']);
        Route::post('/post/create', [PostsController::class, 'create']);
        Route::post('v1/post/create', [PostsController::class, 'v1Create']);
        Route::post('/post/update/{post}', [PostsController::class, 'update']);
        Route::post('/post/save/{post}', [PostSavesController::class, 'save']);
        Route::post('/post/like/{post}', [PostLikesController::class, 'like']);
        Route::delete('/posts/{post}', [PostsController::class, 'destroy']);

        Route::post('/post/search-mobile', [PostSearchController::class, 'searchMobile']);
        Route::post('comment/replies', [CommentsController::class, 'repliesByCommentId']);
        Route::post('post/comment/create', [CommentsController::class, 'store']);
        Route::post('post/comment/reply', [CommentsController::class, 'reply']);
        Route::post('post/comment/like', [CommentsController::class, 'like']);
        Route::post('post/comment/unlike', [CommentsController::class, 'unlike']);
        Route::post('post/comment/{comment}/likeUnlike', [CommentsController::class, 'likeUnlike']);
        Route::post('post/comment/replyNew', [CommentsController::class, 'replyNew']);


        Route::post('/transactions/create', [TransactionsController::class, 'create']);
        Route::post('/transactions/requests', [TransactionsController::class, 'requests']);
        Route::post('/transactions/requested', [TransactionsController::class, 'requested']);

        Route::post('flags', [FlagController::class, 'index']);
        Route::post('post/flag', [FlaggedContentController::class, 'flag']);

        Route::post('user-flags', [UserFlagController::class, 'index']);
        Route::post('user/flag', [FlaggedUserController::class, 'flag']);

        Route::post('user/updateHirer', [UsersController::class, 'updateHirer']);
        Route::post('user/updateFreelancer', [UsersController::class, 'updateFreelancer']);
        Route::post('user/updateFreelancerProfileImage', [UsersController::class, 'updateFreelancerProfileImage']);
        Route::post('user/searchFreelancer', [UsersController::class, 'searchFreelancer']);
        Route::post('user/follow/{user}', [UsersController::class, 'follow']);
        Route::post('user/unfollow/{user}', [UsersController::class, 'unfollow']);

        Route::post('user/notifications', [UsersController::class, 'notifications']);
        Route::get('user/counter', [UsersController::class, 'counter']);
        Route::post('user/mark-as-read',[UsersController::class,'markAsRead']);


        Route::post('complaint/create', [ComplaintsController::class, 'create']);

        Route::post('/user/logout', [UsersController::class, 'logout']);
        Route::post('/send_chat_push/{user}', [UsersController::class, 'send_chat_push']);

        Route::get('/my-subscriptions', [SubscriptionController::class, 'my_subscriptions']);
        Route::post('/subscription/get_link', [SubscriptionController::class, 'get_link']);
        Route::post('/subscription/create-payment-intent', [SubscriptionController::class, 'get_link']);
        Route::post('/subscription/subscribe', [SubscriptionController::class, 'subscribe']);
        Route::post('/one-time/get_link', [SubscriptionController::class, 'get_link_one_time']);
        Route::get('/subscription/confirm_checkout/{checkout_id}', [SubscriptionController::class, 'details_checkout_id']);
        Route::get('/current-subscription', [SubscriptionController::class, 'current']);
        Route::post('/subscription/change_plan', [SubscriptionController::class, 'change_plan']);
        Route::post('/subscription/pause', [SubscriptionController::class, 'pause']);
        Route::post('/subscription/resume', [SubscriptionController::class, 'resume']);
        Route::post('/subscription/cancel', [SubscriptionController::class, 'cancel']);
        Route::post('/subscription/force_cancel', [SubscriptionController::class, 'force_cancel']);

        Route::post('/user/deleteAccountOtp', [UsersController::class, 'deleteAccountOtp']);
        Route::post('/user/deleteAccount', [UsersController::class, 'deleteAccount']);
    });

    Route::get('/plans',[SubscriptionController::class,'plans']);

    Route::post('/posts/get', [PostsController::class, 'getPostById']);
    Route::get('/posts/delete/{id}', [PostsController::class, 'deletePostById']);
    Route::post('/posts/list/guest', [PostsController::class, 'listGuest']);

    Route::get('/states/list', [StatesController::class, 'list']);
    Route::post('/breeds/list', [BreedsController::class, 'list']);
    Route::post('/colors/list', [ColorsController::class, 'list']);
    Route::get('/packages/list', [PackageController::class, 'list']);


    Route::post('/user/login', [UsersController::class, 'login']);

    Route::post('/user/registerHirerOtp', [UsersController::class, 'registerHirerOtp']);
    Route::post('/user/data-validation', [UsersController::class, 'userDataValidation']);
    Route::post('/user/registerHirer', [UsersController::class, 'registerHirer']);

    Route::post('/user/registerFreelancerOtp', [UsersController::class, 'registerFreelancerOtp']);
    Route::post('/user/registerFreelancer', [UsersController::class, 'registerFreelancer']);


    Route::post('post/comment/{post}', [CommentsController::class, 'commentsByPostId']);

    Route::post('/post/search', [PostSearchController::class, 'search']);
    Route::post('/post/search/guest', [PostSearchController::class, 'searchGuest']);

    Route::get('post/comment/guest/{id}', [CommentsController::class, 'commentsByPostIdGuest']);
    Route::post('/forget-otp', [UsersController::class, 'forgetOTP']);
    Route::post('/forget-password', [UsersController::class, 'forgetPassword']);

    Route::get('user/profile/{user}', [UsersController::class, 'otherProfile']);
    Route::post('/payment-intent',[\App\Http\Controllers\Api\SubscriptionController::class,'paymentIntent']);


    Route::get('test/email', function (Request $request) {

    /**
     *
     * eflHkq842zI:APA91bHoKoXDJQdoR7lQQ4du1GdqddSqY5_yjl_n5pvjCmGFaTQ-DiO8pe_0HAP9--nQ9RjS58auMIHbMwuNHhb0AGs3Wa6X3xrX2o8-o5H7P4BhVKjoRfURH6Zb29RAGq2QiN5D6Vy3
     *
     */

    $device_tokens = 'eflHkq842zI:APA91bHoKoXDJQdoR7lQQ4du1GdqddSqY5_yjl_n5pvjCmGFaTQ-DiO8pe_0HAP9--nQ9RjS58auMIHbMwuNHhb0AGs3Wa6X3xrX2o8-o5H7P4BhVKjoRfURH6Zb29RAGq2QiN5D6Vy3';

    try {
        $note = Larafirebase::withTitle("Happy Tails TV")
        ->withBody("Moavia liked your post")
        ->sendMessage([$device_tokens]);

        dd($note);

        // return response()->json(['Notification Sent']);

    } catch (\Throwable $th) {
        # Error Sending Notification
        dd($th);
        return response()->json(['Something went wrong !']);
    }

});
});
