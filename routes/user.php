<?php

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\User\ViewsController;
use App\Http\Controllers\User\WithdrawalController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\DepositController;
use App\Http\Controllers\User\PaystackController;
use App\Http\Controllers\User\UserSubscriptionController;
use App\Http\Controllers\User\UserInvPlanController;
use App\Http\Controllers\User\VerifyController;
use App\Http\Controllers\User\SomeController;
use App\Http\Controllers\User\SocialLoginController;
use App\Http\Controllers\User\ExchangeController;
use App\Http\Controllers\User\FlutterwaveController;
use App\Http\Controllers\User\MembershipController;
use App\Http\Controllers\User\LoanController;
use App\Http\Controllers\User\TransferController;
use App\Http\Controllers\User\StatementController;
use App\Http\Controllers\Auth\EmailCodeVerificationController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

// Email verification routes (Code-based verification)
Route::get('/verify-email', [EmailCodeVerificationController::class, 'notice'])->middleware('auth')->name('verification.notice');

Route::post('/verify-email-code', [EmailCodeVerificationController::class, 'verify'])->middleware(['auth', 'throttle:6,1'])->name('verification.verify');

Route::post('/email/verification-notification', [EmailCodeVerificationController::class, 'resend'])->middleware(['auth', 'throttle:6,1'])->name('verification.send');


// Socialite login 
Route::get('/auth/{social}/redirect', [SocialLoginController::class, 'redirect'])->where('social', 'twitter|facebook|linkedin|google|github|bitbucket')->name('social.redirect');
Route::get('/auth/{social}/callback', [SocialLoginController::class, 'authenticate'])->where('social', 'twitter|facebook|linkedin|google|github|bitbucket')->name('social.callback');

Route::get('/ref/{id}', 'App\Http\Controllers\Controller@ref')->name('ref');

/*    Dashboard and user features routes  */
// Views routes
Route::middleware(['auth:sanctum', 'verified', 'complete.kyc', 'check.banned'])->get('/dashboard', [ViewsController::class, 'dashboard'])->name('dashboard');

Route::middleware(['auth:sanctum', 'verified', 'check.banned'])->prefix('dashboard')->group(function () {

	// Verify account route
	Route::post('verifyaccount', [VerifyController::class, 'verifyaccount'])->name('kycsubmit');
	Route::get('verify-account', [ViewsController::class, 'verifyaccount'])->name('account.verify');
	Route::get('kyc-form', [ViewsController::class, 'verificationForm'])->name('kycform');
	Route::get('support', [ViewsController::class, 'support'])->name('support');
	Route::get('pin', [ViewsController::class, 'pin'])->name('pin');
	Route::post('pinstatus', [ViewsController::class, 'pinstatus'])->name('pinstatus');

	Route::middleware('complete.kyc')->group(function () {
		// Route::get('verify-account', [ViewsController::class, 'verifyaccount'])->name('account-verify');
		Route::get('account-settings', [ViewsController::class, 'profile'])->name('profile');
		Route::get('accountdetails', [ViewsController::class, 'accountdetails'])->name('accountdetails');
		Route::get('notification', [ViewsController::class, 'notification'])->name('notification');

		// Notification routes
		Route::get('notifications', [NotificationController::class, 'index'])->name('notifications');
		Route::get('notifications/recent', [NotificationController::class, 'recent'])->name('notifications.recent');
		Route::post('notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
		Route::post('notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readall');
		Route::delete('notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.delete');
		Route::get('notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.count');

		Route::get('editpass', [ViewsController::class, 'editpass'])->name('editpass');
		Route::get('deposits', [ViewsController::class, 'deposits'])->name('deposits');
		Route::get('skip_account', [ViewsController::class, 'skip_account']);

		// Route::get('tradinghistory', [ViewsController::class, 'tradinghistory'])->name('tradinghistory');
		Route::get('accounthistory', [ViewsController::class, 'accounthistory'])->name('accounthistory');
		Route::get('statement', [StatementController::class, 'index'])->name('statement');
		Route::post('statement/generate', [StatementController::class, 'generate'])->name('statement.generate');
		Route::get('withdrawals', [ViewsController::class, 'withdrawals'])->name('withdrawalsdeposits');
		Route::get('code1verification', [ViewsController::class, 'code1'])->name('code1verification');
		Route::get('verificationcode2', [ViewsController::class, 'code2'])->name('verificationcode2');
		Route::get('verification3code', [ViewsController::class, 'code3'])->name('verification3code');
		Route::get('sucesstransfer', [ViewsController::class, 'sucesstransfer'])->name('sucesstransfer');
		Route::get('transactiondetails/{id}', [ViewsController::class, 'transactiondetails'])->name('transactiondetails');
		Route::get('depositdetails/{id}', [ViewsController::class, 'depositdetails'])->name('depositdetails');

		Route::get('localtransfer', [ViewsController::class, 'localtransfer'])->name('localtransfer');

		Route::get('internationaltransfer', [ViewsController::class, 'internationaltransfer'])->name('internationaltransfer');

		Route::get('internaltransfer', [ViewsController::class, 'internaltransfer'])->name('internaltransfer');
		Route::post('verify-account', [WithdrawalController::class, 'verifyAccount'])->name('verifyaccount');

		// Route::get('subtrade', [ViewsController::class, 'subtrade'])->name('subtrade');
		// Route::get('buy-plan', [ViewsController::class, 'mplans'])->name('mplans');
		// Route::get('myplans/{sort}', [ViewsController::class, 'myplans'])->name('myplans');
		// Route::get('sort-plans/{sorttype}', [ViewsController::class, 'sortPlans'])->name('sortplans');

		// Route::get('plan-details/{id}', [ViewsController::class, 'planDetails'])->name('plandetails');
		// Route::get('cancel-plan/{id}', [UserInvPlanController::class, 'cancelPlan'])->name('cancelplan');

		Route::get('referuser', [ViewsController::class, 'referuser'])->name('referuser');
		Route::get('loan', [ViewsController::class, 'loan'])->name('loan');
		
		// OLD CARD ROUTES - Redirected to new system
		Route::get('card', function() { return redirect()->route('cards.index'); })->name('card');
		Route::get('freeze', function() { return redirect()->route('cards.index')->with('message', 'Please use the toggle option on your card'); });
		Route::get('unfreeze', function() { return redirect()->route('cards.index')->with('message', 'Please use the toggle option on your card'); });
		Route::post('createcard', function() { return redirect()->route('cards.apply'); })->name('createcard');
		Route::post('fundcard', function() { return redirect()->route('cards.index')->with('message', 'Please use the fund option on your card'); })->name('fundcard');
		Route::post('withdrawcard', function() { return redirect()->route('cards.index')->with('message', 'Please use the withdraw option on your card'); })->name('withdrawcard');

		Route::post('loan', [LoanController::class, 'loan'])->name('loan');
		Route::get('viewloan', [LoanController::class, 'veiwloans'])->name('veiwloan');
		Route::post('export-loans', [LoanController::class, 'exportLoans'])->name('exportloans');

		// Route::get('manage-account-security', [ViewsController::class, 'twofa'])->name('twofa');
		// Route::get('transfer-funds', [ViewsController::class, 'transferview'])->name('transferview');

		// Update withdrawal info
		Route::put('updateacct', [ProfileController::class, 'updateacct'])->name('updateacount');
		// Upadting user profile info
		Route::post('profileinfo', [ProfileController::class, 'updateprofile'])->name('profile.update');
		// Update password

		//updateprofilephoto
		Route::post('updateprofilephoto', [ProfileController::class, 'updateprofilephoto'])->name('updateprofilephoto');

		Route::put('changepin', [ProfileController::class, 'changepin'])->name('changepin');

		Route::put('updatepass', [ProfileController::class, 'updatepass'])->name('updateuserpass');

		// Update emal preference
		Route::put('update-email-preference', [ProfileController::class, 'updateemail'])->name('updateemail');

		// Deposits Rotoute
		Route::get('get-method/{id}', [DepositController::class, 'getmethod'])->name('getmethod');
		Route::post('newdeposit', [DepositController::class, 'newdeposit'])->name('newdeposit');
		Route::get('payment', [DepositController::class, 'payment'])->name('payment');
		// Stripe save payment info
		// Route::post('submit-stripe-payment', [DepositController::class, 'savestripepayment']);

		// Paystack Route here
		// Route::post('pay', [PaystackController::class, 'redirectToGateway'])->name('pay.paystack');
		// Route::get('paystackcallback', [PaystackController::class, 'handleGatewayCallback']);
		Route::post('savedeposit', [DepositController::class, 'savedeposit'])->name('savedeposit');

		// Flutterwave Routes here
		// Route::post('/payviaflutterwave', [FlutterwaveController::class, 'initialize'])->name('paybyflutterwave');
		// The callback url after a payment
		// Route::get('/rave/callback', [FlutterwaveController::class, 'callback'])->name('callback');

		// Withdrawals
		Route::post('enter-amount', [WithdrawalController::class, 'withdrawamount'])->name('withdrawamount');
		Route::get('withdraw-funds', [WithdrawalController::class, 'withdrawfunds'])->name('withdrawfunds');
		Route::get('getotp', [WithdrawalController::class, 'getotp'])->name('getotp');
		Route::get('otpview', [WithdrawalController::class, 'otpview'])->name('otpview');
		Route::post('completewithdrawal', [WithdrawalController::class, 'completewithdrawal'])->name('completewithdrawal');

		// Internal transfer (no rate limit - instant processing)
		Route::post('internaltransfer', [WithdrawalController::class, 'internaltransfer'])->name('internaltransfer');

		// All other transfers (with rate limiting)
		Route::middleware('transfer.rate')->group(function () {
			Route::post('internationaltransfer', [WithdrawalController::class, 'internationaltransfer'])->name('internationaltransfer');
			Route::post('localtransfer', [WithdrawalController::class, 'localtransfer'])->name('localtransfer');
			Route::post('paypal', [WithdrawalController::class, 'paypal'])->name('paypal');
			Route::post('cryptocurrency', [WithdrawalController::class, 'crypto'])->name('cryptocurrency');
			Route::post('skrill', [WithdrawalController::class, 'skrill'])->name('skrill');
			Route::post('googlepay', [WithdrawalController::class, 'googlepay'])->name('googlepay');
			Route::post('payoneer', [WithdrawalController::class, 'payoneer'])->name('payoneer');
			Route::post('transferwise', [WithdrawalController::class, 'transferwise'])->name('transferwise');
			Route::post('westernunion', [WithdrawalController::class, 'westernunion'])->name('westernunion');
		});

		Route::post('codecomfirm', [WithdrawalController::class, 'codecomfirm'])->name('codecomfirm');
		Route::get('previewinternationaltransfer', [ViewsController::class, 'previewinternationaltransfer'])->name('previewinternationaltransfer');
		Route::get('confirm_transaction', [WithdrawalController::class, 'confirm'])->name('confirm');
		Route::get('cancelconfirm', [WithdrawalController::class, 'cancelconfirm'])->name('cancelconfirm');
		Route::get('proceed_with_transcation', [WithdrawalController::class, 'proceedtranscation'])->name('proceedtranscation');
		Route::get('previewtransfer', [WithdrawalController::class, 'previewtransfer'])->name('previewtransfer');
		// Subscription Trading
		Route::post('savemt4details', [UserSubscriptionController::class, 'savemt4details'])->name('savemt4details');
		Route::get('delsubtrade/{id}', [UserSubscriptionController::class, 'delsubtrade'])->name('delsubtrade');
		Route::get('renew/subscription/{id}', [UserSubscriptionController::class, 'renewSubscription'])->name('renewsub');

		// Investment routes
		Route::prefix('investments')->name('investments.')->group(function () {
			Route::get('/', 'App\Http\Controllers\User\InvestmentController@index')->name('index');
			Route::get('/invest/{planId}', 'App\Http\Controllers\User\InvestmentController@invest')->name('invest');
			Route::post('/process', 'App\Http\Controllers\User\InvestmentController@processInvestment')->name('process');
			Route::get('/my-investments', 'App\Http\Controllers\User\InvestmentController@myInvestments')->name('my-investments');
			Route::get('/view/{id}', 'App\Http\Controllers\User\InvestmentController@viewInvestment')->name('view');
		});

		// Route::post('changetheme', [SomeController::class, 'changetheme'])->name('changetheme');

		// Route::post('paypalverify/{amount}', 'App\Http\Controllers\Controller@paypalverify')->name('paypalverify');
		// Route::get('cpay/{amount}/{coin}/{ui}/{msg}', 'App\Http\Controllers\Controller@cpay')->name('cpay');
		// Crypto Swap routes
		Route::get('crypto-swap', [ExchangeController::class, 'assetview'])->name('assetbalance');
		Route::get('swap-history', [ExchangeController::class, 'history'])->name('swaphistory');
		Route::get('asset-price/{base}/{quote}/{amount}', [ExchangeController::class, 'getprice'])->name('getprice');
		Route::post('exchange', [ExchangeController::class, 'exchange'])->name('exchangenow');
		Route::get('balances/{coin}', [ExchangeController::class, 'getBalance'])->name('getbalance');
		Route::get('all-balances', [ExchangeController::class, 'getAllBalances'])->name('getallbalances');
		Route::get('live-prices', [ExchangeController::class, 'getLivePrices'])->name('getliveprices');
		Route::get('calculate-swap', [ExchangeController::class, 'calculateSwapPreview'])->name('calculateswap');

		// USer to User transfer
		Route::post('transfertouser', [TransferController::class, 'transfertouser'])->name('transfertouser');

		// binance crypto payments routes
		Route::get('/binance/success', [ViewsController::class, 'binanceSuccess'])->name('bsuccess');
		Route::get('/binance/error', [ViewsController::class, 'binanceError'])->name('berror');


		//membership route for user side
		// Route::name('user.')->group(function () {
		// 	Route::get('/courses', [MembershipController::class, 'courses'])->name('courses');
		// 	Route::get('/course-details/{course}/{id}', [MembershipController::class, 'courseDetails'])->name('course.details');
		// 	Route::post('/buy-course', [MembershipController::class, 'buyCourse'])->name('buycourse');
		// 	Route::get('/my-courses', [MembershipController::class, 'myCourses'])->name('mycourses');
		// 	Route::get('/course-details/{id}', [MembershipController::class, 'myCoursesDetails'])->name('mycoursedetails');
		// 	Route::get('/learning/{lesson}/{course?}', [MembershipController::class, 'learning'])->name('learning');
		// });

		//signals
		// Route::get('/trade-signals', [ViewsController::class, 'tradeSignals'])->name('tsignals');
		// Route::get('/renew-subscription', [TransferController::class, 'renewSignalSub'])->name('renewsignals');

		// Virtual Cards Routes
		Route::prefix('cards')->name('cards.')->group(function () {
			Route::get('/', 'App\Http\Controllers\User\CardController@index')->name('index');
			Route::get('/apply', 'App\Http\Controllers\User\CardController@showApplicationForm')->name('apply');
			Route::post('/apply', 'App\Http\Controllers\User\CardController@applyCard')->name('apply.submit');
			Route::get('/{id}', 'App\Http\Controllers\User\CardController@viewCard')->name('view');
			Route::get('/{id}/transactions', 'App\Http\Controllers\User\CardController@cardTransactions')->name('transactions');
			Route::post('/{id}/fund', 'App\Http\Controllers\User\CardController@fundCard')->name('fund');
			Route::post('/{id}/withdraw', 'App\Http\Controllers\User\CardController@withdrawFromCard')->name('withdraw');
			Route::post('/{id}/toggle-status', 'App\Http\Controllers\User\CardController@toggleCardStatus')->name('toggle');
			Route::get('/{id}/reveal', 'App\Http\Controllers\User\CardController@revealCardNumber')->name('reveal');
		});

		// Grant Application Routes
		Route::prefix('grant-application')->name('grant.')->group(function () {
			Route::get('/', 'App\Http\Controllers\User\GrantApplicationController@index')->name('index');
			Route::get('/individual', 'App\Http\Controllers\User\GrantApplicationController@individual')->name('individual');
			Route::get('/company', 'App\Http\Controllers\User\GrantApplicationController@company')->name('company');
			Route::post('/individual', 'App\Http\Controllers\User\GrantApplicationController@storeIndividual')->name('storeIndividual');
			Route::post('/company', 'App\Http\Controllers\User\GrantApplicationController@storeCompany')->name('storeCompany');
			Route::get('/processing', 'App\Http\Controllers\User\GrantApplicationController@processing')->name('processing');
			Route::get('/results', 'App\Http\Controllers\User\GrantApplicationController@results')->name('results');
			Route::get('/my-applications', 'App\Http\Controllers\User\GrantApplicationController@myApplications')->name('myApplications');
			Route::get('/{id}', 'App\Http\Controllers\User\GrantApplicationController@view')->name('view');
			Route::get('/{id}/edit', 'App\Http\Controllers\User\GrantApplicationController@edit')->name('edit');
			Route::post('/{id}/individual', 'App\Http\Controllers\User\GrantApplicationController@updateIndividual')->name('updateIndividual');
			Route::post('/{id}/company', 'App\Http\Controllers\User\GrantApplicationController@updateCompany')->name('updateCompany');
		});
	});
});
Route::post('sendcontact', 'App\Http\Controllers\User\UsersController@sendcontact')->name('enquiry');
