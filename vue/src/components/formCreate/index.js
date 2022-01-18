import formCreateFactory from "@/components/formCreate/core";

import formCreate from './formCreate';
import alias from "./core/alias";

function install(FormCreate){

	//重命名组建
	FormCreate.componentAlias(alias);

	//自定义组件
	// components.forEach(component => {
    //     FormCreate.component(component.name, component);
    // });
	
	

}


export default formCreateFactory({
	install
});
