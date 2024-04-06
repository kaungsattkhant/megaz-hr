import './bootstrap';
import '../css/app.css';

///////////// Tailwind section //////////////
import { Collapse, Select, Carousel, initTE, Modal, Ripple, Dropdown, Datepicker, Input, Tab } from 'tw-elements';
initTE({ Collapse, Select, Carousel, Modal, Ripple, Dropdown, Datepicker, Input, Tab});

//////////////................/////////////////

import {createApp} from 'vue/dist/vue.esm-bundler';
import { store } from './Store';
import firebase from 'firebase/compat/app';
import 'firebase/compat/messaging';
import Notifications from '@kyvg/vue3-notification';

// const firebaseConfig = {
//     apiKey: import.meta.env.VITE_GOOGLE_API_KEY,
//     authDomain: import.meta.env.VITE_GOOGLE_AUTH_DOMAIN,
//     projectId: import.meta.env.VITE_GOOGLE_PROJECT_ID,
//     storageBucket: import.meta.env.VITE_GOOGLE_STORAGE_BUCKET,
//     messagingSenderId: import.meta.env.VITE_GOOGLE_MESSAGING_SENDER_ID,
//     appId: import.meta.env.VITE_GOOGLE_APP_ID
// };

const firebaseConfig = {
    apiKey: "AIzaSyA_RiMlzMDYxKF_iT8wBxPAW3NpKxEPxas",
    authDomain: "megaz-project.firebaseapp.com",
    projectId: "megaz-project",
    storageBucket: "megaz-project.appspot.com",
    messagingSenderId: "25949618078",
    appId: "1:25949618078:web:86369862c2a1fc084ca08f",
    measurementId: "G-M6MF9D78DD"
  };
//   const firebaseConfig = {
//     apiKey: "AIzaSyAyQupmlsJiHgzdJaTH8Os4uq9Wij268YA",
//     authDomain: "megaz-test.firebaseapp.com",
//     projectId: "megaz-test",
//     storageBucket: "megaz-test.appspot.com",
//     messagingSenderId: "105252991244",
//     appId: "1:105252991244:web:e4e4234ee6c8a2e6c25929",
//     measurementId: "G-7832JEYHFH"
//   };

const app = createApp({});
firebase.initializeApp(firebaseConfig);

import NavBarComponent from './Components/Common/NavBarComponent.vue';
import StaffListComponent from './Components/Staff/StaffListComponent.vue';
import StaffCreateComponent from './Components/Staff/StaffCreateComponent.vue';
import TasksCrudComponent from './Components/Tasks/TasksCrudComponent.vue';
import DepartmentsCrudComponent from './Components/Departments/DepartmentsCrudComponent.vue';
import RolesCrudComponent from './Components/Roles/RolesCrudComponent.vue';
import AreasCrudComponent from './Components/Areas/AreasCrudComponent.vue';
import InventoriesCrudComponent from './Components/Inventories/InventoriesCrudComponent.vue';
import RoomCrudComponent from './Components/TablesAndRooms/RoomCrudComponent.vue';
import ServicesCrudComponent from './Components/Services/ServicesCrudComponent.vue';
import InventoryLedgersComponent from './Components/Inventories/InventoryLedgersComponent.vue';
import MenuListComponent from './Components/Menus/MenuListComponent.vue';
import MenuCreateComponent from './Components/Menus/MenuCreateComponent.vue';
import ComplainsCrudComponent from './Components/Complains/ComplainsCrudComponent.vue';
import PurchaseOrderListComponent from './Components/PurchaseOrders/PurchaseOrderListComponent.vue';
import PurchaseOrderCreateComponent from './Components/PurchaseOrders/PurchaseOrderCreateComponent.vue';
import PurchaseOrderConfirmComponent from './Components/PurchaseOrders/PurchaseOrderConfirmComponent.vue';
import ItemCrudComponent from './Components/Items/ItemCrudComponent.vue';
import ItemUsageForecastListComponent from './Components/ItemUsageForecastings/ItemUsageForecastListComponent.vue';
import ItemUsageForecastCreateComponent from './Components/ItemUsageForecastings/ItemUsageForecastCreateComponent.vue';
import ItemUsageForecastDetailComponent from './Components/ItemUsageForecastings/ItemUsageForecastDetailComponent.vue';
import AccountingCrudComponent from './Components/Accounting/AccountingCrudComponent.vue';
import FinancialTransactionCrudComponent from './Components/FinancialTransaction/FinancialTransactionCrudComponent.vue';
import CashbookCrudComponent from './Components/Cashbook/CashbookCrudComponent.vue';
import ArListComponent from './Components/AR/ArListComponent.vue';
import ArHistoryComponent from './Components/AR/ArHistoryComponent.vue';
import ArPaidComponent from './Components/AR/ArPaidComponent.vue';
import SupplierListComponent from './Components/Supplier/SupplierListComponent.vue';
import SupplierCreateComponent from './Components/Supplier/SupplierCreateComponent.vue';
import SupplierUpdateComponent from './Components/Supplier/SupplierUpdateComponent.vue';
import TableCrudComponent from './Components/TablesAndRooms/TableCrudComponent.vue';

import LoginComponent from './Components/Auth/LoginComponent.vue';
import LogoutComponent from './Components/Auth/LogoutComponent.vue';

import CustomersListComponent from './Components/Pos/Customers/CustomersListComponent.vue';
import CustomersCreateComponent from './Components/Pos/Customers/CustomersCreateComponent.vue';
import HomePageComponent from './Components/Pos/Home/HomePageComponent.vue';
import PosArCrudComponent from './Components/Pos/AR/PosArCrudComponent.vue';
import PosCashbookCrudComponent from './Components/Pos/Cashbook/PosCashbookCrudComponent.vue';
import CashbookDetailComponent from './Components/Pos/Cashbook/CashbookDetailComponent.vue';
import InvoiceListComponent from './Components/Pos/Invoices/InvoiceListComponent.vue';
import InvoiceDetailComponent from './Components/Pos/Invoices/InvoiceDetailComponent.vue';

import LoginComponentPos from './Components/Pos/Auth/LoginComponentPos.vue';

app.component('NavBarComponent', NavBarComponent);
app.component('StaffListComponent', StaffListComponent);
app.component('StaffCreateComponent', StaffCreateComponent);
app.component('TasksCrudComponent', TasksCrudComponent);
app.component('DepartmentsCrudComponent', DepartmentsCrudComponent);
app.component('RolesCrudComponent', RolesCrudComponent);
app.component('AreasCrudComponent', AreasCrudComponent);
app.component('InventoriesCrudComponent', InventoriesCrudComponent);
app.component('RoomCrudComponent', RoomCrudComponent);
app.component('ServicesCrudComponent', ServicesCrudComponent);
app.component('InventoryLedgersComponent', InventoryLedgersComponent);
app.component('MenuListComponent', MenuListComponent);
app.component('MenuCreateComponent', MenuCreateComponent);
app.component('ComplainsCrudComponent', ComplainsCrudComponent);
app.component('AccountingCrudComponent', AccountingCrudComponent);
app.component('FinancialTransactionCrudComponent', FinancialTransactionCrudComponent);
app.component('CashbookCrudComponent', CashbookCrudComponent);
app.component('ArListComponent', ArListComponent);
app.component('ArHistoryComponent', ArHistoryComponent);
app.component('ArPaidComponent', ArPaidComponent);

app.component('PurchaseOrderListComponent', PurchaseOrderListComponent);
app.component('PurchaseOrderCreateComponent', PurchaseOrderCreateComponent);
app.component('PurchaseOrderConfirmComponent', PurchaseOrderConfirmComponent);
app.component('ItemCrudComponent', ItemCrudComponent);
app.component('ItemUsageForecastListComponent', ItemUsageForecastListComponent);
app.component('ItemUsageForecastCreateComponent', ItemUsageForecastCreateComponent);
app.component('ItemUsageForecastDetailComponent', ItemUsageForecastDetailComponent);
app.component('TableCrudComponent', TableCrudComponent);


app.component('SupplierListComponent', SupplierListComponent);
app.component('SupplierCreateComponent', SupplierCreateComponent);
app.component('SupplierUpdateComponent', SupplierUpdateComponent);

app.component('LoginComponent', LoginComponent);
app.component('LogoutComponent', LogoutComponent);

app.component('CustomersListComponent', CustomersListComponent);
app.component('CustomersCreateComponent', CustomersCreateComponent);
app.component('HomePageComponent', HomePageComponent);
app.component('PosArCrudComponent', PosArCrudComponent);
app.component('PosCashbookCrudComponent', PosCashbookCrudComponent);
app.component('CashbookDetailComponent', CashbookDetailComponent);
app.component('InvoiceListComponent', InvoiceListComponent);
app.component('InvoiceDetailComponent', InvoiceDetailComponent);
app.component('LoginComponentPos', LoginComponentPos);

app.use(store);
app.use(Notifications);
app.mount('#app');
