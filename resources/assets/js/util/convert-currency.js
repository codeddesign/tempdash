module.exports = function(value) {
    return '$' + (value / 100).toFixed(2);
};