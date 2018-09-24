@extends('default')

@section('script-vars')
    <script>
        window.page.support_topics = @json($support_topics);
        window.page.do_search_route = '{{ route('auth_contact_do_search', [], false) }}';
    </script>
@endsection

@section('content')
    <div id="auth_contact_page" class="auth-contact-page">
        <div class="top-section">
            <div class="container">
                <div class="search-area">
                    <h1>Support Center</h1>
                    <p>Get the most out of Ternio. Get in touch with us if you don't find what you are looking for.</p>
                    <form>
                        <div class="search-input">
                            <div class="search-icon-container">
                                <i class="fa fa-search"></i>
                            </div>
                            <div class="input-container">
                                <input v-model="search_term" v-on:input="handleSearchInput($event)" type="text" placeholder="Have a question? Ask or enter a search term here."/>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div v-on:click="removeSupportFilter" class="mid-section">
            <div class="container">
                <div v-cloak v-if="!search_term" class="row">
                    <div class="col-md-6">
                        <div v-bind:class="{active: support_filter_type === 'tech_support'}" v-on:click="updateSupportTypeFilter($event, 'tech_support')"
                             class="support-type-pane">
                            <i class="fa fa-wrench"></i>
                            <h5 class="align-content-center">Technical Support</h5>
                            <p class="align-content-center">
                                Description would go here.
                            </p>
                            <div class="view-button">View</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div v-bind:class="{active: support_filter_type === 'account_management'}" v-on:click="updateSupportTypeFilter($event, 'account_management')"
                             class="support-type-pane ">
                            <i class="fa fa-desktop"></i>
                            <h5 class="align-content-center">Account Management</h5>
                            <p class="type-desc align-content-center">
                                Description would go here.
                            </p>
                            <div class="view-button">View</div>
                        </div>
                    </div>
                </div>
                <div class="top-listing-section">
                    <div class="topic-listing" v-for="(support_topic, index) in filterSupportTopics(support_topics)" >
                        <div class="clearfix">
                            <div class="topic" v-text="support_topic.topic"></div>
                            <div class="topic-listing-expand">
                                <a v-on:click="toggleTopicReveal($event, index)" href="">
                                    <i v-bind:class="{'fa-angle-right': support_topic.is_closed, 'fa-angle-down': !support_topic.is_closed}" class="fa"></i>
                                </a>
                            </div>
                        </div>
                        <div v-cloak v-if="!support_topic.is_closed" class="topic-content" v-text="support_topic.content"></div>
                    </div>
                    <h5 class="no-results-notice" v-if="search_term && support_topics.length === 0 && !is_ajaxing">
                        No search results.
                    </h5>
                    <a v-if="!search_term" href="" class="load-more-button">
                        Load More
                    </a>
                </div>
            </div>
        </div>
        <div class="lower-section">
            <div class="container">
                <h4>Can't Find an Answer?</h4>
                <div class="buttons-row">
                    <a href="" class="footer-button create-support-ticket">Create a Support Ticket</a>&nbsp;
                    <a href="" class="footer-button send-us-a-tweet">Send us a tweet</a>
                </div>
            </div>
        </div>
    </div>
@endsection