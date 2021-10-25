import config from "@/config";
import http from "@/utils/request"
import tool from '@/utils/tool'
export default {
	state: {
		list:[],
	},
	mutations: {
		SET_MENU_LIST(state, value){
			state.list = value
		},
	},
	actions:{
		async getMenus({commit,state}) {
				await  http.post('/admin/menu/lists').then(data => {
					commit('SET_MENU_LIST', data)
					tool.data.set("MENU",data);
				})
				return state.list;
		},
	}
}
