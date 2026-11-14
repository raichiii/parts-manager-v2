/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))
require('@fortawesome/fontawesome-free/js/all.js');
require('bootstrap-table/dist/bootstrap-table.min.css');
require('bootstrap-table/dist/bootstrap-table.js');
require('bootstrap-table/dist/bootstrap-table-locale-all.min.js');

require('tableexport.jquery.plugin');
require('bootstrap-table/dist/extensions/export/bootstrap-table-export.min.js');
require('bootstrap-table/dist/extensions/print/bootstrap-table-print.min.js');
require('bootstrap-table/dist/extensions/filter-control/bootstrap-table-filter-control.min.css');
require('bootstrap-table/dist/extensions/filter-control/bootstrap-table-filter-control.min.js');
require('bootstrap-table/dist/extensions/resizable/bootstrap-table-resizable.min.js');

// require('vue-popperjs/dist/vue-popper.min.js');

require('jquery-resizable-columns/dist/jquery.resizableColumns.js');
require('jquery-resizable-columns/dist/jquery.resizableColumns.min.js');
require('jquery-resizable-columns/dist/jquery.resizableColumns.css');

Vue.component('example-component', require('./components/ExampleComponent').default);
Vue.component('searchbar-component', require('./components/Searchbar').default);
Vue.component('bootstrap-table-component', require('./components/BootstrapTableComponent').default);
Vue.component('auto-complete-component', require('./components/AutoCompleteComponent').default);
Vue.component('vuejs-datepicker-component', require('./components/VueJsDatePickerComponent').default);
Vue.component('simple-datepicker-component', require('./components/SimpleDatePickerComponent').default);
Vue.component('comfirm-component', require('./components/ComfirmComponent').default);
Vue.component('popper-component', require('./components/PopperComponent').default);
Vue.component('file-upload-component', require('./components/FileUploadComponent').default);

require('./components/address/companies/index');
require('./components/address/companies/create');
require('./components/address/companies/edit');
require('./components/address/offices/create');
require('./components/address/offices/edit');
require('./components/address/customers/create');
require('./components/address/customers/edit');
require('./components/address/individuals/create');
require('./components/address/individuals/edit');

require('./components/board/parts/index');
require('./components/board/parts/create');
require('./components/board/parts/edit');
require('./components/board/parts/kensaku');
require('./components/board/mountedBoards/create');
require('./components/board/mountedBoards/edit');
require('./components/board/printCircuitBoards/index');
require('./components/board/printCircuitBoards/create');
require('./components/board/printCircuitBoards/edit');
require('./components/board/detailedBoards/create');
require('./components/board/detailedBoards/edit');
require('./components/board/detailedBoards/index');
require('./components/board/detailedBoards/kensaku');
require('./components/board/others/index');
require('./components/board/others/create');
require('./components/board/others/edit');

require('./components/order/jobs/index');
require('./components/order/jobs/create');
require('./components/order/jobs/edit');
require('./components/order/requestedLists/index');
require('./components/order/requestedLists/create');
require('./components/order/requestedLists/edit');
require('./components/order/orderedLists/index');
require('./components/order/orderedLists/create');
require('./components/order/orderedLists/edit');
require('./components/order/purchasedLists/index');
require('./components/order/purchasedLists/create');
require('./components/order/purchasedLists/edit');
require('./components/order/unregisteredModels/index');

require('./components/other/accountLists/create');
require('./components/other/accountLists/edit');
require('./components/other/textInfos/index');
require('./components/other/chatgpt/index');


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */


$('.access-popover').popover().click(function () {
    let pop = $(this);
    setTimeout(function () {
        pop.popover('hide');
    }, 3000)
});
