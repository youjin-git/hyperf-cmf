import http from "@/utils/request";

export default async function getFilePath(id){
		if(!id){
			return '';
		}
		return await http().params({id}).post('util/file/get_path');
}
