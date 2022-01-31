<template>
	<section class="aminui-wrapper">
		<div v-if="!ismobile" class="aminui-side-split">
			<div class="adminui-side-split-scroll">
				<el-scrollbar>
					<ul>
						<li
							v-for="item in menu"
							:key="item"
							:class="pmenu.name == item.name ? 'active' : ''"
							@click="showMenu(item)"
						>
							<i :class="item.meta.icon || 'el-icon-menu'"></i>
							<p>{{ item.meta.title }}</p>
						</li>
					</ul>
				</el-scrollbar>
			</div>
		</div>
		<div
			v-if="!ismobile"
			:class="menuIsCollapse ? 'aminui-side isCollapse' : 'aminui-side'"
		>
			<div v-if="!menuIsCollapse" class="adminui-side-top">
				<h2>{{ pmenu.meta.title }}</h2>
			</div>
			<div class="adminui-side-scroll">
				<el-scrollbar>
					{{active}}
					<el-menu
						:default-active="active"
						router
						:collapse="menuIsCollapse"
					>
						<NavMenu :navMenus="nextMenu"></NavMenu>
					</el-menu>
				</el-scrollbar>
			</div>
		</div>
		<Side-m v-if="ismobile"></Side-m>
		<div class="aminui-body el-container">
			<Topbar>
				<userbar></userbar>
			</Topbar>
			<Tags v-if="!ismobile && layoutTags"></Tags>
			<div class="adminui-main" id="adminui-main">
				<router-view v-slot="{ Component }">
					<keep-alive
						:include="this.$store.state.keepAlive.keepLiveRoute"
					>
						<component
							:is="Component"
							:key="$route.fullPath"
							v-if="$store.state.keepAlive.routeShow"
						/>
					</keep-alive>
				</router-view>
				<iframe-view></iframe-view>
			</div>
		</div>
	</section>
	<div class="layout-setting" @click="openSetting">
		<i class="el-icon-brush"></i>
	</div>
	<el-drawer
		title="布局实时演示"
		v-model="settingDialog"
		:size="400"
		append-to-body
		destroy-on-close
	>
		<setting></setting>
	</el-drawer>
</template>

<script>
import SideM from "./components/sideM.vue";
import Topbar from "./components/topbar.vue";
import Tags from "./components/tags.vue";
import NavMenu from "./components/NavMenu.vue";
import userbar from "./components/userbar.vue";
import setting from "./components/setting.vue";
import iframeView from "./components/iframeView.vue";
import Store from "@/store";
export default {
	name: "index",
	components: {
		SideM,
		Topbar,
		Tags,
		NavMenu,
		userbar,
		setting,
		iframeView,
	},
	data() {
		return {
			settingDialog: false,
			menu: [],
			nextMenu: [],
			pmenu: {
				meta: {},
			},
			active: "",
		};
	},
	computed: {
		ismobile() {
			return this.$store.state.global.ismobile;
		},
		layout() {
			return this.$store.state.global.layout;
		},
		layoutTags() {
			return this.$store.state.global.layoutTags;
		},
		menuIsCollapse() {
			return this.$store.state.global.menuIsCollapse;
		},
	},
	async created() {
		this.onLayoutResize();
		window.addEventListener("resize", this.onLayoutResize);
		// var menu = this.$TOOL.data.get("MENU");
		let menu = await Store.dispatch("getMenus");
		this.menu = this.filterUrl(menu);
		this.showThis();
	},
	watch: {
		$route() {
			this.showThis();
		},
		layout: {
			handler(val) {
				document.body.setAttribute("data-layout", val);
			},
			immediate: true,
		},
		active(val,path) {
			console.log(this.$route);
			this.$router.push({
				path: val
			});
		},
	},
	methods: {
		openSetting() {
			this.settingDialog = true;
		},
		onLayoutResize() {
			const clientWidth = document.body.clientWidth;
			if (clientWidth < 992) {
				this.$store.commit("SET_ismobile", false);
			} else {
				this.$store.commit("SET_ismobile", false);
			}
		},
		//路由监听高亮
		showThis() {
			this.pmenu = this.$route.meta.breadcrumb
				? this.$route.meta.breadcrumb[0]
				: {};

			this.nextMenu = this.filterUrl(this.pmenu.children);
			this.$nextTick(() => {
				this.active = this.$route.meta.active || this.$route.fullPath;
			});

		},
		//点击显示
		showMenu(route) {
			console.log(route);
			this.pmenu = route;
			this.nextMenu = this.filterUrl(route.children);
			if((!route.children || route.children.length == 0) && route.component){
				this.$router.push({path: route.path})
			}

			this.getRouteFirstChild(route);
		},
		getRouteFirstChild(route) {
			console.log('getRouteFirstChild',route.name,this.pmenu);

			if (route.children) {
				this.$nextTick(() => {
					this.active = route.children[0].path;
				});
			}
		},
		//转换外部链接的路由
		filterUrl(map) {
			var newMap = [];
			map &&
				map.forEach((item) => {
					console.log('filterUrl',item);
					item.meta = item.meta ? item.meta : {};
					//处理隐藏
					if (item.meta.hidden) {
						return false;
					}
					//处理http
					// if (item.meta.type == "iframe") {
					// 	item.path = `/i/${item.name}`;
					// }
					//递归循环
					if (item.children && item.children.length > 0) {
						item.children = this.filterUrl(item.children);
					}
					newMap.push(item);
				});
						console.log('filterUrl',newMap);
			return newMap;
		},
	},
};
</script>
