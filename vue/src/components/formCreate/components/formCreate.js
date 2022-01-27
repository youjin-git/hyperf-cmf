import {defineComponent,getCurrentInstance,toRefs,reactive,markRaw,watch} from "vue";



var ElementPlus = require("element-plus");

const NAME = 'FormCreate';

export  default  function $FormCreate(FormCreate,app) {
	// console.log('app',app._context.components,ElementPlus);

	return defineComponent({
		name: NAME,
		components: app._context.components,
		props: {
			rule: {
				type: Array,
				required: true,
				default:() => [],
			},
			option: {
				type: Object,
				default: () => ({}),
			},
			modelValue: {
				type: Object,
				default:()=>({}),
			},
			getApi: {
				type: Function,
			},
			api: Object,
		},
		emits: [
			"update:api",
			"update:modelValue",
			"mounted",
			"getApi",
			"submit",
		],
		render: function () {
			return this.fc.$handleRender();
		},
		setup(props) {
			const vm = getCurrentInstance();

			let { rule, modelValue } = toRefs(props);

			
			const data = reactive({
				destroyed: false,
				isShow: true,
				unique: 1,
				renderRule: rule.value || [],
				updateValue: JSON.stringify(modelValue),
			});



			const fc = new FormCreate(vm);
			fc.install();
			const fapi = fc.api();

			watch( () => [...rule.value],function(n){
					fc.reloadRule(rule.value);
			})

			return {
				fc: markRaw(fc),
				fapi: markRaw(fapi),
				...toRefs(data),
				refresh: function refresh() {
					++data.unique;
				},
			}
		},
		created: function created() {
			const vm = getCurrentInstance();
			console.log(vm.ctx.fc.init);
			vm.ctx.fc.init();
			vm.emit("getApi", vm.ctx.fc);
		},
	});
}



