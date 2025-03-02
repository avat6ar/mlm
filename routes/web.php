<?php

use Illuminate\Support\Facades\Route;

Route::get('/clear', function ()
{
  \Illuminate\Support\Facades\Artisan::call('optimize:clear');
});

Route::get('cron', 'CronController@cron')->name('cron');

// User Support Ticket
Route::controller('TicketController')->prefix('ticket')->name('ticket.')->group(function ()
{
  Route::get('/', 'supportTicket')->name('index');
  Route::get('new', 'openSupportTicket')->name('open');
  Route::post('create', 'storeSupportTicket')->name('store');
  Route::get('view/{ticket}', 'viewTicket')->name('view');
  Route::post('reply/{ticket}', 'replyTicket')->name('reply');
  Route::post('close/{ticket}', 'closeTicket')->name('close');
  Route::get('download/{ticket}', 'ticketDownload')->name('download');
});

Route::controller('SiteController')->group(function ()
{
  Route::get('cookie-policy', 'cookiePolicy')->name('cookie.policy');

  Route::get('/cookie/accept', 'cookieAccept')->name('cookie.accept');
  Route::get('policy/{slug}/{id}', 'policyPages')->name('policy.pages');
  Route::get('placeholder-image/{size}', 'placeholderImage')->name('placeholder.image');

  Route::post('/check/referral', 'CheckUsername')->name('check.referral');
  Route::get('/', function ()
  {
    return redirect()->route('user.login');
  })->name('home');
});

