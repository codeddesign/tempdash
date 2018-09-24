@extends('default')
@section('script-vars')
    <script>
        window.page.employees = @json($company_employees);
        window.page.new_user_post_url = '{{ route('admin_create_update_user', [], false) }}';
        window.page.delete_user_url = '{{ route('admin_delete_user', [], false) }}';
        window.page.refresh_user_list_url = '{{ route('ajax_refresh_user_list', [], false) }}';
        window.page.search_users_url = '{{ route('ajax_search_users', [], false) }}';
        window.page.admin_toggle_active_url = '{{ route('admin_toggle_active', [], false) }}';
        window.page.permissions = @json($permissions);
        window.page.total_num_of_employees = {{ $total_num_of_employees }};
        window.page.departments = @json($departments);
    </script>
@endsection
@section('content')
    <div id="user_management" class="user-management-page clearfix">
        <h1>User Management</h1>
        {{--<div class="filter-search-section clearfix">--}}
        {{--<div class="filters">--}}
        {{--<div class="filter">--}}
        {{--<select v-model="active_filter" v-on:change="updateFilter">--}}
        {{--<option value="all">All Users</option>--}}
        {{--<option value="active">Active Users</option>--}}
        {{--<option value="inactive">Inactive Users</option>--}}
        {{--</select>--}}
        {{--</div>--}}
        {{--<div class="filter">--}}
        {{--<select v-model="department_filter" v-on:change="updateFilter">--}}
        {{--<option value="">All Departments</option>--}}
        {{--<option v-for="department in departments" v-bind:value="department"--}}
        {{--v-text="department"></option>--}}
        {{--</select>--}}
        {{--</div>--}}
        {{--</div>--}}
        {{--<div class="search-section">--}}
        {{--<form>--}}
        {{--<input v-model="search_keyword" v-on:input="handleSearchInput" type="text" placeholder="Search"/>--}}
        {{--<img src="/img/icons/search.svg"/>--}}
        {{--</form>--}}
        {{--</div>--}}
        {{--</div>--}}
        <div class="add-user-button-section clearfix">
            {{--<button v-on:click="openCreateUpdateUserDialog($event)" class="add-user"><img src="/img/icons/plus.svg"/>--}}
            {{--Add New User--}}
            {{--</button>--}}
            {{--<div class="view-mode">--}}
            {{--<button v-on:click="goToNextPage" v-bind:disabled="is_ajaxing"--}}
            {{--v-if="!disable_paging && current_page <= num_of_pages" class="next">--}}
            {{--<span v-text="is_ajaxing ? 'Please wait...' : 'Next ' + rows_per_page "></span> <img--}}
            {{--v-if="!is_ajaxing" src="/img/icons/right_arrow.svg"/>--}}
            {{--</button>--}}
            {{--</div>--}}
        </div>
        <div class="user-listings-section">
            <div class="user-listings-heading">
                <a class="add-button" data-ignore-clickaway="1" v-on:click="showHeaderDropDown($event, 'add-button')"
                   href="">
                    <img data-ignore-clickaway="1" src="/img/icons/gold-plus.svg"/>
                </a>
                <div v-cloak v-show="open_drop_down === 'add-button'" class="user-add-menu drop-menu">
                    <ul>
                        <li><a v-on:click="openCreateUpdateUserDialog($event, null)" href="">Add New User</a></li>
                        <li><a data-ignore-clickaway="1" href="">Add New Payee</a></li>
                        <li><a data-ignore-clickaway="1" href="">Edit User Preferences</a></li>
                        <li><a data-ignore-clickaway="1" v-on:click="showHeaderDropDown($event, '')" href="">Close</a>
                        </li>
                    </ul>
                </div>
                <ul>
                    <li class="name">
                        <span data-ignore-clickaway="1" v-on:click="showHeaderDropDown($event, 'user-filter')"
                              class="label">
                            <span data-ignore-clickaway="1" v-text="user_filter_text"></span> <img
                                    src="/img/icons/vertical-circles.svg"/>
                        </span>
                        <div v-cloak v-show="open_drop_down === 'user-filter'" class="drop-menu">
                            <ul>
                                <li><a v-on:click="updateUserFilter($event, 'all')" data-ignore-clickaway="1" href="">All
                                        Users</a></li>
                                <li><a v-on:click="updateUserFilter($event, 'active')" data-ignore-clickaway="1"
                                       href="">Active Users</a></li>
                                <li><a v-on:click="updateUserFilter($event, 'inactive')" data-ignore-clickaway="1"
                                       href="">Inactive Users</a></li>
                                <li><a data-ignore-clickaway="1" v-on:click="showHeaderDropDown($event, '')" href="">Close</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="department">
                        <span data-ignore-clickaway="1" v-on:click="showHeaderDropDown($event, 'department')"
                              class="label">
                            <span data-ignore-clickaway="1"
                                  v-text="!department_filter ? 'All Departments' : department_filter"></span> <img
                                    src="/img/icons/vertical-circles.svg"/>
                        </span>
                        <div v-cloak v-show="open_drop_down === 'department'" class="drop-menu">
                            <ul>
                                <li>
                                    <a v-on:click="updateDepartmentFilter($event, '')" href="">All Departments</a>
                                </li>
                                <li v-for="department in departments">
                                    <a v-on:click="updateDepartmentFilter($event, department)" data-ignore-clickaway="1"
                                       href="" v-text="department"></a>
                                </li>
                                <li><a data-ignore-clickaway="1" v-on:click="showHeaderDropDown($event, '')" href="">Close</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="role">
                        <span data-ignore-clickaway="1" v-on:click="showHeaderDropDown($event, 'roles')" class="label">
                            <span data-ignore-clickaway="1"
                                  v-text="!role_filter ? 'All Roles' : role_filter"></span> <img
                                    src="/img/icons/vertical-circles.svg"/>
                        </span>
                        <div v-cloak v-show="open_drop_down === 'roles'" class="drop-menu">
                            <ul>
                                <li><a v-on:click="updateRoleFilter($event, '')" data-ignore-clickaway="1" href="">All
                                        Roles</a></li>
                                <li><a v-on:click="updateRoleFilter($event, 'Admin')" data-ignore-clickaway="1" href="">Admin</a>
                                </li>
                                <li><a v-on:click="updateRoleFilter($event, 'User')" data-ignore-clickaway="1" href="">User</a>
                                </li>
                                <li><a data-ignore-clickaway="1" v-on:click="showHeaderDropDown($event, '')" href="">Close</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="activity">
                        <span data-ignore-clickaway="1" v-on:click="showHeaderDropDown($event, 'time-active')"
                              class="label">
                            <span data-ignore-clickaway="1" v-text="!time_filter ? 'Last Active' : time_filter"></span> <img
                                    src="/img/icons/vertical-circles.svg"/>
                        </span>
                        <div v-cloak v-show="open_drop_down === 'time-active'" class="drop-menu">
                            <ul>
                                <li><a data-ignore-clickaway="1" v-on:click="updateLastActiveFilter($event, '')"
                                       href="">All</a></li>
                                <li><a data-ignore-clickaway="1" v-on:click="updateLastActiveFilter($event, 'Today')"
                                       href="">Today</a></li>
                                <li><a data-ignore-clickaway="1"
                                       v-on:click="updateLastActiveFilter($event, 'Yesterday')" href="">Yesterday</a>
                                </li>
                                <li><a data-ignore-clickaway="1" v-on:click="updateLastActiveFilter($event, 'Last 7')"
                                       href="">Last 7</a></li>
                                <li><a data-ignore-clickaway="1" v-on:click="updateLastActiveFilter($event, 'Last 30')"
                                       href="">Last 30</a></li>
                                <li><a data-ignore-clickaway="1"
                                       v-on:click="updateLastActiveFilter($event, 'This Year')" href="">This Year</a>
                                </li>
                                <li><a data-ignore-clickaway="1" v-on:click="showHeaderDropDown($event, '')" href="">Close</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    {{--<li>--}}
                    {{--<span data-ignore-clickaway="1" v-on:click="showHeaderDropDown($event, 'created-by')" class="label">--}}
                    {{--Created By <img src="/img/icons/vertical-circles.svg"/>--}}
                    {{--</span>--}}
                    {{--<div v-cloak v-show="open_drop_down === 'created-by'" class="drop-menu search">--}}
                    {{--<img class="search-icon" src="/img/icons/search-black.svg"/>--}}
                    {{--<ul>--}}
                    {{--<li>--}}
                    {{--<form>--}}
                    {{--<input data-ignore-clickaway="1" type="text"/>--}}
                    {{--</form>--}}
                    {{--</li>--}}
                    {{--<li><a data-ignore-clickaway="1" href="">Search Option 1</a></li>--}}
                    {{--<li><a data-ignore-clickaway="1" href="">Search Option 2</a></li>--}}
                    {{--<li><a data-ignore-clickaway="1" href="">Search Option 3</a></li>--}}
                    {{--<li><a data-ignore-clickaway="1" href="">Search Option 4</a></li>--}}
                    {{--<li><a data-ignore-clickaway="1" v-on:click="showHeaderDropDown($event, '')" href="">Close</a></li>--}}
                    {{--</ul>--}}
                    {{--</div>--}}
                    {{--</li>--}}
                </ul>
            </div>
            <div v-cloak v-for="employee in search_results_or_employees" class="listing">
                <ul>
                    <li class="name">
                        <div class="online-indicator" v-bind:class="{online: !employee.is_inactive}"></div>
                        <div style="cursor: pointer;"
                             data-ignore-clickaway="1"
                             v-on:click="activateInlineEditing($event, employee, 'name')"
                             v-show="!inline_editing_field || !inline_editing_field[employee.id] || inline_editing_field[employee.id].field !== 'name'"
                             v-text="employee.full_name">
                        </div>

                        <input v-cloak
                               v-show="inline_editing_field && inline_editing_field[employee.id] && inline_editing_field[employee.id].field === 'name'"
                               class="inline-editing-input"
                               data-ignore-clickaway="1" v-bind:value="employee.full_name" type="text"/>
                    </li>
                    <li class="department">
                        <div style="cursor: pointer;"
                             data-ignore-clickaway="1"
                             v-on:click="activateInlineEditing($event, employee, 'department')"
                             v-show="!inline_editing_field || !inline_editing_field[employee.id] || inline_editing_field[employee.id].field !== 'department'"
                             v-text="employee.department">
                        </div>

                        <input v-cloak
                               v-show="inline_editing_field && inline_editing_field[employee.id] && inline_editing_field[employee.id].field === 'department'"
                               class="inline-editing-input"
                               data-ignore-clickaway="1" v-bind:value="employee.department" type="text"/>
                    </li>
                    <li class="role">
                        <div style="cursor: pointer;"
                             data-ignore-clickaway="1"
                             v-on:click="activateInlineEditing($event, employee, 'role')"
                             v-show="!inline_editing_field || !inline_editing_field[employee.id] || inline_editing_field[employee.id].field !== 'role'"
                             v-text="employee.role">
                        </div>

                        <select v-cloak
                               v-show="inline_editing_field && inline_editing_field[employee.id] && inline_editing_field[employee.id].field === 'role'"
                               class="inline-editing-input"
                                data-ignore-clickaway="1" v-bind:value="employee.role">
                            <option value="Admin">Admin</option>
                            <option value="User">User</option>
                        </select>
                    </li>
                    <li class="activity" v-text="employee.last_activity"></li>
                    {{--<li class="created-by" v-text="employee.created_by || 'Self'">Created by</li>--}}
                    <li class="icons">
                        <a class="edit-user" v-on:click="openCreateUpdateUserDialog($event, employee)" href=""><img
                        src="/img/icons/edit.svg"/></a>
                        <a v-on:click="deleteUser($event, employee)" class="delete-user" href=""><img
                                    src="/img/icons/trash.svg"/></a>
                    </li>
                </ul>
            </div>
        </div>
        <div v-if="!disable_paging"
             v-bind:style="{width: pagination_bar_width + 'px', left: pagination_bar_left_pos + 'px'}"
             class="pagination-section">
            <div class="rows-per-page-section">
                <label>Rows Per Page: </label>
                <select v-model="rows_per_page">
                    <option value="15">15</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="500">500</option>
                </select>
            </div>
            <ul class="pages">
                <li v-if="current_page > 1" class="page-back">
                    <a v-on:click="goToPage($event, 1)" href="">
                        <img src="/img/icons/left_double_arrow.svg"/>
                    </a>
                </li>
                <li v-bind:class="{selected: pagination_link.selected}" v-for="pagination_link in pagination_links">
                    <a href="" v-on:click="goToPage($event, pagination_link.page)" v-text="pagination_link.page"></a>
                </li>
                <li v-if="current_page < (num_of_pages - 3)" class="page-forward">
                    <a v-on:click="goToPage($event, num_of_pages)" href="">
                        <img src="/img/icons/right_double_arrow.svg"/>
                    </a>
                </li>
            </ul>
        </div>
        <div v-on:click="closeCreateEditUserDialog($event)" v-cloak v-bind:class="{show: show_create_edit_user_modal}"
             id="create_edit_user">
            <div class="content-area">
                <div class="pager">
                    <div class="pager-line">
                        <div v-on:click="goToCeumPage(1)" class="point first"
                             v-bind:class="{selected: ceum_page === 1}">
                            <div class="label">Name</div>
                        </div>
                        <div v-on:click="goToCeumPage(2)" class="point second"
                             v-bind:class="{selected: ceum_page === 2}">
                            <div class="label">Phone</div>
                        </div>
                        <div v-on:click="goToCeumPage(3)" class="point third"
                             v-bind:class="{selected: ceum_page === 3}">
                            <div class="label">Email</div>
                        </div>
                        <div v-on:click="goToCeumPage(4)" class="point fourth"
                             v-bind:class="{selected: ceum_page === 4}">
                            <div class="label">Department</div>
                        </div>
                        <div v-on:click="goToCeumPage(5)" class="point fifth"
                             v-bind:class="{selected: ceum_page === 5}">
                            <div class="label">Permissions</div>
                        </div>
                        <div v-on:click="goToCeumPage(6)" class="point sixth"
                             v-bind:class="{selected: ceum_page === 6}">
                            <div class="label">Review</div>
                        </div>
                    </div>
                </div>
                <div v-if="ceum_page === 1" class="page name">
                    <h3>What's your user's name?</h3>
                    <div class="data-section">
                        <input style="margin-left: 10px;" v-model="ceum_name" placeholder="Last Name, First Name"
                               class="data-input" type="text"/>
                        <span v-on:click="toggleCeumStatus" class="input-button white-circle-text"
                              v-text="ceum_status ? 'Active' : 'Inactive'"></span>
                        <span v-on:click="toggleCeumRole" class="input-button white-circle-text"
                              v-text="ceum_role"></span>
                        <a v-on:click="goToCeumPage(ceum_page + 1)" class="move-forward"><img
                                    src="/img/icons/right_caret.svg"></a>
                    </div>
                </div>
                <div v-if="ceum_page === 2" class="page">
                    <h3>What's your user's phone number?</h3>
                    <div class="data-section">
                        <input v-model="ceum_phone" placeholder="(XXX) XXX - XXXX" class="data-input" type="tel"/>
                        <a v-on:click="goToCeumPage(ceum_page + 1)" class="move-forward"><img
                                    src="/img/icons/right_caret.svg"></a>
                        <a v-on:click="goToCeumPage(ceum_page - 1)" class="move-back"><img
                                    src="/img/icons/left_caret.svg"></a>
                    </div>
                </div>
                <div v-if="ceum_page === 3" class="page">
                    <h3>What's your user's email address?</h3>
                    <div class="data-section">
                        <input v-model="ceum_email" placeholder="user@domain.com" class="data-input" type="email"/>
                        <a v-on:click="goToCeumPage(ceum_page + 1)" class="move-forward"><img
                                    src="/img/icons/right_caret.svg"></a>
                        <a v-on:click="goToCeumPage(ceum_page - 1)" class="move-back"><img
                                    src="/img/icons/left_caret.svg"></a>
                    </div>
                </div>
                <div v-if="ceum_page === 4" class="page department">
                    <h3>Which department is your user a part of?</h3>
                    <div class="data-section">
                        <input v-model="ceum_department" placeholder="Department Name" class="data-input" type="text"/>
                        <a v-on:click="goToCeumPage(ceum_page + 1)" class="move-forward"><img
                                    src="/img/icons/right_caret.svg"></a>
                        <a v-on:click="goToCeumPage(ceum_page - 1)" class="move-back"><img
                                    src="/img/icons/left_caret.svg"></a>
                    </div>
                </div>
                <div v-if="ceum_page === 5" class="page permissions">
                    <h3>Select your user's permissions.</h3>
                    <div class="data-section">
                        <ul class="check-box-list">
                            <li>
                                <img v-on:click="togglePermission('allow_user_management')"
                                     class="checkbox"
                                     v-bind:src="'/img/icons/' + (ceum_permissions['allow_user_management'] ? 'ceum_checked.png' : 'ceum_unchecked.png')"/>
                                Allow user management access.
                            </li>
                            <li>
                                <img v-on:click="togglePermission('allow_ledger_report')"
                                     class="checkbox" src="/img/icons/ceum_unchecked.png"
                                     v-bind:src="'/img/icons/' + (ceum_permissions['allow_ledger_report'] ? 'ceum_checked.png' : 'ceum_unchecked.png')"/>
                                Allow ledger report access.
                            </li>
                            <li>
                                <img v-on:click="togglePermission('allow_support_access')"
                                     class="checkbox" src="/img/icons/ceum_unchecked.png"
                                     v-bind:src="'/img/icons/' + (ceum_permissions['allow_support_access'] ? 'ceum_checked.png' : 'ceum_unchecked.png')"/>
                                Allow support access.
                            </li>
                            <li>
                                <img v-on:click="togglePermission('allow_accounting_access')"
                                     class="checkbox" src="/img/icons/ceum_checked.png"
                                     v-bind:src="'/img/icons/' + (ceum_permissions['allow_accounting_access'] ? 'ceum_checked.png' : 'ceum_unchecked.png')"/>
                                Allow accounting access.
                            </li>
                        </ul>
                        <a v-on:click="goToCeumPage(ceum_page + 1)" class="move-forward"><img
                                    src="/img/icons/right_caret.svg"></a>
                        <a v-on:click="goToCeumPage(ceum_page - 1)" class="move-back"><img
                                    src="/img/icons/left_caret.svg"></a>
                    </div>
                </div>
                <div v-if="ceum_page === 6" class="page review">
                    <h3>Here's your new user.</h3>
                    <div class="data-section">
                        <span v-if="ceum_full_name" class="white-circle-text" v-text="ceum_full_name"></span>
                        <span class="white-circle-text" v-text="ceum_status ? 'Active' : 'Inactive'"></span>
                        <span v-if="ceum_role" class="white-circle-text" v-text="ceum_role"></span>
                        <span v-if="ceum_phone" class="white-circle-text" v-text="ceum_phone"></span>
                        <span v-if="ceum_email" class="white-circle-text" v-text="ceum_email"></span>
                        <span v-if="ceum_department" class="white-circle-text" v-text="ceum_department"></span>

                        <span v-if="ceum_permissions['allow_user_management']" class="permission">
                                <img class="checkbox" src="/img/icons/ceum_checked.png"/> Allow user management access.
                            </span>

                        <span v-if="ceum_permissions['allow_ledger_report']" class="permission">
                                <img class="checkbox" src="/img/icons/ceum_checked.png"/> Allow ledger report access.
                            </span>

                        <span v-if="ceum_permissions['allow_support_access']" class="permission">
                                <img class="checkbox" src="/img/icons/ceum_checked.png"/> Allow support access.
                            </span>

                        <span v-if="ceum_permissions['allow_accounting_access']" class="permission">
                                <img class="checkbox" src="/img/icons/ceum_checked.png"/> Allow accounting access.
                            </span>

                        <a v-on:click="goToCeumPage(ceum_page - 1)" class="move-back"><img
                                    src="/img/icons/left_caret.svg"></a>
                    </div>
                </div>
                <div v-if="ceum_error && ceum_page < 5" class="error" v-text="ceum_error"></div>
                <h4>Not ready to add a new user? <a v-on:click="closeCreateEditUserDialog($event)" class="cancel"
                                                    href="">Cancel</a></h4>
                <button v-on:click="createUpdateUser($event)"
                        v-text="is_ajaxing ? 'Saving...' : 'Save'"
                        v-if="ceum_page === 6" class="save-button"></button>
            </div>
        </div>
    </div>
@endsection
