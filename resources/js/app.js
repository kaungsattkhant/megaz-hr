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

const firebaseConfig = {
    apiKey: import.meta.env.VITE_GOOGLE_API_KEY,
    authDomain: import.meta.env.VITE_GOOGLE_AUTH_DOMAIN,
    projectId: import.meta.env.VITE_GOOGLE_PROJECT_ID,
    storageBucket: import.meta.env.VITE_GOOGLE_STORAGE_BUCKET,
    messagingSenderId: import.meta.env.VITE_GOOGLE_MESSAGING_SENDER_ID,
    appId: import.meta.env.VITE_GOOGLE_APP_ID,
    measurementId: import.meta.env.VITE_GOOGLE_MEASUREMENT_ID
};

const app = createApp({});
firebase.initializeApp(firebaseConfig);

import NavBarComponent from './Components/Common/NavBarComponent.vue';
import StaffListComponent from './Components/Staff/StaffListComponent.vue';
import StaffCreateComponent from './Components/Staff/StaffCreateComponent.vue';
import StaffEditComponent from './Components/Staff/StaffEditComponent.vue';
import TasksCrudComponent from './Components/Tasks/TasksCrudComponent.vue';
import TaskReportComponent from './Components/Tasks/TaskReportComponent.vue';
import DepartmentsCrudComponent from './Components/Departments/DepartmentsCrudComponent.vue';
import RolesCrudComponent from './Components/Roles/RolesCrudComponent.vue';
import AreasCrudComponent from './Components/Areas/AreasCrudComponent.vue';
import InventoriesCrudComponent from './Components/Inventories/InventoriesCrudComponent.vue';
import RoomCrudComponent from './Components/TablesAndRooms/RoomCrudComponent.vue';
import ServicesCrudComponent from './Components/Services/ServicesCrudComponent.vue';
import InventoryLedgersComponent from './Components/Inventories/InventoryLedgersComponent.vue';
import InventoryReceivesListComponent from './Components/Transfers/InventoryReceivesListComponent.vue';
import InventoryTransfersListComponent from './Components/Transfers/InventoryTransfersListComponent.vue';
import InventoryTransferHistoryListComponent from './Components/Transfers/InventoryTransferHistoryListComponent.vue';
import UsedDefectedItemsListComponent from './Components/UsedDefectedItems/UsedDefectedItemsListComponent.vue';
import MenuCategoryCrudComponent from './Components/Menus/MenuCategoryCrudComponent.vue';
import MenuListComponent from './Components/Menus/MenuListComponent.vue';
import MenuCreateComponent from './Components/Menus/MenuCreateComponent.vue';
import MenuEditComponent from './Components/Menus/MenuEditComponent.vue';
import ComplainsCrudComponent from './Components/Complains/ComplainsCrudComponent.vue';
import PurchaseOrderListComponent from './Components/PurchaseOrders/PurchaseOrderListComponent.vue';
import PurchaseOrderCreateComponent from './Components/PurchaseOrders/PurchaseOrderCreateComponent.vue';
import PurchaseOrderConfirmComponent from './Components/PurchaseOrders/PurchaseOrderConfirmComponent.vue';
import PurchaseOrderBuyComponent from './Components/PurchaseOrders/PurchaseOrderBuyComponent.vue';
import ConfirmPurchaseOrderItemsComponent from './Components/PurchaseOrders/ConfirmPurchaseOrderItemsComponent.vue';
import PurchaseOrderWithLeftItemsComponent from './Components/PurchaseOrders/PurchaseOrderWithLeftItemsComponent.vue';
import LeftItemListComponent from './Components/PurchaseOrders/LeftItemListComponent.vue';
import ItemCrudComponent from './Components/Items/ItemCrudComponent.vue';
import ItemPricingHistoryComponent from './Components/Items/ItemPricingHistoryComponent.vue';
import ItemUsageForecastListComponent from './Components/ItemUsageForecastings/ItemUsageForecastListComponent.vue';
import ItemUsageForecastCreateComponent from './Components/ItemUsageForecastings/ItemUsageForecastCreateComponent.vue';
import ItemUsageForecastDetailComponent from './Components/ItemUsageForecastings/ItemUsageForecastDetailComponent.vue';
import UomConversionCrudComponent from './Components/ItemUom/UomConversionCrudComponent.vue';
import AccountingCrudComponent from './Components/Accounting/AccountingCrudComponent.vue';
import FinancialTransactionCrudComponent from './Components/FinancialTransaction/FinancialTransactionCrudComponent.vue';
import CashbookCrudComponent from './Components/Cashbook/CashbookCrudComponent.vue';
import ApListComponent from './Components/AP/ApListComponent.vue';
import ApHistoryComponent from './Components/AP/ApHistoryComponent.vue';
import ArCrudComponent from './Components/AR/ArCrudComponent.vue';
import ArDetailComponent from './Components/AR/ArDetailComponent.vue';
import ArHistoryComponent from './Components/AR/ArHistoryComponent.vue';
import ArPaidComponent from './Components/AR/ArPaidComponent.vue';
import SupplierListComponent from './Components/Supplier/SupplierListComponent.vue';
import SupplierCreateComponent from './Components/Supplier/SupplierCreateComponent.vue';
import SupplierUpdateComponent from './Components/Supplier/SupplierUpdateComponent.vue';
import FixedAssetCrudComponent from './Components/FixedAssets/FixedAssetCrudComponent.vue';
import AssetItemCrudComponent from './Components/FixedAssets/AssetItemCrudComponent.vue';
import AssetCrudComponent from './Components/FixedAssets/AssetCrudComponent.vue';
import TableCrudComponent from './Components/TablesAndRooms/TableCrudComponent.vue';
import PackagesListComponent from './Components/Packages/PackagesListComponent.vue';
import PackagesCreateComponent from './Components/Packages/PackagesCreateComponent.vue';
import MenuAndServiceDiscountCrudComponent from './Components/MenuAndServiceDiscount/MenuAndServiceDiscountCrudComponent.vue';
import RoomDiscountCrudComponent from './Components/RoomDiscount/RoomDiscountCrudComponent.vue';
import DeliveryChargesCrudComponent from './Components/DeliveryCharges/DeliveryChargesCrudComponent.vue';

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
import PosBookingListComponent from './Components/Pos/Booking/PosBookingListComponent.vue';
import PosBookingCreateComponent from './Components/Pos/Booking/PosBookingCreateComponent.vue';
import PosMenuOrderComponent from './Components/Pos/MenuOrder/PosMenuOrderComponent.vue';
import UomCrudComponent from './Components/Uoms/UomCrudComponent.vue';

import LoginComponentPos from './Components/Pos/Auth/LoginComponentPos.vue';
import LogoutComponentPos from './Components/Pos/Auth/LogoutComponentPos.vue';
import PosNotificationComponent from './Components/Pos/Common/PosNotificationComponent.vue';

import CrmCustomerListComponent from './Components/CRM/CustomerListComponent.vue';
import CrmCustomerBirthdaysListComponent from './Components/CRM/CustomerBirthdaysListComponent.vue';
import CrmCustomerDetailComponent from './Components/CRM/CustomerDetailComponent.vue';
import LevelDiscountCrudComponent from './Components/CRM/LevelDiscountCrudComponent.vue';
import BirthdayPromotionCrudComponent from './Components/CRM/BirthdayPromotionCrudComponent.vue';
import CustomTaskCrudComponent from './Components/Tasks/CustomTaskCrudComponent.vue';
import TasksReportComponent from './Components/Tasks/TasksReportComponent.vue';
import JournalsCrudComponent from './Components/Journals/JournalsCrudComponent.vue';
import AdvancedCrudComponent from './Components/Advanced/AdvancedCrudComponent.vue';
import AdvancedDetailComponent from './Components/Advanced/AdvancedDetailComponent.vue';
import PrepaidCrudComponent from './Components/Prepaid/PrepaidCrudComponent.vue';
import CashFlowStatementComponent from './Components/CashFlowStatement/CashFlowStatementComponent.vue';
import SkillCrudComponent from './Components/Skill/SkillCrudComponent.vue';
import CookingPlaceComponent from './Components/CookingPlace/CookingPlaceComponent.vue';


app.component('NavBarComponent', NavBarComponent);
app.component('StaffListComponent', StaffListComponent);
app.component('StaffCreateComponent', StaffCreateComponent);
app.component('StaffEditComponent', StaffEditComponent);
app.component('TasksCrudComponent', TasksCrudComponent);
app.component('TaskReportComponent', TaskReportComponent);
app.component('DepartmentsCrudComponent', DepartmentsCrudComponent);
app.component('RolesCrudComponent', RolesCrudComponent);
app.component('AreasCrudComponent', AreasCrudComponent);
app.component('InventoriesCrudComponent', InventoriesCrudComponent);
app.component('RoomCrudComponent', RoomCrudComponent);
app.component('ServicesCrudComponent', ServicesCrudComponent);
app.component('InventoryLedgersComponent', InventoryLedgersComponent);
app.component('InventoryReceivesListComponent', InventoryReceivesListComponent);
app.component('InventoryTransfersListComponent', InventoryTransfersListComponent);
app.component('InventoryTransferHistoryListComponent', InventoryTransferHistoryListComponent);
app.component('UsedDefectedItemsListComponent', UsedDefectedItemsListComponent);
app.component('MenuCategoryCrudComponent', MenuCategoryCrudComponent);
app.component('MenuListComponent', MenuListComponent);
app.component('MenuCreateComponent', MenuCreateComponent);
app.component('MenuEditComponent', MenuEditComponent);
app.component('ComplainsCrudComponent', ComplainsCrudComponent);
app.component('AccountingCrudComponent', AccountingCrudComponent);
app.component('FinancialTransactionCrudComponent', FinancialTransactionCrudComponent);
app.component('CashbookCrudComponent', CashbookCrudComponent);
app.component('ApListComponent', ApListComponent);
app.component('ApHistoryComponent', ApHistoryComponent);
app.component('ArCrudComponent', ArCrudComponent);
app.component('ArDetailComponent', ArDetailComponent);
app.component('ArHistoryComponent', ArHistoryComponent);
app.component('ArPaidComponent', ArPaidComponent);
app.component('PackagesListComponent', PackagesListComponent);
app.component('PackagesCreateComponent', PackagesCreateComponent);
app.component('MenuAndServiceDiscountCrudComponent', MenuAndServiceDiscountCrudComponent);
app.component('RoomDiscountCrudComponent', RoomDiscountCrudComponent);
app.component('DeliveryChargesCrudComponent', DeliveryChargesCrudComponent);

app.component('PurchaseOrderListComponent', PurchaseOrderListComponent);
app.component('PurchaseOrderCreateComponent', PurchaseOrderCreateComponent);
app.component('PurchaseOrderConfirmComponent', PurchaseOrderConfirmComponent);
app.component('PurchaseOrderBuyComponent', PurchaseOrderBuyComponent);
app.component('ConfirmPurchaseOrderItemsComponent', ConfirmPurchaseOrderItemsComponent);
app.component('PurchaseOrderWithLeftItemsComponent', PurchaseOrderWithLeftItemsComponent);
app.component('LeftItemListComponent', LeftItemListComponent);
app.component('ItemCrudComponent', ItemCrudComponent);
app.component('ItemPricingHistoryComponent', ItemPricingHistoryComponent);
app.component('ItemUsageForecastListComponent', ItemUsageForecastListComponent);
app.component('ItemUsageForecastCreateComponent', ItemUsageForecastCreateComponent);
app.component('ItemUsageForecastDetailComponent', ItemUsageForecastDetailComponent);
app.component('UomConversionCrudComponent', UomConversionCrudComponent);
app.component('UomCrudComponent',UomCrudComponent);
app.component('TableCrudComponent', TableCrudComponent);


app.component('SupplierListComponent', SupplierListComponent);
app.component('SupplierCreateComponent', SupplierCreateComponent);
app.component('SupplierUpdateComponent', SupplierUpdateComponent);

app.component('FixedAssetCrudComponent', FixedAssetCrudComponent);
app.component('AssetItemCrudComponent', AssetItemCrudComponent);
app.component('AssetCrudComponent', AssetCrudComponent);

app.component('LoginComponent', LoginComponent);
app.component('LogoutComponent', LogoutComponent);
app.component('PosNotificationComponent', PosNotificationComponent);

app.component('CustomersListComponent', CustomersListComponent);
app.component('CustomersCreateComponent', CustomersCreateComponent);
app.component('HomePageComponent', HomePageComponent);
app.component('PosArCrudComponent', PosArCrudComponent);
app.component('PosCashbookCrudComponent', PosCashbookCrudComponent);
app.component('CashbookDetailComponent', CashbookDetailComponent);
app.component('InvoiceListComponent', InvoiceListComponent);
app.component('InvoiceDetailComponent', InvoiceDetailComponent);
app.component('PosBookingListComponent', PosBookingListComponent);
app.component('PosBookingCreateComponent', PosBookingCreateComponent);
app.component('PosMenuOrderComponent', PosMenuOrderComponent);

app.component('LoginComponentPos', LoginComponentPos);
app.component('LogoutComponentPos', LogoutComponentPos);

app.component('CrmCustomerListComponent', CrmCustomerListComponent);
app.component('CrmCustomerBirthdaysListComponent', CrmCustomerBirthdaysListComponent);
app.component('LevelDiscountCrudComponent', LevelDiscountCrudComponent);
app.component('BirthdayPromotionCrudComponent', BirthdayPromotionCrudComponent);
app.component('CrmCustomerDetailComponent', CrmCustomerDetailComponent);
app.component('CustomTaskCrudComponent', CustomTaskCrudComponent);
app.component('TasksReportComponent',TasksReportComponent);
app.component('JournalsCrudComponent',JournalsCrudComponent);
app.component('AdvancedCrudComponent',AdvancedCrudComponent);
app.component('AdvancedDetailComponent',AdvancedDetailComponent);
app.component('PrepaidCrudComponent',PrepaidCrudComponent);
app.component('CashFlowStatementComponent',CashFlowStatementComponent);
app.component('SkillCrudComponent',SkillCrudComponent);
app.component('CookingPlaceComponent',CookingPlaceComponent);

app.use(store);
app.use(Notifications);
app.mount('#app');


