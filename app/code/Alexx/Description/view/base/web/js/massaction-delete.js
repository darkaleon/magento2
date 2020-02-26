define([
    'underscore',
    'mageUtils',
], function (_, utils) {
    'use strict';
    var mixin = {
        defaultCallback: function (action, data) {
            var itemsType = 'selected',
                selections = {};

            selections[itemsType] = data[itemsType];

            if (!selections[itemsType].length) {
                selections[itemsType] = false;
            }

            _.extend(selections, data.params || {});
            utils.submit({
                url: action.url,
                data: selections
            });
        }
    }
    return function (target) {
        return target.extend(mixin);
    };
});
