import extend from "@/utils/lib/extend";
var vue = require("vue");
import {
	_isSlot,
	is,
	copy,
	deepExtend,
	getSlot,
	deepCopy,
	hasProperty,
	mergeProps,
	$set,
	_defineProperty,
	_objectSpread2,
	upper,
	_toConsumableArray,
	toLine,
	uniqueId,
	toCase,
	lower,
	toProps,
} from "../../common";


function isNativeTag(tag) {
	return (
		vue.getCurrentInstance().appContext.config.isNativeTag(tag) ||
		["span"].findIndex((v) => {
			return v == tag;
		}) >= 0
	);
}


export function  CreateNodeFactory() {
	var aliasMap = {};
	var CreateNode = function () {};
	extend(CreateNode.prototype, {
		make: function (tag, data, children) {
			return this.h(tag, toProps(data), children);
		},
		h: function h(tag, data, children) {
			return vue.createVNode(
				isNativeTag(tag) ? tag : vue.resolveComponent(tag),
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
				var line = toLine(k); //button
				var lower = toString(k).toLocaleLowerCase(); //button
				var v = nodes[k]; //el-button

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
