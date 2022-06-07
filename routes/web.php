<?php

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


Auth::routes();

Route::get('/', 'IndexController@index')->name('index');
Route::get('login', 'Auth\LoginController@showLoginForm')->name('showLoginForm');
Route::post('login', 'Auth\LoginController@login')->name('login');
Route::any('logout', 'Auth\LoginController@logout')->name('admin.logout');

Route::get('accounting', 'IndexController@accounting')->name('accounting');
Route::get('user-expense', 'IndexController@userExpense')->name('userExpense');
Route::get('user', 'IndexController@user')->name('user');
Route::get('user-detail', 'IndexController@userDetail')->name('user.detail');
Route::get('payment', 'IndexController@payment')->name('user.payment');
Route::get('imageServers', 'IndexController@imageServers')->name('imageServers');
Route::get('mail_domain', 'IndexController@mailDomain')->name('mailDomain');
Route::get('group-discount', 'IndexController@groupDiscount')->name('groupDiscount');
Route::get('group-discount/edit/{id}', 'IndexController@editGroupDiscount')->name('index.editGroupDiscount');
Route::get('group-discount/add', 'IndexController@editGroupDiscount')->name('index.addGroupDiscount');
Route::get('utm', 'IndexController@utm')->name('utm');
Route::get('swpt', 'IndexController@swpt')->name('swpt');
Route::get('system-env', 'IndexController@systemEnv')->name('systemEnv');
Route::any('system-env/edit/{id}', 'IndexController@systemEnvEdit')->name('systemEnvEdit');
Route::get('engine-version', 'IndexController@engineVersion')->name('engineVersion');
Route::any('engine-version/edit/{id}', 'IndexController@engineVersionEdit')
->name('engineVersionEdit');



//Route Supported Software 
Route::resource('supported-software', SupSoftwareController::class);



Route::group(['prefix' => 'scene'], function() {
    Route::get('/', 'IndexController@scene')->name('scene');
    Route::get('detail/{id}', 'IndexController@sceneDetail')->where(['id' => '[0-9]+'])->name('scene.detail');
    Route::get('analyze', 'AjaxController@sceneAnalyze')->where(['id' => '[0-9]+'])->name('scene.analyze');
});

Route::group(['prefix' => 'job'], function() {
    Route::get('/', 'JobController@index')->name('job.index');
    Route::get('detail/{id}', 'JobController@detail')->where(array('id' => '[0-9]+'))->name('job.detail');
});
Route::group(['prefix' => 'promotion'], function() {
    Route::get('/', 'PromotionController@index')->name('promotion.coupon.index');
    Route::any('coupon/add', 'PromotionController@edit')->name('promotion.coupon.add');
    Route::any('coupon/edit/{id}', 'PromotionController@edit')->where(['id' => '[0-9]+'])->name('promotion.coupon.edit');
    Route::any('coupon/delete/{id}', 'PromotionController@delete')->where(array('id' => '[0-9]+'))->name('promotion.coupon.delete');
    Route::get('gift', 'PromotionController@gift')->name('promotion.gift.index');
    Route::any('gift/add', 'PromotionController@giftEdit')->name('promotion.gift.add');
    Route::any('gift/edit/{id}', 'PromotionController@giftEdit')->where(array('id' => '[0-9]+'))->name('promotion.gift.edit');
    Route::get('gift/delete/{id}', 'PromotionController@giftDelete')->where(array('id' => '[0-9]+'))->name('promotion.gift.delete');
    Route::get('gift/buildCondition', 'PromotionController@giftBuildCondition')->name('promotion.gift.buildCondition');
    Route::get('link_affiliate', 'PromotionController@linkAffiliate')->name('promotion.link_affiliate.index');
    Route::any('affiliate_link/add', 'PromotionController@editAffiliateLink')->name('promotion.affiliate_link.add');
    Route::any('affiliate_link/edit/{id}', 'PromotionController@editAffiliateLink')->where(['id' => '[0-9]+'])->name('promotion.affiliate_link.edit');
    Route::any('affiliate_link/delete/{id}', 'PromotionController@deleteAffiliateLink')->where(['id' => '[0-9]+'])->name('promotion.affiliate_link.delete');
});

Route::group(['prefix' => 'ajax'], function() {
    Route::any('jobList', 'AjaxController@jobList')->name('ajax.jobList');
    Route::any('userList', 'AjaxController@userList')->name('ajax.userList');
    Route::any('addCredits', 'AjaxController@addCredits')->name('ajax.addCredits');
    Route::any('paymentHistory', 'AjaxController@paymentHistory')->name('ajax.paymentHistory');
    Route::any('accounting', 'AjaxController@accounting')->name('ajax.accounting');
    Route::any('user-expense', 'AjaxController@userExpense')->name('ajax.userExpense');
    Route::any('scene', 'AjaxController@scene')->name('ajax.scene');
    Route::any('activity', 'AjaxController@activity')->name('ajax.activity');
    Route::any('browseOutput', 'AjaxController@browseOutput')->name('ajax.browseOutput');
    Route::any('downloadOutput', 'AjaxController@downloadOutput')->name('ajax.downloadOutput');
    Route::get('getLogTask', 'AjaxController@getLogTask')->name('ajax.getLogTask');
    Route::any('editUserLevel/{id}', 'AjaxController@editUserLevel')->where(array('id' => '[0-9]+'))->name('ajax.editUserLevel');
    Route::get('getCouponList', 'AjaxController@getCouponList')->name('ajax.coupon.list');
    Route::get('changeJobAmount', 'AjaxController@changeJobAmount')->where(array('id' => '[0-9]+'))->name('ajax.job.amount');
    Route::get('getGiftList', 'AjaxController@getGiftList')->name('ajax.gift.list');
    Route::any('activeUser/{id}', 'AjaxController@activeUser')->where(array('id' => '[0-9]+'))->name('ajax.user.active');
    Route::any('getMailFeedback/{id}', 'AjaxController@getMailFeedback')->where(array('id' => '[0-9]+'))->name('ajax.user.get-feedback');
    Route::any('editJobStatus/{id}', 'AjaxController@editJobStatus')->where(array('id' => '[0-9]+'))->name('ajax.job.status');
    Route::any('userSearch', 'AjaxController@userSearch')->name('ajax.userSearch');
    Route::any('affiliate_link', 'AjaxController@linkAffiliateList')->name('ajax.affiliate_link.list');
    Route::any('list_admin_add_credits', 'AjaxController@getListActivityAdminAddCredits')->name('ajax.listAdminAddCredits');
    Route::any('mark-user-as-hacker/{id}', 'AjaxController@markUserAsHacker')->where(array('id' => '[0-9]+'))->name('ajax.user.mark-user-as-hacker');
    Route::any('editUserRoles/{id}', 'AjaxController@editUserRoles')->where(array('id' => '[0-9]+'))->name('ajax.editUserRoles');
    Route::any('add-image-servers', 'AjaxController@addImageServers')->name('ajax.addImageServers');
    Route::any('delete-image-servers', 'AjaxController@deleteImageServers')->name('ajax.deleteImageServers');
    Route::any('get-software-version', 'AjaxController@getSoftwareVersion')->name('ajax.getSoftwareVersion');
    Route::any('get-engine-version', 'AjaxController@getEngineVersion')->name('ajax.getEngineVersion');
    Route::any('copy-image-server', 'AjaxController@copyImageServer')->name('ajax.copyImageServer');
    Route::any('request-more-infomation/{id}', 'AjaxController@requestMoreInfomation')->name('ajax.requestMoreInfomation');
    Route::any('update-user-multiaz/{id}', 'AjaxController@updateUserMultiAz')->name('ajax.updateUserMultiAz');
    Route::any('export-users', 'AjaxController@exportUsers')->name('ajax.exportUsers');
    Route::any('export-jobs', 'AjaxController@exportJobs')->name('ajax.exportJobs');
    Route::any('update-job-cost', 'AjaxController@updateJobCost')->name('ajax.updateJobCost');
    Route::any('update-user-preview-limit/{id}', 'AjaxController@updateUserPreviewLimit')->name('ajax.updateUserPreviewLimit');
    Route::any('update-user-auto-sync-asset/{id}', 'AjaxController@updateAutoSyncAsset')->name('ajax.updateAutoSyncAsset');
    Route::any('black-domain', 'AjaxController@getBlackDomain')->name('ajax.getBlackDomain');
    Route::any('while-domain', 'AjaxController@getWhileDomain')->name('ajax.getWhileDomain');
    Route::any('delete-black-domain/{id}', 'AjaxController@deleteBlackDomain')->name('ajax.deleteBlackDomain');
    Route::any('delete-while-domain/{id}', 'AjaxController@deleteWhileDomain')->name('ajax.deleteWhileDomain');
    Route::any('add-black-domain', 'AjaxController@addBlackDomain')->name('ajax.addBlackDomain');
    Route::any('add-while-domain', 'AjaxController@addWhileDomain')->name('ajax.addWhileDomain');
    Route::any('get-list-group-discount', 'AjaxController@getGroupDiscount')->name('ajax.getGroupDiscount');
    Route::post('update-group-discount', 'AjaxController@updateGroupDiscount')->name('ajax.updateGroupDiscount');
    Route::any('delete-group-discount', 'AjaxController@deleteGroupDiscount')->name('ajax.deleteGroupDiscount');
    Route::any('update-user-company/{id}', 'AjaxController@updateUserCompany')->name('ajax.updateUserCompany');
    Route::any('update-user-note/{id}', 'AjaxController@updateUserNote')->name('ajax.updateUserNote');
    Route::any('export-accounting', 'AjaxController@exportAccounting')->name('ajax.exportAccounting');
    Route::any('export-user-expense', 'AjaxController@exportUserExpense')->name('ajax.exportUserExpense');
    Route::any('update-user-country-code/{id}', 'AjaxController@updateUserCountry')->name('ajax.updateUserCountry');
    Route::any('utm-list', 'AjaxController@utmList')->name('ajax.utmList');
    Route::any('update-user-student/{id}', 'AjaxController@updateUserStudent')->name('ajax.updateUserStudent');
    Route::any('update-user-download-dataset/{id}', 'AjaxController@updateUserDownloadDataset')->name('ajax.updateUserDownloadDataset');
    Route::any('ovrUserLv/{id}', 'AjaxController@ovrUserLv')->where(array('id' => '[0-9]+'))->name('ajax.ovrUserLv');
    Route::post('updateJobRenderTime', 'AjaxController@updateJobRenderTime')->where(array('id' => '[0-9]+'))->name('ajax.job.timeRender');
    Route::any('updateJobMachineType/{id}', 'AjaxController@updateJobMachineType')->where(array('id' => '[0-9]+'))->name('ajax.updateJobMachineType');
    Route::any('updateDefaultPackage', 'AjaxController@updateDefaultPackage')->name('ajax.updateDefaultPackage');
    Route::any('list-swpt', 'AjaxController@ListSoftwarePackageType')->name('ajax.ListSoftwarePackageType');
    Route::any('mark-as-old-user/{id}', 'AjaxController@markOldUser')->where(array('id' => '[0-9]+'))->name('ajax.user.markOldUser');
    Route::any('update-user-region/{id}', 'AjaxController@updateRegion')->where(array('id' => '[0-9]+'))->name('ajax.user.updateRegion');
    Route::any('update-status-region', 'AjaxController@updateStatusRegion')->name('updateStatusRegion');
    Route::any('update-user-status-column/{id}', 'AjaxController@updateUserStatusColumn')->name('updateUserStatusColumn');
    Route::any('update-user-config/{id}', 'AjaxController@adminUpdateUserConfig')->name('ajax.adminUpdateUserConfig');
    Route::any('notify-reload-user-app/{id}', 'AjaxController@notifyReloadDesktopApp')->name('ajax.notifyReloadDesktopApp');
    Route::any('list-system-env', 'AjaxController@systemEnvList')->name('ajax.system_env.list');
    Route::any('update-custom-system-env', 'AjaxController@updateCustomSystemEnv')->name('ajax.updateCustomSystemEnv');
    Route::any('add-custom-system-env', 'AjaxController@addCustomSystemEnv')->name('ajax.addCustomSystemEnv');
    Route::any('export-users/last-activity', 'AjaxController@exportUsersLastActivity')->name('ajax.exportUsersLastActivity');
    Route::any('force-sync-user-asset/{user_email}', 'AjaxController@forceSyncUserAccess')->name('ajax.forceSyncUserAccess');
    Route::any('force-sync-user-output', 'AjaxController@forceSyncUserOutput')->name('ajax.forceSyncUserOutput');
    Route::any('list-engine-version', 'AjaxController@engineVersionList')->name('ajax.engineVersionList');
    Route::any('delete-engine-version', 'AjaxController@engineVersionDelete')->name('ajax.engineVersionDelete');
    
    //Goi den ham controller List
    Route::get('list-support-software','AjaxController@supportSoftwareList')
    ->name('ajax.supportSoftwareList');
    //Delete Software
    Route::any('delete-support-software', 'AjaxController@supportSoftwareDelete')
    ->name('ajax.supportSoftwareDelete');
    

});


//Route Test API
Route::post('tinsert', 'TestAPI@insertAPI')
->name('APIinsert');

Route::post('tupdate', 'TestAPI@updateAPI')
->name('APIupdate');
