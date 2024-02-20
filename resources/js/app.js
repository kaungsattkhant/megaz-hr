import './bootstrap';
import '../css/app.css';

///////////// Tailwind section //////////////
import { Collapse, Select, Carousel, initTE, Modal, Ripple, Dropdown, Datepicker, Tab } from 'tw-elements';
initTE({ Collapse, Select, Carousel, Modal, Ripple, Dropdown, Datepicker, Tab});

//////////////................/////////////////

import {createApp} from 'vue/dist/vue.esm-bundler';

import StaffListComponent from './Components/Staff/StaffListComponent.vue';
import StaffCreateComponent from './Components/Staff/StaffCreateComponent.vue';
import TasksCrudComponent from './Components/Tasks/TasksCrudComponent.vue';
import DepartmentsCrudComponent from './Components/Departments/DepartmentsCrudComponent.vue';
import RolesCrudComponent from './Components/Roles/RolesCrudComponent.vue';
import AreasCrudComponent from './Components/Areas/AreasCrudComponent.vue';
import InventoriesCrudComponent from './Components/Inventories/InventoriesCrudComponent.vue';
import TablesCrudComponent from './Components/TablesAndRooms/TablesCrudComponent.vue';
import ServicesCrudComponent from './Components/Services/ServicesCrudComponent.vue';
import InventoryLedgersComponent from './Components/Inventories/InventoryLedgersComponent.vue';
import MenuListComponent from './Components/Menus/MenuListComponent.vue';
import MenuCreateComponent from './Components/Menus/MenuCreateComponent.vue';

const app = createApp({});
app.component('StaffListComponent', StaffListComponent);
app.component('StaffCreateComponent', StaffCreateComponent);
app.component('TasksCrudComponent', TasksCrudComponent);
app.component('DepartmentsCrudComponent', DepartmentsCrudComponent);
app.component('RolesCrudComponent', RolesCrudComponent);
app.component('AreasCrudComponent', AreasCrudComponent);
app.component('InventoriesCrudComponent', InventoriesCrudComponent);
app.component('TablesCrudComponent', TablesCrudComponent);
app.component('ServicesCrudComponent', ServicesCrudComponent);
app.component('InventoryLedgersComponent', InventoryLedgersComponent);
app.component('MenuListComponent', MenuListComponent);
app.component('MenuCreateComponent', MenuCreateComponent);

app.mount('#app');
