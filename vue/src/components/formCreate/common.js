function $set(target, field, value) {
	target[field] = value;
}
function $del(target, field) {
	delete target[field];
}
function hasProperty(rule, k) {
	return Object.hasOwnProperty.call(rule, k);
}

function ownKeys(object, enumerableOnly) {
	var keys = Object.keys(object);

	if (Object.getOwnPropertySymbols) {
		var symbols = Object.getOwnPropertySymbols(object);
		if (enumerableOnly)
			symbols = symbols.filter(function (sym) {
				return Object.getOwnPropertyDescriptor(object, sym).enumerable;
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

function _typeof(obj) {
	"@babel/helpers - typeof";

	if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") {
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

var _extends =
	Object.assign ||
	function (a) {
		for (var b, c = 1; c < arguments.length; c++) {
			for (var d in ((b = arguments[c]), b)) {
				Object.prototype.hasOwnProperty.call(b, d) && $set(a, d, b[d]);
			}
		}

		return a;
	};

var id = 0;
function uniqueId() {
	return (
		Math.random().toString(36).substr(3, 3) +
		Number("".concat(Date.now()).concat(++id)).toString(36)
	);
}
function extend() {
	return _extends.apply(this, arguments);
}

function getSlot(slots, exclude) {
	return Object.keys(slots).reduce(function (lst, name) {
		if (!exclude || exclude.indexOf(name) === -1) {
			lst.push(slots[name]);
		}

		return lst;
	}, []);
}

function toLine(name) {
	var line = name.replace(/([A-Z])/g, "-$1").toLocaleLowerCase();
	if (line.indexOf("-") === 0) line = line.substr(1);
	return line;
}
function upper(str) {
	return str.replace(str[0], str[0].toLocaleUpperCase());
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

var is = {
	type: function type(arg, _type) {
		return Object.prototype.toString.call(arg) === "[object " + _type + "]";
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
["Date", "Object", "Function", "String", "Boolean", "Array", "Number"].forEach(
	function (t) {
		is[t] = function (arg) {
			return is.type(arg, t);
		};
	}
);

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
	if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n))
		return _arrayLikeToArray(o, minLen);
}

function _nonIterableSpread() {
	throw new TypeError(
		"Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."
	);
}
function lower(str) {
	return str.replace(str[0], str[0].toLowerCase());
}

function toCase(str) {
	var to = str.replace(/(-[a-z])/g, function (v) {
		return v.replace("-", "").toLocaleUpperCase();
	});
	return lower(to);
}
function _arrayLikeToArray(arr, len) {
	if (len == null || len > arr.length) len = arr.length;

	for (var i = 0, arr2 = new Array(len); i < len; i++) arr2[i] = arr[i];

	return arr2;
}

var normalMerge = ["props"];
var toArrayMerge = ["class", "style", "directives"];
var functionalMerge = ["on"];

var mergeFn = function mergeFn(fn1, fn2) {
	return function () {
		fn1 && fn1.apply(this, arguments);
		fn2 && fn2.apply(this, arguments);
	};
};

var mergeProps = function mergeProps(objects) {
	var initial =
		arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};
	var opt =
		arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {};

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
					a[key] = _objectSpread2(_objectSpread2({}, a[key]), b[key]);
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
							a[key][hook] = mergeFn(a[key][hook], b[key][hook]);
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
function deepExtend(origin) {
	var target =
		arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};
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

function copy(value) {
	return deepCopy(value);
}

function deepCopy(value) {
	return deepExtend(
		{},
		{
			value: value,
		}
	).value;
}
export {
	copy,
	$set,
	deepExtend,
	getSlot,
	deepCopy,
	hasProperty,
	lower,
	toCase,
	uniqueId,
	is,
	extend,
	toLine,
	upper,
	_objectSpread2,
	_defineProperty,
	mergeProps,
	_toConsumableArray,
};
