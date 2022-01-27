import http from "@/utils/request";

export default async function getFilePath(id){
		console.log(id);
		if(!id){
			return '';
		}
		return await http.reflush().post('util/file/get_path',{id});

}
