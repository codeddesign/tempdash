@extends('default')
@section('content')
    <div id="publishers-page">
        <svg style="position: absolute; z-index: 4000" id="listings-helper" xmlns="http://www.w3.org/2000/svg">
            <path id="listings-helper-path1" stroke="#f5c257" fill="none" stroke-width="2"></path>
            <path id="listings-helper-path2" stroke="#f5c257" fill="none" stroke-width="2"></path>
            <text x="0" y="0" id="listings-helper-label" style="fill: #464a53; font: normal 14px sans-serif;">Intermediaries</text>
        </svg>
        @include('components/head-stats')
        <div id="graph-area">
            <div>
                @include('publishers/graph')
            </div>
        </div>
        <div id="publishers-main">
            <div class="listings-section">
                <ul class="listing-headings">
                    <li class="publisher-listing">Brand</li>
                    <li class="impressions-listing">Impressions</li>
                    <li class="ad-spend-listing">Ad Spend</li>
                    <li class="direct-listing">Direct</li>
                    <li class="one-listing">1</li>
                    <li class="two-listing">2+</li>
                    <li class="starting-cpm">Starting CPM</li>
                    <li class="eCPM-listing">eCPM</li>
                </ul>
                <div v-for="i in 20" class="listings">
                    <ul class="listing">
                        <li class="publisher-listing">General Motors</li>
                        <li class="impressions-listing">1,900,000</li>
                        <li class="ad-spend-listing">$8,265.00</li>
                        <li class="direct-listing">12%</li>
                        <li class="one-listing">0%</li>
                        <li class="two-listing">80%</li>
                        <li class="starting-cpm">$4.10</li>
                        <li class="eCPM-listing">$4.25</li>
                    </ul>
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
    </div>
@endsection
@section('inline-scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/svg.js/2.6.5/svg.min.js"></script>
@endsection