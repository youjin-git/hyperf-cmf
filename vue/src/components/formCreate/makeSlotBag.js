import {
	extend,
	_objectSpread2,
	upper,
	_toConsumableArray,
	toLine,
	is,
} from "./common";
var vue = require("vue");

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
						children.push.apply(children, _toConsumableArray(res));
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

export { makeSlotBag };
