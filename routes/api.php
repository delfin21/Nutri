
<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

use App\Http\Controllers\API\{
    AuthController,
    RegisterController,
    FarmerDashboardController,
    FarmerProfileController,
    FarmerProductController,
    FarmerOrderController,
    ProductController,
    CategoryController,
    ReviewController,
    CartController,
    BuyerCheckoutController,
    BuyerPurchaseController,
    ConversationController,
    MessageController,
    UserController,
    ProductTemplateController,
    ReturnRequestController
};
use App\Http\Controllers\Farmer\FarmerVerificationController;
use App\Http\Controllers\Buyer\BuyerNotificationController;
use App\Http\Controllers\Farmer\FarmerNotificationController;
use App\Http\Controllers\Farmer\FarmerReturnController;
use App\Http\Controllers\PaymentController;
use App\Models\User;
use App\Services\FirebaseNotificationService;
use App\Http\Controllers\API\FarmerVerificationApiController;

// Public Auth Routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [RegisterController::class, 'register']);

// PayMongo
Route::post('/paymongo/payment-intent', [PaymentController::class, 'createPaymentIntent']);
Route::post('/paymongo/payment-method', [PaymentController::class, 'createPaymentMethod']);
Route::post('/paymongo/attach', [PaymentController::class, 'attachPaymentMethod']);

// Authenticated Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', fn(Request $request) => $request->user());

    // Farmer Routes
    Route::get('/farmer/dashboard', [FarmerDashboardController::class, 'index']);
    Route::get('/farmer/profile', [FarmerProfileController::class, 'show']);
    Route::post('/farmer/profile/update', [FarmerProfileController::class, 'update']);
    Route::get('/farmer/products', [FarmerProductController::class, 'index']);
    Route::post('/farmer/products', [FarmerProductController::class, 'store']);
    Route::put('/farmer/products/{id}', [FarmerProductController::class, 'update']);
    Route::delete('/farmer/products/{id}', [FarmerProductController::class, 'destroy']);
    Route::get('/farmer/orders', [FarmerOrderController::class, 'index']);
    Route::put('/farmer/orders/{id}', [FarmerOrderController::class, 'update']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/farmer/verify', [FarmerVerificationController::class, 'verify']);
    Route::get('/farmer/my-verification', [FarmerVerificationApiController::class, 'myRequest']);
    Route::get('/admin/verifications', [FarmerVerificationApiController::class, 'index']);
    Route::patch('/admin/verifications/{id}/approve', [FarmerVerificationApiController::class, 'approve']);
    Route::patch('/admin/verifications/{id}/reject', [FarmerVerificationApiController::class, 'reject']);
});
    // Buyer Routes
    Route::post('/orders', [BuyerCheckoutController::class, 'checkout']);
    Route::get('/buyer/purchases', [BuyerPurchaseController::class, 'index']);
    Route::put('/buyer/orders/{id}/confirm', [BuyerPurchaseController::class, 'confirmDelivery']);
    Route::put('/buyer/orders/{id}/cancel', [BuyerPurchaseController::class, 'cancel']);

    // User Profile
    Route::get('/user/profile', [UserController::class, 'profile']);
    Route::put('/user/profile/update', [UserController::class, 'update']);
    Route::put('/user/profile/update-personal', [UserController::class, 'updatePersonal']);
    Route::put('/user/profile/update-business', [UserController::class, 'updateBusiness']);
    Route::put('/user/profile/update-payout', [UserController::class, 'updatePayout']);
    Route::put('/user/profile/update-address', [UserController::class, 'updateAddress']);

    // Cart
    Route::get('/cart', [CartController::class, 'getCartItems']);
    Route::post('/cart/add', [CartController::class, 'addToCart']);
    Route::put('/cart/update/{id}', [CartController::class, 'updateCart']);
    Route::delete('/cart/remove/{id}', [CartController::class, 'removeCartItem']);

    // Messaging
    Route::get('/messages/conversation/{otherUserId}', [MessageController::class, 'getConversation']);
    Route::post('/messages/send', [MessageController::class, 'sendMessage']);
    Route::get('/conversations', [ConversationController::class, 'index']);
    Route::get('/conversations/{conversation}/messages', [MessageController::class, 'index']);
    Route::post('/conversations/{conversation}/messages', [MessageController::class, 'store']);
    Route::post('/conversations', [ConversationController::class, 'store']);
    Route::get('/users/opposite', [UserController::class, 'listOppositeUsers']);

    // Notifications
    Route::get('/buyer/notifications', [BuyerNotificationController::class, 'index']);
    Route::get('/farmer/notifications', [FarmerNotificationController::class, 'farmerIndex']);

    // Returns
    Route::post('/returns', [ReturnRequestController::class, 'store']);
    Route::prefix('farmer')->group(function () {
        Route::get('/returns', [FarmerReturnController::class, 'index']);
        Route::post('/returns/{id}/reply', [FarmerReturnController::class, 'reply']);
        Route::patch('/returns/{id}/approve', [FarmerReturnController::class, 'approve']);
        Route::patch('/returns/{id}/reject', [FarmerReturnController::class, 'reject']);
    });

    // Device Token
    Route::post('/device-tokens', function (Request $request) {
        $request->validate(['token' => 'required']);
        $user = $request->user();

        $user->deviceTokens()->updateOrCreate(
            ['user_id' => $user->id],
            ['token' => $request->token]
        );

        return response()->json(['status' => 'token_saved']);
    });
});

// Buyer Short Routes
Route::middleware('auth:sanctum')->prefix('buyer')->group(function () {
    Route::get('/orders', [BuyerPurchaseController::class, 'index']);
    Route::put('/orders/{id}/cancel', [BuyerPurchaseController::class, 'cancel']);
    Route::put('/orders/{id}/confirm', [BuyerPurchaseController::class, 'confirmDelivery']);
});

// Public Product APIs
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::get('/products/by-category/{id}', [ProductController::class, 'getByCategory']);
Route::get('/categories-list', [ProductController::class, 'getValidCategories']);
Route::get('/products/{product}/reviews', [ReviewController::class, 'index']);
Route::get('/product-templates/{category}', [ProductTemplateController::class, 'getByCategory']);

// FCM Test
Route::post('/test-order-fcm', function () {
    $user = User::where('role', 'buyer')->first();
    $tokens = $user->deviceTokens->pluck('token')->toArray();

    $data = [
        'registration_ids' => $tokens,
        'notification' => [
            'title' => 'Order Update',
            'body' => 'Your order has been shipped!'
        ],
        'data' => [
            'type' => 'order',
            'orderId' => 47
        ]
    ];

    $response = Http::withHeaders([
        'Authorization' => 'key=' . env('FCM_SERVER_KEY'),
        'Content-Type' => 'application/json'
    ])->post('https://fcm.googleapis.com/fcm/send', $data);

    return response()->json(['status' => 'sent', 'fcm_response' => $response->json()]);
});

Route::post('/test-fcm-v1', function (Request $request, FirebaseNotificationService $fcm) {
    $tokens = User::where('role', 'buyer')->first()->deviceTokens->pluck('token')->toArray();

    $fcm->sendToDevice(
        $tokens,
        $request->input('title', 'Default Title'),
        $request->input('body', 'Default Body'),
        $request->all()
    );

    return response()->json(['status' => 'sent']);
});
