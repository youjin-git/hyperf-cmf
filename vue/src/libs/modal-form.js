import http from "@/utils/request";
import {
	ref,
	reactive,
	h,
	render,
	createVNode,
	resolveComponent,
	defineComponent,
	extend,
	component,
} from "vue";

import { toProps } from "./common.js";
import formCreateFactory from "@/components/formCreate/core";

let fApi;
let unique = 1;

const uniqueId = () => ++unique;

// import formCreate from '../components/formCreate/index'


var formCreateGlobalOptions = {
	form:{
		labelWidth:	'120px',
		size:"small",
	},
	submitBtn: {
		show: false,
	},
	upload:{
		props: {
			onSuccess(res, file) {
				if (res.status === 200) {
					console.log(file);
					file.url = res.data.src;
				} else {
					this.$Message.error(res.msg);
				}
			},
		},
	}
};


export default function modalForm(app) {

	return function (formRequestPromise, config = {}) {

		return new Promise((resolve, reject) => {
			formRequestPromise
				.then((data) => {
					// console.log(data.config);
					 data.config = Object.assign(data.config,formCreateGlobalOptions);


					// Object.assign(formCreateglobalOptions,)

					// if (!data.config.form) data.config.form = {};
					// if (!data.config.formData) data.config.formData = {};
					// data.config.formData = {
					// 	...data.config.formData,
					// 	...config.formData,
					// };
					// data.config.form.labelWidth =
					// 	data.config.form.labelWidth || "120px";
					console.log('options:',toProps({
						props: {
							rule: data.rule,
							option:data.config,
						},
						on: {
							getApi: (e) => {
								fApi = e;
							},
						},
					}));

					const formCreateMessage = h(
						app._context.components.formCreate,
						toProps({
							props: {
								rule: data.rule,
								option:data.config,
							},
							on: {
								getApi: (e) => {
									fApi = e;
								},
							},
						})
					);

					this.$msgbox({
						buttonSize:'mini',
						dangerouslyUseHTMLString: true,
						title: data.title,
						customClass: config.class || "modal-form",
						message: formCreateMessage,
						beforeClose: (action, instance, done) => {
							if (action === "confirm") {
								console.log(fApi);
								instance.confirmButtonLoading = true;
								fApi.submit((formData) => {
									http().params(formData)[data.method.toLowerCase()](
										data.action
									).then((res) => {
											this.$message.success(
												res.message || "提交成功"
											);
											done();
											resolve(res);
										})
										.finally(() => {
											instance.confirmButtonLoading = false;
										});
								});
								instance.confirmButtonLoading = false;
							} else {
								instance.confirmButtonLoading = false;
								done();
							}
							// fApi.asubmit();
							// if (action === 'confirm') {
							// 	instance.confirmButtonLoading = true
							// 	fApi.submit((formData) => {
							// 		http[data.method.toLowerCase()](data.action, formData).then((res) => {
							// 			done()
							// 			this.$message.success(res.message || '提交成功')
							// 			resolve(res)
							// 		}).catch(err => {
							// 			this.$message.error(err.message || '提交失败')
							// 			reject(err)
							// 		}).finally(() => {
							// 			instance.confirmButtonLoading = false
							// 		})
							// 	}, () => (instance.confirmButtonLoading = false))
							// } else {
							// 	instance.confirmButtonLoading = false
							// 	done()
							// }
						},
					});
				})
				.catch((e) => {
					this.$message.error(e.message);
				});
		});
	};
}
