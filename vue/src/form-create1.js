var define = {};
(function (global, factory) {
	typeof exports === "object" && typeof module !== "undefined"
		? factory(exports, require("vue"), require("element-plus"))
		: typeof define === "function" && define.amd
		? define(["exports", "vue", "element-plus"], factory)
		: ((global = global || self),
		  factory((global.formCreate = {}), global.Vue, global.ElementPlus));
})(this, function (exports, vue, ElementPlus) {
	"use strict";

	function _unsupportedIterableToArray(o, minLen) {
		if (!o) return;
		if (typeof o === "string") return _arrayLikeToArray(o, minLen);
		var n = Object.prototype.toString.call(o).slice(8, -1);
		if (n === "Object" && o.constructor) n = o.constructor.name;
		if (n === "Map" || n === "Set") return Array.from(o);
		if (
			n === "Arguments" ||
			/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)
		)
			return _arrayLikeToArray(o, minLen);
	}
	function $set(target, field, value) {
		target[field] = value;
	}
	function $del(target, field) {
		delete target[field];
	}
	var _extends =
		Object.assign ||
		function (a) {
			for (var b, c = 1; c < arguments.length; c++) {
				for (var d in ((b = arguments[c]), b)) {
					Object.prototype.hasOwnProperty.call(b, d) &&
						$set(a, d, b[d]);
				}
			}

			return a;
		};
	function extend() {
		return _extends.apply(this, arguments);
	}

	function _arrayLikeToArray(arr, len) {
		if (len == null || len > arr.length) len = arr.length;

		for (var i = 0, arr2 = new Array(len); i < len; i++) arr2[i] = arr[i];

		return arr2;
	}
	function _iterableToArray(iter) {
		if (typeof Symbol !== "undefined" && Symbol.iterator in Object(iter))
			return Array.from(iter);
	}
	function _nonIterableSpread() {
		throw new TypeError(
			"Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."
		);
	}

	function _arrayWithoutHoles(arr) {
		if (Array.isArray(arr)) return _arrayLikeToArray(arr);
	}

	function _toConsumableArray(arr) {
		return (
			_arrayWithoutHoles(arr) ||
			_iterableToArray(arr) ||
			_unsupportedIterableToArray(arr) ||
			_nonIterableSpread()
		);
	}
	function _defineProperty(obj, key, value) {
		if (key in obj) {
			Object.defineProperty(obj, key, {
				value: value,
				enumerable: true,
				configurable: true,
				writable: true,
			});
		} else {
			obj[key] = value;
		}

		return obj;
	}
	function ownKeys(object, enumerableOnly) {
		var keys = Object.keys(object);

		if (Object.getOwnPropertySymbols) {
			var symbols = Object.getOwnPropertySymbols(object);
			if (enumerableOnly)
				symbols = symbols.filter(function (sym) {
					return Object.getOwnPropertyDescriptor(
						object,
						sym
					).enumerable;
				});
			keys.push.apply(keys, symbols);
		}

		return keys;
	}

	function _objectSpread2(target) {
		for (var i = 1; i < arguments.length; i++) {
			var source = arguments[i] != null ? arguments[i] : {};

			if (i % 2) {
				ownKeys(Object(source), true).forEach(function (key) {
					_defineProperty(target, key, source[key]);
				});
			} else if (Object.getOwnPropertyDescriptors) {
				Object.defineProperties(
					target,
					Object.getOwnPropertyDescriptors(source)
				);
			} else {
				ownKeys(Object(source)).forEach(function (key) {
					Object.defineProperty(
						target,
						key,
						Object.getOwnPropertyDescriptor(source, key)
					);
				});
			}
		}

		return target;
	}

	var NAME$8 = "FormCreate";
	var $FormCreate = function (FormCreate) {
		return vue.defineComponent({
			name: NAME$8,
			props: {
				rule: {
					type: Array,
					required: true,
				},
				option: {
					type: Object,
					default: function _default() {
						return {};
					},
				},
				extendOption: Boolean,
				modelValue: Object,
				api: Object,
			},
			render: function render() {
				console.log(this.fc);

				return this.fc.render();
			},
			setup: function setup(props) {
				var vm = vue.getCurrentInstance(); //获取当前组件的实例
				const { rule, modelValue } = vue.toRefs(props);
				console.log(rule);
				var data = vue.reactive({
					destroyed: false,
					isShow: true,
					unique: 1,
					renderRule: _toConsumableArray(rule.value || []),
					updateValue: JSON.stringify(modelValue),
				});
				var fc = new FormCreate(vm);

				return _objectSpread2({ fc: fc }, vue.toRefs(data));
			},
		});
	};

	function FormCreateFactory(config) {
		var directives = {};

		function create(rules, _opt) {
			var app = createFormApp(rules, _opt || {});
			config.appUse && config.appUse(app);
			var vm = app.mount(
				(_opt === null || _opt === void 0 ? void 0 : _opt.el) ||
					document.body
			);
			return vm.$refs.fc.fapi;
		}

		function createFormApp(rules, option) {
			var Type = $form();
			return vue.createApp({
				data: function data() {
					//todo 外部无法修改
					return {
						rule: vue.ref(rules),
						option: vue.ref(option || {}),
					};
				},
				render: function render() {
					console.log(Type);
					return vue.h(
						Type,
						_objectSpread2(
							{
								ref: "fc",
							},
							this.$data
						)
					);
				},
			});
		} //todo 检查回调函数作用域

		var NAME$9 = "FcFragment";
		var fragment = vue.defineComponent({
			name: NAME$9,
			inheritAttrs: false,
			props: ["formCreateInject"],
			setup: function setup(props) {
				var data = vue.toRef(props, "formCreateInject");
				var $inject = vue.reactive(_objectSpread2({}, data.value));
				vue.watch(data, function () {
					extend($inject, data.value);
				});
				vue.provide("formCreateInject", $inject);
			},
			render: function render() {
				return this.$slots["default"]();
			},
		});

		var components = _defineProperty({}, fragment.name, fragment);

		function funcProxy(that, proxy) {
			Object.defineProperties(
				that,
				Object.keys(proxy).reduce(function (initial, k) {
					initial[k] = {
						get: function get() {
							return proxy[k]();
						},
					};
					return initial;
				}, {})
			);
		}

		var id$1 = 1;
		function Render(handle) {
			extend(this, {
				$handle: handle,
				fc: handle.fc,
				vm: handle.vm,
				$manager: handle.$manager,
				// vNode: new handle.fc.CreateNode(handle.vm),
				id: id$1++,
			});
			funcProxy(this, {
				options: function options() {
					return handle.options;
				},
				sort: function sort() {
					return handle.sort;
				},
			});
			// this.initCache();
			this.initRender();
		}
		useRender(Render);

		function useRender(Render) {
			extend(Render.prototype, {
				initRender: function initRender() {
					this.cacheConfig = {};
				},
				render: function render() {
					var _this = this;
					console.log(this.vm);

					if (!this.vm.isShow) {
						return;
					}

					// this.$manager.beforeRender();
					var slotBag = makeSlotBag();
					// this.sort.forEach(function (k) {
					// 	_this.renderSlot(slotBag, _this.$handle.ctxs[k]);
					// });
					return this.$manager.render(slotBag);
				},
			});
		}

		function Handler(fc) {
			var _this = this;

			// funcProxy(this, {
			// 	options: function options() {
			// 		return fc.options.value || {};
			// 	},
			// 	bus: function bus() {
			// 		return fc.bus;
			// 	}
			// });

			extend(this, {
				fc: fc,
				vm: fc.vm,
				watching: false,
				loading: false,
				reloading: false,
				noWatchFn: null,
				deferSyncFn: null,
				isMounted: false,
				formData: vue.reactive({}),
				subForm: {},
				form: vue.reactive({}),
				appendData: {},
				providers: {},
				cycleLoad: null,
				loadedId: 1,
				nextTick: null,
				changeStatus: false,
				pageEnd: true,
				nextReload: function nextReload() {
					_this.lifecycle("reload");
				},
			});
			// this.initData(fc.rules);
			// this.$manager = new fc.manager(this);
			this.$render = new Render(this);
			// this.api = fc.extendApi(Api(this), this);
		}

		extend(Handler.prototype, {
			initData: function initData(rules) {
				console.log(rules, 8);
				extend(this, {
					fieldCtx: {},
					ctxs: {},
					nameCtx: {},
					sort: [],
					rules: rules,
					repeatRule: [],
				});
			},
			init: function init() {
				this.appendData = _objectSpread2(
					_objectSpread2(
						_objectSpread2({}, this.options.formData || {}),
						this.fc.vm.modelValue || {}
					),
					this.appendData
				);
				this.useProvider();
				this.usePage();
				this.loadRule();
				this.$manager.__init();
			},
			isBreakWatch: function isBreakWatch() {
				return this.loading || this.noWatchFn || this.reloading;
			},
		});

		useRender$1(Handler);
		function useRender$1(Handler) {
			extend(Handler.prototype, {
				clearNextTick: function clearNextTick() {
					this.nextTick && clearTimeout(this.nextTick);
					this.nextTick = null;
				},
				bindNextTick: function bindNextTick(fn) {
					var _this = this;

					this.clearNextTick();
					this.nextTick = setTimeout(function () {
						fn();
						_this.nextTick = null;
					}, 10);
				},
				render: function render() {
					// console.warn('%c render', 'color:green');
					console.log(this);

					++this.loadedId;
					if (this.vm.unique > 0) return this.$render.render();
					else {
						this.vm.unique = 1;
						return [];
					}
				},
			});
		}

		function FormCreate(vm) {
			var _this = this;
			extend(this, {
				// 	create: create,
				vm: vm.ctx,
				//
				// 	rules: vm.props.rule,
				// 	prop: {
				// 		components: components,
				// 		directives: directives
				// 	},
				//
				// 	unwatch: null,
				// 	options: vue.ref({}),
				// 	extendApi: config.extendApi || function (api) {
				// 		return api;
				// 	}
				// });
				// vue.watch(this.options, function () {
				// 	_this.$handle.$manager.updateOptions(_this.options.value);
				//
				// 	_this.api().refresh();
				// }, {
				// 	deep: true
			});
			// extend(vm.appContext.components, components);
			// extend(vm.appContext.directives, directives);
			this.$handle = new Handler(this);
		}

		extend(FormCreate.prototype, {
			render: function render() {
				return this.$handle.render();
			},
		});

		console.log(FormCreate);

		function $form() {
			return $FormCreate(FormCreate);
		}

		return $form();
	}

	function elmFormCreate() {
		return FormCreateFactory({
			ui: '"element-ui"',
			version: '"2.5.9"',

			attrs: {
				normal: ["col", "wrap"],
				array: ["className"],
				key: ["title", "info"],
			},
		});
	}

	var FormCreate = elmFormCreate();

	exports.default = FormCreate;
});
