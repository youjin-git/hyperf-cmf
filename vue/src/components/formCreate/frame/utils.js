
export function toProps(rule) {
    const prop = {...(rule.props || {})};

    Object.keys(rule.on || {}).forEach(k => {
        const name = `on${upper(k)}`;
        if (Array.isArray(prop[name])) {
            prop[name] = [...prop[name], rule.on[k]];
        } else if (prop[name]) {
            prop[name] = [prop[name], rule.on[k]];
        } else {
            prop[name] = rule.on[k];
        }
    })
    prop.key = rule.key;
    prop.ref = rule.ref;
    prop.class = rule.class;
    prop.style = rule.style;
    if (prop.slot) delete prop.slot;

    return prop;
}
