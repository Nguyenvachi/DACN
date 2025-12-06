/**
 * DataTables Configuration
 * File này chứa config chung cho DataTables trong toàn bộ project
 */

// Import DataTables và plugins
import 'datatables.net-bs5';
import 'datatables.net-responsive-bs5';
import 'datatables.net-buttons-bs5';

// Import CSS
import 'datatables.net-bs5/css/dataTables.bootstrap5.min.css';
import 'datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css';
import 'datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css';

/**
 * Config mặc định cho DataTables - tiếng Việt
 */
export const dataTablesDefaultConfig = {
    language: {
        "sProcessing": "Đang xử lý...",
        "sLengthMenu": "Hiển thị _MENU_ dòng",
        "sZeroRecords": "Không tìm thấy dữ liệu",
        "sInfo": "Hiển thị _START_ đến _END_ trong tổng số _TOTAL_ dòng",
        "sInfoEmpty": "Hiển thị 0 đến 0 trong tổng số 0 dòng",
        "sInfoFiltered": "(lọc từ _MAX_ dòng)",
        "sSearch": "Tìm kiếm:",
        "sUrl": "",
        "oPaginate": {
            "sFirst": "Đầu",
            "sPrevious": "Trước",
            "sNext": "Tiếp",
            "sLast": "Cuối"
        }
    },
    responsive: true,
    pageLength: 25,
    lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Tất cả"]],
    order: [[0, 'desc']], // Mặc định sắp xếp cột đầu tiên giảm dần
    columnDefs: [
        { orderable: false, targets: -1 } // Disable sort cho cột cuối cùng (thường là actions)
    ]
};

/**
 * Initialize DataTables cho một table cụ thể
 * @param {string} selector - jQuery selector của table
 * @param {object} customConfig - Config tùy chỉnh (optional)
 */
export function initDataTable(selector, customConfig = {}) {
    if (typeof $ !== 'undefined' && $.fn.DataTable) {
        const config = { ...dataTablesDefaultConfig, ...customConfig };
        return $(selector).DataTable(config);
    } else {
        console.error('jQuery hoặc DataTables chưa được load');
    }
}

/**
 * Initialize DataTables cho tất cả tables với class .data-table
 */
export function initAllDataTables() {
    if (typeof $ !== 'undefined' && $.fn.DataTable) {
        $('.data-table').each(function() {
            if (!$.fn.DataTable.isDataTable(this)) {
                $(this).DataTable(dataTablesDefaultConfig);
            }
        });
    }
}
