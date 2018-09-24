
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries.
 */

require('./bootstrap');

window.Vue = require('vue');

require('./models/user-registration-form');
require('./models/user-email-verification');
require('./models/auth-new-password');
require('./models/auth');
require('./models/user-manage-account');
require('./models/auth-password-recovery');
require('./models/user-management');
require('./models/dashboard');
require('./models/support-auth-contact');
require('./models/nav-menu');
require('./models/layout-live-toggle');
require('./models/layout-date-selector');
require('./models/financial');
require('./models/publishers');
require('./models/layout-title-section');
import Layout from './layout';

const layout = new Layout();
layout.initialize();



