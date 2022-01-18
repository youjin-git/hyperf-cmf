import {defineComponent,getCurrentInstance,toRefs,reactive,markRaw} from "vue";
var ElementPlus = require("element-plus");

const NAME = 'FormCreate';

export  default  function $FormCreate(FormCreate) {
	return defineComponent({
		name: NAME,
		components: {
			...ElementPlus,
		},
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
			vm.ctx.fc.init();
			vm.emit("getApi", vm.ctx.fc);
		},
	});
}


