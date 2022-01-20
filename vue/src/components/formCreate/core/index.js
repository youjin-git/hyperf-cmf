import extend from "@/utils/lib/extend";

import formCreate from '../formCreate';

import $FormCreate from "@/components/formCreate/components/formCreate";

import is from "@/utils/lib/typs";

export default function formCreateFactory(config){

	function componentAlias(alias) {
        // CreateNode.use(alias);
    }

	function component(id,component){
		
	}
	
	function $form() {
		return $FormCreate(formCreate);
	}

	if(config.install){
		if(is.Function(config.install)){
			config.install(formCreate)
		}
	}
	
	function useAttr(formCreate) {
		extend(formCreate,{
			componentAlias
		})
	}


	// useAttr(create);

	return extend({
		$form
	})
}
