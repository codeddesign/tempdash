@extends('default')
@section('content')
    <div id="dashboard_home" class="dashboard-page">
        @include('components/head-stats')
        <div class="supply-chain-view-area graph-section-common">
            <div class="supply-chain-view">
                <span class="label">Supply Chain</span>
                <span class="graph-toggle">
                    <a v-on:click="setGraph($event, 'sine')" href="">
                        <img style="width: 15px;" v-bind:src="'/img/icons/bargraph' + (current_graph === 'sine' ? '-selected' : '') + '.svg'"/>
                    </a>
                    <a v-on:click="setGraph($event, 'vertical-numbers')" href="">
                        <img style="width: 27px;" v-bind:src="'/img/icons/linegraph' + (current_graph === 'vertical-numbers' ? '-selected' : '') + '.svg'"/>
                    </a>
                    <a v-on:click="setGraph($event, 'circle')" href="">
                       <img style="width: 19px;" v-bind:src="'/img/icons/piegraph' + (current_graph === 'circle' ? '-selected' : '') + '.svg'"/>
                    </a>
                </span>
                <div v-show="current_graph === 'sine'" v-cloak class="home-graph" id="home-sine-graph-container">
                    @include('home/sine-graph')
                </div>
                <div v-show="current_graph === 'vertical-numbers'" class="home-graph" v-cloak id="home-number-graph-container">
                    @include('home/number-graph')
                </div>
                <div v-show="current_graph === 'circle'" class="home-graph" v-cloak id="home-number-graph-container">
                    <img style="max-width: 100%; max-height: 100%; margin-left: -111px; margin-top: 30px;" src="/img/circle-chart.png"/>
                </div>
                <div class="border-right"></div>
            </div>
            <div v-cloak class="supply-chain-stats">
                <div class="stat">
                    <h4>Publisher Spend</h4>
                    <h2>$390,013</h2>
                    <div class="bottom-border"></div>
                </div>
                <div class="stat publisher-percentage">
                    <h4>Publisher Share</h4>
                    <h2>43%</h2>
                </div>
            </div>
        </div>
        <div class="inventory-section">
            <div class="chart">
                <div class="chart-graphic">
                    <img src="/img/pie_graph.png"/>
                </div>
            </div>
            <div class="inventory-listings">
                <h3>Transparent Inventory <span class="emphasis">(Top Sources)</span></h3>
                <div class="listing-headings">
                    <h4>Brand</h4>
                    <h4>Impressions</h4>
                    <h4>Ad Spend</h4>
                    <h4>Average eCPM</h4>
                    <h4>to Publisher</h4>
                </div>
                <div class="listing">
                    <div>OpenX</div>
                    <div>10,000,008</div>
                    <div>$110,000</div>
                    <div>$1.8</div>
                    <div>30%</div>
                </div>
                <div class="listing">
                    <div>Rubicon</div>
                    <div>8,000,582</div>
                    <div>$85,143</div>
                    <div>$1.3</div>
                    <div>28%</div>
                </div>
                <div class="listing">
                    <div>Pubmatic</div>
                    <div>6,500,231</div>
                    <div>$74,120,112</div>
                    <div>$1.2</div>
                    <div>24%</div>
                </div>
                <div class="listing">
                    <div>RPM</div>
                    <div>5,242,001</div>
                    <div>$56,100</div>
                    <div>$1.1</div>
                    <div>19%</div>
                </div>
                <div class="more-listings">
                    <a href="{{ route('transparency', [], false) }}">
                        View All Transparent Sources
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection