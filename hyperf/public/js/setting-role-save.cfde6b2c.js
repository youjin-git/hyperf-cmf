(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["setting-role-save"],{"2a626":function(e,t,n){"use strict";n.r(t);var r=n("7a23");function i(e,t,n,i,o,a){var s=Object(r["resolveComponent"])("form-create"),u=Object(r["resolveComponent"])("el-dialog");return Object(r["openBlock"])(),Object(r["createBlock"])(u,{title:o.titleMap[o.mode],modelValue:o.visible,"onUpdate:modelValue":t[1]||(t[1]=function(e){return o.visible=e}),width:500,"destroy-on-close":"",onClosed:t[2]||(t[2]=function(t){return e.$emit("closed")})},{default:Object(r["withCtx"])((function(){return[Object(r["createVNode"])(s,{modelValue:o.fapi,"onUpdate:modelValue":t[0]||(t[0]=function(e){return o.fapi=e}),rule:o.rule,option:o.option,onSubmit:e.onSubmit},null,8,["modelValue","rule","option","onSubmit"])]})),_:1},8,["title","modelValue"])}var o=n("1da1"),a=(n("4e82"),n("96cf"),{emits:["success","closed"],data:function(){return{fapi:null,rule:[{type:"input",field:"4hs5srgn1vy3",title:"输入框",info:"",_fc_drag_tag:"input",hidden:!1,display:!0},{type:"textarea",field:"4hs5srgn1vy3",title:"输入框11",info:"",_fc_drag_tag:"input",hidden:!1,display:!0}],option:{},mode:"add",titleMap:{add:"新增",edit:"编辑",show:"查看"},visible:!1,isSaveing:!1,form:{id:"",label:"",alias:"",sort:1,parentId:""},groups:[],groupsProps:{value:"id",emitPath:!1,checkStrictly:!0}}},mounted:function(){this.getGroup()},methods:{open:function(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:"add";return this.mode=e,this.visible=!0,this},getGroup:function(){var e=this;return Object(o["a"])(regeneratorRuntime.mark((function t(){var n;return regeneratorRuntime.wrap((function(t){while(1)switch(t.prev=t.next){case 0:return t.next=2,e.$API.system.role.list.get();case 2:n=t.sent,e.groups=n.data;case 4:case"end":return t.stop()}}),t)})))()},submit:function(){var e=this;this.$refs.dialogForm.validate(function(){var t=Object(o["a"])(regeneratorRuntime.mark((function t(n){var r;return regeneratorRuntime.wrap((function(t){while(1)switch(t.prev=t.next){case 0:if(!n){t.next=7;break}return e.isSaveing=!0,t.next=4,e.$API.demo.post.post(e.form);case 4:r=t.sent,e.isSaveing=!1,200==r.code?(e.$emit("success",e.form,e.mode),e.visible=!1,e.$message.success("操作成功")):e.$alert(r.message,"提示",{type:"error"});case 7:case"end":return t.stop()}}),t)})));return function(e){return t.apply(this,arguments)}}())},setData:function(e){this.form.id=e.id,this.form.label=e.label,this.form.alias=e.alias,this.form.sort=e.sort,this.form.parentId=e.parentId}}}),s=n("6b0d"),u=n.n(s);const l=u()(a,[["render",i]]);t["default"]=l}}]);