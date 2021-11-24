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
			modelValue: Object,
        },
        data() {
            return {
            }
        },
        render:function(){
            return this.fc.$handleRender();
        },
        setup:function setup(props) {
            var vm = vue.getCurrentInstance();
            let { rule,modelValue } = vue.toRefs(props);

            var fc = new FormCreate(vm)
            fc.install();
			console.log('fc',fc);

            return _objectSpread2({
				fc:fc
            })
        },
        beforeCreate:function () {
            this.fc.init();
        }
    })
}


export default $FormCreate(FormCreate)
