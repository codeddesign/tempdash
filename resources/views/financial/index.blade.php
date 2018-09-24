@extends('default')
@section('script-vars')
    <script>
        window.page.payments = @json($payments);
        window.page.total_num = @json($total_count);
        window.page.refresh_list_url = '{{ route('ajax_refresh_payments_listing', [], false) }}'
    </script>
@endsection
@section('content')
    <div id="financial-page">
        <h1>Payment History</h1>
        <div class="filter-search-section clearfix">
            <div class="filter-section">
                <select>
                    <option>All</option>
                    <option>Wire Transfer</option>
                    <option>ACH</option>
                    <option>Check</option>
                </select>
            </div>
            <div class="search-section">
                <form>
                    <input type="text" placeholder="Search"/>
                    <img src="/img/icons/search.svg"/>
                </form>
            </div>
        </div>
        <div class="command-button-section clearfix">
            <div class="command-buttons">
                <button class="edit-payout-options">Edit Payout Options</button>
                <button>Run Ledger Report</button>
            </div>
            <div class="view-buttons">
                <button v-on:click="goToNextPage" class="next"><span v-text="is_ajaxing ? 'Please wait...' : 'Next ' + rows_per_page "></span> <img src="/img/icons/right_arrow.svg"></button>
            </div>
        </div>
        <div class="payments-list-section">
            <div class="header-section">
                <ul>
                    <li class="check-box-header"></li>
                    <li class="date-header">Date</li>
                    <li class="payout-method-header">Payout Method</li>
                    <li class="pay-schedule-header">Pay Schedule</li>
                    <li class="pay-period-header">Pay Period</li>
                    <li class="amount-header">Amount</li>
                </ul>
            </div>
            <div class="listings">
                <div v-for="payment in payments" class="listing">
                    <ul>
                        <li class="check-box-header">
                            <input type="checkbox"/>
                        </li>
                        <li class="date-header" v-text="convertDate(payment.created_at, 'MMMM DD, YYYY')"></li>
                        <li class="payout-method-header" v-text="payment.payout_method"></li>
                        <li class="pay-schedule-header" v-text="payment.pay_schedule"></li>
                        <li class="pay-period-header"
                            v-text="convertDate(payment.pay_period_start, 'MM/DD/YYYY') + ' - ' + convertDate(payment.pay_period_end, 'MM/DD/YYYY')"></li>
                        <li class="amount-header" v-text="convertCurrency(payment.amount)"></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="pagination-section clearfix" v-bind:style="{width: pagination_bar_width + 'px', left: pagination_bar_left_pos + 'px'}">
            <div class="rows-per-page-section"><label>Rows Per Page: </label>
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
    </div>
@endsection