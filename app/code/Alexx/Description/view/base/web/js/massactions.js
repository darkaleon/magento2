define([
    'ko',
    'underscore',
    'Magento_Ui/js/grid/massactions',
    'mageUtils'
], function (ko, _, Massactions,utils) {
    'use strict';

    return Massactions.extend({
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
    });
});
