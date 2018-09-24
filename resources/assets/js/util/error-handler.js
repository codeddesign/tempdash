/**
 * Universal error handler for frontend JS
 */

module.exports = function(err) {

    // Redirect to the appropriate page
    if (err.response && err.response.status === 302) {
        window.location = err.response.data.redirect_to;
    }
    else if (err.response && err.response.status === 422) {
        window.alert('Your page session has expired, please refresh your browser.');
    }
    else {
        window.alert(err.message);
        window.console.error(err);
    }
};