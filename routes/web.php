<?php

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

Route::redirect('/', 'home');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => ['auth']], function () {
    Route::group(['prefix' => 'users'], function () {
        Route::get('', 'UserController@index')->name('users.index');
        Route::get('{user}', 'UserController@show')->name('users.show');
        Route::put('{user}', 'UserController@update')->name('users.update');
        Route::put('{user}/update_password', 'UserController@updatePassword')->name('users.updatePassword');
        Route::get('{user}/edit', 'UserController@edit')->name('users.edit');
    });

    Route::group(['prefix' => 'configs'], function () {
        Route::resource('', 'ConfigController', ['names' => 'configs']);
        Route::get('edit_all', 'ConfigController@editAll')->name('configs.editAll');
        Route::put('update_all', 'ConfigController@updateAll')->name('configs.updateAll');
    });

    Route::group(['prefix' => 'address'], function () {
        Route::resource('companies', 'Address\CompanyController');
        Route::resource('individuals', 'Address\IndividualController');
        Route::group(['prefix' => 'companies'], function () {

            // /companies/{company}
            Route::group(['prefix' => '{company}'], function () {
                Route::resource('offices', 'Address\OfficeController');

                // /companies/{company}/offices
                Route::group(['prefix' => 'offices'], function () {
                    Route::group(['prefix' => '{office}'], function () {

                        // /companies/{company}/offices/{office}/customers
                        Route::resource('customers', 'Address\CustomerController');
                    });
                });
                // /companies/{company}/customers
                Route::group(['prefix' => 'customers'], function () {
                    Route::get('', 'Address\CustomerController@index')->name('customers.company.index');
                    Route::get('create', 'Address\CustomerController@create')->name('customers.company.create');
                });
            });

            // /companies/offices
            Route::group(['prefix' => 'offices'], function () {
                Route::post('', 'Address\OfficeController@store')->name('offices.form.store');
                //companies/offices/customers
                Route::group(['prefix' => 'customers'], function () {
                    Route::post('', 'Address\CustomerController@store')->name('customers.form.store');
                });
            });
        });
    });

    Route::group(['prefix' => 'board'], function () {
        Route::resource('print_circuit_boards', 'Board\PrintCircuitBoardController', ['names' => 'printCircuitBoards']);
        Route::get('parts/kensaku', 'Board\PartController@kensaku')->name('parts.kensaku');
        Route::post('parts/change_csv', 'Board\PartController@changeCsv')->name('parts.changeCsv');
        Route::get('parts/download_csv/buhin', 'Board\PartController@downloadCsv')->name('parts.downloadCsv');
        Route::get('parts/download_csv/board', 'Board\PrintCircuitBoardController@downloadCsv')->name('printCircuitBoards.downloadCsv');
        Route::resource('parts', 'Board\PartController', ['names' => 'parts']);
        Route::resource('others', 'Board\OtherController', ['names' => 'others']);
        Route::group(['prefix' => 'print_circuit_boards/{print_circuit_board}'], function () {
            Route::resource('mounted_boards', 'Board\MountedBoardController', ['names' => 'mountedBoards']);
        });
        Route::get('detailed_boards/kensaku', 'Board\DetailedBoardController@kensaku')->name('detailedBoards.kensaku');
        Route::post('detailed_boards/change_csv', 'Board\DetailedBoardController@changeCsv')->name('detailedBoards.changeCsv');
        Route::resource('detailed_boards', 'Board\DetailedBoardController', ['names' => 'detailedBoards']);
    });

    Route::group(['prefix' => 'order'], function () {
        Route::resource('jobs', 'Order\JobController');
        Route::resource('unregistered_lists', 'Order\UnregisteredListController', ['names' => 'unregisteredLists']);
        Route::resource('requested_lists', 'Order\RequestedListController', ['names' => 'requestedLists']);
        Route::group(['prefix' => 'requested_lists'], function () {
            Route::post('multi_order', 'Order\RequestedListController@multiOrder')->name('requestedLists.multiOrder');
            Route::post('multi_delete', 'Order\RequestedListController@multiDelete')->name('requestedLists.multiDelete');
            Route::post('import', 'Order\RequestedListController@import')->name('requestedLists.import');
            Route::put('{requested_list}/replicate', 'Order\RequestedListController@replicate')->name('requestedLists.replicate');
            Route::post('purchaseOrder/edit', 'Order\PurchaseOrderController@edit')->name('purchaseOrder.edit');
            Route::post('purchaseOrder', 'Order\PurchaseOrderController@make')->name('purchaseOrder.make');
        });
        Route::resource('ordered_lists', 'Order\OrderedListController', ['names' => 'orderedLists']);
        Route::group(['prefix' => 'ordered_lists'], function () {
            Route::post('multi_receive', 'Order\OrderedListController@multiReceive')->name('orderedLists.multiReceive');
            Route::post('multi_delete', 'Order\OrderedListController@multiDelete')->name('orderedLists.multiDelete');
            Route::post('import', 'Order\OrderedListController@import')->name('orderedLists.import');
        });
        Route::resource('purchased_lists', 'Order\PurchasedListController', ['names' => 'purchasedLists']);
        Route::group(['prefix' => 'purchased_lists'], function () {
            Route::post('multi_delete', 'Order\PurchasedListController@multiDelete')->name('purchasedLists.multiDelete');
        });
        Route::resource('unregistered_models', 'Order\UnregisteredModelController', ['names' => 'unregisteredModels']);
    });

    Route::group(['prefix' => 'other'], function () {
        Route::resource('account_lists', 'Other\AccountListController', ['names' => 'accountLists']);
        Route::resource('text_infos', 'Other\TextInfoController', ['names' => 'textInfos']);
        Route::resource('chatgpt', 'Other\ChatGPTController', ['names' => 'chatgpt']);
    });
});
