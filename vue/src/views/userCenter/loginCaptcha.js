
import { ref,reactive } from 'vue'
import http from "@/utils/request"

const captchatImg = ref("");
const getCaptcha = async ()=>{
	return await http.get('/util/captcha/get').then((data) => {
		captchatImg.value = data.image
		return Promise.resolve(data);
	})
}

export  {
	captchatImg,
	getCaptcha
};
