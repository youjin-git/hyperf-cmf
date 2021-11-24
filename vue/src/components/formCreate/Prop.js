function parseProp(name, id) {
    var prop;
    if (arguments.length === 2) {
        prop = arguments[1];
        id = prop[name];
    } else {
        prop = arguments[2];
    }

    return {
        id: id,
        prop: prop
    };
}

function nameProp() {
    return parseProp.apply(void 0, ['name'].concat(Array.prototype.slice.call(arguments)));
}

var BaseParser = {
    init: function init(ctx) {},
    toFormValue: function toFormValue(value, ctx) {
        return value;
    },
    toValue: function toValue(formValue, ctx) {
        return formValue;
    },
    mounted: function mounted(ctx) {},
    render: function render(children, ctx) {
        console.log('BaseParser',ctx)
        return ctx.defaultRender(ctx, children);
    },
    mergeProp: function mergeProp(ctx) {}
};

export {
    nameProp,
    BaseParser
}
