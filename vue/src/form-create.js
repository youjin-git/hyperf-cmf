/*!
 * @form-create/element-ui v3.0.0.beta.1
 * (c) 2018-2021 xaboy
 * Github https://github.com/xaboy/form-create
 * Released under the MIT License.
 */
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

	ElementPlus =
		ElementPlus &&
		Object.prototype.hasOwnProperty.call(ElementPlus, "default")
			? ElementPlus["default"]
			: ElementPlus;

	function _typeof(obj) {
		"@babel/helpers - typeof";

		if (
			typeof Symbol === "function" &&
			typeof Symbol.iterator === "symbol"
		) {
			return function (obj) {
				return typeof obj;
			};
		} else {
			return function (obj) {
				return obj &&
					typeof Symbol === "function" &&
					obj.constructor === Symbol &&
					obj !== Symbol.prototype
					? "symbol"
					: typeof obj;
			};
		}
	}

	function _classCallCheck(instance, Constructor) {
		if (!(instance instanceof Constructor)) {
			throw new TypeError("Cannot call a class as a function");
		}
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

	function _inherits(subClass, superClass) {
		if (typeof superClass !== "function" && superClass !== null) {
			throw new TypeError(
				"Super expression must either be null or a function"
			);
		}

		subClass.prototype = Object.create(superClass && superClass.prototype, {
			constructor: {
				value: subClass,
				writable: true,
				configurable: true,
			},
		});
		if (superClass) _setPrototypeOf(subClass, superClass);
	}

	function _getPrototypeOf(o) {
		var _getPrototypeOf = Object.setPrototypeOf
			? Object.getPrototypeOf
			: function _getPrototypeOf(o) {
					return o.__proto__ || Object.getPrototypeOf(o);
			  };
		return _getPrototypeOf(o);
	}

	function _setPrototypeOf(o, p) {
		var _setPrototypeOf =
			Object.setPrototypeOf ||
			function _setPrototypeOf(o, p) {
				o.__proto__ = p;
				return o;
			};

		return _setPrototypeOf(o, p);
	}

	function _isNativeReflectConstruct() {
		if (typeof Reflect === "undefined" || !Reflect.construct) return false;
		if (Reflect.construct.sham) return false;
		if (typeof Proxy === "function") return true;

		try {
			Date.prototype.toString.call(
				Reflect.construct(Date, [], function () {})
			);
			return true;
		} catch (e) {
			return false;
		}
	}

	function _assertThisInitialized(self) {
		if (self === void 0) {
			throw new ReferenceError(
				"this hasn't been initialised - super() hasn't been called"
			);
		}

		return self;
	}

	function _possibleConstructorReturn(self, call) {
		if (call && (typeof call === "object" || typeof call === "function")) {
			return call;
		}

		return _assertThisInitialized(self);
	}

	function _createSuper(Derived) {
		var hasNativeReflectConstruct = _isNativeReflectConstruct();

		return function _createSuperInternal() {
			var Super = _getPrototypeOf(Derived),
				result;

			if (hasNativeReflectConstruct) {
				var NewTarget = _getPrototypeOf(this).constructor;

				result = Reflect.construct(Super, arguments, NewTarget);
			} else {
				result = Super.apply(this, arguments);
			}

			return _possibleConstructorReturn(this, result);
		};
	}

	function _toConsumableArray(arr) {
		return (
			_arrayWithoutHoles(arr) ||
			_iterableToArray(arr) ||
			_unsupportedIterableToArray(arr) ||
			_nonIterableSpread()
		);
	}

	function _arrayWithoutHoles(arr) {
		if (Array.isArray(arr)) return _arrayLikeToArray(arr);
	}

	function _iterableToArray(iter) {
		if (typeof Symbol !== "undefined" && Symbol.iterator in Object(iter))
			return Array.from(iter);
	}

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

	function _arrayLikeToArray(arr, len) {
		if (len == null || len > arr.length) len = arr.length;

		for (var i = 0, arr2 = new Array(len); i < len; i++) arr2[i] = arr[i];

		return arr2;
	}

	function _nonIterableSpread() {
		throw new TypeError(
			"Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."
		);
	}

	function getSlot(slots, exclude) {
		return Object.keys(slots).reduce(function (lst, name) {
			if (!exclude || exclude.indexOf(name) === -1) {
				lst.push(slots[name]);
			}

			return lst;
		}, []);
	}

	var NAME = "fcCheckbox";
	var Checkbox = vue.defineComponent({
		name: NAME,
		props: {
			modelValue: {
				type: Array,
				default: function _default() {
					return [];
				},
			},
			type: String,
		},
		emits: ["update:modelValue"],
		setup: function setup(props, _) {
			var _toRefs = vue.toRefs(vue.inject("formCreateInject")),
				options = _toRefs.options;

			var trueValue = vue.ref([]);
			var value = vue.toRef(props, "modelValue");

			var update = function update() {
				trueValue.value = value.value
					? options.value
							.filter(function (opt) {
								return value.value.indexOf(opt.value) !== -1;
							})
							.map(function (option) {
								return option.label;
							})
					: [];
			};

			update();
			return {
				options: options,
				trueValue: trueValue,
				value: value,
				onInput: function onInput(n) {
					_.emit(
						"update:modelValue",
						options.value
							.filter(function (opt) {
								return n.indexOf(opt.label) !== -1;
							})
							.map(function (opt) {
								return opt.value;
							})
							.filter(function (v) {
								return v !== undefined;
							})
					);
				},
				update: update,
			};
		},
		watch: {
			modelValue: function modelValue() {
				this.update();
			},
		},
		render: function render() {
			var _this$$slots$default,
				_this$$slots,
				_this = this;

			var name =
				this.type === "button" ? "ElCheckboxButton" : "ElCheckbox";
			var Type = vue.resolveComponent(name);
			return vue.createVNode(
				vue.resolveComponent("ElCheckboxGroup"),
				vue.mergeProps(this.$attrs, {
					modelValue: this.trueValue,
					"onUpdate:modelValue": this.onInput,
				}),
				_objectSpread2(
					{
						default: function _default() {
							return [
								_this.options.map(function (opt, index) {
									var props = _objectSpread2({}, opt);

									delete props.value;
									return vue.createVNode(
										Type,
										vue.mergeProps(props, {
											key: name + index + opt.value,
										}),
										null
									);
								}),
								(_this$$slots$default = (_this$$slots =
									_this.$slots)["default"]) === null ||
								_this$$slots$default === void 0
									? void 0
									: _this$$slots$default.call(_this$$slots),
							];
						},
					},
					getSlot(this.$slots, ["default"])
				)
			);
		},
	});

	function toArray(value) {
		return Array.isArray(value)
			? value
			: [null, undefined, ""].indexOf(value) > -1
			? []
			: [value];
	}

	// https://github.com/developit/mitt
	function Mitt(all) {
		all = all || new Map();
		var mitt = {
			$on: function $on(type, handler) {
				var handlers = all.get(type);
				var added = handlers && handlers.push(handler);

				if (!added) {
					all.set(type, [handler]);
				}
			},
			$once: function $once(type, handler) {
				handler._once = true;
				mitt.$on(type, handler);
			},
			$off: function $off(type, handler) {
				var handlers = all.get(type);

				if (handlers) {
					handlers.splice(handlers.indexOf(handler) >>> 0, 1);
				}
			},
			$emit: function $emit(type) {
				for (
					var _len = arguments.length,
						args = new Array(_len > 1 ? _len - 1 : 0),
						_key = 1;
					_key < _len;
					_key++
				) {
					args[_key - 1] = arguments[_key];
				}

				(all.get(type) || []).slice().map(function (handler) {
					if (handler._once) {
						mitt.$off(type, handler);
						delete handler._once;
					}

					handler.apply(void 0, args);
				});
				(all.get("*") || []).slice().map(function (handler) {
					handler(type, args);
				});
			},
		};
		return mitt;
	}

	function styleInject(css, ref) {
		if (ref === void 0) ref = {};
		var insertAt = ref.insertAt;

		if (!css || typeof document === "undefined") {
			return;
		}

		var head = document.head || document.getElementsByTagName("head")[0];
		var style = document.createElement("style");
		style.type = "text/css";

		if (insertAt === "top") {
			if (head.firstChild) {
				head.insertBefore(style, head.firstChild);
			} else {
				head.appendChild(style);
			}
		} else {
			head.appendChild(style);
		}

		if (style.styleSheet) {
			style.styleSheet.cssText = css;
		} else {
			style.appendChild(document.createTextNode(css));
		}
	}

	var css_248z =
		"._fc-frame .fc-files img {\n    width: 100%;\n    height: 100%;\n    display: inline-block;\n    vertical-align: top;\n}\n\n._fc-frame .fc-upload-btn {\n    border: 1px dashed #c0ccda;\n    cursor: pointer;\n}\n\n._fc-frame .fc-upload-cover {\n    opacity: 0;\n    position: absolute;\n    top: 0;\n    bottom: 0;\n    left: 0;\n    right: 0;\n    background: rgba(0, 0, 0, .6);\n    -webkit-transition: opacity .3s;\n    -o-transition: opacity .3s;\n    transition: opacity .3s;\n}\n\n._fc-frame .fc-upload-cover i {\n    color: #fff;\n    font-size: 20px;\n    cursor: pointer;\n    margin: 0 2px;\n}\n\n._fc-frame .fc-files:hover .fc-upload-cover {\n    opacity: 1;\n}\n\n._fc-frame .el-upload {\n    display: block;\n}\n\n._fc-frame .fc-upload-btn, .fc-files {\n    display: inline-block;\n    width: 58px;\n    height: 58px;\n    text-align: center;\n    line-height: 58px;\n    border: 1px solid #c0ccda;\n    border-radius: 4px;\n    overflow: hidden;\n    background: #fff;\n    position: relative;\n    -webkit-box-shadow: 2px 2px 5px rgba(0, 0, 0, .1);\n    box-shadow: 2px 2px 5px rgba(0, 0, 0, .1);\n    margin-right: 4px;\n    -webkit-box-sizing: border-box;\n    box-sizing: border-box;\n}\n";
	styleInject(css_248z);

	function _isSlot(s) {
		return (
			typeof s === "function" ||
			(Object.prototype.toString.call(s) === "[object Object]" &&
				!vue.isVNode(s))
		);
	}

	var NAME$1 = "fcFrame";
	var Frame = vue.defineComponent({
		name: NAME$1,
		props: {
			type: {
				type: String,
				default: "input",
			},
			field: String,
			helper: {
				type: Boolean,
				default: true,
			},
			disabled: {
				type: Boolean,
				default: false,
			},
			src: {
				type: String,
				required: true,
			},
			icon: {
				type: String,
				default: "el-icon-upload2",
			},
			width: {
				type: String,
				default: "500px",
			},
			height: {
				type: String,
				default: "370px",
			},
			maxLength: {
				type: Number,
				default: 0,
			},
			okBtnText: {
				type: String,
				default: "确定",
			},
			closeBtnText: {
				type: String,
				default: "关闭",
			},
			modalTitle: String,
			handleIcon: {
				type: [String, Boolean],
				default: undefined,
			},
			title: String,
			allowRemove: {
				type: Boolean,
				default: true,
			},
			onOpen: {
				type: Function,
				default: function _default() {},
			},
			onOk: {
				type: Function,
				default: function _default() {},
			},
			onCancel: {
				type: Function,
				default: function _default() {},
			},
			onLoad: {
				type: Function,
				default: function _default() {},
			},
			onBeforeRemove: {
				type: Function,
				default: function _default() {},
			},
			onRemove: {
				type: Function,
				default: function _default() {},
			},
			onHandle: Function,
			modal: {
				type: Object,
				default: function _default() {
					return {};
				},
			},
			srcKey: [String, Number],
			modelValue: [Array, String, Number, Object],
			previewMask: undefined,
			footer: {
				type: Boolean,
				default: true,
			},
			reload: {
				type: Boolean,
				default: true,
			},
			closeBtn: {
				type: Boolean,
				default: true,
			},
			okBtn: {
				type: Boolean,
				default: true,
			},
		},
		inject: ["formCreateInject"],
		emits: ["update:modelValue", "change"],
		data: function data() {
			return {
				fileList: toArray(this.modelValue),
				previewVisible: false,
				frameVisible: false,
				previewImage: "",
				bus: new Mitt(),
			};
		},
		watch: {
			modelValue: function modelValue(n) {
				this.fileList = toArray(n);
			},
		},
		methods: {
			key: function key(unique) {
				return unique;
			},
			closeModel: function closeModel(close) {
				this.bus.$emit(close ? "$close" : "$ok");

				if (this.reload) {
					this.bus.$off("$ok");
					this.bus.$off("$close");
				}

				this.frameVisible = false;
			},
			handleCancel: function handleCancel() {
				this.previewVisible = false;
			},
			showModel: function showModel() {
				if (this.disabled || false === this.onOpen()) {
					return;
				}

				this.frameVisible = true;
			},
			input: function input() {
				var n = this.fileList;
				var val = this.maxLength === 1 ? n[0] || "" : n;
				this.$emit("update:modelValue", val);
				this.$emit("change", val);
			},
			makeInput: function makeInput() {
				var _this = this;

				return vue.createVNode(
					vue.resolveComponent("ElInput"),
					vue.mergeProps(
						{
							type: "text",
							modelValue: this.fileList
								.map(function (v) {
									return _this.getSrc(v);
								})
								.toString(),
							readonly: true,
						},
						{
							key: this.key("input"),
						}
					),
					{
						append: function append() {
							return vue.createVNode(
								vue.resolveComponent("ElButton"),
								{
									icon: _this.icon,
									onClick: function onClick() {
										return _this.showModel();
									},
								},
								null
							);
						},
						suffix: function suffix() {
							return _this.fileList.length
								? vue.createVNode(
										"i",
										{
											class: "el-input__icon el-icon-circle-close",
											onClick: function onClick() {
												_this.fileList = [];

												_this.input();
											},
										},
										null
								  )
								: null;
						},
					}
				);
			},
			makeGroup: function makeGroup(children) {
				if (!this.maxLength || this.fileList.length < this.maxLength) {
					children.push(this.makeBtn());
				}

				return vue.createVNode(
					"div",
					{
						key: this.key("group"),
					},
					[children]
				);
			},
			makeItem: function makeItem(index, children) {
				return vue.createVNode(
					"div",
					{
						class: "fc-files",
						key: this.key("file" + index),
					},
					[children]
				);
			},
			valid: function valid(f) {
				var field = this.formCreateInject.field || this.field;

				if (field && f !== field) {
					throw new Error("[frame]无效的字段值");
				}
			},
			makeIcons: function makeIcons(val, index) {
				if (this.handleIcon !== false || this.allowRemove === true) {
					var icons = [];

					if (
						(this.type !== "file" && this.handleIcon !== false) ||
						(this.type === "file" && this.handleIcon)
					) {
						icons.push(this.makeHandleIcon(val, index));
					}

					if (this.allowRemove) {
						icons.push(this.makeRemoveIcon(val, index));
					}

					return vue.createVNode(
						"div",
						{
							class: "fc-upload-cover",
							key: this.key("uc"),
						},
						[icons]
					);
				}
			},
			makeHandleIcon: function makeHandleIcon(val, index) {
				var _this2 = this;

				return vue.createVNode(
					"i",
					{
						class:
							this.handleIcon === true ||
							this.handleIcon === undefined
								? "el-icon-view"
								: this.handleIcon,
						onClick: function onClick() {
							return _this2.handleClick(val);
						},
						key: this.key("hi" + index),
					},
					null
				);
			},
			makeRemoveIcon: function makeRemoveIcon(val, index) {
				var _this3 = this;

				return vue.createVNode(
					"i",
					{
						class: "el-icon-delete",
						onClick: function onClick() {
							return _this3.handleRemove(val);
						},
						key: this.key("ri" + index),
					},
					null
				);
			},
			makeFiles: function makeFiles() {
				var _this4 = this;

				return this.makeGroup(
					this.fileList.map(function (src, index) {
						return _this4.makeItem(index, [
							vue.createVNode(
								"i",
								{
									class: "el-icon-tickets",
									onClick: function onClick() {
										return _this4.handleClick(src);
									},
								},
								null
							),
							_this4.makeIcons(src, index),
						]);
					})
				);
			},
			makeImages: function makeImages() {
				var _this5 = this;

				return this.makeGroup(
					this.fileList.map(function (src, index) {
						return _this5.makeItem(index, [
							vue.createVNode(
								"img",
								{
									src: _this5.getSrc(src),
								},
								null
							),
							_this5.makeIcons(src, index),
						]);
					})
				);
			},
			makeBtn: function makeBtn() {
				var _this6 = this;

				return vue.createVNode(
					"div",
					{
						class: "fc-upload-btn",
						onClick: function onClick() {
							return _this6.showModel();
						},
						key: this.key("btn"),
					},
					[
						vue.createVNode(
							"i",
							{
								class: this.icon,
							},
							null
						),
					]
				);
			},
			handleClick: function handleClick(src) {
				var _this7 = this;

				if (this.disabled) {
					return;
				}

				return (
					this.onHandle ||
					function (src) {
						_this7.previewImage = _this7.getSrc(src);
						_this7.previewVisible = true;
					}
				)(src);
			},
			handleRemove: function handleRemove(src) {
				if (this.disabled) {
					return;
				}

				if (false !== this.onBeforeRemove(src)) {
					this.fileList.splice(this.fileList.indexOf(src), 1);
					this.input();
					this.onRemove(src);
				}
			},
			getSrc: function getSrc(src) {
				return !this.srcKey ? src : src[this.srcKey];
			},
			frameLoad: function frameLoad(iframe) {
				var _this8 = this;

				this.onLoad(iframe);

				try {
					if (this.helper === true) {
						iframe["form_create_helper"] = {
							close: function close(field) {
								_this8.valid(field);

								_this8.closeModel();
							},
							set: function set(field, value) {
								_this8.valid(field);

								!_this8.disabled &&
									_this8.$emit("update:modelValue", value);
							},
							get: function get(field) {
								_this8.valid(field);

								return _this8.modelValue;
							},
							onOk: function onOk(fn) {
								return _this8.bus.$on("$ok", fn);
							},
							onClose: function onClose(fn) {
								return _this8.bus.$on("$close", fn);
							},
						};
					}
				} catch (e) {
					console.error(e);
				}
			},
			makeFooter: function makeFooter() {
				var _this9 = this;

				var _this$$props = this.$props,
					okBtnText = _this$$props.okBtnText,
					closeBtnText = _this$$props.closeBtnText,
					closeBtn = _this$$props.closeBtn,
					okBtn = _this$$props.okBtn,
					footer = _this$$props.footer;

				if (!footer) {
					return;
				}

				return vue.createVNode("div", null, [
					closeBtn
						? vue.createVNode(
								vue.resolveComponent("ElButton"),
								{
									onClick: function onClick() {
										return (
											_this9.onCancel() !== false &&
											(_this9.frameVisible = false)
										);
									},
								},
								_isSlot(closeBtnText)
									? closeBtnText
									: {
											default: function _default() {
												return [closeBtnText];
											},
									  }
						  )
						: null,
					okBtn
						? vue.createVNode(
								vue.resolveComponent("ElButton"),
								{
									type: "primary",
									onClick: function onClick() {
										return (
											_this9.onOk() !== false &&
											_this9.closeModel()
										);
									},
								},
								_isSlot(okBtnText)
									? okBtnText
									: {
											default: function _default() {
												return [okBtnText];
											},
									  }
						  )
						: null,
				]);
			},
		},
		render: function render() {
			var _this10 = this;

			var type = this.type;
			var node;

			if (type === "input") {
				node = this.makeInput();
			} else if (type === "image") {
				node = this.makeImages();
			} else {
				node = this.makeFiles();
			}

			var _this$$props2 = this.$props,
				_this$$props2$width = _this$$props2.width,
				width =
					_this$$props2$width === void 0
						? "30%"
						: _this$$props2$width,
				height = _this$$props2.height,
				src = _this$$props2.src,
				title = _this$$props2.title,
				modalTitle = _this$$props2.modalTitle;
			this.$nextTick(function () {
				if (_this10.$refs.frame) {
					_this10.frameLoad(_this10.$refs.frame.contentWindow || {});
				}
			});
			return vue.createVNode(
				"div",
				{
					class: "_fc-frame",
				},
				[
					node,
					vue.createVNode(
						vue.resolveComponent("ElDialog"),
						{
							appendToBody: true,
							modal: this.previewMask,
							title: modalTitle,
							modelValue: this.previewVisible,
							onClose: this.handleCancel,
						},
						{
							default: function _default() {
								return [
									vue.createVNode(
										"img",
										{
											alt: "example",
											style: "width: 100%",
											src: _this10.previewImage,
										},
										null
									),
								];
							},
						}
					),
					vue.createVNode(
						vue.resolveComponent("ElDialog"),
						vue.mergeProps(
							{
								appendToBody: true,
							},
							_objectSpread2(
								{
									width: width,
									title: title,
								},
								this.modal
							),
							{
								modelValue: this.frameVisible,
								onClose: function onClose() {
									return _this10.closeModel(true);
								},
							}
						),
						{
							default: function _default() {
								return [
									_this10.frameVisible || !_this10.reload
										? vue.createVNode(
												"iframe",
												{
													ref: "frame",
													src: src,
													frameBorder: "0",
													style: {
														height: height,
														border: "0 none",
														width: "100%",
													},
												},
												null
										  )
										: null,
								];
							},
							footer: function footer() {
								return _this10.makeFooter();
							},
						}
					),
				]
			);
		},
		mounted: function mounted() {
			var _this11 = this;

			var close = function close() {
				return (_this11.frameVisible = false);
			};

			this.formCreateInject.api.on(
				"fc:closeModal:" + this.formCreateInject.name,
				close
			);
			this.formCreateInject.api.on(
				"fc:closeModal:" + this.formCreateInject.field,
				close
			);
		},
	});

	var NAME$2 = "fcRadio";
	var Radio = vue.defineComponent({
		name: NAME$2,
		props: {
			modelValue: {
				type: [String, Number],
				default: function _default() {
					return [];
				},
			},
			type: String,
		},
		emits: ["update:modelValue"],
		setup: function setup(props, _) {
			var _toRefs = vue.toRefs(vue.inject("formCreateInject")),
				options = _toRefs.options;

			var trueValue = vue.ref([]);
			var value = vue.toRef(props, "modelValue");

			var update = function update() {
				trueValue.value = options.value
					.filter(function (opt) {
						return opt.value === value.value;
					})
					.reduce(function (initial, opt) {
						return opt.label;
					}, "");
			};

			update();
			return {
				options: options,
				trueValue: trueValue,
				value: value,
				onInput: function onInput(n) {
					_.emit(
						"update:modelValue",
						options.value
							.filter(function (opt) {
								return opt.label === n;
							})
							.reduce(function (initial, opt) {
								return opt.value;
							}, "")
					);
				},
				update: update,
			};
		},
		watch: {
			modelValue: function modelValue() {
				this.update();
			},
		},
		render: function render() {
			var _this$$slots$default,
				_this$$slots,
				_this = this;

			var name = this.type === "button" ? "ElRadioButton" : "ElRadio";
			var Type = vue.resolveComponent(name);
			return vue.createVNode(
				vue.resolveComponent("ElRadioGroup"),
				vue.mergeProps(this.$attrs, {
					modelValue: this.trueValue,
					"onUpdate:modelValue": this.onInput,
				}),
				_objectSpread2(
					{
						default: function _default() {
							return [
								_this.options.map(function (opt, index) {
									var props = _objectSpread2({}, opt);

									delete props.value;
									return vue.createVNode(
										Type,
										vue.mergeProps(props, {
											key: name + index + opt.value,
										}),
										null
									);
								}),
								(_this$$slots$default = (_this$$slots =
									_this.$slots)["default"]) === null ||
								_this$$slots$default === void 0
									? void 0
									: _this$$slots$default.call(_this$$slots),
							];
						},
					},
					getSlot(this.$slots, ["default"])
				)
			);
		},
	});

	var NAME$3 = "fcSelect";
	var Select = vue.defineComponent({
		name: NAME$3,
		props: {
			modelValue: {
				type: Array,
				default: function _default() {
					return [];
				},
			},
			type: String,
		},
		emits: ["update:modelValue"],
		setup: function setup(props) {
			var _toRefs = vue.toRefs(vue.inject("formCreateInject")),
				options = _toRefs.options;

			var value = vue.toRef(props, "modelValue");
			return {
				options: options,
				value: value,
			};
		},
		render: function render() {
			var _this = this,
				_this$$slots$default,
				_this$$slots;

			return vue.createVNode(
				vue.resolveComponent("ElSelect"),
				vue.mergeProps(this.$attrs, {
					modelValue: this.value,
					"onUpdate:modelValue": function onUpdateModelValue(v) {
						return _this.$emit("update:modelValue", v);
					},
				}),
				_objectSpread2(
					{
						default: function _default() {
							return [
								_this.options.map(function (props, index) {
									return vue.createVNode(
										vue.resolveComponent("ElOption"),
										vue.mergeProps(props, {
											key: "" + index + props.value,
										}),
										null
									);
								}),
								(_this$$slots$default = (_this$$slots =
									_this.$slots)["default"]) === null ||
								_this$$slots$default === void 0
									? void 0
									: _this$$slots$default.call(_this$$slots),
							];
						},
					},
					getSlot(this.$slots, ["default"])
				)
			);
		},
	});

	var NAME$4 = "fcTree";
	var Tree = vue.defineComponent({
		name: NAME$4,
		formCreateParser: {
			mergeProp: function mergeProp(ctx) {
				var props = ctx.prop.props;
				if (!props.nodeKey) props.nodeKey = "id";
				if (!props.props)
					props.props = {
						label: "title",
					};
			},
		},
		props: {
			type: String,
			modelValue: {
				type: [Array, String, Number],
				default: function _default() {
					return [];
				},
			},
		},
		emits: ["update:modelValue"],
		watch: {
			modelValue: function modelValue() {
				this.setValue();
			},
		},
		methods: {
			updateValue: function updateValue() {
				if (!this.$refs.tree) return;
				var value;

				if (this.type === "selected") {
					value = this.$refs.tree.getCurrentKey();
				} else {
					value = this.$refs.tree.getCheckedKeys();
				}

				this.$emit("update:modelValue", value);
			},
			setValue: function setValue() {
				if (!this.$refs.tree) return;
				var type = this.type;

				if (type === "selected") {
					this.$refs.tree.setCurrentKey(this.modelValue);
				} else {
					this.$refs.tree.setCheckedKeys(toArray(this.modelValue));
				}
			},
		},
		render: function render() {
			return vue.createVNode(
				vue.resolveComponent("ElTree"),
				vue.mergeProps(this.$attrs, {
					ref: "tree",
					"onCheck-change": this.updateValue,
					"onNode-click": this.updateValue,
				}),
				this.$slots
			);
		},
		mounted: function mounted() {
			this.setValue();
		},
	});

	var css_248z$1 =
		"._fc-upload .fc-files img {\n    width: 100%;\n    height: 100%;\n    display: inline-block;\n    vertical-align: top;\n}\n\n._fc-upload .fc-upload-btn {\n    border: 1px dashed #c0ccda;\n    cursor: pointer;\n}\n\n._fc-upload .fc-upload-cover {\n    opacity: 0;\n    position: absolute;\n    top: 0;\n    bottom: 0;\n    left: 0;\n    right: 0;\n    background: rgba(0, 0, 0, .6);\n    -webkit-transition: opacity .3s;\n    -o-transition: opacity .3s;\n    transition: opacity .3s;\n}\n\n._fc-upload .fc-upload-cover i {\n    color: #fff;\n    font-size: 20px;\n    cursor: pointer;\n    margin: 0 2px;\n}\n\n._fc-upload .fc-files:hover .fc-upload-cover {\n    opacity: 1;\n}\n\n._fc-upload .el-upload {\n    display: block;\n}\n\n._fc-upload .fc-upload-btn, ._fc-upload .fc-files {\n    display: inline-block;\n    width: 58px;\n    height: 58px;\n    text-align: center;\n    line-height: 58px;\n    border: 1px solid #c0ccda;\n    border-radius: 4px;\n    overflow: hidden;\n    background: #fff;\n    position: relative;\n    -webkit-box-shadow: 2px 2px 5px rgba(0, 0, 0, .1);\n    box-shadow: 2px 2px 5px rgba(0, 0, 0, .1);\n    margin-right: 4px;\n    -webkit-box-sizing: border-box;\n    box-sizing: border-box;\n}\n";
	styleInject(css_248z$1);

	function parseFile(file, i) {
		return {
			url: file,
			name: getFileName(file),
			uid: i,
		};
	}

	function getFileName(file) {
		return ("" + file).split("/").pop();
	}

	var NAME$5 = "fcUpload";
	var Upload = vue.defineComponent({
		name: NAME$5,
		inheritAttrs: false,
		props: {
			onHandle: Function,
			uploadType: {
				type: String,
				default: "file",
			},
			limit: {
				type: Number,
				default: 0,
			},
			allowRemove: {
				type: Boolean,
				default: true,
			},
			previewMask: undefined,
			modalTitle: String,
			handleIcon: [String, Boolean],
			modelValue: [Array, String],
		},
		emits: ["update:modelValue"],
		data: function data() {
			return {
				uploadList: [],
				previewVisible: false,
				previewImage: "",
				fileList: [],
			};
		},
		created: function created() {
			this.fileList = toArray(this.modelValue).map(parseFile);
		},
		watch: {
			modelValue: function modelValue(n) {
				if (
					this.$refs.upload.uploadFiles.every(function (file) {
						return !file.status || file.status === "success";
					})
				) {
					this.$refs.upload.uploadFiles = toArray(n).map(parseFile);
					this.uploadList = this.$refs.upload.uploadFiles;
				}

				this.fileList = toArray(n).map(parseFile);
			},
			limit: function limit(n, o) {
				if (o === 1 || n === 1) {
					this.update();
				}
			},
		},
		methods: {
			key: function key(unique) {
				return unique;
			},
			isDisabled: function isDisabled() {
				return this.$attrs.disabled === true;
			},
			onRemove: function onRemove(file) {
				if (this.isDisabled()) {
					return;
				}

				this.$refs.upload.handleRemove(file);
			},
			handleClick: function handleClick(file) {
				var _this = this;

				if (this.isDisabled()) {
					return;
				}

				window.vm2 = this;
				(
					this.onHandle ||
					function (file) {
						_this.previewImage = file.url;
						_this.previewVisible = true;
					}
				)(file);
			},
			makeItem: function makeItem(file, index) {
				return this.uploadType === "image"
					? vue.createVNode(
							"img",
							{
								src: file.url,
								key: this.key("img" + index),
							},
							null
					  )
					: vue.createVNode(
							"i",
							{
								class: "el-icon-document",
								key: this.key("i" + index),
							},
							null
					  );
			},
			makeRemoveIcon: function makeRemoveIcon(file, index) {
				var _this2 = this;

				return vue.createVNode(
					"i",
					{
						class: "el-icon-delete",
						onClick: function onClick() {
							return _this2.onRemove(file);
						},
						key: this.key("ri" + index),
					},
					null
				);
			},
			makeHandleIcon: function makeHandleIcon(file, index) {
				var _this3 = this;

				return vue.createVNode(
					"i",
					{
						class:
							this.handleIcon === true ||
							this.handleIcon === undefined
								? "el-icon-view"
								: this.handleIcon,
						onClick: function onClick() {
							return _this3.handleClick(file);
						},
						key: this.key("hi" + index),
					},
					null
				);
			},
			makeProgress: function makeProgress(file, index) {
				return vue.createVNode(
					vue.resolveComponent("ElProgress"),
					vue.mergeProps(
						{
							percentage: file.percentage,
							type: "circle",
							width: 52,
						},
						{
							style: "margin-top:2px;",
							key: this.key("pg" + index),
						}
					),
					null
				);
			},
			makeIcons: function makeIcons(file, index) {
				var icons = [];

				if (this.allowRemove || this.handleIcon !== false) {
					if (
						(this.uploadType !== "file" &&
							this.handleIcon !== false) ||
						(this.uploadType === "file" && this.handleIcon)
					) {
						icons.push(this.makeHandleIcon(file, index));
					}

					if (this.allowRemove) {
						icons.push(this.makeRemoveIcon(file, index));
					}

					return vue.createVNode(
						"div",
						{
							class: "fc-upload-cover",
						},
						[icons]
					);
				}
			},
			makeFiles: function makeFiles() {
				var _this4 = this;

				return this.uploadList.map(function (file, index) {
					return vue.createVNode(
						"div",
						{
							key: _this4.key(index),
							class: "fc-files",
						},
						[
							file.percentage !== undefined &&
							file.status !== "success"
								? _this4.makeProgress(file, index)
								: [
										_this4.makeItem(file, index),
										_this4.makeIcons(file, index),
								  ],
						]
					);
				});
			},
			makeUpload: function makeUpload() {
				var _this$$slots$default,
					_this$$slots,
					_this5 = this;

				var isShow = !this.limit || this.limit > this.uploadList.length;
				return vue.createVNode(
					vue.resolveComponent("ElUpload"),
					vue.mergeProps(this.$attrs, {
						showFileList: false,
						fileList: this.fileList,
						ref: "upload",
						style: {
							display: "inline-block",
						},
						key: this.key("upload"),
					}),
					_objectSpread2(
						{
							default: function _default() {
								return [
									isShow
										? ((_this$$slots$default =
												(_this$$slots = _this5.$slots)[
													"default"
												]) === null ||
										  _this$$slots$default === void 0
												? void 0
												: _this$$slots$default.call(
														_this$$slots
												  )) ||
										  vue.createVNode(
												"div",
												{
													class: "fc-upload-btn",
												},
												[
													vue.createVNode(
														"i",
														{
															class: "el-icon-upload2",
														},
														null
													),
												]
										  )
										: undefined,
								];
							},
						},
						getSlot(this.$slots, ["default"])
					)
				);
			},
			update: function update() {
				var files = this.$refs.upload.uploadFiles
					.map(function (file) {
						return file.url;
					})
					.filter(function (url) {
						return url !== undefined;
					});
				this.$emit(
					"update:modelValue",
					this.limit === 1 ? files[0] || "" : files
				);
			},
			handleCancel: function handleCancel() {
				this.previewVisible = false;
			},
		},
		render: function render() {
			var _this6 = this;

			return vue.createVNode(
				"div",
				{
					class: "_fc-upload",
				},
				[
					[
						this.$attrs.showFileList ? [] : this.makeFiles(),
						this.makeUpload(),
					],
					vue.createVNode(
						vue.resolveComponent("ElDialog"),
						{
							appendToBody: true,
							modal: this.previewMask,
							title: this.modalTitle,
							modelValue: this.previewVisible,
							onClose: this.handleCancel,
						},
						{
							default: function _default() {
								return [
									vue.createVNode(
										"img",
										{
											alt: "example",
											style: "width: 100%",
											src: _this6.previewImage,
										},
										null
									),
								];
							},
						}
					),
				]
			);
		},
		mounted: function mounted() {
			var _this7 = this;

			this.uploadList = this.$refs.upload.uploadFiles;
			this.$watch(
				function () {
					return _this7.$refs.upload.uploadFiles;
				},
				function () {
					_this7.update();
				},
				{
					deep: true,
				}
			);
		},
	});

	var is = {
		type: function type(arg, _type) {
			return (
				Object.prototype.toString.call(arg) === "[object " + _type + "]"
			);
		},
		Undef: function Undef(v) {
			return v === undefined || v === null;
		},
		Element: function Element(arg) {
			return (
				_typeof(arg) === "object" &&
				arg !== null &&
				arg.nodeType === 1 &&
				!is.Object(arg)
			);
		},
		trueArray: function trueArray(data) {
			return Array.isArray(data) && data.length > 0;
		},
	};

	[
		"Date",
		"Object",
		"Function",
		"String",
		"Boolean",
		"Array",
		"Number",
	].forEach(function (t) {
		is[t] = function (arg) {
			return is.type(arg, t);
		};
	});

	function hasProperty(rule, k) {
		return {}.hasOwnProperty.call(rule, k);
	}

	function _isSlot$1(s) {
		return (
			typeof s === "function" ||
			(Object.prototype.toString.call(s) === "[object Object]" &&
				!vue.isVNode(s))
		);
	}

	var NAME$6 = "fcGroup";
	var Group = vue.defineComponent({
		name: NAME$6,
		props: {
			field: String,
			rule: Array,
			expand: Number,
			options: Object,
			button: {
				type: Boolean,
				default: true,
			},
			max: {
				type: Number,
				default: 0,
			},
			min: {
				type: Number,
				default: 0,
			},
			modelValue: {
				type: Array,
				default: function _default() {
					return [];
				},
			},
			disabled: {
				type: Boolean,
				default: false,
			},
			syncDisabled: {
				type: Boolean,
				default: true,
			},
			fontSize: {
				type: Number,
				default: 28,
			},
			onBeforeRemove: {
				type: Function,
				default: function _default() {},
			},
			onBeforeAdd: {
				type: Function,
				default: function _default() {},
			},
		},
		inject: ["formCreateInject"],
		data: function data() {
			return {
				len: 0,
				cacheRule: {},
				cacheValue: {},
			};
		},
		emits: ["update:modelValue", "change", "itemMounted"],
		watch: {
			disabled: function disabled(n) {
				if (this.syncDisabled) {
					var lst = this.cacheRule;
					Object.keys(lst).forEach(function (k) {
						lst[k].$f.disabled(n);
					});
				}
			},
			expand: function expand(n) {
				var d = n - this.modelValue.length;

				if (d > 0) {
					this.expandRule(d);
				}
			},
			modelValue: {
				handler: function handler(n) {
					var _this = this;

					n = n || [];
					var keys = Object.keys(this.cacheRule),
						total = keys.length,
						len = total - n.length;

					if (len < 0) {
						for (var i = len; i < 0; i++) {
							this.addRule(n.length + i);
						}

						for (var _i = 0; _i < total; _i++) {
							this.setValue(keys[_i], n[_i]);
						}
					} else {
						if (len > 0) {
							for (var _i2 = 0; _i2 < len; _i2++) {
								this.removeRule(keys[total - _i2 - 1]);
							}

							this.subForm();
						}

						n.forEach(function (val, i) {
							_this.setValue(keys[i], n[i]);
						});
					}
				},
				deep: true,
			},
		},
		methods: {
			_value: function _value(v) {
				return v && hasProperty(v, this.field) ? v[this.field] : v;
			},
			cache: function cache(k, val) {
				this.cacheValue[k] = JSON.stringify(val);
			},
			input: function input(value) {
				this.$emit("update:modelValue", value);
				this.$emit("change", value);
			},
			formData: function formData(key, _formData) {
				var _this2 = this;

				var cacheRule = this.cacheRule;
				var keys = Object.keys(cacheRule);

				if (
					keys.filter(function (k) {
						return cacheRule[k].$f;
					}).length !== keys.length
				) {
					return;
				}

				var value = keys.map(function (k) {
					var data =
						key === k
							? _formData
							: _objectSpread2({}, _this2.cacheRule[k].$f.form);
					var value = _this2.field
						? data[_this2.field] || null
						: data;

					_this2.cache(k, value);

					return value;
				});
				this.input(value);
			},
			setValue: function setValue(key, value) {
				var field = this.field;

				if (field) {
					value = _defineProperty({}, field, this._value(value));
				}

				if (
					this.cacheValue[key] ===
					JSON.stringify(field ? value[field] : value)
				) {
					return;
				}

				this.cache(key, value);
			},
			addRule: function addRule(i, emit) {
				var _this3 = this;

				var rule = this.formCreateInject.form.copyRules(
					this.rule || []
				);
				var options = this.options
					? _objectSpread2({}, this.options)
					: {
							submitBtn: false,
							resetBtn: false,
					  };
				this.cacheRule[++this.len] = {
					rule: rule,
					options: options,
				};

				if (emit) {
					this.$nextTick(function () {
						return _this3.$emit(
							"add",
							rule,
							Object.keys(_this3.cacheRule).length - 1
						);
					});
				}
			},
			add$f: function add$f(i, key, $f) {
				var _this4 = this;

				this.cacheRule[key].$f = $f;
				this.subForm();
				this.$nextTick(function () {
					if (_this4.syncDisabled) {
						$f.disabled(_this4.disabled);
					}

					_this4.$emit(
						"itemMounted",
						$f,
						Object.keys(_this4.cacheRule).indexOf(key)
					);
				});
			},
			subForm: function subForm() {
				var _this5 = this;

				this.formCreateInject.subForm(
					Object.keys(this.cacheRule).map(function (k) {
						return _this5.cacheRule[k].$f;
					})
				);
			},
			removeRule: function removeRule(key, emit) {
				var _this6 = this;

				var index = Object.keys(this.cacheRule).indexOf(key);
				delete this.cacheRule[key];
				delete this.cacheValue[key];

				if (emit) {
					this.$nextTick(function () {
						return _this6.$emit("remove", index);
					});
				}
			},
			add: function add(i) {
				if (
					this.disabled ||
					false === this.onBeforeAdd(this.modelValue)
				) {
					return;
				}

				this.addRule(i, true);
			},
			del: function del(index, key) {
				if (
					this.disabled ||
					false === this.onBeforeRemove(this.modelValue)
				) {
					return;
				}

				this.removeRule(key, true);
				this.subForm();
				this.modelValue.splice(index, 1);
				this.input(this.modelValue);
			},
			addIcon: function addIcon(key) {
				return vue.createVNode(
					"i",
					{
						key: "a".concat(key),
						class: "el-icon-circle-plus-outline",
						style: "font-size:"
							.concat(this.fontSize, "px;cursor:")
							.concat(
								this.disabled
									? "not-allowed;color:#c9cdd4"
									: "pointer",
								";"
							),
						onClick: this.add,
					},
					null
				);
			},
			delIcon: function delIcon(index, key) {
				var _this7 = this;

				return vue.createVNode(
					"i",
					{
						key: "d".concat(key),
						class: "el-icon-remove-outline",
						style: "font-size:"
							.concat(this.fontSize, "px;cursor:")
							.concat(
								this.disabled
									? "not-allowed;color:#c9cdd4"
									: "pointer;color:#606266",
								";"
							),
						onClick: function onClick() {
							return _this7.del(index, key);
						},
					},
					null
				);
			},
			makeIcon: function makeIcon(total, index, key) {
				var _this8 = this;

				if (this.$slots.button) {
					return this.$slots.button({
						total: total,
						index: index,
						vm: this,
						key: key,
						del: function del() {
							return _this8.del(index, key);
						},
						add: this.add,
					});
				}

				if (index === 0) {
					return [
						this.max !== 0 && total >= this.max
							? null
							: this.addIcon(key),
						this.min === 0 || total > this.min
							? this.delIcon(index, key)
							: null,
					];
				}

				if (index >= this.min) {
					return this.delIcon(index, key);
				}
			},
			emitEvent: function emitEvent(name, args, index, key) {
				this.$emit.apply(
					this,
					[name].concat(_toConsumableArray(args), [
						this.cacheRule[key].$f,
						index,
					])
				);
			},
			expandRule: function expandRule(n) {
				for (var i = 0; i < n; i++) {
					this.modelValue.push(this.field ? null : {});
				}
			},
		},
		created: function created() {
			this._.appContext.components.FormCreate =
				this.formCreateInject.form.$form();
			var d = (this.expand || 0) - this.modelValue.length;

			if (d > 0) {
				this.expandRule(d);
			}

			for (var i = 0; i < this.modelValue.length; i++) {
				this.addRule(i);
			}
		},
		render: function render() {
			var _this9 = this;

			var keys = Object.keys(this.cacheRule);
			var button = this.button;
			return keys.length === 0
				? this.$slots["default"]
					? this.$slots["default"]({
							vm: this,
							add: this.add,
					  })
					: vue.createVNode(
							"i",
							{
								key: "a_def",
								class: "el-icon-circle-plus-outline",
								style: "font-size:"
									.concat(
										this.fontSize,
										"px;vertical-align:middle;color:"
									)
									.concat(
										this.disabled
											? "#c9cdd4;cursor: not-allowed"
											: "#606266;cursor:pointer",
										";"
									),
								onClick: this.add,
							},
							null
					  )
				: vue.createVNode(
						"div",
						{
							key: "con",
						},
						[
							keys.map(function (key, index) {
								var _slot;

								var _this9$cacheRule$key =
										_this9.cacheRule[key],
									rule = _this9$cacheRule$key.rule,
									options = _this9$cacheRule$key.options;
								return vue.createVNode(
									vue.resolveComponent("ElRow"),
									{
										align: "middle",
										type: "flex",
										key: key,
										style: "border-bottom:1px dashed #DCDFE6;margin-bottom:10px;",
									},
									{
										default: function _default() {
											return [
												vue.createVNode(
													vue.resolveComponent(
														"ElCol"
													),
													{
														span: button ? 20 : 24,
													},
													{
														default:
															function _default() {
																return [
																	vue.createVNode(
																		vue.resolveComponent(
																			"ElFormItem"
																		),
																		null,
																		{
																			default:
																				function _default() {
																					return [
																						vue.createVNode(
																							vue.resolveComponent(
																								"FormCreate"
																							),
																							{
																								key: key,
																								"onUpdate:modelValue":
																									function onUpdateModelValue(
																										formData
																									) {
																										return _this9.formData(
																											key,
																											formData
																										);
																									},
																								modelValue:
																									_this9.field
																										? _defineProperty(
																												{},
																												_this9.field,
																												_this9._value(
																													_this9
																														.modelValue[
																														index
																													]
																												)
																										  )
																										: _this9
																												.modelValue[
																												index
																										  ],
																								"onEmit-event":
																									function onEmitEvent(
																										name
																									) {
																										for (
																											var _len =
																													arguments.length,
																												args =
																													new Array(
																														_len >
																														1
																															? _len -
																															  1
																															: 0
																													),
																												_key = 1;
																											_key <
																											_len;
																											_key++
																										) {
																											args[
																												_key -
																													1
																											] =
																												arguments[
																													_key
																												];
																										}

																										return _this9.emitEvent(
																											name,
																											args,
																											index,
																											key
																										);
																									},
																								"onUpdate:api":
																									function onUpdateApi(
																										$f
																									) {
																										return _this9.add$f(
																											index,
																											key,
																											$f
																										);
																									},
																								rule: rule,
																								option: options || {
																									submitBtn: false,
																									resetBtn: false,
																								},
																								extendOption: true,
																							},
																							null
																						),
																					];
																				},
																		}
																	),
																];
															},
													}
												),
												button
													? vue.createVNode(
															vue.resolveComponent(
																"ElCol"
															),
															{
																span: 2,
																pull: 1,
																push: 1,
															},
															_isSlot$1(
																(_slot =
																	_this9.makeIcon(
																		keys.length,
																		index,
																		key
																	))
															)
																? _slot
																: {
																		default:
																			function _default() {
																				return [
																					_slot,
																				];
																			},
																  }
													  )
													: null,
											];
										},
									}
								);
							}),
						]
				  );
		},
	});

	var NAME$7 = "fcSubForm";
	var Sub = vue.defineComponent({
		name: NAME$7,
		props: {
			rule: Array,
			options: Object,
			modelValue: {
				type: Object,
				default: function _default() {
					return {};
				},
			},
			disabled: {
				type: Boolean,
				default: false,
			},
			syncDisabled: {
				type: Boolean,
				default: true,
			},
		},
		inject: ["formCreateInject"],
		data: function data() {
			return {
				cacheValue: {},
				subApi: {},
			};
		},
		emits: ["fc:subform", "update:modelValue", "change", "itemMounted"],
		watch: {
			disabled: function disabled(n) {
				this.syncDisabled && this.subApi.disabled(n);
			},
			modelValue: function modelValue(n) {
				this.setValue(n);
			},
		},
		methods: {
			formData: function formData(value) {
				this.cacheValue = JSON.stringify(value);
				this.$emit("update:modelValue", value);
				this.$emit("change", value);
			},
			setValue: function setValue(value) {
				var str = JSON.stringify(value);

				if (this.cacheValue === str) {
					return;
				}

				this.cacheValue = str;
				this.subApi.coverValue(value || {});
			},
			add$f: function add$f(api) {
				var _this = this;

				this.subApi = api;
				this.formCreateInject.subForm(api);
				this.$nextTick(function () {
					_this.syncDisabled && api.disabled(_this.disabled);

					_this.$emit("itemMounted", api);
				});
			},
		},
		created: function created() {
			this._.appContext.components.FormCreate =
				this.formCreateInject.form.$form();
		},
		render: function render() {
			return vue.createVNode(
				vue.resolveComponent("FormCreate"),
				{
					"onUpdate:modelValue": this.formData,
					modelValue: this.modelValue,
					"onEmit-event": this.$emit,
					"onUpdate:api": this.add$f,
					rule: this.rule,
					option: this.options || {
						submitBtn: false,
						resetBtn: false,
					},
					extendOption: true,
				},
				null
			);
		},
	});

	var components = [Checkbox, Frame, Radio, Select, Tree, Upload, Group, Sub];

	var NAME$8 = "FormCreate";
	function $FormCreate(FormCreate) {
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
			emits: [
				"update:api",
				"update:modelValue",
				"mounted",
				"submit",
				"change",
				"emit-event",
				"control",
				"remove-rule",
				"remove-field",
				"sync",
			],
			render: function render() {
				return this.fc.render();
			},
			setup: function setup(props) {
				var vm = vue.getCurrentInstance(); //获取当前组件的实例
				vue.provide("pfc", vm.ctx);
				var parent = vue.inject("pfc");
				console.log(vm, parent);
				const { rule, modelValue } = vue.toRefs(props);
				var data = vue.reactive({
					destroyed: false,
					isShow: true,
					unique: 1,
					renderRule: _toConsumableArray(rule.value || []),
					updateValue: JSON.stringify(modelValue),
				});

				var fc = new FormCreate(vm);
				console.log(fc, 22);
				var fapi = fc.api();
				console.log(fc.api, 1111);
				vue.onMounted(function () {
					fc.mounted();
					vm.ctx.$el.__fc__ = fc;
				});
				vue.onBeforeUnmount(function () {
					data.destroyed = true;
					fc.unmount();
				});
				vue.onUpdated(function () {
					fc.updated();
				});
				vue.watch(
					function () {
						return _toConsumableArray(rule.value);
					},
					function (n) {
						if (
							fc.$handle.isBreakWatch() ||
							(n.length === data.renderRule.length &&
								n.every(function (v) {
									return data.renderRule.indexOf(v) > -1;
								}))
						)
							return;
						fc.$handle.reloadRule(rule.value);
						vm.ctx.renderRule();
					}
				);
				vue.watch(props.option, function (n) {
					fc.initOptions(n);
					fapi.refresh();
				});
				vue.watch(
					modelValue,
					function (n) {
						if (JSON.stringify(n) === data.updateValue) return;
						fapi.setValue(n);
					},
					{
						deep: true,
					}
				);
				return _objectSpread2(
					_objectSpread2(
						{
							fc: vue.markRaw(fc),
							parent: parent ? vue.markRaw(parent) : parent,
							fapi: vue.markRaw(fapi),
						},
						vue.toRefs(data)
					),
					{},
					{
						refresh: function refresh() {
							++data.unique;
						},
						renderRule: function renderRule() {
							data.renderRule = _toConsumableArray(
								rule.value || []
							);
						},
						updateValue: function updateValue(value) {
							if (data.destroyed) return;
							data.updateValue = JSON.stringify(value);
							this.$emit("update:modelValue", value);
						},
					}
				);
			},
			beforeCreate: function beforeCreate() {
				this.fc.init();
				this.$emit("update:api", this.fapi);
			},
		});
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

	var normalMerge = ["props"];
	var toArrayMerge = ["class", "style", "directives"];
	var functionalMerge = ["on"];

	var mergeProps = function mergeProps(objects) {
		var initial =
			arguments.length > 1 && arguments[1] !== undefined
				? arguments[1]
				: {};
		var opt =
			arguments.length > 2 && arguments[2] !== undefined
				? arguments[2]
				: {};

		var _normalMerge = [].concat(
			normalMerge,
			_toConsumableArray(opt["normal"] || [])
		);

		var _toArrayMerge = [].concat(
			toArrayMerge,
			_toConsumableArray(opt["array"] || [])
		);

		var _functionalMerge = [].concat(
			functionalMerge,
			_toConsumableArray(opt["functional"] || [])
		);

		var propsMerge = opt["props"] || [];
		return objects.reduce(function (a, b) {
			for (var key in b) {
				if (a[key]) {
					if (propsMerge.indexOf(key) > -1) {
						a[key] = mergeProps([b[key]], a[key]);
					} else if (_normalMerge.indexOf(key) > -1) {
						a[key] = _objectSpread2(
							_objectSpread2({}, a[key]),
							b[key]
						);
					} else if (_toArrayMerge.indexOf(key) > -1) {
						var arrA = a[key] instanceof Array ? a[key] : [a[key]];
						var arrB = b[key] instanceof Array ? b[key] : [b[key]];
						a[key] = [].concat(
							_toConsumableArray(arrA),
							_toConsumableArray(arrB)
						);
					} else if (_functionalMerge.indexOf(key) > -1) {
						for (var event in b[key]) {
							if (a[key][event]) {
								var _arrA =
									a[key][event] instanceof Array
										? a[key][event]
										: [a[key][event]];

								var _arrB =
									b[key][event] instanceof Array
										? b[key][event]
										: [b[key][event]];

								a[key][event] = [].concat(
									_toConsumableArray(_arrA),
									_toConsumableArray(_arrB)
								);
							} else {
								a[key][event] = b[key][event];
							}
						}
					} else if (key === "hook") {
						for (var hook in b[key]) {
							if (a[key][hook]) {
								a[key][hook] = mergeFn(
									a[key][hook],
									b[key][hook]
								);
							} else {
								a[key][hook] = b[key][hook];
							}
						}
					} else {
						a[key] = b[key];
					}
				} else {
					if (
						_normalMerge.indexOf(key) > -1 ||
						_functionalMerge.indexOf(key) > -1 ||
						propsMerge.indexOf(key) > -1
					) {
						a[key] = _objectSpread2({}, b[key]);
					} else if (_toArrayMerge.indexOf(key) > -1) {
						a[key] =
							b[key] instanceof Array
								? _toConsumableArray(b[key])
								: _typeof(b[key]) === "object"
								? _objectSpread2({}, b[key])
								: b[key];
					} else a[key] = b[key];
				}
			}

			return a;
		}, initial);
	};

	var mergeFn = function mergeFn(fn1, fn2) {
		return function () {
			fn1 && fn1.apply(this, arguments);
			fn2 && fn2.apply(this, arguments);
		};
	};

	var keyAttrs = [
		"type",
		"slot",
		"emitPrefix",
		"value",
		"name",
		"native",
		"hidden",
		"display",
		"inject",
		"options",
		"emit",
		"link",
		"prefix",
		"suffix",
		"update",
		"sync",
		"optionsTo",
		"key",
	];
	var arrayAttrs = ["validate", "children", "control"];
	var normalAttrs = ["effect"];
	function attrs() {
		return [].concat(
			keyAttrs,
			_toConsumableArray(normalMerge),
			_toConsumableArray(toArrayMerge),
			_toConsumableArray(functionalMerge),
			arrayAttrs,
			normalAttrs
		);
	}

	function deepExtend(origin) {
		var target =
			arguments.length > 1 && arguments[1] !== undefined
				? arguments[1]
				: {};
		var mode = arguments.length > 2 ? arguments[2] : undefined;
		var isArr = false;

		for (var key in target) {
			if (Object.prototype.hasOwnProperty.call(target, key)) {
				var clone = target[key];

				if ((isArr = Array.isArray(clone)) || is.Object(clone)) {
					var nst = origin[key] === undefined;

					if (isArr) {
						isArr = false;
						nst && $set(origin, key, []);
					} else if (clone._clone && mode !== undefined) {
						if (mode) {
							clone = clone.getRule();
							nst && $set(origin, key, {});
						} else {
							$set(origin, key, clone._clone());
							continue;
						}
					} else {
						nst && $set(origin, key, {});
					}

					origin[key] = deepExtend(origin[key], clone, mode);
				} else if (is.Undef(clone)) {
					$set(origin, key, clone);
				} else if (clone.__json !== undefined) {
					$set(origin, key, clone.__json);
				} else if (clone.__origin !== undefined) {
					$set(origin, key, clone.__origin);
				} else {
					$set(origin, key, clone);
				}
			}
		}

		return mode !== undefined && Array.isArray(origin)
			? origin.filter(function (v) {
					return !v || !v.__ctrl;
			  })
			: origin;
	}
	function deepCopy(value) {
		return deepExtend(
			{},
			{
				value: value,
			}
		).value;
	}

	function format(type, msg, rule) {
		return (
			"[form-create ".concat(type, "]: ").concat(msg) +
			(rule
				? "\n\nrule: " +
				  JSON.stringify(rule.getRule ? rule.getRule() : rule)
				: "")
		);
	}
	function err(msg, rule) {
		console.error(format("err", msg, rule));
	}
	function logError(e) {
		err(e.toString());
		console.error(e);
	}

	var PREFIX = "[[FORM-CREATE-PREFIX-";
	var SUFFIX = "-FORM-CREATE-SUFFIX]]";
	var $T = "$FN:";
	var FUNCTION = "function";
	function toJson(obj, space) {
		return JSON.stringify(
			deepExtend([], obj, true),
			function (key, val) {
				if (val && val._isVue === true) return undefined;

				if (_typeof(val) !== FUNCTION) {
					return val;
				}

				if (hasProperty(val, "__json")) {
					return val.__json;
				}

				if (val.__origin) val = val.__origin;
				if (val.__emit) return undefined;
				return PREFIX + val + SUFFIX;
			},
			space
		);
	}

	function makeFn(fn) {
		return eval("(" + FUNCTION + "(){return " + fn + " })()");
	}

	function parseFn(fn, mode) {
		if (fn && is.String(fn)) {
			var v = fn.trim();
			var flag = false;

			try {
				if (v.indexOf(SUFFIX) > 0 && v.indexOf(PREFIX) === 0) {
					v = v.replace(SUFFIX, "").replace(PREFIX, "");
					flag = true;
				} else if (v.indexOf($T) === 0) {
					v = v.replace($T, "");
					flag = true;
				} else if (v.indexOf("$FNX:") === 0) {
					v = makeFn(
						"function($inject){" + v.replace("$FNX:", "") + "}"
					);
					v.__json = fn;
					v.__inject = true;
					return v;
				} else if (
					!mode &&
					v.indexOf(FUNCTION) === 0 &&
					v !== FUNCTION
				) {
					flag = true;
				}

				if (!flag) return fn;
				var val = makeFn(
					v.indexOf(FUNCTION) === -1 && v.indexOf("(") !== 0
						? FUNCTION + " " + v
						: v
				);
				val.__json = fn;
				return val;
			} catch (e) {
				err("\u89E3\u6790\u5931\u8D25:".concat(v));
				return undefined;
			}
		}

		return fn;
	}
	function parseJson(json, mode) {
		return JSON.parse(json, function (k, v) {
			if (is.Undef(v) || !v.indexOf) return v;
			return parseFn(v, mode);
		});
	}

	function toLine(name) {
		var line = name.replace(/([A-Z])/g, "-$1").toLocaleLowerCase();
		if (line.indexOf("-") === 0) line = line.substr(1);
		return line;
	}
	function upper(str) {
		return str.replace(str[0], str[0].toLocaleUpperCase());
	}

	function enumerable(value, writable) {
		return {
			value: value,
			enumerable: false,
			configurable: false,
			writable: !!writable,
		};
	} //todo 优化位置

	function copyRule(rule, mode) {
		return copyRules([rule], mode || false)[0];
	}
	function copyRules(rules, mode) {
		return deepExtend([], _toConsumableArray(rules), mode || false);
	}
	function mergeRule(rule, merge) {
		mergeProps(Array.isArray(merge) ? merge : [merge], rule, {
			array: arrayAttrs,
			normal: normalAttrs,
		});
		return rule;
	}
	function getRule(rule) {
		return is.Function(rule.getRule) ? rule.getRule() : rule;
	}
	function mergeGlobal(target, merge) {
		if (!target) return merge;
		Object.keys(merge || {}).forEach(function (k) {
			if (merge[k]) {
				target[k] = mergeRule(target[k] || {}, merge[k]);
			}
		});
		return target;
	}
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
	function byCtx(rule) {
		return rule.__fc__ || (rule.__origin__ ? rule.__origin__.__fc__ : null);
	}
	function invoke(fn, def) {
		try {
			def = fn();
		} catch (e) {
			logError(e);
		}

		return def;
	}
	function makeSlotBag() {
		var slotBag = {};

		var slotName = function slotName(n) {
			return n || "default";
		};

		return {
			setSlot: function setSlot(slot, vnFn) {
				slot = slotName(slot);
				if (!vnFn || (Array.isArray(vnFn) && vnFn.length)) return;
				if (!slotBag[slot]) slotBag[slot] = [];
				slotBag[slot].push(vnFn);
			},
			getSlot: function getSlot(slot) {
				slot = slotName(slot);
				var children = [];
				(slotBag[slot] || []).forEach(function (fn) {
					if (Array.isArray(fn)) {
						children.push.apply(children, _toConsumableArray(fn));
					} else if (is.Function(fn)) {
						var res = fn();

						if (Array.isArray(res)) {
							children.push.apply(
								children,
								_toConsumableArray(res)
							);
						} else {
							children.push(res);
						}
					} else if (!is.Undef(fn)) {
						children.push(fn);
					}
				});
				return children;
			},
			getSlots: function getSlots() {
				var _this = this;

				var slots = {};
				Object.keys(slotBag).forEach(function (k) {
					slots[k] = function () {
						return _this.getSlot(k);
					};
				});
				return slots;
			},
			slotLen: function slotLen(slot) {
				slot = slotName(slot);
				return slotBag[slot] ? slotBag[slot].length : 0;
			},
			mergeBag: function mergeBag(bag) {
				var _this2 = this;

				if (!bag) return this;
				var slots = is.Function(bag.getSlots) ? bag.getSlots() : bag;

				if (Array.isArray(bag) || vue.isVNode(bag)) {
					this.setSlot(undefined, function () {
						return bag;
					});
				} else {
					Object.keys(slots).forEach(function (k) {
						_this2.setSlot(k, slots[k]);
					});
				}

				return this;
			},
		};
	}
	function toProps(rule) {
		var prop = _objectSpread2({}, rule.props || {});

		Object.keys(rule.on || {}).forEach(function (k) {
			var name = "on".concat(upper(k));

			if (Array.isArray(prop[name])) {
				prop[name] = [].concat(_toConsumableArray(prop[name]), [
					rule.on[k],
				]);
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

	function toString(val) {
		return val == null
			? ""
			: _typeof(val) === "object"
			? JSON.stringify(val, null, 2)
			: String(val);
	}

	var id = 0;
	function uniqueId() {
		return (
			Math.random().toString(36).substr(3, 3) +
			Number("".concat(Date.now()).concat(++id)).toString(36)
		);
	}

	function deepSet(data, idx, val) {
		var _data = data,
			to;
		(idx || "").split(".").forEach(function (v) {
			if (to) {
				if (!_data[to]) {
					_data[to] = {};
				}

				_data = _data[to];
			}

			to = v;
		});
		_data[to] = val;
		return _data;
	}

	function baseRule() {
		return {
			props: {},
			on: {},
			options: [],
			children: [],
			hidden: false,
			display: true,
			value: undefined,
		};
	}
	function creatorFactory(name, init) {
		return function (title, field, value) {
			var props =
				arguments.length > 3 && arguments[3] !== undefined
					? arguments[3]
					: {};
			var maker = new Creator(name, title, field, value, props);

			if (init) {
				if (is.Function(init)) init(maker);
				else maker.props(init);
			}

			return maker;
		};
	}
	function Creator(type, title, field, value, props) {
		this._data = extend(baseRule(), {
			type: type,
			title: title,
			field: field,
			value: value,
			props: props || {},
		});
		this.event = this.on;
	}
	extend(Creator.prototype, {
		getRule: function getRule() {
			return this._data;
		},
		setProp: function setProp(key, value) {
			$set(this._data, key, value);
			return this;
		},
		modelField: function modelField(field) {
			this._data.modelField = field;
			return this;
		},
		_clone: function _clone() {
			var clone = new this.constructor();
			clone._data = copyRule(this._data);
			return clone;
		},
	});
	function appendProto(attrs) {
		attrs.forEach(function (name) {
			Creator.prototype[name] = function (key) {
				mergeRule(
					this._data,
					_defineProperty(
						{},
						name,
						arguments.length < 2
							? key
							: _defineProperty({}, key, arguments[1])
					)
				);
				return this;
			};
		});
	}
	appendProto(attrs());

	var commonMaker = creatorFactory("");
	function create(type, field, title) {
		var make = commonMaker("", field);
		make._data.type = type;
		make._data.title = title;
		return make;
	}
	function makerFactory() {
		return {
			create: create,
			factory: creatorFactory,
		};
	}

	function copy(value) {
		return deepCopy(value);
	}

	function byRules(ctxs, origin) {
		return Object.keys(ctxs).reduce(function (initial, key) {
			initial[key] = origin ? ctxs[key].origin : ctxs[key].rule;
			return initial;
		}, {});
	}

	function Api(h) {
		function tidyFields(fields) {
			if (is.Undef(fields)) fields = h.fields();
			else if (!Array.isArray(fields)) fields = [fields];
			return fields;
		}

		function props(fields, key, val) {
			tidyFields(fields).forEach(function (field) {
				var ctx = h.getCtx(field);
				if (!ctx) return;
				$set(ctx.rule, key, val);
				h.$render.clearCache(ctx);
			});
		}

		function allSubForm() {
			var subs = h.subForm;
			return Object.keys(subs).reduce(function (initial, k) {
				var sub = subs[k];
				if (!sub) return initial;
				if (Array.isArray(sub))
					initial.push.apply(initial, _toConsumableArray(sub));
				else initial.push(sub);
				return initial;
			}, []);
		}

		var api = {
			helper: {
				tidyFields: tidyFields,
				props: props,
			},

			get config() {
				return h.options;
			},

			set config(val) {
				h.fc.options.value = val;
			},

			get options() {
				return h.options;
			},

			set options(val) {
				h.fc.options.value = val;
			},

			get form() {
				return h.form;
			},

			get rule() {
				return h.rules;
			},

			get parent() {
				return h.vm.parent && h.vm.parent.fapi;
			},

			get children() {
				return allSubForm();
			},

			formData: function formData(fields) {
				return tidyFields(fields).reduce(function (initial, id) {
					var ctx = h.fieldCtx[id];
					if (!ctx) return initial;
					initial[ctx.field] = copy(ctx.rule.value);
					return initial;
				}, copy(h.appendData));
			},
			getValue: function getValue(field) {
				var ctx = h.fieldCtx[field];
				if (!ctx) return;
				return copy(ctx.rule.value);
			},
			coverValue: function coverValue(formData) {
				h.deferSyncValue(function () {
					Object.keys(h.fieldCtx).forEach(function (key) {
						var ctx = h.fieldCtx[key];
						if (!ctx) return (h.appendData[key] = formData[key]);
						ctx.rule.value = hasProperty(formData, key)
							? formData[key]
							: undefined;
					});
				});
			},
			setValue: function setValue(field) {
				var formData = field;
				if (arguments.length >= 2)
					formData = _defineProperty({}, field, arguments[1]);
				h.deferSyncValue(function () {
					Object.keys(formData).forEach(function (key) {
						var ctx = h.fieldCtx[key];
						if (!ctx) return (h.appendData[key] = formData[key]);
						ctx.rule.value = formData[key];
					});
				});
			},
			removeField: function removeField(field) {
				var ctx = h.getCtx(field);
				if (!ctx) return;
				ctx.rm();
				return ctx.origin;
			},
			removeRule: function removeRule(rule) {
				var ctx = rule && byCtx(rule);
				if (!ctx) return;
				ctx.rm();
				return ctx.origin;
			},
			fields: function fields() {
				return h.fields();
			},
			append: function append(rule, after, child) {
				var fields = Object.keys(h.fieldCtx),
					index = h.sort.length - 1,
					rules;
				if (rule.field && fields.indexOf(rule.field) > -1)
					return err(
						"".concat(
							rule.field,
							" \u5B57\u6BB5\u5DF2\u5B58\u5728"
						),
						rule
					);
				var ctx = h.getCtx(after);

				if (ctx) {
					if (child) {
						rules = ctx.rule.children;
						index = ctx.rule.children.length - 1;
					} else {
						index = ctx.root.indexOf(ctx.origin);
						rules = ctx.root;
					}
				} else rules = h.rules;

				rules.splice(index + 1, 0, rule);
			},
			prepend: function prepend(rule, after, child) {
				var fields = Object.keys(h.fieldCtx),
					index = 0,
					rules;
				if (rule.field && fields.indexOf(rule.field) > -1)
					return err(
						"".concat(
							rule.field,
							" \u5B57\u6BB5\u5DF2\u5B58\u5728"
						),
						rule
					);
				var ctx = h.getCtx(after);

				if (ctx) {
					if (child) {
						rules = ctx.rule.children;
					} else {
						index = ctx.root.indexOf(ctx.origin);
						rules = ctx.root;
					}
				} else rules = h.rules;

				rules.splice(index, 0, rule);
			},
			hidden: function hidden(state, fields) {
				props(fields, "hidden", !!state);
				h.refresh();
			},
			hiddenStatus: function hiddenStatus(id) {
				var ctx = h.getCtx(id);
				if (!ctx) return;
				return !!ctx.rule.hidden;
			},
			display: function display(state, fields) {
				props(fields, "display", !!state);
				h.refresh();
			},
			displayStatus: function displayStatus(id) {
				var ctx = h.getCtx(id);
				if (!ctx) return;
				return !!ctx.rule.display;
			},
			disabled: function disabled(_disabled, fields) {
				tidyFields(fields).forEach(function (field) {
					var ctx = h.fieldCtx[field];
					if (!ctx) return;
					$set(ctx.rule.props, "disabled", !!_disabled);
				});
				h.refresh();
			},
			model: function model(origin) {
				return byRules(h.fieldCtx, origin);
			},
			component: function component(origin) {
				return byRules(h.nameCtx, origin);
			},
			bind: function bind() {
				return api.form;
			},
			reload: function reload(rules) {
				h.reloadRule(rules);
			},
			updateOptions: function updateOptions(options) {
				h.fc.updateOptions(options);
				api.refresh();
			},
			onSubmit: function onSubmit(fn) {
				api.updateOptions({
					onSubmit: fn,
				});
			},
			sync: function sync(field) {
				var ctx = is.Object(field) ? byCtx(field) : h.getCtx(field);

				if (ctx && !ctx.deleted) {
					var subForm = h.subForm[field];

					if (subForm) {
						if (Array.isArray(subForm)) {
							subForm.forEach(function (form) {
								form.refresh();
							});
						} else if (subForm) {
							subForm.refresh();
						}
					} //ctx.updateKey(true);

					h.$render.clearCache(ctx);
					h.refresh();
				}
			},
			refresh: function refresh() {
				allSubForm().forEach(function (sub) {
					sub.refresh();
				});
				h.$render.clearCacheAll();
				h.refresh();
			},
			refreshOptions: function refreshOptions() {
				h.$manager.updateOptions(h.options);
				api.refresh();
			},
			hideForm: function hideForm(hide) {
				h.vm._.setupState.isShow = !hide;
			},
			changeStatus: function changeStatus() {
				return h.changeStatus;
			},
			clearChangeStatus: function clearChangeStatus() {
				h.changeStatus = false;
			},
			updateRule: function updateRule(id, rule) {
				var r = api.getRule(id);
				r && extend(r, rule);
			},
			updateRules: function updateRules(rules) {
				Object.keys(rules).forEach(function (id) {
					api.updateRule(id, rules[id]);
				});
			},
			mergeRule: function mergeRule$1(id, rule) {
				var ctx = h.getCtx(id);
				ctx && mergeRule(ctx.rule, rule);
			},
			mergeRules: function mergeRules(rules) {
				Object.keys(rules).forEach(function (id) {
					api.mergeRule(id, rules[id]);
				});
			},
			getRule: function getRule(id, origin) {
				var ctx = h.getCtx(id);

				if (ctx) {
					return origin ? ctx.origin : ctx.rule;
				}
			},
			updateValidate: function updateValidate(id, validate, merge) {
				if (merge) {
					api.mergeRule(id, {
						validate: validate,
					});
				} else {
					props(id, "validate", validate);
				}
			},
			updateValidates: function updateValidates(validates, merge) {
				Object.keys(validates).forEach(function (id) {
					api.updateValidate(id, validates[id], merge);
				});
			},
			refreshValidate: function refreshValidate() {
				api.refresh();
			},
			resetFields: function resetFields(fields) {
				tidyFields(fields).forEach(function (field) {
					var ctx = h.fieldCtx[field];
					if (!ctx) return;
					h.$render.clearCache(ctx);
					ctx.rule.value = copy(ctx.defaultValue);
					h.refreshControl(ctx);
				});
			},
			method: function method(id, name) {
				var el = api.el(id);
				if (!el || !el[name])
					throw new Error(
						format(
							"err",
							"".concat(name, "\u65B9\u6CD5\u4E0D\u5B58\u5728")
						)
					);
				return function () {
					return el[name].apply(el, arguments);
				};
			},
			exec: function exec(id, name) {
				for (
					var _len = arguments.length,
						args = new Array(_len > 2 ? _len - 2 : 0),
						_key = 2;
					_key < _len;
					_key++
				) {
					args[_key - 2] = arguments[_key];
				}

				return invoke(function () {
					return api.method(id, name).apply(void 0, args);
				});
			},
			toJson: function toJson$1(space) {
				return toJson(api.rule, space);
			},
			trigger: function trigger(id, event) {
				var el = api.el(id);

				for (
					var _len2 = arguments.length,
						args = new Array(_len2 > 2 ? _len2 - 2 : 0),
						_key2 = 2;
					_key2 < _len2;
					_key2++
				) {
					args[_key2 - 2] = arguments[_key2];
				}

				el && el.$emit.apply(el, [event].concat(args));
			},
			el: function el(id) {
				var ctx = h.getCtx(id);
				if (ctx) return ctx.el || h.vm.$refs[ctx.ref];
			},
			closeModal: function closeModal(id) {
				h.bus.$emit("fc:closeModal:" + id);
			},
			getSubForm: function getSubForm(field) {
				return h.subForm[field];
			},
			nextTick: function nextTick(fn) {
				h.bus.$once("next-tick", fn);
				h.refresh();
			},
			nextRefresh: function nextRefresh(fn) {
				h.nextRefresh();
				fn && invoke(fn);
			},
		};
		["on", "once", "off", "set"].forEach(function (n) {
			api[n] = function () {
				var _h$bus;

				(_h$bus = h.bus).$on.apply(_h$bus, arguments);
			};
		});
		api.changeValue = api.changeField = api.setValue;
		return api;
	}

	function useCache(Render) {
		extend(Render.prototype, {
			initCache: function initCache() {
				this.clearCacheAll();
			},
			clearCache: function clearCache(ctx) {
				if (!this.cache[ctx.id]) {
					ctx.parent && this.clearCache(ctx.parent);
					return;
				}

				if (
					this.cache[ctx.id].use === true ||
					this.cache[ctx.id].parent
				) {
					this.$handle.refresh();
				}

				var parent = this.cache[ctx.id].parent;
				this.cache[ctx.id] = null;
				parent && this.clearCache(parent);
			},
			clearCacheAll: function clearCacheAll() {
				this.cache = {};
			},
			setCache: function setCache(ctx, vnode, parent) {
				this.cache[ctx.id] = {
					vnode: vnode,
					use: false,
					parent: parent,
					slot: ctx.rule.slot,
				};
			},
			getCache: function getCache(ctx) {
				var cache = this.cache[ctx.id];
				cache.use = true;
				return cache.vnode;
			},
		});
	}

	function toCase(str) {
		var to = str.replace(/(-[a-z])/g, function (v) {
			return v.replace("-", "").toLocaleUpperCase();
		});
		return lower(to);
	}

	function lower(str) {
		return str.replace(str[0], str[0].toLowerCase());
	}

	function useRender(Render) {
		extend(Render.prototype, {
			initRender: function initRender() {
				this.cacheConfig = {};
			},
			render: function render() {
				var _this = this;

				console.warn("renderrrrr", this.id);

				if (!this.vm.isShow) {
					return;
				}

				this.$manager.beforeRender();
				var slotBag = makeSlotBag();
				this.sort.forEach(function (k) {
					_this.renderSlot(slotBag, _this.$handle.ctxs[k]);
				});
				return this.$manager.render(slotBag);
			},
			renderSlot: function renderSlot(slotBag, ctx, parent) {
				if (this.isFragment(ctx)) {
					ctx.initProp();
					this.mergeGlobal(ctx);
					ctx.initNone();
					var slots = this.renderChildren(ctx);
					var def = slots["default"];
					def &&
						slotBag.setSlot(ctx.rule.slot, function () {
							return def();
						});
					delete slots["default"];
					slotBag.mergeBag(slots);
				} else {
					slotBag.setSlot(ctx.rule.slot, this.renderCtx(ctx, parent));
				}
			},
			mergeGlobal: function mergeGlobal(ctx) {
				var _this2 = this;

				var g = this.$handle.options.global;
				if (!g) return;

				if (!this.cacheConfig[ctx.trueType]) {
					this.cacheConfig[ctx.trueType] = vue.computed(function () {
						var g = _this2.$handle.options.global;
						return mergeRule({}, [
							g["*"],
							g[ctx.originType] ||
								g[ctx.type] ||
								g[ctx.type] ||
								{},
						]);
					});
				}

				ctx.prop = mergeRule({}, [
					this.cacheConfig[ctx.trueType].value,
					ctx.prop,
				]);
			},
			setOptions: function setOptions(ctx) {
				if (ctx.prop.optionsTo && ctx.prop.options) {
					deepSet(ctx.prop, ctx.prop.optionsTo, ctx.prop.options);
				}
			},
			renderSides: function renderSides(vn, ctx, temp) {
				var prop = ctx[temp ? "rule" : "prop"];
				return [
					this.renderRule(prop.prefix),
					vn,
					this.renderRule(prop.suffix),
				];
			},
			renderCtx: function renderCtx(ctx, parent) {
				var _this3 = this;
				if (ctx.type === "hidden") return;
				var rule = ctx.rule;

				if (
					!this.cache[ctx.id] ||
					this.cache[ctx.id].slot !== rule.slot
				) {
					var vn;
					ctx.initProp();
					this.mergeGlobal(ctx);
					ctx.initNone();
					this.$manager.tidyRule(ctx);
					this.setOptions(ctx);
					this.ctxProp(ctx);
					var prop = ctx.prop;

					if (prop.hidden) {
						this.setCache(ctx, undefined, parent);
						return;
					}

					vn = function vn() {
						return _this3.item(ctx, function () {
							var _vn = ctx.parser.render(
								_this3.renderChildren(ctx),
								ctx
							);

							_vn = _this3.renderSides(_vn, ctx);

							if (
								!(!ctx.input && is.Undef(prop["native"])) &&
								prop["native"] !== true
							) {
								_vn = _this3.$manager.makeWrap(ctx, _vn);
							}

							if (ctx.none) {
								_vn = _this3.display(_vn);
							}

							return _vn;
						});
					};

					this.setCache(ctx, vn, parent);
					ctx._vnode = vn;
					return vn;
				}

				return this.getCache(ctx);
			},
			getModelField: function getModelField(ctx) {
				return (
					ctx.rule.modelField ||
					ctx.parser.modelField ||
					this.fc.modelFields[this.vNode.aliasMap[ctx.field]] ||
					this.fc.modelFields[ctx.field] ||
					this.fc.modelFields[ctx.originType] ||
					"modelValue"
				);
			},
			display: function display(vn) {
				var _this4 = this;

				if (Array.isArray(vn)) {
					var data = [];
					vn.forEach(function (v) {
						if (Array.isArray(v)) return _this4.display(v);
						if (_this4.none(v)) data.push(v);
					});
					return data;
				} else {
					return this.none(vn);
				}
			},
			none: function none(vn) {
				if (vn) {
					if (Array.isArray(vn.props.style)) {
						vn.props.style.push({
							display: "none",
						});
					} else {
						vn.props.style = [
							vn.props.style,
							{
								display: "none",
							},
						];
					}

					return vn;
				}
			},
			item: function item(ctx, vn) {
				return this.vNode.h(
					"FcFragment",
					{
						key: ctx.key,
						formCreateInject: this.injectProp(ctx),
					},
					vn
				);
			},
			isFragment: function isFragment(ctx) {
				return ctx.type === "fragment" || ctx.type === "template";
			},
			injectProp: function injectProp(ctx) {
				var _this5 = this;

				return {
					api: this.$handle.api,
					form: this.fc.create,
					subForm: function subForm(_subForm) {
						_this5.$handle.addSubForm(ctx, _subForm);
					},
					field: ctx.field,
					options: ctx.prop.options,
					children: ctx.rule.children,
					rule: ctx.rule,
					prop: (function () {
						var temp = _objectSpread2({}, ctx.prop);

						return (
							(temp.on = temp.on
								? _objectSpread2({}, temp.on)
								: {}),
							temp
						);
					})(),
				};
			},
			ctxProp: function ctxProp(ctx, custom) {
				var _this6 = this;

				var ref = ctx.ref,
					key = ctx.key,
					rule = ctx.rule;
				this.$manager.mergeProp(ctx, custom);
				ctx.parser.mergeProp(ctx, custom);
				var props = [
					{
						ref: ref,
						key: rule.key || "".concat(key, "fc"),
						slot: undefined,
						on: {
							vnodeMounted: function vnodeMounted(vn) {
								vn.el.__rule__ = ctx;

								_this6.onMounted(ctx, vn.el);
							},
						},
					},
				];

				if (!custom && ctx.input) {
					var field = this.getModelField(ctx);
					props.push({
						on: _defineProperty(
							{},
							"update:".concat(field),
							function update(value) {
								console.log(value, ctx.field);

								_this6.onInput(ctx, value);
							}
						),
						props: _defineProperty(
							{},
							field,
							this.$handle.getFormData(ctx)
						),
					});
				}

				mergeProps(props, ctx.prop);
				return ctx.prop;
			},
			onMounted: function onMounted(ctx, el) {
				ctx.el = this.vm.$refs[ctx.ref] || el;
				ctx.parser.mounted(ctx);
				this.$handle.effect(ctx, "mounted");
			},
			onInput: function onInput(ctx, value) {
				this.$handle.onInput(ctx, value);
			},
			renderChildren: function renderChildren(ctx) {
				var _this7 = this;

				var children = ctx.rule.children;
				if (!is.trueArray(children)) return {};
				var slotBag = makeSlotBag();
				var flag = true;
				children.map(function (child) {
					if (!child) return;
					if (is.String(child)) return slotBag.setSlot(null, child);

					if (child.__fc__) {
						return _this7.renderSlot(slotBag, child.__fc__, ctx);
					}

					if (
						flag &&
						!_this7.$handle.isRepeatRule(
							child.__origin__ || child
						) &&
						child.type
					) {
						flag = false;

						_this7.vm.$nextTick(function () {
							_this7.$handle.loadChildren(children, ctx);

							_this7.$handle.refresh();
						});
					}
				});
				return slotBag.getSlots();
			},
			defaultRender: function defaultRender(ctx, children) {
				var prop = ctx.prop;
				if (this.vNode[ctx.type])
					return this.vNode[ctx.type](prop, children);
				if (this.vNode[ctx.originType])
					return this.vNode[ctx.originType](prop, children);
				return this.vNode.make(lower(ctx.originType), prop, children);
			},
			renderRule: function renderRule(rule, children, origin) {
				var _this8 = this;

				if (!rule) return undefined;
				if (is.String(rule)) return rule;
				var type;

				if (origin) {
					type = rule.type;
				} else {
					type = rule.is;
					if (rule.type) {
						type = toCase(rule.type);
						console.log("tyoe", type);
						var alias = this.vNode.aliasMap[type];
						if (alias) type = toCase(alias);
					}
				}

				if (!type) return undefined;
				var slotBag = makeSlotBag();

				if (is.trueArray(rule.children)) {
					rule.children.forEach(function (v) {
						v &&
							slotBag.setSlot(
								v === null || v === void 0 ? void 0 : v.slot,
								function () {
									return _this8.renderRule(v);
								}
							);
					});
				}

				return this.vNode.make(
					type,
					rule,
					slotBag.mergeBag(children).getSlots()
				);
			},
		});
	}

	var id$1 = 1;
	function Render(handle) {
		extend(this, {
			$handle: handle,
			fc: handle.fc,
			vm: handle.vm,
			$manager: handle.$manager,
			vNode: new handle.fc.CreateNode(handle.vm),
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
		this.initCache();
		this.initRender();
	}
	useCache(Render);
	useRender(Render);

	function useInject(Handler) {
		extend(Handler.prototype, {
			parseInjectEvent: function parseInjectEvent(rule, on) {
				if (rule.inject === false) return;
				var inject = rule.inject || this.options.injectEvent;
				return this.parseEventLst(rule, on, inject);
			},
			parseEventLst: function parseEventLst(rule, data, inject, deep) {
				var _this = this;

				Object.keys(data).forEach(function (k) {
					var fn = _this.parseEvent(rule, data[k], inject, deep);

					if (fn) {
						data[k] = fn;
					}
				});
				return data;
			},
			parseEvent: function parseEvent(rule, fn, inject, deep) {
				if (is.Function(fn) && (!is.Undef(inject) || fn.__inject)) {
					return this.inject(rule, fn, inject);
				} else if (
					!deep &&
					Array.isArray(fn) &&
					fn[0] &&
					(is.String(fn[0]) || is.Function(fn[0]))
				) {
					return this.parseEventLst(rule, fn, inject, true);
				} else if (is.String(fn)) {
					var val = parseFn(fn);
					return is.String(val)
						? val
						: this.parseEvent(rule, val, inject, true);
				}
			},
			parseEmit: function parseEmit(ctx) {
				var _this2 = this;

				var event = {},
					rule = ctx.rule,
					emitPrefix = rule.emitPrefix,
					field = rule.field,
					name = rule.name,
					inject = rule.inject;
				var emit = rule.emit || [];

				if (is.trueArray(emit)) {
					var emitKey = emitPrefix || field || name;

					if (emitKey) {
						emit.forEach(function (eventName) {
							if (!eventName) return;
							var eventInject;

							if (is.Object(eventName)) {
								eventInject = eventName.inject;
								eventName = eventName.name;
							}

							var fieldKey = toLine(
								"".concat(emitKey, "-").concat(eventName)
							);

							var fn = function fn() {
								var _this2$vm, _this2$vm2;

								for (
									var _len = arguments.length,
										arg = new Array(_len),
										_key = 0;
									_key < _len;
									_key++
								) {
									arg[_key] = arguments[_key];
								}

								(_this2$vm = _this2.vm).$emit.apply(
									_this2$vm,
									[fieldKey].concat(arg)
								);

								(_this2$vm2 = _this2.vm).$emit.apply(
									_this2$vm2,
									["emit-event", fieldKey].concat(arg)
								);
							};

							fn.__emit = true;

							if (!eventInject && inject === false) {
								event[eventName] = fn;
							} else {
								var _inject =
									eventInject ||
									inject ||
									_this2.options.injectEvent;

								event[eventName] = is.Undef(_inject)
									? fn
									: _this2.inject(rule, fn, _inject);
							}
						});
					}
				}

				ctx.computed.on = event;
				return event;
			},
			getInjectData: function getInjectData(self, inject) {
				var _this$vm = this.vm,
					option = _this$vm.option,
					rule = _this$vm.rule;
				return {
					$f: this.api,
					rule: rule,
					self: self.__origin__,
					option: option,
					inject: inject,
				};
			},
			inject: function inject(self, _fn, _inject2) {
				if (_fn.__origin) {
					if (this.watching && !this.loading) return _fn;
					_fn = _fn.__origin;
				}

				var h = this;

				var fn = function fn() {
					var data = h.getInjectData(self, _inject2);

					for (
						var _len2 = arguments.length,
							args = new Array(_len2),
							_key2 = 0;
						_key2 < _len2;
						_key2++
					) {
						args[_key2] = arguments[_key2];
					}

					data.args = [].concat(args);
					args.unshift(data);
					return _fn.apply(this, args);
				};

				fn.__origin = _fn;
				fn.__json = _fn.__json;
				return fn;
			},
		});
	}

	var EVENT = ["hook:updated", "hook:mounted"];
	function usePage(Handler) {
		extend(Handler.prototype, {
			usePage: function usePage() {
				var _this = this;

				var page = this.options.page;
				if (!page) return;
				var first = 25;
				var limit = getLimit(this.rules);

				if (is.Object(page)) {
					if (page.first) first = parseInt(page.first, 10) || first;
					if (page.limit) limit = parseInt(page.limit, 10) || limit;
				}

				extend(this, {
					first: first,
					limit: limit,
					pageEnd: this.rules.length <= first,
				});
				this.bus.$on("page-end", function () {
					return _this.vm.$emit("page-end", _this.api);
				});
				this.pageLoad();
			},
			pageLoad: function pageLoad() {
				var _this2 = this;

				var pageFn = function pageFn() {
					if (_this2.pageEnd) {
						_this2.vm.$off(EVENT, pageFn);

						_this2.bus.$emit("page-end");
					} else {
						_this2.first += _this2.limit;
						_this2.pageEnd = _this2.rules.length <= _this2.first;

						_this2.loadRule();

						_this2.refresh();
					}
				};

				this.vm.$on(EVENT, pageFn);
			},
		});
	}

	function getLimit(rules) {
		return rules.length < 31 ? 31 : Math.ceil(rules.length / 3);
	}

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
				++this.loadedId;
				if (this.vm.unique > 0) return this.$render.render();
				else {
					this.vm.unique = 1;
					return [];
				}
			},
		});
	}

	function isNone(ctx) {
		var none = !(is.Undef(ctx.prop.display) || !!ctx.prop.display);

		if (ctx.parent) {
			return ctx.parent.none || none;
		} else {
			return none;
		}
	}

	function bind(ctx) {
		Object.defineProperties(ctx.origin, {
			__fc__: enumerable(vue.markRaw(ctx), true),
		});
	}

	function RuleContext(handle, rule) {
		var id = uniqueId();
		var isInput = !!rule.field;
		extend(this, {
			id: id,
			ref: id,
			wrapRef: id + "fi",
			rule: rule,
			origin: rule.__origin__ || rule,
			name: rule.name,
			none: false,
			watch: [],
			linkOn: [],
			root: [],
			ctrlRule: [],
			parent: null,
			cacheConfig: null,
			prop: _objectSpread2({}, rule),
			computed: {},
			payload: {},
			refRule: {},
			input: isInput,
			el: undefined,
			defaultValue: isInput ? deepCopy(rule.value) : undefined,
			field: rule.field || undefined,
		});
		this.updateType();
		this.updateKey();
		bind(this);
		this.update(handle, true);
	}
	extend(RuleContext.prototype, {
		effectData: function effectData(name) {
			if (!this.payload[name]) {
				this.payload[name] = {};
			}

			return this.payload[name];
		},
		clearEffectData: function clearEffectData(name) {
			delete this.payload[name];
		},
		updateKey: function updateKey(flag) {
			this.key = uniqueId();
			flag && this.parent && this.parent.updateKey(flag);
		},
		updateType: function updateType() {
			this.originType = this.rule.type;
			this.type = toCase(this.rule.type);
		},
		setParser: function setParser(parser) {
			this.parser = parser;
			parser.init(this);
		},
		initProp: function initProp() {
			var _this = this;

			var rule = _objectSpread2({}, this.rule);

			delete rule.children;
			this.prop = mergeProps(
				[rule].concat(
					_toConsumableArray(
						Object.keys(this.payload).map(function (k) {
							return _this.payload[k];
						})
					),
					[this.computed]
				)
			);
		},
		initNone: function initNone() {
			this.none = isNone(this);
		},
		check: function check(handle) {
			return this.vm === handle.vm;
		},
		unwatch: function unwatch() {
			this.watch.forEach(function (un) {
				return un();
			});
			this.watch = [];
			this.refRule = {};
		},
		unlink: function unlink() {
			this.linkOn.forEach(function (un) {
				return un();
			});
			this.linkOn = [];
		},
		link: function link() {
			this.unlink();
			this.$handle.appendLink(this);
		},
		watchTo: function watchTo() {
			var _this2 = this;

			this.vm.$nextTick(function () {
				_this2.$handle.watchCtx(_this2);
			});
		},
		delete: function _delete() {
			var undef = void 0;
			this.unwatch();
			this.unlink();
			this.rmCtrl();
			extend(this, {
				deleted: true,
				prop: _objectSpread2({}, this.rule),
				computed: {},
				el: undef,
				$handle: undef,
				$render: undef,
				$api: undef,
				vm: undef,
				vNode: undef,
				parent: null,
				cacheConfig: null,
				none: false,
			});
		},
		rmCtrl: function rmCtrl() {
			this.ctrlRule.forEach(function (ctrl) {
				return ctrl.__fc__ && ctrl.__fc__.rm();
			});
			this.ctrlRule = [];
		},
		rm: function rm() {
			var _this3 = this;

			var _rm = function _rm() {
				var index = _this3.root.indexOf(_this3.origin);

				if (index > -1) {
					_this3.root.splice(index, 1);

					_this3.$handle && _this3.$handle.refresh();
				}
			};

			if (this.deleted) {
				_rm();

				return;
			}

			this.$handle.noWatch(function () {
				_this3.$handle.deferSyncValue(function () {
					_this3.rmCtrl();

					_rm();

					_this3.$handle.rmCtx(_this3);

					extend(_this3, {
						root: [],
					});
				});
			});
		},
		update: function update(handle, init) {
			extend(this, {
				deleted: false,
				$handle: handle,
				$render: handle.$render,
				$api: handle.api,
				vm: handle.vm,
				trueType: handle.getType(this.originType),
				vNode: handle.$render.vNode,
				updated: false,
			});
			!init && this.unwatch();
			this.watchTo();
			this.link();
		},
	});

	function useLoader(Handler) {
		extend(Handler.prototype, {
			nextRefresh: function nextRefresh(fn) {
				var _this = this;

				var id = this.loadedId;
				this.vm.$nextTick(function () {
					id === _this.loadedId && (fn ? fn() : _this.refresh());
				});
			},
			parseRule: function parseRule(_rule) {
				var _this2 = this;

				var rule = getRule(_rule);
				Object.defineProperties(rule, {
					__origin__: enumerable(_rule, true),
				});
				fullRule(rule);
				this.appendValue(rule);
				rule.options = Array.isArray(rule.options) ? rule.options : [];
				[rule, rule["prefix"], rule["suffix"]].forEach(function (item) {
					if (!item) {
						return;
					}

					_this2.loadFn(item, rule);
				});
				this.loadCtrl(rule);

				if (rule.update) {
					rule.update = parseFn(rule.update);
				}

				return rule;
			},
			loadFn: function loadFn(item, rule) {
				var _this3 = this;

				["on", "props"].forEach(function (k) {
					item[k] && _this3.parseInjectEvent(rule, item[k]);
				});
			},
			loadCtrl: function loadCtrl(rule) {
				rule.control &&
					rule.control.forEach(function (ctrl) {
						if (ctrl.handle) {
							ctrl.handle = parseFn(ctrl.handle);
						}
					});
			},
			syncProp: function syncProp(ctx) {
				var _this4 = this;

				var rule = ctx.rule;
				is.trueArray(rule.sync) &&
					mergeProps(
						[
							{
								on: rule.sync.reduce(function (pre, prop) {
									pre["update:".concat(prop)] = function (
										val
									) {
										rule.props[prop] = val;

										_this4.vm.$emit(
											"sync",
											prop,
											val,
											rule,
											_this4.fapi
										);
									};

									return pre;
								}, {}),
							},
						],
						ctx.computed
					);
			},
			isRepeatRule: function isRepeatRule(rule) {
				return this.repeatRule.indexOf(rule) > -1;
			},
			loadRule: function loadRule() {
				var _this5 = this;

				// console.warn('%c load', 'color:blue');
				this.cycleLoad = false;
				this.loading = true;

				if (this.pageEnd) {
					this.bus.$emit("load-start");
				}

				this.deferSyncValue(function () {
					_this5._loadRule(_this5.rules);

					_this5.loading = false;

					if (_this5.cycleLoad && _this5.pageEnd) {
						return _this5.loadRule();
					}

					if (_this5.pageEnd) {
						_this5.bus.$emit("load-end");
					}

					_this5.vm.renderRule();

					_this5.syncForm();
				});
			},
			loadChildren: function loadChildren(children, parent) {
				this.cycleLoad = false;
				this.loading = true;
				this.bus.$emit("load-start");

				this._loadRule(children, parent);

				this.loading = false;

				if (this.cycleLoad) {
					return this.loadRule();
				} else {
					this.bus.$emit("load-end");
					this.syncForm();
				}

				this.$render.clearCache(parent);
			},
			_loadRule: function _loadRule(rules, parent) {
				var _this6 = this;

				var preIndex = function preIndex(i) {
					var pre = rules[i - 1];

					if (!pre || !pre.__fc__) {
						return i > 0 ? preIndex(i - 1) : -1;
					}

					var index = _this6.sort.indexOf(pre.__fc__.id);

					return index > -1 ? index : preIndex(i - 1);
				};

				var loadChildren = function loadChildren(children, parent) {
					if (is.trueArray(children)) {
						_this6._loadRule(children, parent);
					}
				};

				rules.map(function (_rule, index) {
					if (parent && (is.String(_rule) || is.Undef(_rule))) return;
					if (!_this6.pageEnd && !parent && index >= _this6.first)
						return;
					if (!is.Object(_rule) || !getRule(_rule).type)
						return err("未定义生成规则的 type 字段", _rule);

					if (
						_rule.__fc__ &&
						_rule.__fc__.root === rules &&
						_this6.ctxs[_rule.__fc__.id]
					) {
						loadChildren(_rule.__fc__.rule.children, _rule.__fc__);
						return _rule.__fc__;
					}

					var rule = getRule(_rule);

					var isRepeat = function isRepeat() {
						return !!(
							rule.field &&
							_this6.fieldCtx[rule.field] &&
							_this6.fieldCtx[rule.field] !== _rule.__fc__
						);
					};

					_this6.ruleEffect(rule, "init", {
						repeat: isRepeat(),
					});

					if (isRepeat()) {
						_this6.repeatRule.push(_rule);

						_this6.vm.$emit("repeat-field", _rule, _this6.api);

						return err(
							"".concat(
								rule.field,
								" \u5B57\u6BB5\u5DF2\u5B58\u5728"
							),
							_rule
						);
					}

					var ctx;
					var isCopy = false;
					var isInit = !!_rule.__fc__;

					if (isInit) {
						ctx = _rule.__fc__;
						var check = !ctx.check(_this6);

						if (ctx.deleted) {
							if (check) {
								if (isCtrl(ctx)) {
									return;
								}

								ctx.update(_this6);
							}
						} else {
							if (check) {
								if (isCtrl(ctx)) {
									return;
								}

								rules[index] = _rule = _rule._clone
									? _rule._clone()
									: copyRule(_rule);
								ctx = null;
								isCopy = true;
							}
						}
					}

					if (!ctx) {
						ctx = new RuleContext(_this6, _this6.parseRule(_rule));

						_this6.bindParser(ctx);
					} else {
						if (ctx.originType !== ctx.rule.type) {
							ctx.updateType();

							_this6.bindParser(ctx);
						}

						_this6.appendValue(ctx.rule);
					}

					_this6.parseEmit(ctx);

					_this6.syncProp(ctx);

					ctx.parent = parent || null;
					ctx.root = rules;

					_this6.setCtx(ctx);

					!isCopy && !isInit && _this6.effect(ctx, "load");
					ctx.parser.loadChildren === false ||
						loadChildren(ctx.rule.children, ctx);

					if (!parent) {
						var _preIndex = preIndex(index);

						if (_preIndex > -1 || !index) {
							_this6.sort.splice(_preIndex + 1, 0, ctx.id);
						} else {
							_this6.sort.push(ctx.id);
						}
					}

					var r = ctx.rule;

					if (!ctx.updated) {
						ctx.updated = true;

						if (is.Function(r.update)) {
							_this6.bus.$once("load-end", function () {
								_this6.refreshUpdate(ctx, r.value);
							});
						}

						_this6.effect(ctx, "loaded");
					} // if (ctx.input)
					//     Object.defineProperty(r, 'value', this.valueHandle(ctx));

					if (_this6.refreshControl(ctx)) _this6.cycleLoad = true;
					return ctx;
				});
			},
			refreshControl: function refreshControl(ctx) {
				return ctx.input && ctx.rule.control && this.useCtrl(ctx);
			},
			useCtrl: function useCtrl(ctx) {
				var _this7 = this;

				var controls = getCtrl(ctx),
					validate = [],
					api = this.api;
				if (!controls.length) return false;

				var _loop = function _loop(i) {
					var control = controls[i],
						handleFn =
							control.handle ||
							function (val) {
								return val === control.value;
							};

					var data = _objectSpread2(
						_objectSpread2({}, control),
						{},
						{
							valid: invoke(function () {
								return handleFn(ctx.rule.value, api);
							}),
							ctrl: findCtrl(ctx, control.rule),
						}
					);

					if (
						(data.valid && data.ctrl) ||
						(!data.valid && !data.ctrl)
					)
						return "continue";
					validate.push(data);
				};

				for (var i = 0; i < controls.length; i++) {
					var _ret = _loop(i);

					if (_ret === "continue") continue;
				}

				if (!validate.length) return false;
				var flag = false;
				validate.reverse().forEach(function (_ref) {
					var valid = _ref.valid,
						rule = _ref.rule,
						prepend = _ref.prepend,
						append = _ref.append,
						child = _ref.child,
						ctrl = _ref.ctrl;

					if (is.String(rule[0])) {
						valid
							? ctx.ctrlRule.push({
									__ctrl: true,
									children: rule,
									valid: valid,
							  })
							: ctx.ctrlRule.splice(
									ctx.ctrlRule.indexOf(ctrl),
									1
							  );

						_this7.vm.$nextTick(function () {
							_this7.api.hidden(!valid, rule);
						});

						return;
					}

					if (valid) {
						flag = true;
						var ruleCon = {
							type: "template",
							native: true,
							__ctrl: true,
							children: rule,
						};
						ctx.ctrlRule.push(ruleCon);

						_this7.bus.$once("load-start", function () {
							// this.cycleLoad = true;
							if (prepend) {
								api.prepend(ruleCon, prepend, child);
							} else if (append || child) {
								api.append(ruleCon, append || ctx.id, child);
							} else {
								ctx.root.splice(
									ctx.root.indexOf(ctx.origin) + 1,
									0,
									ruleCon
								);
							}
						});
					} else {
						ctx.ctrlRule.splice(ctx.ctrlRule.indexOf(ctrl), 1);
						var ctrlCtx = byCtx(ctrl);
						ctrlCtx && ctrlCtx.rm();
					}
				});
				this.vm.$emit("control", ctx.origin, this.api);
				this.effect(ctx, "control");
				return flag;
			},
			reloadRule: function reloadRule(rules) {
				return this._reloadRule(rules);
			},
			_reloadRule: function _reloadRule(rules) {
				var _this8 = this;

				// console.warn('%c reload', 'color:red');
				if (!rules) rules = this.rules;

				var ctxs = _objectSpread2({}, this.ctxs);

				this.clearNextTick();
				this.initData(rules);
				this.fc.rules = rules;
				this.bus.$once("load-end", function () {
					Object.keys(ctxs)
						.filter(function (id) {
							return _this8.ctxs[id] === undefined;
						})
						.forEach(function (id) {
							return _this8.rmCtx(ctxs[id]);
						});

					_this8.$render.clearCacheAll();
				});
				this.reloading = true;
				this.loadRule();
				this.reloading = false;
				this.refresh();
				this.bus.$off("next-tick", this.nextReload);
				this.bus.$once("next-tick", this.nextReload);
			},
			//todo 组件生成全部通过 alias
			refresh: function refresh() {
				this.vm.refresh();
			},
		});
	}

	function fullRule(rule) {
		var def = baseRule();
		Object.keys(def).forEach(function (k) {
			if (!hasProperty(rule, k)) rule[k] = def[k];
		});
		return rule;
	}

	function getCtrl(ctx) {
		var control = ctx.rule.control || [];
		if (is.Object(control)) return [control];
		else return control;
	}

	function findCtrl(ctx, rule) {
		for (var i = 0; i < ctx.ctrlRule.length; i++) {
			var ctrl = ctx.ctrlRule[i];
			if (ctrl.children === rule) return ctrl;
		}
	}

	function isCtrl(ctx) {
		return !!ctx.rule.__ctrl;
	}

	function useInput(Handler) {
		extend(Handler.prototype, {
			setValue: function setValue(ctx, value, formValue, setFlag) {
				if (ctx.deleted) return;
				ctx.rule.value = value;
				this.changeStatus = true;
				this.nextRefresh();
				this.$render.clearCache(ctx);
				this.setFormData(ctx, formValue);
				this.syncValue();
				this.valueChange(ctx, value);
				this.vm.$emit(
					"change",
					ctx.field,
					value,
					ctx.origin,
					this.api,
					setFlag
				);
				this.effect(ctx, "value");
			},
			onInput: function onInput(ctx, value) {
				var val;

				if (
					ctx.input &&
					(this.isQuote(
						ctx,
						(val = ctx.parser.toValue(value, ctx))
					) ||
						this.isChange(ctx, value))
				) {
					this.setValue(ctx, val, value);
				}
			},
			setFormData: function setFormData(ctx, value) {
				$set(this.formData, ctx.field, value);
			},
			getFormData: function getFormData(ctx) {
				return this.formData[ctx.field];
			},
			syncForm: function syncForm() {
				var _this = this;

				Object.keys(this.formData).reduce(
					function (initial, field) {
						var ctx = _this.getCtx(field);

						initial[field] = vue.toRef(ctx.rule, "value");
						return initial;
					},
					Object.keys(this.appendData).reduce(function (
						initial,
						field
					) {
						initial[field] = vue.customRef(function (
							track,
							trigger
						) {
							return {
								get: function get() {
									track();
									return _this.appendData[field];
								},
								set: function set(val) {
									trigger();
									_this.appendData[field] = val;

									_this.syncValue();
								},
							};
						});
						return initial;
					},
					this.form)
				);
				this.syncValue();
			},
			appendValue: function appendValue(rule) {
				if (!rule.field || !hasProperty(this.appendData, rule.field))
					return;
				rule.value = this.appendData[rule.field];
				delete this.appendData[rule.field];
			},
			addSubForm: function addSubForm(ctx, subForm) {
				if (ctx.input) {
					this.subForm[ctx.field] = subForm;
				}
			},
			deferSyncValue: function deferSyncValue(fn) {
				if (!this.deferSyncFn) {
					this.deferSyncFn = fn;
				}

				invoke(fn);

				if (this.deferSyncFn === fn) {
					this.deferSyncFn = null;

					if (fn.sync) {
						this.syncValue();
					}
				}
			},
			syncValue: function syncValue() {
				if (this.deferSyncFn) {
					return (this.deferSyncFn.sync = true);
				}

				this.vm.updateValue(_objectSpread2({}, this.form));
			},
			isChange: function isChange(ctx, value) {
				return (
					JSON.stringify(this.getFormData(ctx)) !==
					JSON.stringify(value)
				);
			},
			isQuote: function isQuote(ctx, value) {
				return (
					(is.Object(value) || Array.isArray(value)) &&
					value === ctx.rule.value
				);
			},
			refreshUpdate: function refreshUpdate(ctx, val) {
				var _this2 = this;

				var fn = ctx.rule.update;

				if (is.Function(fn)) {
					var state = invoke(function () {
						return fn(val, ctx.origin, _this2.api);
					});
					if (state === undefined) return;
					ctx.rule.hidden = state === true;
				}
			},
			valueChange: function valueChange(ctx, val) {
				this.refreshRule(ctx, val);
				this.bus.$emit("change-" + ctx.field, val);
			},
			refreshRule: function refreshRule(ctx, val) {
				if (this.refreshControl(ctx)) {
					this.$render.clearCacheAll();
					this.loadRule();
					this.refresh();
				}

				this.refreshUpdate(ctx, val);
			},
			appendLink: function appendLink(ctx) {
				var _this3 = this;

				var link = ctx.rule.link;
				is.trueArray(link) &&
					link.forEach(function (field) {
						var fn = function fn() {
							return _this3.refreshRule(ctx, ctx.rule.value);
						};

						_this3.bus.$on("change-" + field, fn);

						ctx.linkOn.push(function () {
							return _this3.bus.$off("change-" + field, fn);
						});
					});
			},
			fields: function fields() {
				return Object.keys(this.formData);
			},
		});
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
			return ctx.$render.defaultRender(ctx, children);
		},
		mergeProp: function mergeProp(ctx) {},
	};

	function useContext(Handler) {
		extend(Handler.prototype, {
			getCtx: function getCtx(id) {
				return this.fieldCtx[id] || this.nameCtx[id] || this.ctxs[id];
			},
			setCtx: function setCtx(ctx) {
				var id = ctx.id,
					field = ctx.field,
					name = ctx.name,
					rule = ctx.rule;
				this.ctxs[id] = ctx;
				if (name) $set(this.nameCtx, name, ctx);
				if (!ctx.input) return;
				this.fieldCtx[field] = ctx;
				this.setFormData(ctx, ctx.parser.toFormValue(rule.value, ctx));

				if (this.isMounted && !this.reloading) {
					this.vm.$emit(
						"change",
						ctx.field,
						rule.value,
						ctx.origin,
						this.api
					);
				}
			},
			getParser: function getParser(ctx) {
				var list = this.fc.parsers;
				return (
					list[ctx.originType] ||
					list[toCase(ctx.type)] ||
					list[ctx.trueType] ||
					BaseParser
				);
			},
			bindParser: function bindParser(ctx) {
				ctx.setParser(this.getParser(ctx));
			},
			getType: function getType(alias) {
				var map = this.fc.CreateNode.aliasMap;
				var type = map[alias] || map[toCase(alias)] || alias;
				return toCase(type);
			},
			noWatch: function noWatch(fn) {
				if (!this.noWatchFn) {
					this.noWatchFn = fn;
				}

				invoke(fn);

				if (this.noWatchFn === fn) {
					this.noWatchFn = null;
				}
			},
			watchCtx: function watchCtx(ctx) {
				var _this = this;
				console.log(ctx);
				var none = [
					"field",
					"value",
					"vm",
					"template",
					"name",
					"config",
					"control",
					"inject",
					"sync",
					"payload",
					"optionsTo",
					"update",
				];
				var all = attrs();
				all.filter(function (k) {
					return none.indexOf(k) === -1;
				}).forEach(function (key) {
					var ref = vue.toRef(ctx.rule, key);
					var flag = key === "children";
					ctx.refRule[key] = ref;
					ctx.watch.push(
						vue.watch(
							flag
								? function () {
										return _toConsumableArray(
											ref.value || []
										);
								  }
								: ref,
							function (_, o) {
								var n = ref.value;
								console.log("change", key, ctx.rule.field, _);
								if (_this.isBreakWatch()) return;
								if (flag && ctx.parser.loadChildren === false)
									return;
								_this.watching = true;

								if (key === "link") {
									ctx.link();
									return;
								} else if (["props", "on"].indexOf(key) > -1) {
									_this.parseInjectEvent(ctx.rule, n || {});

									if (key === "props" && ctx.input) {
										_this.setFormData(
											ctx,
											ctx.parser.toFormValue(
												ctx.rule.value,
												ctx
											)
										);
									}
								} else if (key === "emit") _this.parseEmit(ctx);
								else if (["prefix", "suffix"].indexOf(key) > -1)
									n && _this.loadFn(n, ctx.rule);
								else if (key === "type") {
									ctx.updateType();

									_this.bindParser(ctx);
								} else if (key === "children") {
									_this.deferSyncValue(function () {
										o &&
											o.forEach(function (child) {
												if (
													(n || []).indexOf(child) ===
														-1 &&
													child &&
													!is.String(child) &&
													child.__fc__ &&
													!_this.ctxs[child.__fc__.id]
												) {
													_this.rmCtx(child.__fc__);
												}
											});
										is.trueArray(n) &&
											_this.loadChildren(n, ctx);
									});
								}

								_this.$render.clearCache(ctx);

								_this.watching = false;
							},
							{
								deep: !flag,
								sync: flag,
							}
						)
					);
				});

				if (ctx.input) {
					var val = vue.toRef(ctx.rule, "value");
					console.log("value", ctx.rule.field, ctx.rule.value);
					ctx.watch.push(
						vue.watch(val, function () {
							console.log("value", ctx.rule.field, val);
							var formValue = ctx.parser.toFormValue(
								val.value,
								ctx
							);

							if (_this.isChange(ctx, formValue)) {
								_this.setValue(ctx, val.value, formValue, true);
							}
						})
					);
				}

				this.watchEffect(ctx);
			},
			rmSub: function rmSub(sub) {
				var _this2 = this;

				is.trueArray(sub) &&
					sub.forEach(function (r) {
						r && r.__fc__ && _this2.rmCtx(r.__fc__);
					});
			},
			rmCtx: function rmCtx(ctx) {
				var _this3 = this;

				// console.trace(ctx.field,'deleted');
				if (ctx.deleted) return;
				var id = ctx.id,
					field = ctx.field,
					name = ctx.name;
				$del(this.ctxs, id);
				var f = this.fieldCtx[field];
				var flag = false;

				if (field && (!f || f === ctx)) {
					$del(this.formData, field);
					$del(this.form, field);
					$del(this.fieldCtx, field);
					$del(this.subForm, field);
					flag = true;
				}

				if (name && this.nameCtx[name] === ctx) {
					$del(this.nameCtx, name);
				}

				if (!this.reloading) {
					if (ctx.parser.loadChildren !== false) {
						this.deferSyncValue(function () {
							if (is.trueArray(ctx.rule.children)) {
								ctx.rule.children.forEach(function (h) {
									return h.__fc__ && _this3.rmCtx(h.__fc__);
								});
							}

							_this3.syncValue();
						});
					}

					if (ctx.root === this.rules) {
						this.vm.renderRule();
					}
				}

				var index = this.sort.indexOf(id);

				if (index > -1) {
					this.sort.splice(index, 1);
				}

				this.$render.clearCache(ctx);
				ctx["delete"]();
				this.effect(ctx, "deleted");
				flag &&
					this.vm.$emit("remove-field", field, ctx.rule, this.api);
				ctx.rule.__ctrl ||
					this.vm.$emit("remove-rule", ctx.rule, this.api);
				return ctx;
			},
		});
	}

	function useLifecycle(Handler) {
		extend(Handler.prototype, {
			mounted: function mounted() {
				var _this = this;

				var _mounted = function _mounted() {
					_this.isMounted = true;

					_this.lifecycle("mounted");
				};

				if (this.pageEnd) {
					_mounted();
				} else {
					this.bus.$once("page-end", _mounted);
				}
			},
			lifecycle: function lifecycle(name) {
				var _this2 = this;

				var fn = this.options[name];
				is.Function(fn) &&
					invoke(function () {
						return fn(_this2.api);
					});
				this.vm.$emit(name, this.api);
			},
		});
	}

	function useEffect(Handler) {
		extend(Handler.prototype, {
			useProvider: function useProvider() {
				var _this = this;

				var ps = this.fc.providers;
				Object.keys(ps).forEach(function (k) {
					var prop = ps[k];
					prop._c = getComponent(prop);

					_this.onEffect(prop);

					_this.providers[k] = prop;
				});
			},
			onEffect: function onEffect(provider) {
				var _this2 = this;

				var used = [];
				(provider._c || ["*"]).forEach(function (name) {
					var type = name === "*" ? "*" : _this2.getType(name);
					if (used.indexOf(type) > -1) return;
					used.push(type);

					_this2.bus.$on(
						"p:"
							.concat(provider.name, ":")
							.concat(type, ":")
							.concat(provider.input ? 1 : 0),
						function (event, args) {
							provider[event] &&
								provider[event].apply(
									provider,
									_toConsumableArray(args)
								);
						}
					);
				});
				provider._used = used;
			},
			watchEffect: function watchEffect(ctx) {
				var _this3 = this;

				var vm = this.vm;
				Object.keys(ctx.rule.effect || {}).forEach(function (k) {
					ctx.watch.push(
						vm.$watch(
							function () {
								return ctx.rule.effect[k];
							},
							function (n) {
								_this3.effect(
									ctx,
									"watch",
									_defineProperty({}, k, n)
								);
							},
							{
								deep: true,
							}
						)
					);
				});
			},
			ruleEffect: function ruleEffect(rule, event, append) {
				this.emitEffect(
					{
						rule: rule,
						input: !!rule.field,
						type: this.getType(rule.type),
					},
					event,
					append
				);
			},
			effect: function effect(ctx, event, custom) {
				this.emitEffect(
					{
						rule: ctx.rule,
						input: ctx.input,
						type: ctx.trueType,
						ctx: ctx,
						custom: custom,
					},
					event
				);
			},
			getEffect: function getEffect(rule, name) {
				if (
					hasProperty(rule, "effect") &&
					hasProperty(rule.effect, name)
				)
					return rule.effect[name];
				else return undefined;
			},
			emitEffect: function emitEffect(_ref, event, append) {
				var _this4 = this;

				var ctx = _ref.ctx,
					rule = _ref.rule,
					input = _ref.input,
					type = _ref.type,
					custom = _ref.custom;
				if (!type || type === "fcFragment") return;
				var effect = custom ? custom : rule.effect || {};
				Object.keys(effect).forEach(function (attr) {
					var p = _this4.providers[attr];
					if (!p || (p.input && !input)) return;

					var _type;

					if (!p._c) {
						_type = "*";
					} else if (p._used.indexOf(type) > -1) {
						_type = type;
					} else {
						return;
					}

					var data = _objectSpread2(
						{
							value: effect[attr],
							getValue: function getValue() {
								return _this4.getEffect(rule, attr);
							},
						},
						append || {}
					);

					if (ctx) {
						data.getProp = function () {
							return ctx.effectData(attr);
						};

						data.clearProp = function () {
							return ctx.clearEffectData(attr);
						};

						data.mergeProp = function (prop) {
							return mergeProps([prop], data.getProp());
						};
					}

					_this4.bus.$emit(
						"p:"
							.concat(attr, ":")
							.concat(_type, ":")
							.concat(p.input ? 1 : 0),
						event,
						[data, rule, _this4.api]
					);
				});
			},
		});
	}

	function unique(arr) {
		return arr.filter(function (item, index, arr) {
			return arr.indexOf(item, 0) === index;
		});
	}

	function getComponent(p) {
		var c = p.components;
		if (Array.isArray(c))
			return unique(
				c.filter(function (v) {
					return v !== "*";
				})
			);
		else if (is.String(c)) return [c];
		else return false;
	}

	function Handler(fc) {
		var _this = this;

		funcProxy(this, {
			options: function options() {
				return fc.options.value || {};
			},
			bus: function bus() {
				return fc.bus;
			},
		});
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
		this.initData(fc.rules);
		this.$manager = new fc.manager(this);
		this.$render = new Render(this);
		this.api = fc.extendApi(Api(this), this);
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

	useInject(Handler);
	usePage(Handler);
	useRender$1(Handler);
	useLoader(Handler);
	useInput(Handler);
	useContext(Handler);
	useLifecycle(Handler);
	useEffect(Handler);

	// https://github.com/ElemeFE/element/blob/dev/packages/upload/src/ajax.js
	function getError(action, option, xhr) {
		var msg = "fail to ".concat(action, " ").concat(xhr.status, "'");
		var err = new Error(msg);
		err.status = xhr.status;
		err.url = action;
		return err;
	}

	function getBody(xhr) {
		var text = xhr.responseText || xhr.response;

		if (!text) {
			return text;
		}

		try {
			return JSON.parse(text);
		} catch (e) {
			return text;
		}
	}

	function fetch(option) {
		if (typeof XMLHttpRequest === "undefined") {
			return;
		}

		var xhr = new XMLHttpRequest();
		var action = option.action;

		xhr.onerror = function error(e) {
			option.onError(e);
		};

		xhr.onload = function onload() {
			if (xhr.status < 200 || xhr.status >= 300) {
				return option.onError(
					getError(action, option, xhr),
					getBody(xhr)
				);
			}

			option.onSuccess(getBody(xhr));
		};

		xhr.open(option.method || "get", action, true);
		var formData;

		if (option.data) {
			if ((option.dataType || "").toLowerCase() !== "json") {
				formData = new FormData();
				Object.keys(option.data).map(function (key) {
					formData.append(key, option.data[key]);
				});
			} else {
				formData = JSON.stringify(option.data);
				xhr.setRequestHeader("content-type", "application/json");
			}
		}

		if (option.withCredentials && "withCredentials" in xhr) {
			xhr.withCredentials = true;
		}

		var headers = option.headers || {};
		Object.keys(headers).forEach(function (item) {
			if (headers[item] !== null) {
				xhr.setRequestHeader(item, headers[item]);
			}
		});
		xhr.send(formData);
	}

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

	var NAME$a = "FcVnode";
	var vnode = vue.defineComponent({
		name: NAME$a,
		inheritAttrs: false,
		props: ["vnode"],
		render: function render() {
			return this.vnode;
		},
	});

	function tidyDirectives(directives) {
		return Object.keys(directives).map(function (n) {
			var directive = directives[n];
			return [
				vue.resolveDirective(n),
				directive.arg,
				directive.value,
				directive.modifiers,
			];
		});
	}

	function makeDirective(data, vn) {
		var directives = data.directives;
		if (!directives) return vn;

		if (!Array.isArray(directives)) {
			directives = [directives];
		}

		return vue.withDirectives(
			vn,
			directives.reduce(function (lst, v) {
				return lst.concat(tidyDirectives(v));
			}, [])
		);
	}

	function CreateNodeFactory() {
		var aliasMap = {};

		function CreateNode() {}

		extend(CreateNode.prototype, {
			make: function make(tag, data, children) {
				return makeDirective(
					data,
					this.h(tag, toProps(data), children)
				);
			},
			h: function h(tag, data, children) {
				return vue.createVNode(
					vue.getCurrentInstance().appContext.config.isNativeTag(tag)
						? tag
						: vue.resolveComponent(tag),
					data,
					children
				);
			},
			aliasMap: aliasMap,
		});
		extend(CreateNode, {
			aliasMap: aliasMap,
			alias: function alias(_alias, name) {
				aliasMap[_alias] = name;
			},
			use: function use(nodes) {
				Object.keys(nodes).forEach(function (k) {
					var line = toLine(k);
					var lower = toString(k).toLocaleLowerCase();
					var v = nodes[k];
					[k, line, lower].forEach(function (n) {
						CreateNode.alias(k, v);

						CreateNode.prototype[n] = function (data, children) {
							return this.make(v, data, children);
						};
					});
				});
			},
		});
		return CreateNode;
	}

	function createManager(proto) {
		var CustomManager = /*#__PURE__*/ (function (_Manager) {
			_inherits(CustomManager, _Manager);

			var _super = _createSuper(CustomManager);

			function CustomManager() {
				_classCallCheck(this, CustomManager);

				return _super.apply(this, arguments);
			}

			return CustomManager;
		})(Manager);

		Object.assign(CustomManager.prototype, proto);
		return CustomManager;
	}

	function Manager(handler) {
		extend(this, {
			$handle: handler,
			vm: handler.vm,
			options: {},
			ref: "fcForm",
			mergeOptionsRule: {
				normal: ["form", "row", "info", "submitBtn", "resetBtn"],
			},
		});
		this.updateKey();
		this.init();
	}
	extend(Manager.prototype, {
		__init: function __init() {
			var _this = this;

			this.$render = this.$handle.$render;

			this.$r = function () {
				var _this$$render;

				return (_this$$render = _this.$render).renderRule.apply(
					_this$$render,
					arguments
				);
			};
		},
		updateKey: function updateKey() {
			this.key = uniqueId();
		},
		//TODO interface
		init: function init() {},
		update: function update() {},
		beforeRender: function beforeRender() {},
		form: function form() {
			return this.vm.$refs[this.ref];
		},
		mergeOptions: function mergeOptions(args, opt) {
			var _this2 = this;

			return mergeProps(
				args.map(function (v) {
					return _this2.tidyOptions(v);
				}),
				opt,
				this.mergeOptionsRule
			);
		},
		updateOptions: function updateOptions(options) {
			this.options = this.mergeOptions(
				[options],
				this.getDefaultOptions()
			);
			this.update();
		},
		tidyOptions: function tidyOptions(options) {
			return options;
		},
		tidyRule: function tidyRule(ctx) {},
		mergeProp: function mergeProp(ctx) {},
		getDefaultOptions: function getDefaultOptions() {
			return {};
		},
		render: function render(children) {},
	});

	var $fetch = {
		name: "fetch",
		loaded: function loaded() {
			run.apply(void 0, arguments);
		},
		watch: function watch(inject, rule, api) {
			if (!run(inject, rule, api)) {
				inject.clearProp();
				api.sync(rule);
			}
		},
	};

	function parseOpt(option) {
		if (is.String(option)) {
			option = {
				action: option,
				to: "options",
			};
		}

		return option;
	}

	function run(inject, rule, api) {
		var option = inject.value;
		option = parseOpt(option);

		if (!option || !option.action) {
			return false;
		}

		if (!option.to) {
			option.to = "options";
		}

		var _onError = option.onError;

		var check = function check() {
			if (!inject.getValue()) {
				inject.clearProp();
				api.sync(rule);
				return true;
			}
		};

		var set = function set(val) {
			if (val === undefined) {
				inject.clearProp();
				api.sync(rule);
			} else {
				deepSet(inject.getProp(), option.to, val);
			}
		};

		invoke(function () {
			return fetch(
				_objectSpread2(
					_objectSpread2({}, option),
					{},
					{
						onSuccess: function onSuccess(body) {
							if (check()) return;
							set(
								(
									option.parse ||
									function (v) {
										return v.data;
									}
								)(body, rule, api)
							);
							api.sync(rule);
						},
						onError: function onError(e) {
							set(undefined);
							if (check()) return;

							(
								_onError ||
								function (e) {
									return err(
										e.message ||
											"fetch fail " + option.action
									);
								}
							)(e, rule, api);
						},
					}
				)
			);
		});
		return true;
	}

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
			prop: prop,
		};
	}

	function nameProp() {
		return parseProp.apply(
			void 0,
			["name"].concat(Array.prototype.slice.call(arguments))
		);
	}

	function exportAttrs(attrs) {
		var key = attrs.key || [];
		var array = attrs.array || [];
		var normal = attrs.normal || [];
		keyAttrs.push.apply(keyAttrs, _toConsumableArray(key));
		arrayAttrs.push.apply(arrayAttrs, _toConsumableArray(array));
		normalAttrs.push.apply(normalAttrs, _toConsumableArray(normal));
		appendProto(
			[].concat(
				_toConsumableArray(key),
				_toConsumableArray(array),
				_toConsumableArray(normal)
			)
		);
	} //todo 表单嵌套

	function FormCreateFactory(config) {
		var components = _defineProperty({}, fragment.name, fragment);

		var parsers = {};
		var directives = {};
		var modelFields = {};
		var providers = {
			fetch: $fetch,
		};
		var maker = makerFactory();
		var globalConfig = {
			global: {},
		};
		var data = {};
		var CreateNode = CreateNodeFactory();
		exportAttrs(config.attrs || {});

		function directive() {
			var data = nameProp.apply(void 0, arguments);
			if (data.id && data.prop) directives[data.id] = data.prop;
		}

		function register() {
			var data = nameProp.apply(void 0, arguments);
			if (data.id && data.prop)
				providers[data.id] = _objectSpread2(
					_objectSpread2({}, data.prop),
					{},
					{
						name: data.id,
					}
				);
		}

		function componentAlias(alias) {
			CreateNode.use(alias);
		}

		function parser() {
			var data = nameProp.apply(void 0, arguments);
			if (!data.id || !data.prop) return;
			var name = toCase(data.id);
			var parser = data.prop;
			var base = parser.merge === true ? parsers[name] : undefined;
			parsers[name] = _objectSpread2(
				_objectSpread2({}, base || BaseParser),
				parser
			);
			maker[name] = creatorFactory(name);
			parser.maker && extend(maker, parser.maker);
		}

		function component(id, component) {
			var name;

			if (is.String(id)) {
				name = toCase(id);

				if (component === undefined) {
					return components[name];
				}
			} else {
				name = toCase(id.name);
				component = id;
			}

			if (!name || !component) return;
			components[name] = component;
			if (component.formCreateParser)
				parser(name, component.formCreateParser);
		}

		function $form() {
			return $FormCreate(FormCreate);
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

		function use(fn, opt) {
			if (is.Function(fn.install)) fn.install(create, opt);
			else if (is.Function(fn)) fn(create, opt);
			return this;
		}

		function create(rules, _opt) {
			var app = createFormApp(rules, _opt || {});
			config.appUse && config.appUse(app);
			var vm = app.mount(
				(_opt === null || _opt === void 0 ? void 0 : _opt.el) ||
					document.body
			);
			return vm.$refs.fc.fapi;
		}

		function factory() {
			return FormCreateFactory(config);
		}

		function setModelField(name, field) {
			modelFields[name] = field;
		}

		function FormCreate(vm) {
			var _this = this;

			extend(this, {
				create: create,
				vm: vm.ctx,
				manager: createManager(config.manager),
				parsers: parsers,
				providers: providers,
				modelFields: modelFields,
				rules: vm.props.rule,
				prop: {
					components: components,
					directives: directives,
				},
				CreateNode: CreateNode,
				bus: new Mitt(),
				unwatch: null,
				options: vue.ref({}),
				extendApi:
					config.extendApi ||
					function (api) {
						return api;
					},
			});
			vue.watch(
				this.options,
				function () {
					_this.$handle.$manager.updateOptions(_this.options.value);

					_this.api().refresh();
				},
				{
					deep: true,
				}
			);
			extend(vm.appContext.components, components);
			extend(vm.appContext.directives, directives);
			this.$handle = new Handler(this);
		}

		extend(FormCreate.prototype, {
			init: function init() {
				var _this2 = this;

				if (this.isSub()) {
					this.unwatch = this.vm.$watch(
						function () {
							return _this2.vm.parent.option;
						},
						function () {
							_this2.initOptions(_this2.options.value);

							_this2.$handle.api.refresh();
						},
						{
							deep: true,
						}
					);
				}

				this.initOptions(this.vm.option || {});
				this.$handle.init();
			},
			isSub: function isSub() {
				return this.vm.parent && this.vm.extendOption;
			},
			initOptions: function initOptions(options) {
				this.options.value = _objectSpread2(
					{
						formData: {},
						submitBtn: {},
						resetBtn: {},
					},
					deepCopy(globalConfig)
				);

				if (this.isSub()) {
					this.options.value = this.mergeOptions(
						this.options.value,
						this.vm.parent.fapi.config || {},
						true
					);
				}

				this.updateOptions(options);
			},
			mergeOptions: function mergeOptions(target, opt, parent) {
				opt = deepCopy(opt);
				parent &&
					[
						"page",
						"onSubmit",
						"mounted",
						"reload",
						"formData",
						"el",
					].forEach(function (n) {
						delete opt[n];
					});

				if (opt.global) {
					target.global = mergeGlobal(target.global, opt.global);
					delete opt.global;
				}

				this.$handle.$manager.mergeOptions([opt], target);
				return target;
			},
			updateOptions: function updateOptions(options) {
				this.options.value = this.mergeOptions(
					this.options.value,
					options
				);
				this.$handle.$manager.updateOptions(this.options.value);
			},
			api: function api() {
				return this.$handle.api;
			},
			render: function render() {
				return this.$handle.render();
			},
			mounted: function mounted() {
				this.$handle.mounted();
			},
			unmount: function unmount() {
				this.unwatch && this.unwatch();
				this.$handle.reloadRule([]);
			},
			updated: function updated() {
				var _this3 = this;

				this.$handle.bindNextTick(function () {
					return _this3.bus.$emit("next-tick", _this3.$handle.api);
				});
			},
		});

		function useAttr(formCreate) {
			extend(formCreate, {
				version: config.version,
				ui: config.ui,
				data: data,
				maker: maker,
				component: component,
				directive: directive,
				setModelField: setModelField,
				register: register,
				parser: parser,
				use: use,
				factory: factory,
				componentAlias: componentAlias,
				copyRule: copyRule,
				copyRules: copyRules,
				fetch: fetch,
				$form: $form,
				parseJson: parseJson,
				toJson: toJson,
			});
		}

		function useStatic(formCreate) {
			extend(formCreate, {
				create: create,
				install: function install(Vue, options) {
					globalConfig = _objectSpread2(
						_objectSpread2({}, globalConfig),
						options || {}
					);
					if (Vue._installedFormCreate === true) return;
					Vue._installedFormCreate = true;

					var $formCreate = function $formCreate(rules) {
						var opt =
							arguments.length > 1 && arguments[1] !== undefined
								? arguments[1]
								: {};
						return create(rules, opt);
					};

					useAttr($formCreate);
					Vue.config.globalProperties.$formCreate = $formCreate;

					Vue.component("FormCreate", $form());
					Vue.component("fc-vnode", vnode);
				},
			});
		}

		useAttr(create);
		useStatic(create);
		CreateNode.use({
			fragment: "fcFragment",
		});
		console.log(create, config);
		if (config.install) create.use(config);
		return create;
	}

	var DEFAULT_FORMATS = {
		date: "YYYY-MM-DD",
		month: "YYYY-MM",
		datetime: "YYYY-MM-DD HH:mm:ss",
		timerange: "HH:mm:ss",
		daterange: "YYYY-MM-DD",
		monthrange: "YYYY-MM",
		datetimerange: "YYYY-MM-DD HH:mm:ss",
		year: "YYYY",
	};
	var name = "datePicker";
	var datePicker = {
		name: name,
		maker: (function () {
			return [
				"year",
				"month",
				"date",
				"dates",
				"week",
				"datetime",
				"datetimeRange",
				"dateRange",
				"monthRange",
			].reduce(function (initial, type) {
				initial[type] = creatorFactory(name, {
					type: type.toLowerCase(),
				});
				return initial;
			}, {});
		})(),
		mergeProp: function mergeProp(ctx) {
			var props = ctx.prop.props;

			if (!props.valueFormat) {
				props.valueFormat =
					DEFAULT_FORMATS[props.type] || DEFAULT_FORMATS["date"];
			}
		},
	};

	var name$1 = "hidden";
	var hidden = {
		name: name$1,
		maker: _defineProperty({}, name$1, function (field, value) {
			return creatorFactory(name$1)("", field, value);
		}),
		render: function render() {
			return [];
		},
	};

	var name$2 = "input";
	var input = {
		name: name$2,
		maker: (function () {
			var maker = ["password", "url", "email", "text", "textarea"].reduce(
				function (maker, type) {
					maker[type] = creatorFactory(name$2, {
						type: type,
					});
					return maker;
				},
				{}
			);
			maker.idate = creatorFactory(name$2, {
				type: "date",
			});
			return maker;
		})(),
		mergeProp: function mergeProp(ctx) {
			var props = ctx.prop.props;

			if (props && props.autosize && props.autosize.minRows) {
				props.rows = props.autosize.minRows || 2;
			}
		},
	};

	var name$3 = "slider";
	var slider = {
		name: name$3,
		maker: {
			sliderRange: creatorFactory(name$3, {
				range: true,
			}),
		},
		toFormValue: function toFormValue(value, ctx) {
			var isArr = Array.isArray(value),
				props = ctx.prop.props,
				min = props.min || 0,
				parseValue;

			if (props.range === true) {
				parseValue = isArr ? value : [min, parseFloat(value) || min];
			} else {
				parseValue = isArr
					? parseFloat(value[0]) || min
					: parseFloat(value);
			}

			return parseValue;
		},
	};

	var name$4 = "timePicker";
	var timePicker = {
		name: name$4,
		maker: {
			time: creatorFactory(name$4, function (m) {
				return (m.props.isRange = false);
			}),
			timeRange: creatorFactory(name$4, function (m) {
				return (m.props.isRange = true);
			}),
		},
		mergeProp: function mergeProp(ctx) {
			var props = ctx.prop.props;

			if (!props.valueFormat) {
				props.valueFormat = "HH:mm:ss";
			}
		},
	};

	var row = {
		name: "FcRow",
		render: function render(_, ctx) {
			return ctx.vNode.col(
				{
					props: {
						span: 24,
					},
				},
				[ctx.vNode.row(ctx.prop, _)]
			);
		},
	};

	var name$5 = "select";
	var select = {
		name: name$5,
		toFormValue: function toFormValue(value, ctx) {
			if (ctx.prop.props.multiple && !Array.isArray(value)) {
				return toArray(value);
			} else {
				return value;
			}
		},
	};

	var parsers = [datePicker, hidden, input, slider, timePicker, row, select];

	var PRE = "el";
	var alias = {
		button: PRE + "-button",
		icon: "i",
		slider: PRE + "-slider",
		rate: PRE + "-rate",
		upload: "fc-upload",
		cascader: PRE + "-cascader",
		popover: PRE + "-popover",
		tooltip: PRE + "-tooltip",
		colorPicker: PRE + "-colorPicker",
		timePicker: PRE + "-time-picker",
		timeSelect: PRE + "-time-select",
		datePicker: PRE + "-date-picker",
		switch: PRE + "-switch",
		select: "fc-select",
		checkbox: "fc-checkbox",
		radio: "fc-radio",
		inputNumber: PRE + "-input-number",
		number: PRE + "-input-number",
		input: PRE + "-input",
		formItem: PRE + "-form-item",
		form: PRE + "-form",
		frame: "fc-frame",
		col: PRE + "-col",
		row: PRE + "-row",
		tree: "fc-tree",
		autoComplete: PRE + "-autocomplete",
		auto: PRE + "-autocomplete",
		group: "fc-group",
		object: "fc-sub-form",
		subForm: "fc-sub-form",
	};

	function getConfig() {
		return {
			form: {
				inline: false,
				labelPosition: "right",
				labelWidth: "125px",
				disabled: false,
				size: undefined,
			},
			row: {
				show: true,
				gutter: 0,
			},
			submitBtn: {
				type: "primary",
				loading: false,
				disabled: false,
				innerText: "提交",
				show: true,
				col: undefined,
				click: undefined,
			},
			resetBtn: {
				type: "default",
				loading: false,
				disabled: false,
				icon: "el-icon-refresh",
				innerText: "重置",
				show: false,
				col: undefined,
				click: undefined,
			},
		};
	}

	function isTooltip(info) {
		return info.type === "tooltip";
	}

	function tidy(props, name) {
		if (!hasProperty(props, name)) return;

		if (is.String(props[name])) {
			var _props$name;

			props[name] =
				((_props$name = {}),
				_defineProperty(_props$name, name, props[name]),
				_defineProperty(_props$name, "show", true),
				_props$name);
		}
	}

	function isFalse(val) {
		return val === false;
	}

	function tidyBool(opt, name) {
		if (hasProperty(opt, name) && !is.Object(opt[name])) {
			opt[name] = {
				show: !!opt[name],
			};
		}
	}

	var manager = {
		validate: function validate(call) {
			return this.form().validate(call);
		},
		validateField: function validateField(field) {
			var _this = this;

			return new Promise(function (resolve, reject) {
				_this.form().validateField(field, function (res) {
					res ? reject(res) : resolve(null);
				});
			});
		},
		clearValidateState: function clearValidateState(ctx) {
			var fItem = this.vm.$refs[ctx.wrapRef];

			if (fItem) {
				fItem.validateMessage = "";
				fItem.validateState = "";
			}
		},
		tidyOptions: function tidyOptions(options) {
			["submitBtn", "resetBtn", "row", "info", "wrap", "col"].forEach(
				function (name) {
					tidyBool(options, name);
				}
			);
			return options;
		},
		tidyRule: function tidyRule(_ref) {
			var prop = _ref.prop;
			tidy(prop, "title");
			tidy(prop, "info");
			return prop;
		},
		mergeProp: function mergeProp(ctx) {
			ctx.prop = mergeProps(
				[
					{
						info: this.options.info || {},
						wrap: this.options.wrap || {},
						col: this.options.col || {},
					},
					ctx.prop,
				],
				{
					info: {
						trigger: "hover",
						placement: "top-start",
						icon: "el-icon-warning",
					},
					title: {},
					col: {
						span: 24,
					},
					wrap: {},
				},
				{
					normal: ["title", "info", "col", "wrap"],
				}
			);
		},
		getDefaultOptions: function getDefaultOptions() {
			return getConfig();
		},
		update: function update() {
			var form = this.options.form;
			this.rule = {
				props: _objectSpread2({}, form),
				on: {
					submit: function submit(e) {
						e.preventDefault();
					},
				},
				class: [form.className, form["class"], "form-create"],
				style: form.style,
				type: "form",
			};
		},
		beforeRender: function beforeRender() {
			var key = this.key,
				ref = this.ref,
				$handle = this.$handle;
			extend(this.rule, {
				key: key,
				ref: ref,
			});
			extend(this.rule.props, {
				model: $handle.formData,
			});
		},
		render: function render(children) {
			var _this2 = this;

			if (children.slotLen()) {
				children.setSlot(undefined, function () {
					return _this2.makeFormBtn();
				});
			}

			return this.$r(
				this.rule,
				isFalse(this.options.row.show)
					? children.getSlots()
					: [this.makeRow(children)]
			);
		},
		makeWrap: function makeWrap(ctx, children) {
			var _this3 = this;

			var rule = ctx.prop;
			var uni = "".concat(this.key).concat(ctx.key);
			var col = rule.col;
			var isTitle = this.isTitle(rule);
			var labelWidth = !col.labelWidth && !isTitle ? 0 : col.labelWidth;
			var _this$rule$props = this.rule.props,
				inline = _this$rule$props.inline,
				_col = _this$rule$props.col;
			var item = isFalse(rule.wrap.show)
				? children
				: this.$r(
						mergeProps([
							rule.wrap,
							{
								props: _objectSpread2(
									_objectSpread2(
										{
											labelWidth:
												labelWidth === void 0
													? labelWidth
													: toString(labelWidth),
										},
										rule.wrap || {}
									),
									{},
									{
										prop: ctx.field,
										rules: rule.validate,
									}
								),
								class: rule.className,
								key: "".concat(uni, "fi"),
								ref: ctx.wrapRef,
								type: "formItem",
							},
						]),
						_objectSpread2(
							{
								default: function _default() {
									return children;
								},
							},
							isTitle
								? {
										label: function label() {
											return _this3.makeInfo(rule, uni);
										},
								  }
								: {}
						)
				  );
			return inline === true || isFalse(_col) || isFalse(col.show)
				? item
				: this.makeCol(rule, uni, [item]);
		},
		isTitle: function isTitle(rule) {
			if (this.options.form.title === false) return false;
			var title = rule.title;
			return !((!title.title && !title["native"]) || isFalse(title.show));
		},
		makeInfo: function makeInfo(rule, uni) {
			var _this4 = this;

			var titleProp = rule.title;
			var infoProp = rule.info;
			var isTip = isTooltip(infoProp);
			var form = this.options.form;
			var children = [
				(titleProp.title || "") +
					(form.labelSuffix || form["label-suffix"] || ""),
			];

			var titleFn = function titleFn() {
				return _this4.$r(
					mergeProps([
						titleProp,
						{
							props: titleProp,
							key: "".concat(uni, "tit"),
							type: titleProp.type || "span",
						},
					]),
					children
				);
			};

			if (
				!isFalse(infoProp.show) &&
				(infoProp.info || infoProp["native"])
			) {
				if (infoProp.icon !== false) {
					children[infoProp.align !== "left" ? "unshift" : "push"](
						this.$r({
							type: "i",
							class:
								infoProp.icon === true
									? "el-icon-warning"
									: infoProp.icon,
							key: "".concat(uni, "i"),
						})
					);
				}

				var prop = {
					type: infoProp.type || "popover",
					props: _objectSpread2({}, infoProp),
					key: "".concat(uni, "pop"),
				};
				var field = "content";

				if (infoProp.info && !hasProperty(prop.props, field)) {
					prop.props[field] = infoProp.info;
				}

				return this.$r(
					mergeProps([infoProp, prop]),
					_defineProperty(
						{},
						titleProp.slot || (isTip ? "default" : "reference"),
						function () {
							return titleFn();
						}
					)
				);
			}

			return titleFn();
		},
		makeCol: function makeCol(rule, uni, children) {
			var col = rule.col;
			return this.$r(
				{
					class: col["class"],
					type: "col",
					props: col || {
						span: 24,
					},
					key: "".concat(uni, "col"),
				},
				children
			);
		},
		makeRow: function makeRow(children) {
			var row = this.options.row || {};
			return this.$r(
				{
					type: "row",
					props: row,
					class: row["class"],
					key: "".concat(this.key, "row"),
				},
				children
			);
		},
		makeFormBtn: function makeFormBtn() {
			var vn = [];

			if (!isFalse(this.options.submitBtn.show)) {
				vn.push(this.makeSubmitBtn());
			}

			if (!isFalse(this.options.resetBtn.show)) {
				vn.push(this.makeResetBtn());
			}

			if (!vn.length) {
				return;
			}

			var item = this.$r(
				{
					type: "formItem",
					key: "".concat(this.key, "fb"),
				},
				vn
			);
			return this.rule.props.inline === true
				? item
				: this.$r(
						{
							type: "col",
							props: {
								span: 24,
							},
							key: "".concat(this.key, "fc"),
						},
						[item]
				  );
		},
		makeResetBtn: function makeResetBtn() {
			var _this5 = this;
			var resetBtn = this.options.resetBtn;
			return this.$r(
				{
					type: "button",
					props: resetBtn,
					style: {
						width: resetBtn.width,
					},
					on: {
						click: function click() {
							var fApi = _this5.$handle.api;
							resetBtn.click
								? resetBtn.click(fApi)
								: fApi.resetFields();
						},
					},
					key: "".concat(this.key, "b2"),
				},
				[resetBtn.innerText]
			);
		},
		makeSubmitBtn: function makeSubmitBtn() {
			var _this6 = this;

			var submitBtn = this.options.submitBtn;
			return this.$r(
				{
					type: "button",
					props: submitBtn,
					style: {
						width: submitBtn.width,
					},
					on: {
						click: function click() {
							var fApi = _this6.$handle.api;
							submitBtn.click
								? submitBtn.click(fApi)
								: fApi.submit();
						},
					},
					key: "".concat(this.key, "b1"),
				},
				[submitBtn.innerText]
			);
		},
	};

	var maker = {};
	useAlias(maker);
	useSelect(maker);
	useTree(maker);
	useUpload(maker);
	useFrame(maker);

	function useAlias(maker) {
		[
			"group",
			"tree",
			"switch",
			"upload",
			"autoComplete",
			"checkbox",
			"cascader",
			"colorPicker",
			"datePicker",
			"frame",
			"inputNumber",
			"radio",
			"rate",
		].forEach(function (name) {
			maker[name] = creatorFactory(name);
		});
		maker.auto = maker.autoComplete;
		maker.number = maker.inputNumber;
		maker.color = maker.colorPicker;
	}

	function useSelect(maker) {
		var select = "select";
		var multiple = "multiple";
		maker["selectMultiple"] = creatorFactory(
			select,
			_defineProperty({}, multiple, true)
		);
		maker["selectOne"] = creatorFactory(
			select,
			_defineProperty({}, multiple, false)
		);
	}

	function useTree(maker) {
		var name = "tree";
		var types = {
			treeSelected: "selected",
			treeChecked: "checked",
		};
		Object.keys(types).reduce(function (m, key) {
			m[key] = creatorFactory(name, {
				type: types[key],
			});
			return m;
		}, maker);
	}

	function useUpload(maker) {
		var name = "upload";
		var types = {
			image: ["image", 0],
			file: ["file", 0],
			uploadFileOne: ["file", 1],
			uploadImageOne: ["image", 1],
		};
		Object.keys(types).reduce(function (m, key) {
			m[key] = creatorFactory(name, function (m) {
				return m.props({
					uploadType: types[key][0],
					maxLength: types[key][1],
				});
			});
			return m;
		}, maker);
		maker.uploadImage = maker.image;
		maker.uploadFile = maker.file;
	}

	function useFrame(maker) {
		var types = {
			frameInputs: ["input", 0],
			frameFiles: ["file", 0],
			frameImages: ["image", 0],
			frameInputOne: ["input", 1],
			frameFileOne: ["file", 1],
			frameImageOne: ["image", 1],
		};
		Object.keys(types).reduce(function (maker, key) {
			maker[key] = creatorFactory("frame", function (m) {
				return m.props({
					type: types[key][0],
					maxLength: types[key][1],
				});
			});
			return maker;
		}, maker);
		maker.frameInput = maker.frameInputs;
		maker.frameFile = maker.frameFiles;
		maker.frameImage = maker.frameImages;
		return maker;
	}

	var css_248z$2 =
		".form-create .form-create .el-form-item {\n    margin-bottom: 22px;\n}\n\n.form-create .form-create .el-form-item .el-form-item {\n    margin-bottom: 0px;\n}\n\n.form-create{\n    transform: rotateZ(0);\n}\n";
	styleInject(css_248z$2);

	function tidyBtnProp(btn, def) {
		if (is.Boolean(btn))
			btn = {
				show: btn,
			};
		else if (!is.Undef(btn) && !is.Object(btn))
			btn = {
				show: def,
			};
		return btn;
	}

	function extendApi(api, h) {
		extend(api, {
			validate: function validate(callback) {
				return new Promise(function (resolve, reject) {
					var forms = api.children;
					var all = [h.$manager.validate()];
					forms.forEach(function (v) {
						all.push(v.validate());
					});
					Promise.all(all)
						.then(function () {
							resolve(true);
							callback && callback(true);
						})
						["catch"](function (e) {
							reject(e);
							callback && callback(e);
						});
				});
			},
			validateField: function validateField(field, callback) {
				return new Promise(function (resolve, reject) {
					var sub = h.subForm[field] || [];
					var all = [h.$manager.validateField(field)];
					toArray(sub).forEach(function (v) {
						all.push(v.validate());
					});
					Promise.all(all)
						.then(function () {
							resolve(null);
							callback && callback(null);
						})
						["catch"](function (e) {
							reject(e);
							callback && callback(e);
						});
				});
			},
			clearValidateState: function clearValidateState(fields) {
				var _this = this;

				var clearSub =
					arguments.length > 1 && arguments[1] !== undefined
						? arguments[1]
						: true;
				api.helper.tidyFields(fields).forEach(function (field) {
					if (clearSub) _this.clearSubValidateState(field);
					var ctx = h.fieldCtx[field];
					if (!ctx) return;
					h.$manager.clearValidateState(ctx);
				});
			},
			clearSubValidateState: function clearSubValidateState(fields) {
				api.helper.tidyFields(fields).forEach(function (field) {
					var subForm = h.subForm[field];
					if (!subForm) return;

					if (Array.isArray(subForm)) {
						subForm.forEach(function (form) {
							form.clearValidateState();
						});
					} else if (subForm) {
						subForm.clearValidateState();
					}
				});
			},
			btn: {
				loading: function loading() {
					var _loading =
						arguments.length > 0 && arguments[0] !== undefined
							? arguments[0]
							: true;

					api.submitBtnProps({
						loading: !!_loading,
					});
				},
				disabled: function disabled() {
					var _disabled =
						arguments.length > 0 && arguments[0] !== undefined
							? arguments[0]
							: true;

					api.submitBtnProps({
						disabled: !!_disabled,
					});
				},
				show: function show() {
					var isShow =
						arguments.length > 0 && arguments[0] !== undefined
							? arguments[0]
							: true;
					api.submitBtnProps({
						show: !!isShow,
					});
				},
			},
			resetBtn: {
				loading: function loading() {
					var _loading2 =
						arguments.length > 0 && arguments[0] !== undefined
							? arguments[0]
							: true;

					api.resetBtnProps({
						loading: !!_loading2,
					});
				},
				disabled: function disabled() {
					var _disabled2 =
						arguments.length > 0 && arguments[0] !== undefined
							? arguments[0]
							: true;

					api.resetBtnProps({
						disabled: !!_disabled2,
					});
				},
				show: function show() {
					var isShow =
						arguments.length > 0 && arguments[0] !== undefined
							? arguments[0]
							: true;
					api.resetBtnProps({
						show: !!isShow,
					});
				},
			},
			submitBtnProps: function submitBtnProps() {
				var props =
					arguments.length > 0 && arguments[0] !== undefined
						? arguments[0]
						: {};
				var btn = tidyBtnProp(h.options.submitBtn, true);
				extend(btn, props);
				h.options.submitBtn = btn;
				api.refreshOptions();
			},
			resetBtnProps: function resetBtnProps() {
				var props =
					arguments.length > 0 && arguments[0] !== undefined
						? arguments[0]
						: {};
				var btn = tidyBtnProp(h.options.resetBtn, false);
				extend(btn, props);
				h.options.resetBtn = btn;
				api.refreshOptions();
			},
			submit: function submit(successFn, failFn) {
				var _arguments = arguments,
					_this2 = this;

				return new Promise(function (resolve, reject) {
					api.validate()
						.then(function () {
							var formData = api.formData();
							is.Function(successFn) &&
								invoke(function () {
									return successFn(formData, _this2);
								});
							is.Function(h.options.onSubmit) &&
								invoke(function () {
									return h.options.onSubmit(formData, _this2);
								});
							h.vm.$emit("submit", formData, _this2);
							resolve(formData);
						})
						["catch"](function () {
							is.Function(failFn) &&
								invoke(function () {
									return failFn.apply(
										void 0,
										[_this2].concat(
											_toConsumableArray(_arguments)
										)
									);
								});
							reject.apply(
								void 0,
								_toConsumableArray(_arguments)
							);
						});
				});
			},
		});
		return api;
	}

	function install(FormCreate) {
		FormCreate.componentAlias(alias);
		components.forEach(function (component) {
			FormCreate.component(component.name, component);
		});
		parsers.forEach(function (parser) {
			FormCreate.parser(parser);
		});
		Object.keys(maker).forEach(function (name) {
			FormCreate.maker[name] = maker[name];
		});
	}

	function appUse(app) {
		app.use(ElementPlus);
	}

	function elmFormCreate() {
		return FormCreateFactory({
			ui: '"element-ui"',
			version: '"2.5.9"',
			manager: manager,
			appUse: appUse,
			extendApi: extendApi,
			install: install,
			attrs: {
				normal: ["col", "wrap"],
				array: ["className"],
				key: ["title", "info"],
			},
		});
	}

	var FormCreate = elmFormCreate();

	var maker$1 = FormCreate.maker;

	exports.default = FormCreate;
	exports.maker = maker$1;

	Object.defineProperty(exports, "__esModule", { value: true });
});
