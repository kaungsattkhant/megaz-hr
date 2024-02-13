import './bootstrap';
import '../css/app.css';

///////////// Tailwind section //////////////
import { Collapse, Select, Carousel, initTE, Modal, Ripple, Dropdown, Datepicker } from 'tw-elements';
initTE({ Collapse, Select, Carousel, Modal, Ripple, Dropdown, Datepicker});

//////////////................/////////////////

import {createApp} from 'vue/dist/vue.esm-bundler';

import StaffListComponent from './Components/Staff/StaffListComponent.vue';
import StaffCreateComponent from './Components/Staff/StaffCreateComponent.vue';
import TasksCrudComponent from './Components/Tasks/TasksCrudComponent.vue';
import DepartmentsCrudComponent from './Components/Departments/DepartmentsCrudComponent.vue';
import RolesCrudComponent from './Components/Roles/RolesCrudComponent.vue';

const app = createApp({});
app.component('StaffListComponent', StaffListComponent);
app.component('StaffCreateComponent', StaffCreateComponent);
app.component('TasksCrudComponent', TasksCrudComponent);
app.component('DepartmentsCrudComponent', DepartmentsCrudComponent);
app.component('RolesCrudComponent', RolesCrudComponent);

app.mount('#app');
