/**
 * Vue model used to provide data and events for the user management page
 * @see resources/views/user/management.blade.php
 */

import ResizeObserver from 'resize-observer-polyfill';

const errorHandler = require('../util/error-handler');
const side_bar_width = $('#side-menu').width();

if (window.page.route_name === 'user_management') {

    const user_management_section = $('#user_management');
    const user_filter_labels = {
        all: 'All Users',
        active: 'Active Users',
        inactive: 'Inactive Users'
    };

    const management = new Vue({
        el: '#user_management',
        data: {
            employees: window.page.employees,
            total_num_of_employees: window.page.total_num_of_employees,
            departments: window.page.departments,
            rows_per_page: 15,
            current_page: 1,
            department_filter: '',
            time_filter: '',
            role_filter: '',
            inline_editing_field: null,
            employee_being_edited: null,
            open_drop_down: '',
            search_results: [],
            pagination_bar_width: 0,
            pagination_bar_left_pos: side_bar_width,
            search_keyword: '',
            user_form_title: '',
            submit_button_text: '',
            search_input_timer: null,
            selected_employee: {id: null},
            user_filter: 'all',
            show_search_results: false,
            page: 1,
            user_form_mode: '',
            ceum_name: '',
            ceum_phone: '',
            ceum_email: '',
            is_ajaxing: false,
            show_create_edit_user_modal: false,
            ceum_id: null,
            ceum_page: 1,
            ceum_status: true,
            ceum_role: 'Admin',
            ceum_department: '',
            ceum_error: '',
            ceum_permissions: {
                allow_user_management: false,
                allow_ledger_report: false,
                allow_support_access: false,
                allow_accounting_access: false
            }
        },
        created: function (e) {
            // Make the width of the pagination bar match the main section's width
            this.pagination_bar_width = user_management_section.width();

            const ro = new ResizeObserver((entries, observer) => {
                for (const entry of entries) {
                    const {left, top, width, height} = entry.contentRect;
                    this.pagination_bar_width = width;
                }
            });

            ro.observe($('#main').get()[0]);

            // Add listener for menu toggle
            $(document).on('side-menu-toggle', (e, is_closed) => {
                this.pagination_bar_left_pos = is_closed ? 60 : side_bar_width;
            });

            // Add listener for clicking outside of drop downs to disable them
            $(document).on('click', (e) => {
                if (!e.target.dataset.ignoreClickaway) {
                    this.open_drop_down = '';

                    // Save changes
                    if (this.inline_editing_field) {
                        let input_element = this.inline_editing_field[this.employee_being_edited.id].input_element;
                        let value = input_element.value;
                        let field_edited = this.inline_editing_field[this.employee_being_edited.id].field;

                        // Get data to send to server
                        let name = (field_edited === 'name') ? value : `${this.employee_being_edited.first_name} ${this.employee_being_edited.last_name}`;
                        let department = (field_edited === 'department') ? value : this.employee_being_edited.department;
                        let role = (field_edited === 'role') ? value : this.employee_being_edited.role;
                        let name_pieces = (name.match(/[\w\s]+\s+[\w\s]+/)) ?
                            name.split(' ') : [];

                        let user_data = {
                            id: this.employee_being_edited.id,
                            first_name: (name_pieces[0] || '').trim() || null,
                            last_name: (name_pieces[1] || '').trim() || null,
                            phone: this.employee_being_edited.phone,
                            email: this.employee_being_edited.email,
                            department: department,
                            role: role,
                            is_inactive: !this.employee_being_edited.status,
                            permissions: this.employee_being_edited.permissions
                        };

                        this.createUpdateUser(null, user_data)
                            .then(() => {
                                this.inline_editing_field = null;
                                this.employee_being_edited = null;
                            });
                    }
                }
            });
        },
        methods: {

            showHeaderDropDown: function (e, label) {
                e.preventDefault();
                this.open_drop_down = label;
            },

            togglePermission: function (permission) {
                this.ceum_permissions[permission] = !this.ceum_permissions[permission];
            },

            toggleCeumStatus: function () {
                this.ceum_status = !this.ceum_status;
            },

            closeCreateEditUserDialog: function (e) {
                e.preventDefault();
                if (e.target.id === 'create_edit_user' || e.target.className === 'cancel') {
                    this.show_create_edit_user_modal = false;
                }
            },

            activateInlineEditing: function (e, employee, field_name) {

                // Find the input element and focus it
                let input_element = null;
                for (let child_element of e.target.parentElement.children) {
                    if (child_element.nodeName === 'INPUT' || child_element.nodeName === 'SELECT') {
                        input_element = child_element;
                        break;
                    }
                }

                if (input_element) {
                    setTimeout(() => { input_element.focus(); }, 100);
                    this.inline_editing_field = {};
                    this.inline_editing_field[employee.id] = {field: field_name, input_element: input_element};
                    this.employee_being_edited = employee;
                }
            },

            updateLastActiveFilter: function (e, filter_label) {
                e.preventDefault();
                this.current_page = 1;
                this.time_filter = filter_label;
                this.open_drop_down = '';
                return this.refreshListings();
            },

            toggleCeumRole: function () {
                this.ceum_role = (this.ceum_role === 'Admin') ? 'User' : 'Admin';
            },

            openCreateUpdateUserDialog: function (e, employee) {
                e.preventDefault();
                this.ceum_error = '';

                if (employee) {
                    this.ceum_id = employee.id;
                    this.ceum_role = employee.role;
                    this.ceum_status = !employee.is_inactive;
                    this.ceum_page = 1;
                    this.ceum_department = employee.department;
                    this.ceum_email = employee.email;
                    this.ceum_name = `${employee.last_name}, ${employee.first_name}`;
                    this.ceum_permissions = {
                        allow_user_management: employee.permissions ? employee.permissions['allow_user_management'] || false : false,
                        allow_ledger_report: employee.permissions ? employee.permissions['allow_ledger_report'] || false : false,
                        allow_support_access: employee.permissions ? employee.permissions['allow_support_access'] || false : false,
                        allow_accounting_access: employee.permissions ? employee.permissions['allow_accounting_access'] || false : false
                    };

                    this.ceum_phone = employee.phone;
                } else {
                    this.ceum_id = null;
                    this.ceum_role = 'Admin';
                    this.ceum_status = true;
                    this.ceum_page = 1;
                    this.ceum_department = '';
                    this.ceum_email = '';
                    this.ceum_name = '';
                    this.ceum_phone = '';
                    this.ceum_permissions = {
                        allow_user_management: false,
                        allow_ledger_report: false,
                        allow_support_access: false,
                        allow_accounting_access: false
                    };
                }

                this.show_create_edit_user_modal = true;
            },

            goToNextPage: function () {
                if (this.current_page < this.num_of_pages) {
                    this.current_page = this.current_page + 1;
                    return this.refreshListings();
                }
            },

            updateUserFilter: function (e, filter_label) {
                e.preventDefault();
                this.current_page = 1;
                this.user_filter = filter_label;
                this.open_drop_down = '';
                return this.refreshListings();
            },

            updateDepartmentFilter: function (e, filter_label) {
                e.preventDefault();
                this.current_page = 1;
                this.department_filter = filter_label;
                this.open_drop_down = '';
                return this.refreshListings();
            },

            updateRoleFilter: function (e, filter_label) {
                e.preventDefault();
                this.current_page = 1;
                this.role_filter = filter_label;
                this.open_drop_down = '';
                return this.refreshListings();
            },

            refreshListings: async function () {
                try {
                    this.is_ajaxing = true;
                    let results = await axios.get(window.page.refresh_user_list_url,
                        {
                            params: {
                                department_filter: this.department_filter,
                                user_filter: this.user_filter,
                                role_filter: this.role_filter,
                                rows_per_page: this.rows_per_page,
                                time_filter: this.time_filter,
                                current_page: this.current_page
                            }
                        });

                    this.is_ajaxing = false;

                    // Populate results
                    this.employees = results.data.results;
                    this.total_num_of_employees = results.data.total_count;

                } catch (err) {
                    this.is_ajaxing = false;
                    errorHandler(err);
                }
            },

            handleSearchInput: async function () {
                if (this.search_input_timer)
                    window.clearTimeout(this.search_input_timer);

                // Clear out search results if search keyword is empty
                if (!this.search_keyword) {
                    this.search_results = [];
                } else {

                    // Perform asynchronous search
                    this.search_input_timer = window.setTimeout(async () => {
                        try {
                            let results = await axios.get(window.page.search_users_url, {params: {keyword: this.search_keyword}});
                            this.search_results = results.data.employees;
                        } catch (err) {
                            errorHandler(err);
                        }

                    }, 200);
                }
            },

            getFormSelectAllButtonText: function () {
                let caption = 'Select All';
                this.user_form.select_all_mode = 'select_all';

                if (this.user_form.permissions) {
                    for (let i in this.user_form.permissions) {
                        if (this.user_form.permissions.hasOwnProperty(i)) {
                            if (this.user_form.permissions[i]) {
                                caption = 'Unselect All';
                                this.user_form.select_all_mode = 'deselect_all';
                                break;
                            }
                        }
                    }
                }

                return caption;
            },

            formatDate: function (date) {
                return (new Date(date)).toLocaleDateString("en-US", {year: "numeric", month: "long", day: "numeric"});
            },

            deleteUser: async function (e, employee) {
                e.preventDefault();
                if (employee.id) {
                    if (confirm(`Are you sure you want to delete user ${employee.full_name}?`)) {
                        try {
                            this.is_ajaxing = true;
                            let results = await axios.delete(`${window.page.delete_user_url}?id=${employee.id}`);
                            this.is_ajaxing = false;

                            // Delete the search result
                            if (this.search_results.length > 0 && this.search_keyword) {
                                this.search_results = this.search_results.filter(v => v.id !== employee.id);
                            }

                            // Delete the employee
                            this.employees = this.employees.filter(v => v.id !== employee.id);


                        } catch (err) {
                            this.is_ajaxing = false;
                            errorHandler(err);
                        }
                    }
                }
            },

            toggleUserActive: async function (e, employee) {
                e.preventDefault();

                // Update employee
                if (!this.is_ajaxing) {

                    try {
                        this.is_ajaxing = true;

                        let result = await axios.put(window.page.admin_toggle_active_url, {id: employee.id});

                        this.is_ajaxing = false;

                        // Update search result
                        if (this.search_results.length > 0 && this.search_keyword && this.search_results.find(v => v.id === employee.id)) {
                            this.search_results.find(v => v.id === employee.id).is_inactive = result.data.user.is_inactive;
                        }

                        // Update employee
                        this.employees.find(v => v.id === employee.id).is_inactive = result.data.user.is_inactive;

                    } catch (err) {
                        this.is_ajaxing = true;
                        errorHandler(err);
                    }

                }
            },

            toggleExpand: function (employee, e) {
                e.preventDefault();

                // Toggle selected search result
                if (this.search_results.length > 0 && this.search_keyword && this.search_results.find(v => v.id === employee.id)) {
                    this.search_results = this.search_results.map(v => {
                        if (v.id === employee.id)
                            v.is_expanded = !employee.is_expanded;

                        return v;
                    });
                }

                // Toggle selected employee
                if (this.employees.find(v => v.id === employee.id)) {
                    this.employees = this.employees.map(v => {
                        if (v.id === employee.id)
                            v.is_expanded = !employee.is_expanded;

                        return v;
                    });
                }
            },

            toggleSelectAllPermissions: function () {
                this.user_form.permissions = this.user_form.permissions || {};

                // Add missing permissions
                for (let i = 0; i < window.page.permissions.length; i++) {
                    let permission = window.page.permissions[i];

                    this.user_form.permissions[permission.machine_name] = (this.user_form.select_all_mode === 'select_all');
                }

                this.$forceUpdate();
            },

            goToPage: function (e, num_of_pages) {
                e.preventDefault();
                this.current_page = num_of_pages;
                return this.refreshListings();
            },

            createUpdateUser: async function (e, data) {

                if (e)
                    e.preventDefault();

                if (!this.is_ajaxing) {

                    // Get data to send to server
                    let user_data = {};
                    if (!data) {
                        let name_pieces = (this.ceum_name.match(/[\w\s]+,[\w\s]+/)) ?
                            this.ceum_name.split(',') : [];

                        user_data = {
                            id: this.ceum_id,
                            first_name: (name_pieces[1] || '').trim() || null,
                            last_name: (name_pieces[0] || '').trim() || null,
                            phone: this.ceum_phone,
                            email: this.ceum_email,
                            department: this.ceum_department,
                            role: this.ceum_role,
                            is_inactive: !this.ceum_status,
                            permissions: this.ceum_permissions
                        };
                    } else {
                        user_data = data;
                    }

                    try {
                        this.is_ajaxing = true;
                        const result = await axios.post(window.page.new_user_post_url, user_data);
                        this.is_ajaxing = false;

                        this.show_create_edit_user_modal = false;

                        // Update search result
                        if (this.search_results.find(v => v.id === result.data.user_id)) {
                            this.search_results = this.search_results.map(v => {
                                if (v.id === result.data.user_id) {
                                    v = Object.assign({}, result.data.employee);
                                    v.is_expanded = true;
                                }

                                return v;
                            });
                        }

                        // Update existing employee
                        if (this.employees.find(v => v.id === result.data.user_id)) {
                            this.employees = this.employees.map(v => {
                                if (v.id === result.data.user_id) {
                                    v = Object.assign({}, result.data.employee);
                                    v.is_expanded = true;
                                }

                                return v;
                            });
                        } else {
                            window.location.reload();
                        }

                        this.selected_employee = {id: null};

                        $('#user_form').modal('hide');
                    } catch (err) {
                        this.is_ajaxing = false;
                        if (err.response && err.response.status === 422) {

                            // Check for name errors
                            if (err.response.data.errors.first_name || err.response.data.errors.last_name) {
                                this.ceum_page = 1;
                                this.ceum_error = "Please enter the user's name in the format specified (e.g Jackson, Emily).";
                            } else if (err.response.data.errors.phone) {
                                this.ceum_page = 2;
                                this.ceum_error = err.response.data.errors.phone[0];
                            } else if (err.response.data.errors.email) {
                                this.ceum_page = 3;
                                this.ceum_error = err.response.data.errors.email[0];
                            } else if (err.response.data.errors.department) {
                                this.ceum_page = 4;
                                this.ceum_error = 'Please enter the department the user works in.';
                            }
                        } else {
                            errorHandler(err);
                        }
                    }
                }
            },

            goToCeumPage: function (page) {
                this.ceum_page = page;
            }
        },
        computed: {
            num_of_pages: function () {
                return Math.round(this.total_num_of_employees / this.rows_per_page);
            },

            disable_paging: function () {
                return this.search_results.length > 0;
            },

            user_filter_text: function () {
                return user_filter_labels[this.user_filter];
            },

            ceum_full_name: function () {
                let ret_val = '';
                if (this.ceum_name.match(/[\w\s]+,[\w\s]+/)) {
                    let pieces = this.ceum_name.split(',');
                    ret_val = `${pieces[1].trim()} ${pieces[0].trim()}`;
                }

                return ret_val;
            },

            search_results_or_employees: function () {
                return this.search_results.length > 0 ? this.search_results : this.employees;
            },

            pagination_links: function () {
                let ret_val = [];

                if (this.num_of_pages > 1) {
                    let end_page = (this.num_of_pages >= 7) ? 7 : this.num_of_pages;
                    if (this.num_of_pages <= end_page) {
                        for (let x = 1; x <= end_page; x++)
                            ret_val.push({page: x, selected: x === parseInt(this.current_page)});
                    }
                    else {
                        let start_page = this.current_page - 2;
                        start_page = (start_page < 1) ? 1 : start_page;

                        let end_page = this.current_page + 4;
                        if (end_page > this.num_of_pages)
                            end_page = this.num_of_pages;

                        for (let x = start_page; x <= end_page; x++)
                            ret_val.push({page: x, selected: x === parseInt(this.current_page)});
                    }
                }

                return ret_val;
            }
        },
        watch: {
            rows_per_page: function(val) {
                this.current_page = 1;
                return this.refreshListings();
            }
        }
    });
}