// (function (global, factory) {
//     factory(exports, require('vue'), require('element-plus'))
// }(this,function(exports,vue,ElementPlus){

var vue = require('vue');
var ElementPlus = require('element-plus');
var {ElInput} = require('element-plus')
import {FormCreate} from './formCreate';
import {_objectSpread2} from './common';
function $FormCreate(FormCreate) {
    var NAME$8 = 'FormCreate';
    return vue.defineComponent({
        name:NAME$8,
		components:{
        	...ElementPlus
		},
        props: {
            rule:{
                type: Array,
                required: true
            },
            option: {
                type: Object,
                "default": function _default() {
                    return {};
                }
            },
			modelValue:  {
				type: Object,
				"default": function _default() {
					return {};
				}
			},
			getApi:{
				type:Function
			},
            api: Object
        },
		emits: ['update:api', 'update:modelValue', 'mounted','getApi','submit'],
        render:function(){
            return this.fc.$handleRender();
        },
        setup:function setup(props) {
            var vm = vue.getCurrentInstance();
            let { rule,modelValue } = vue.toRefs(props);
            var fc = new FormCreate(vm)
            var data = vue.reactive({
                destroyed: false,
                isShow: true,
                unique: 1,
                renderRule: (rule.value || []),
                updateValue: JSON.stringify(modelValue)
            });
            fc.install();
			var fapi = fc.api();

            return _objectSpread2({
				fc:vue.markRaw(fc),
				fapi: vue.markRaw(fapi)
            },vue.toRefs(data),{},{
                refresh: function refresh() {
                    ++data.unique;
                },
            })
        },
		created: function created() {
			var vm = vue.getCurrentInstance();
			vm.setupState.fc.init(); //4928
			// _this.$emit('itemMounted', api);
			vm.emit('getApi', vm.setupState.fc);
		}
    })
}


export default $FormCreate(FormCreate)
