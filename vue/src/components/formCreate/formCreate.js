import {mergeProps,$set,extend,_defineProperty,_objectSpread2,upper,_toConsumableArray,toLine,uniqueId,toCase,lower} from "./common";
import {nameProp,BaseParser} from "./Prop";

var vue = require('vue');
import {makeSlotBag} from "./makeSlotBag";



function toProps(rule) {
    var prop = _objectSpread2({}, rule.props || {});
    Object.keys(rule.on || {}).forEach(function (k) {
        var name = "on".concat(upper(k));
        if (Array.isArray(prop[name])) {
            prop[name] = [].concat(_toConsumableArray(prop[name]), [rule.on[k]]);
        } else if (prop[name]) {
            prop[name] = [prop[name], rule.on[k]];
        } else {
            prop[name] = rule.on[k];
        }
    });

    prop.key = rule.key;
    prop.ref = rule.ref;
    prop["class"] = rule["class"];
    prop.style = rule.style;
    if (prop.slot) delete prop.slot;
    return prop;
}

function CreateNodeFactory() {
    var aliasMap = {};
    var CreateNode = function () {};
    extend(CreateNode.prototype,{
        make:function (tag,data,children) {
        	console.log(toProps(data));

            return this.h(tag, toProps(data), children);
        },
        h: function h(tag, data, children) {
            return vue.createVNode(vue.getCurrentInstance().appContext.config.isNativeTag(tag) ? tag : vue.resolveComponent(tag), data, children);
        },
        aliasMap: aliasMap
    })
    extend(CreateNode,{
        aliasMap: aliasMap,
        alias: function alias(_alias, name) {
            aliasMap[_alias] = name;
        },
        use: function use(nodes) {
            Object.keys(nodes).forEach(function (k) {
                var line = toLine(k);  //button
                var lower = toString(k).toLocaleLowerCase();  //button
                var v = nodes[k];    //el-button

                [k, line, lower].forEach(function (n) {
                    CreateNode.alias(k, v);

                    CreateNode.prototype[n] = function (data, children) {
                        return this.make(v, data, children);
                    };


                });
            });
        }
    })

    return CreateNode;
}

var row = {
    name: 'FcRow',
    render: function render(_, ctx) {
        return ctx.vNode.col({
            props: {
                span: 24
            }
        }, [ctx.vNode.row(ctx.prop, _)]);
    }
};

var parsers = [row];

var PRE = 'el';
var alias = {
    button: PRE + '-button',
    icon: 'i',
    slider: PRE + '-slider',
    rate: PRE + '-rate',
    upload: 'fc-upload',
    cascader: PRE + '-cascader',
    popover: PRE + '-popover',
    tooltip: PRE + '-tooltip',
    colorPicker: PRE + '-colorPicker',
    timePicker: PRE + '-time-picker',
    timeSelect: PRE + '-time-select',
    datePicker: PRE + '-date-picker',
    'switch': PRE + '-switch',
    // select: 'fc-select',
    checkbox: 'fc-checkbox',
    radio: 'fc-radio',
    inputNumber: PRE + '-input-number',
    number: PRE + '-input-number',
    input: PRE + '-input',
    formItem: PRE + '-form-item',
    form: PRE + '-form',
    frame: 'fc-frame',
    col: PRE + '-col',
    row: PRE + '-row',
    tree: 'fc-tree',
    autoComplete: PRE + '-autocomplete',
    auto: PRE + '-autocomplete',
    group: 'fc-group',
    object: 'fc-sub-form',
    subForm: 'fc-sub-form'
};


function RuleContext(handle, rule) {
    var id = uniqueId();
    var isInput = !!rule.field;
    extend(this, {
        id: id,
        ref: id,
        rule: rule,
        name: rule.name,
        prop: _objectSpread2({}, rule),
        el: undefined,
        field: rule.field || undefined
    });

    this.updateType();
}

var CreateNode = CreateNodeFactory();


extend(RuleContext.prototype,{
    updateType: function updateType() {
        this.originType = this.rule.type;
        this.type = toCase(this.rule.type);
    },
    setParser: function setParser(parser) {
        this.parser = parser;

        parser.init(this);
    },
    defaultRender: function defaultRender(ctx, children) {
        var prop = ctx.prop;

        if (CreateNode.prototype[ctx.type]) return CreateNode.prototype[ctx.type](prop, children);
        // if (this.vNode[ctx.originType]) return this.vNode[ctx.originType](prop, children);
        return CreateNodeFactory().prototype.make(lower(ctx.originType), prop, children);
    },
})

var NAME$9 = 'FcFragment';
var fragment = vue.defineComponent({
    name: NAME$9,
    inheritAttrs: false,
    props: ['formCreateInject'],
    setup: function setup(props) {
        var data = vue.toRef(props, 'formCreateInject'); // 获取这个props['formCreateInject']

        var $inject = vue.reactive(_objectSpread2({}, data.value));
        vue.watch(data, function () {
            extend($inject, data.value);
        });
        vue.provide('formCreateInject', $inject);
    },
    render: function render() {
        return this.$slots["default"]();
    }
});

function FormCreate(vm) {  //4776

    var components = _defineProperty({}, fragment.name, fragment);  //定义一个 FcFragment

    function use(fn, opt) {
        // if (is.Function(fn.install)) fn.install(create, opt);else if (is.Function(fn)) fn(create, opt);
        // return this;
    }

    var _this = this;
    extend(this,{
		formData: vue.reactive({}),
        fieldCtx: {},
        ctxs: {},
        parsers: {},
        bindParser: function bindParser(ctx) {
            ctx.setParser(BaseParser);
        },
        parser:function(){
            var data = nameProp.apply(void 0, arguments);
            // if (!data.id || !data.prop) return;
            var name = toCase(data.id);
            var parser = data.prop;
            var base = parser.merge === true ? parsers[name] : undefined;
            this.parsers[name] = _objectSpread2(_objectSpread2({}, base || BaseParser), parser);
            extend(vm.appContext.components, this.parsers);
            // console.log("parser",this.parsers);
            // maker[name] = creatorFactory(name);
            // parser.maker && extend(maker, parser.maker);
        },
        nameCtx: {},
        sort: [],
        CreateNode: CreateNode,
        rules:vm.props.rule,
        use:use,
        vm:vm,
        options: {form:{}},
        init:function () {   //4812
            this.loadRule()
            this.initOptions(this.vm.option || {});
            this.clearCacheAll();
            this.$r = function () {
                return this.renderRule.apply(this, arguments);
            };
        },
        renderRule: function(rule, children, origin) {

            var type;
            type = rule.type;
            if (type) {
                type = toCase(rule.type);
                var alias = this.CreateNode.aliasMap[type];
                if (alias) type = toCase(alias);
            }
            var slotBag = makeSlotBag();
            return this.CreateNode.prototype.make(type,rule,slotBag.mergeBag(children).getSlots())
        },
        loadRule(){
          let rules = this.rules;
          var _this = this;
            rules.map(function (_rule, index) {
                var ctx;
                ctx = new RuleContext(_this,(_rule));
                _this.bindParser(ctx);
                _this.sort.push(ctx.id);
                _this.setCtx(ctx);
            });
        },
        setCtx(ctx){
            var id = ctx.id;
            this.ctxs[id] = ctx;
        },
        $handleRender(){
            return this.$renderRender()
        },
        $renderRender(){
            var _this = this;
            var slotBag = makeSlotBag();


            this.sort.forEach(function (k) {

                _this.renderSlot(slotBag, _this.ctxs[k]);
            });

            // console.log(this.$manager.render,'2333');
            return this.$managerRender(slotBag);
        },
        $managerRender(children){
                var _this2 = this;
                if (children.slotLen()) {
                    // children.setSlot(undefined, function () {
                    //     // return _this2.makeFormBtn();
                    // });
                }


                return this.$r(this.rule, [this.makeRow(children)]);
        },
        makeRow: function makeRow(children) {
            var row = this.options.row || {};
            return this.$r({
                type: 'row',
                props: row,
                "class": row["class"],
                key: "".concat(this.key, "row")
            }, children);
        },
		makeWrap:function(ctx, children){
			var _this3 = this;
			var rule = ctx.prop;
			var uni = "".concat(this.key).concat(ctx.key);

			var isTitle = true;
			var col = rule.col;
			var labelWidth = 100;
			return this.$r(mergeProps([rule.wrap, {
				props: _objectSpread2(_objectSpread2({
					labelWidth: labelWidth === void 0 ? labelWidth : toString(labelWidth)
				}, rule.wrap || {}), {}, {
					prop: ctx.field,
					rules: rule.validate
				}),
				"class": rule.className,
				key: "".concat(uni, "fi"),
				ref: ctx.wrapRef,
				type: 'formItem'
			}]), _objectSpread2({
				"default": function _default() {
					return children;
				}
			}, isTitle ? {
				label: function label() {
					return 'w1w1w1';
				}
			} : {}))
		},

        renderSlot(slotBag, ctx, parent){
            slotBag.setSlot(ctx.rule.slot, this.renderCtx(ctx, parent));
        },
        clearCacheAll: function clearCacheAll() {
            this.cache = {};
        },
        renderChildren: function renderChildren(ctx) {
            return {};
        },
        renderCtx(ctx, parent){

            var rule = ctx.rule;
            var _this = this;

            if(!this.cache[ctx.id]){
				this.ctxProp(ctx);
				
				this.setCache(ctx, undefined, parent);
                var vn = function vn() {

                    return _this.item(ctx, function () {

                        var _vn = ctx.parser.render(_this.renderChildren(ctx), ctx);
						_vn = _this.makeWrap(ctx, _vn);
                        //
						// if (!(!ctx.input && is.Undef(prop["native"])) && prop["native"] !== true) {
						//
						// }

                        // _this.renderSides(_vn,ctx)
                        return _vn;
                    });
                };
                this.setCache(ctx, vn, parent);
                ctx._vnode = vn;
            }
            return this.getCache(ctx);
        },
		ctxProp:function(ctx,custom){
			var _this6 = this;
			var ref = ctx.ref,
				key = ctx.key,
				rule = ctx.rule;

			var props = [{
				ref: ref,
				key: rule.key || "".concat(key, "fc"),
				slot: undefined,
			}];

				var field = this.getModelField(ctx);
				console.log('ctx',field);

				props.push({
					on: _defineProperty({}, "update:".concat(field), function update(value) {
						_this6.onInput(ctx, value);
						// console.log('update',value);
						console.log(_this6.formData)
					}),
					props: _defineProperty({}, field,_this.getFormData(ctx))
				});
			mergeProps(props, ctx.prop);

			return ctx.prop;
		},
		getFormData: function getFormData(ctx) {
			return this.formData[ctx.id];
		},
		onInput: function onInput(ctx, value) {
			var val = value;
			// if (ctx.input && (this.isQuote(ctx, val = ctx.parser.toValue(value, ctx)) || this.isChange(ctx, value))) {
					this.setValue(ctx, val, value);
			// }
		},
		setValue: function setValue(ctx, value, formValue, setFlag) {
			// if (ctx.deleted) return;
			ctx.rule.value = value;

			this.changeStatus = true;
			// this.nextRefresh();
			this.clearCache(ctx);
			this.setFormData(ctx, formValue);
			// this.syncValue();
			// this.valueChange(ctx, value);
			// this.vm.emit('change', ctx.field, value, ctx.origin, this.api, setFlag);
			// this.effect(ctx, 'value');
		},
		syncValue: function syncValue() {
			if (this.deferSyncFn) {
				return this.deferSyncFn.sync = true;
			}

			this.vm.setupState.updateValue(_objectSpread2({}));
		},
		setFormData: function setFormData(ctx, value) {
			$set(this.formData, ctx.id, value);
		},

		getModelField(ctx){
			return 'modelValue';
		},
		clearCache: function clearCache(ctx) {
			// if (!this.cache[ctx.id]) {
			// 	ctx.parent && this.clearCache(ctx.parent);
			// 	return;
			// }
			// if (this.cache[ctx.id].use === true || this.cache[ctx.id].parent) {
			// 	this.$handle.refresh();
			// }
			//
			// var parent = this.cache[ctx.id].parent;
			// console.log(this.cache);

			this.cache[ctx.id] = null;
			// parent && this.clearCache(parent);
		},
        // renderSides(vn,ctx,temp){
        //     var prop = ctx[temp ? 'rule' : 'prop'];
        //     console.log('renderSides',prop);
        //     console.log([this.renderRule(prop.prefix), vn, this.renderRule(prop.suffix)]);
        //
        // },
        setCache: function setCache(ctx, vnode, parent) {
            this.cache[ctx.id] = {
                vnode: vnode,
                use: false,
                parent: parent,
                slot: ctx.rule.slot
            };
        },
        getCache: function getCache(ctx) {
            var cache = this.cache[ctx.id];
            cache.use = true;
            return cache.vnode;
        },
        item: function item(ctx, vn) {
            return this.CreateNode.prototype.h('FcFragment', {
                key: ctx.key,
                formCreateInject: this.injectProp(ctx)
            }, vn);
        },
        initOptions(options){
            this.updateOptions(options)
        },
        injectProp: function injectProp(ctx) {
            var _this5 = this;
            return {
                api: "",
                form:"",
                field: ctx.field,
                options: ctx.prop.options,
                children: ctx.rule.children,
                rule: ctx.rule,
                prop: function () {
                    var temp = _objectSpread2({}, ctx.prop);
                    return temp.on = temp.on ? _objectSpread2({}, temp.on) : {}, temp;
                }()
            };
        },
        updateOptions(options){
            this.update();
        },
        update(){
            var form = this.options.form;
            this.rule = {
                props: _objectSpread2({}, form),
                on: {
                    submit: function submit(e) {
                        e.preventDefault();
                    }
                },
                "class": [form.className, form["class"], 'form-create'],
                style: form.style,
                type: 'form'
            };
        }
    })

    var maker = {};

    parsers.forEach((parser)=>{
        this.parser(parser);
    });
    Object.keys(maker).forEach(function (name) {
        this.maker[name] = maker[name];
    });

    CreateNode.use({
        fragment: 'fcFragment'
    });

    extend(this,{
        install:()=>{
            CreateNode.use(alias);
        }
    })


    extend(vm.appContext.components, components);

}




export  {
    FormCreate
}
