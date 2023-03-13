<?php

use App\Models\Server;
use Illuminate\Support\Facades\Route;

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

Route::get('/expiry', function () {
    Log::info("Server expiry cron is running!");
    $currentDate = date('Y-m-d');
    Server::where('expired_at', '<=', $currentDate)->update([
        'is_expired' => 1,
    ]);
});

Route::get('/delete/customer', function () {
    Log::info("Customer deleted cron is running!");
    $currentDate = date('Y-m-d');
    \DB::table('users')
        ->where('is_deleted', '1')
        ->whereRaw("DATEDIFF(CURDATE(),is_deleted_at) > 15")
        ->delete();
});

Route::get('/', function () {
    return view('welcome');
});

Route::get('/welcome', function () {
    return view('welcome');
});

Route::get('/event', function () {
    // event(new \App\Events\NewTrade('test'));
    event(new \App\Events\NewTrade('Hello Ali raza'));
    echo 'Hello Ali raza';
});

Auth::routes();

// admin
Route::prefix('admin')
    ->name('admin.')
    ->group(function () {
        // admin auth
        Route::middleware('guest:admin')->group(function () {
            Route::get('login', [
                App\Http\Controllers\Auth\LoginController::class,
                'showAdminLoginForm',
            ])->name('adminLogin');
            Route::post('login', [
                App\Http\Controllers\Auth\LoginController::class,
                'adminLogin',
            ]);
            Route::post('register', [
                App\Http\Controllers\Auth\RegisterController::class,
                'createAdmin',
            ]);
            Route::get('register', [
                App\Http\Controllers\Auth\RegisterController::class,
                'showAdminRegisterForm',
            ]);
        });
        Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'adminLogout'])->name('logout');

        // admin auth
        Route::middleware(['auth:admin', 'isblocked', 'isdeleted'])->group(
            function () {
                Route::get('/', [
                    App\Http\Controllers\Admin\AdminController::class,
                    'index',
                ])->name('home');
                Route::get('/dashboard', [
                    App\Http\Controllers\Admin\AdminController::class,
                    'index',
                ])->name('dashboard');
                Route::get('/profile', [
                    App\Http\Controllers\Admin\AdminController::class,
                    'profile',
                ])->name('profile');
                Route::post('/profile/update', [
                    App\Http\Controllers\Admin\AdminController::class,
                    'profileUpdate',
                ])->name('profile.update');
                Route::get('/password', [
                    App\Http\Controllers\Admin\AdminController::class,
                    'password',
                ])->name('password');
                Route::post('/password/update', [
                    App\Http\Controllers\Admin\AdminController::class,
                    'passwordUpdate',
                ])->name('password.update');

                //User //Category //package
                Route::middleware('isadmin')->group(function () {
                    // user
                    Route::get('/users', [
                        App\Http\Controllers\Admin\UserController::class,
                        'list',
                    ])->name('users');
                    Route::get('/users/add', [
                        App\Http\Controllers\Admin\UserController::class,
                        'addUser',
                    ])->name('users.add');
                    Route::post('/users/add', [
                        App\Http\Controllers\Admin\UserController::class,
                        'insertUser',
                    ])->name('users.insert');
                    Route::post('/users/delete/{id}', [
                        App\Http\Controllers\Admin\UserController::class,
                        'softDelete',
                    ])->name('users.delete');
                    Route::get('/users/edit/{id}', [
                        App\Http\Controllers\Admin\UserController::class,
                        'editUser',
                    ])->name('users.edit');
                    Route::post('/users/edit/{id}', [
                        App\Http\Controllers\Admin\UserController::class,
                        'updateUser',
                    ])->name('users.update');
                    Route::get('/user/status/{id}/{status}', [
                        App\Http\Controllers\Admin\UserController::class,
                        'changeStatus',
                    ])->name('user.status');

                    //category
                    Route::get('category', [
                        App\Http\Controllers\Admin\CategoryController::class,
                        'index',
                    ])->name('category');
                    Route::post('category/submit', [
                        App\Http\Controllers\Admin\CategoryController::class,
                        'insert',
                    ])->name('category.submit');
                    Route::post('category/update', [
                        App\Http\Controllers\Admin\CategoryController::class,
                        'update',
                    ])->name('category.update');
                    Route::delete('category/delete/{id}', [
                        App\Http\Controllers\Admin\CategoryController::class,
                        'delete',
                    ])->name('category.update');

                    //package
                    Route::get('/package', [
                        App\Http\Controllers\Admin\PackageController::class,
                        'list',
                    ])->name('package');
                    Route::get('/package/add', [
                        App\Http\Controllers\Admin\PackageController::class,
                        'addPackage',
                    ])->name('package.add');
                    Route::post('/packages/add', [
                        App\Http\Controllers\Admin\PackageController::class,
                        'insertPackage',
                    ])->name('package.insert');
                    Route::post('/package/delete/{id}', [
                        App\Http\Controllers\Admin\PackageController::class,
                        'packageDelete',
                    ])->name('package.delete');
                    Route::get('/package/edit/{id}', [
                        App\Http\Controllers\Admin\PackageController::class,
                        'editPackage',
                    ])->name('package.edit');
                    Route::post('/package/edit/{id}', [
                        App\Http\Controllers\Admin\PackageController::class,
                        'updatePackge',
                    ])->name('package.update');

                    // knowledegbase
                    Route::get('/knowledge/add', [
                        App\Http\Controllers\Admin\KnowledgeBaseController::class,
                        'addKnowledge',
                    ])->name('knowledge.add');
                    Route::post('/knowledge/add', [
                        App\Http\Controllers\Admin\KnowledgeBaseController::class,
                        'insertKnowledge',
                    ])->name('knowledge.insert');
                    Route::post('/knowledge/delete/{id}', [
                        App\Http\Controllers\Admin\KnowledgeBaseController::class,
                        'knowledgeDelete',
                    ])->name('knowledge.delete');
                    Route::get('/knowledge/edit/{id}', [
                        App\Http\Controllers\Admin\KnowledgeBaseController::class,
                        'editKnowledge',
                    ])->name('knowledge.edit');
                    Route::post('/knowledge/edit/{id}', [
                        App\Http\Controllers\Admin\KnowledgeBaseController::class,
                        'updateKnowledge',
                    ])->name('knowledge.update');
                });

                Route::get('/knowledge', [
                    App\Http\Controllers\Admin\KnowledgeBaseController::class,
                    'list',
                ])->name('knowledge');
                Route::get('/knowledge/view/{id}', [
                    App\Http\Controllers\Admin\KnowledgeBaseController::class,
                    'viewKnowledge',
                ])->name('knowledge.view');

                // customer
                Route::get('/customers/add', [
                    App\Http\Controllers\Admin\CustomerController::class,
                    'add',
                ])->name('customers.add');
                Route::post('/customers/insert', [
                    App\Http\Controllers\Admin\CustomerController::class,
                    'insert',
                ])->name('customers.insert');

                Route::get('/customers', [
                    App\Http\Controllers\Admin\CustomerController::class,
                    'index',
                ])->name('customers');
                Route::get('/customers/delete', [
                    App\Http\Controllers\Admin\CustomerController::class,
                    'deleteCustomer',
                ])->name('customers.delete.list');
                Route::post('/customers/add/amount', [
                    App\Http\Controllers\Admin\CustomerController::class,
                    'addAmount',
                ])->name('add.amount');
                Route::post('/customers/deduct/amount', [
                    App\Http\Controllers\Admin\CustomerController::class,
                    'deductAmount',
                ])->name('deduct.amount');

                Route::post('/customer/add/whatsapp', [
                    App\Http\Controllers\Admin\CustomerController::class,
                    'addWhatsapp',
                ])->name('customer.whatsapp');
                Route::post('/customer/add/skype', [
                    App\Http\Controllers\Admin\CustomerController::class,
                    'addSkype',
                ])->name('customer.skype');
                Route::get('/customer/view/{id}', [
                    App\Http\Controllers\Admin\CustomerController::class,
                    'view',
                ])->name('customer.view');
                Route::post('/customer/delete/{id}', [
                    App\Http\Controllers\Admin\CustomerController::class,
                    'softDelete',
                ])->name('customer.delete');
                Route::post('/customer/restore/{id}', [
                    App\Http\Controllers\Admin\CustomerController::class,
                    'restoreDelete',
                ])->name('customer.restore');
                Route::get('/customer/status/{id}/{status}', [
                    App\Http\Controllers\Admin\CustomerController::class,
                    'changeStatus',
                ])->name('customer.status');

                //ticket
                Route::get('/ticket/change/{id}/status/{status}', [
                    App\Http\Controllers\Admin\TicketController::class,
                    'changeStatusTicket',
                ])->name('ticket.status.change');
                Route::get('/ticket/view/{id}', [
                    App\Http\Controllers\Admin\TicketController::class,
                    'view',
                ])->name('ticket.view');
                Route::get('/ticket/reply/view/{id}', [
                    App\Http\Controllers\Admin\TicketController::class,
                    'replyView',
                ])->name('ticket.reply.view');
                Route::post('/ticket/message/reply/{id}', [
                    App\Http\Controllers\Admin\TicketController::class,
                    'message',
                ])->name('ticket.message.reply');

                // technical
                // Route::get('/all/support/{id}', [
                //     App\Http\Controllers\Admin\TechnicalController::class,
                //     'allSupport',
                // ])->name('all.support');
                Route::delete('/delete/support/{id}', [
                    App\Http\Controllers\Admin\TechnicalController::class,
                    'deleteSupport',
                ])->name('delete.support');
                Route::get('/all/support', [
                    App\Http\Controllers\Admin\TechnicalController::class,
                    'allSupport',
                ])->name('all.support');
                Route::get('/open/support', [
                    App\Http\Controllers\Admin\TechnicalController::class,
                    'openSupport',
                ])->name('open.support');
                Route::get('/close/support', [
                    App\Http\Controllers\Admin\TechnicalController::class,
                    'closeSupport',
                ])->name('close.support');
                Route::get('/view/support/{id}', [
                    App\Http\Controllers\Admin\TechnicalController::class,
                    'viewSupport',
                ])->name('view.support');

                // order server
                Route::get('/add/server', [
                    App\Http\Controllers\Admin\ServerController::class,
                    'addServer',
                ])->name('add.server');
                Route::post('/add/server', [
                    App\Http\Controllers\Admin\ServerController::class,
                    'insertServer',
                ])->name('insert.server');
                Route::get('/server/get/sale/{id}', [
                    App\Http\Controllers\Admin\ServerController::class,
                    'getServerSalesPlan',
                ])->name('order.server.get.plane');
                Route::delete('/server/delete/{id}', [
                    App\Http\Controllers\Admin\ServerController::class,
                    'deleteServer',
                ])->name('order.server.get.plane');
                Route::get('/all/order/server', [
                    App\Http\Controllers\Admin\ServerController::class,
                    'allServer',
                ])->name('all.server');
                Route::delete('/delete/order/server/{id}', [
                    App\Http\Controllers\Admin\ServerController::class,
                    'deleteOrderServer',
                ])->name('delete.order.server');
                Route::get('/open/order/seallotedrver', [
                    App\Http\Controllers\Admin\ServerController::class,
                    'openServer',
                ])->name('open.server');
                Route::get('/close/order/server', [
                    App\Http\Controllers\Admin\ServerController::class,
                    'closeServer',
                ])->name('close.server');
                Route::get('/view/order/server/{id}', [
                    App\Http\Controllers\Admin\ServerController::class,
                    'viewServer',
                ])->name('view.server');
                Route::post('/install/order/server', [
                    App\Http\Controllers\Admin\ServerController::class,
                    'installServer',
                ])->name('install.server');
                Route::get('/order/server/detail/{type}/{id}', [
                    App\Http\Controllers\Admin\ServerController::class,
                    'detail',
                ])->name('detail.server');

                // Server list
                Route::get('/servers', [
                    App\Http\Controllers\Admin\ServerController::class,
                    'serverList',
                ])->name('list.server');
                Route::get('/servers/category/{id}', [
                    App\Http\Controllers\Admin\ServerController::class,
                    'listByServerid',
                ])->name('category.server');
                Route::get('/servers/expired', [
                    App\Http\Controllers\Admin\ServerController::class,
                    'expiredServerList',
                ])->name('expired.server');
                Route::get('/servers/available', [
                    App\Http\Controllers\Admin\ServerController::class,
                    'availableServerList',
                ])->name('available.server');
                Route::post('/servers/add/expiry', [
                    App\Http\Controllers\Admin\ServerController::class,
                    'addExpiry',
                ])->name('add.expiry.server');

                // sales
                Route::get('/all/sales', [
                    App\Http\Controllers\Admin\SalesController::class,
                    'allSales',
                ])->name('all.sales');
                Route::get('/open/sales', [
                    App\Http\Controllers\Admin\SalesController::class,
                    'openSales',
                ])->name('open.sales');
                Route::get('/close/sales', [
                    App\Http\Controllers\Admin\SalesController::class,
                    'closeSales',
                ])->name('close.sales');

                Route::delete('/delete/sales/{id}', [
                    App\Http\Controllers\Admin\SalesController::class,
                    'deleteSales',
                ])->name('delete.sales');

                Route::get('/view/sales/{id}', [
                    App\Http\Controllers\Admin\SalesController::class,
                    'viewSales',
                ])->name('view.sales');
                Route::get('/approval/sales/{id}', [
                    App\Http\Controllers\Admin\SalesController::class,
                    'approvalSales',
                ])->name('approval.sales');
                Route::get('/reject/sales/{id}', [
                    App\Http\Controllers\Admin\SalesController::class,
                    'rejectSales',
                ])->name('reject.sales');

                // ticket change to un-seen
                Route::get('/ticket/seen/{id}', [
                    App\Http\Controllers\Admin\AdminController::class,
                    'seenTicket',
                ])->name('ticket.unseen');

                // get unseen count of sales
                Route::get('/sales/unseen/count', [
                    App\Http\Controllers\Admin\AdminController::class,
                    'unseenSalescount',
                ])->name('sales.unseen.count');
                // get unseen count of support
                Route::get('/support/unseen/count', [
                    App\Http\Controllers\Admin\AdminController::class,
                    'unseenSupportcount',
                ])->name('support.unseen.count');
                // get unseen count of server
                Route::get('/server/unseen/count', [
                    App\Http\Controllers\Admin\AdminController::class,
                    'unseenServercount',
                ])->name('server.unseen.count');
            }
        );
    });

// customer
Route::middleware(['auth:web', 'isblocked', 'isdeleted'])->group(function () {
    Route::get('/', [
        App\Http\Controllers\HomeController::class,
        'index',
    ])->name('home');
    Route::get('/dashboard', [
        App\Http\Controllers\HomeController::class,
        'index',
    ])->name('dashboard');
    Route::get('/profile', [
        App\Http\Controllers\HomeController::class,
        'profile',
    ])->name('profile');
    Route::post('/profile/update', [
        App\Http\Controllers\HomeController::class,
        'profileUpdate',
    ])->name('profile.update');
    Route::get('/password', [
        App\Http\Controllers\HomeController::class,
        'password',
    ])->name('password');
    Route::post('/password/update', [
        App\Http\Controllers\HomeController::class,
        'passwordUpdate',
    ])->name('password.update');

    // ticket change to un-seen
    Route::get('/ticket/seen/{id}', [
        App\Http\Controllers\HomeController::class,
        'seenTicket',
    ])->name('ticket.unseen');

    // ticket view
    Route::get('/ticket/view/{id}', [
        App\Http\Controllers\HomeController::class,
        'view',
    ])->name('ticket.view');
    Route::get('/ticket/reply/view/{id}', [
        App\Http\Controllers\HomeController::class,
        'replyView',
    ])->name('ticket.reply.view');
    Route::post('/message/reply/{id}', [
        App\Http\Controllers\HomeController::class,
        'message',
    ])->name('ticket.message.reply');

    // get unseen count of sales
    Route::get('/sales/unseen/count', [
        App\Http\Controllers\HomeController::class,
        'getunSeenSalesCount',
    ])->name('sales.seen.count');
    Route::get('/support/unseen/count', [
        App\Http\Controllers\HomeController::class,
        'getUnseenSupportCount',
    ])->name('support.seen.count');
    Route::get('/server/unseen/count', [
        App\Http\Controllers\HomeController::class,
        'getUnseenServerCount',
    ])->name('server.seen.count');

    // server order
    Route::prefix('/order/server')
        ->name('order.server.')
        ->group(function () {
            Route::get('/add', [
                App\Http\Controllers\ServerController::class,
                'addServer',
            ])->name('add');
            Route::post('/submit', [
                App\Http\Controllers\ServerController::class,
                'insert',
            ])->name('submit');
            Route::get('/list', [
                App\Http\Controllers\ServerController::class,
                'list',
            ])->name('list');
            Route::get('/detail/{id}', [
                App\Http\Controllers\ServerController::class,
                'detail',
            ])->name('detail');
            Route::delete('/delete/{id}', [
                App\Http\Controllers\ServerController::class,
                'delete',
            ])->name('delete');
            Route::get('/get/sale/{id}', [
                App\Http\Controllers\ServerController::class,
                'getServerSalesPlan',
            ])->name('order.server.get.plane');
        });

    //server
    Route::prefix('/server')->name('server.')->group(function () {
        Route::get('/', [
            App\Http\Controllers\ServerController::class,
            'serverList',
        ])->name('list');
        Route::get('/expired', [
            App\Http\Controllers\ServerController::class,
            'expiredServerList',
        ])->name('expired');
        Route::get('/available', [
            App\Http\Controllers\ServerController::class,
            'availableServerList',
        ])->name('available');
    });

    // customer balance
    Route::prefix('balance')
        ->name('balance.')
        ->group(function () {
            Route::get('/', [
                App\Http\Controllers\BalanceController::class,
                'addBalance',
            ])->name('add');
            // Route::any('/payment', [
            //     App\Http\Controllers\BalanceController::class,
            //     'loadPayment',
            // ])->name('payment');
            Route::post('/submit', [
                App\Http\Controllers\BalanceController::class,
                'insertBalance',
            ])->name('submit');
            Route::get('/list/balance', [
                App\Http\Controllers\BalanceController::class,
                'listBalance',
            ])->name('list');
            Route::get('/view/balance/{id}', [
                App\Http\Controllers\BalanceController::class,
                'transactionView',
            ])->name('transaction.view');
        });

    //paymentsuccess
    Route::post('/strip/success', [
        App\Http\Controllers\PaymentController::class,
        'stripSuccess',
    ])->name('strip.success');
    Route::post('/strip/cancel', [
        App\Http\Controllers\PaymentController::class,
        'stripCancel',
    ])->name('strip.cancel');

    Route::post('paypal/submit', [App\Http\Controllers\BalanceController::class, 'paypalMethodPaymentsdk'])->name('paypal.submit');
    Route::get('paypal/status', [App\Http\Controllers\BalanceController::class, 'getPayPalStatus'])->name('paypal.status');
    Route::post('paypal/submit/payment', [App\Http\Controllers\BalanceController::class, 'papalMethodPayment'])->name('payment.submit');
    Route::any('paypal/success', [App\Http\Controllers\BalanceController::class, 'paypalSuccess'])->name('paypal.success');
    Route::any('paypal/cancel', [App\Http\Controllers\BalanceController::class, 'paypalCancel'])->name('paypal.cancel');

    // customer technical support
    Route::prefix('technical/support')
        ->name('technicalsupport.')
        ->group(function () {
            Route::get('/', [
                App\Http\Controllers\TechnicalController::class,
                'addSupport',
            ])->name('add');
            Route::post('/submit', [
                App\Http\Controllers\TechnicalController::class,
                'insertSupport',
            ])->name('submit');
            Route::get('/list', [
                App\Http\Controllers\TechnicalController::class,
                'listSupport',
            ])->name('list');
            Route::delete('/delete/support/{id}', [
                App\Http\Controllers\TechnicalController::class,
                'deleteSupport',
            ])->name('delete.support');
        });
});
