import config from "@/config";
import Layout from '@/layout'

//系统路由
const routes = [
	{
		name: "layout",
		path: "/",
		component: () => import(/* webpackChunkName: "layout" */ "@/layout"),
		redirect: config.DASHBOARD_URL || "/dashboard",
		children: [],
	},
	{
		path: "/login",
		component: () =>
			import(/* webpackChunkName: "login" */ "@/views/userCenter/login"),
		meta: {
			title: "登录",
		},
	},
	{
		path: "/test",
		component: () =>
			import(/* webpackChunkName: "login" */ "@/views/test/index"),
		meta: {
			title: "登录",
		},
	},
];

export default routes;
