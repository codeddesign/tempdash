<aside v-bind:class="{'collapsed': is_collapsed}" id="side-menu">
    <div class="menu-container">
        <div class="logo-area">
            <a href="{{ route('home', [], false) }}">
                <img class="logo" src="/img/ternio-logo.svg"/>
            </a>
            <a v-on:click="toggleCollapseMenu($event)" class="collapse-button" href="">
                <img src="/img/menu_icons/collapse.svg"/>
            </a>
        </div>
        <div class="manage-account-button-section">
            <a href="{{ route('user_account', [], false) }}">Manage Account</a>
        </div>
        <div v-for="menu_section in menu.sections" class="menu-section">
            <h4 v-if="menu_section.label !== ''" class="nav-title" v-text="menu_section.label"></h4>
            <ul v-if="menu.items.length > 0" class="nav-menu">
                <li v-bind:class="{selected: selected_item.label === menu_item.label}" v-for="menu_item in menu.items" v-if="menu_section.machine_name == menu_item.section">
                    <div class="menu-item">
                        <img class="icon" v-bind:src="menu_item.icon"/>
                        <a class="label" v-text="menu_item.label" v-bind:href="menu_item.url"></a>
                        <img v-if="menu_item.children && menu_item.children.length > 0" class="caret"
                             v-on:click="expandMenuItem($event, menu_item)"
                             v-bind:src="menu_item.expanded ? '/img/menu_icons/16_arrow_down.svg' : '/img/menu_icons/17_arrow_right.svg'"/>
                    </div>
                    <ul v-if="menu_item.expanded && menu_item.children && menu_item.children.length > 0"
                        class="level-two">
                        <li v-bind:class="{selected: selected_item.label === level_two_menu_item.label}" v-for="level_two_menu_item in menu_item.children">
                            <div class="menu-item">
                                <img class="icon" v-bind:src="level_two_menu_item.icon"/>
                                <a class="label" v-bind:href="level_two_menu_item.url"
                                   v-text="level_two_menu_item.label"></a>
                                <img v-if="level_two_menu_item.children && level_two_menu_item.children.length > 0"
                                     class="caret"
                                     v-on:click="expandMenuItem($event, level_two_menu_item)"
                                     v-bind:src="level_two_menu_item.expanded ? '/img/menu_icons/16_arrow_down.svg' : '/img/menu_icons/17_arrow_right.svg'"/>
                            </div>
                            <ul class="level-three"
                                v-if="level_two_menu_item.expanded && level_two_menu_item.children && level_two_menu_item.children.length > 0">
                                <li v-bind:class="{selected: selected_item.label === level_three_menu_item.label}" v-for="level_three_menu_item in menu_item.children">
                                    <div class="menu-item">
                                        <img class="icon" v-bind:src="level_three_menu_item.icon"/>
                                        <a class="label" v-text="level_three_menu_item.label"
                                           v-bind:href="level_three_menu_item.url"></a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>

    <!-- Collapsed Menu -->
    <div class="collapsed-menu-item-section">
        <div class="logo-section">
            <a href="/"><img src="/img/ternio-symbol.svg"/></a>
        </div>
        <a href="{{ route('user_account', [], false) }}" class="account-button">
            <img src="/img/icons/profile.svg"/>
        </a>
        <div class="menu-icons-section">
            <ul>
                <li v-bind:class="{selected: selected_item.collapsed_label === menu_item.label}" v-for="menu_item in ordered_menu_items">
                    <a v-bind:href="menu_item.url">
                        <img v-on:mouseenter="showTooltip(menu_item)" v-on:mouseleave="hideTooltip()" v-bind:src="menu_item.icon"/>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</aside>