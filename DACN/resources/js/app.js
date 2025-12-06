import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Import jQuery và DataTables
import $ from 'jquery';
window.$ = window.jQuery = $;

// Import DataTables config
import { dataTablesDefaultConfig, initDataTable, initAllDataTables } from './datatables-config';
window.dataTablesDefaultConfig = dataTablesDefaultConfig;
window.initDataTable = initDataTable;
window.initAllDataTables = initAllDataTables;
