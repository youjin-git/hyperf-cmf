import extend from "@/utils/lib/extend";

import formCreate from '../formCreate';

import $FormCreate from "@/components/formCreate/components/formCreate";

import is from "@/utils/lib/typs";

export default function formCreateFactory(config){


	function $form() {
		return $FormCreate(formCreate);
	}

	if(config.install){
		if(is.Function(config.install)){
			config.install(formCreate)
		}
	}

	return extend({
		$form
	})
}
