<template>
	<!-- <div v-if="pageLoading">
		<el-main>
			<el-card shadow="never">
				<el-skeleton :rows="1"></el-skeleton>
			</el-card>
			<el-card shadow="never" style="margin-top: 15px">
				<el-skeleton></el-skeleton>
			</el-card>
		</el-main>
	</div>
	<work v-if="dashboard == '1'" @on-mounted="onMounted"></work>
	<widgets v-else @on-mounted="onMounted"></widgets> -->
	<el-card shadow="hover" header="欢迎">
		<div class="welcome">
			<div class="logo">
				<img src="img/logo.png" />
				<h2>房产BBG线上订片系统</h2>
			</div>
		
			
		</div>
	</el-card>
</template>

<script>
import { defineAsyncComponent } from "vue";
const work = defineAsyncComponent(() => import("./work"));
const widgets = defineAsyncComponent(() => import("./widgets"));

export default {
	name: "dashboard",
	components: {
		work,
		widgets,
	},
	data() {
		return {
			pageLoading: true,
			dashboard: "0",
		};
	},
	created() {
	},
	mounted() {},
	methods: {
		onMounted() {
			this.pageLoading = false;
		},
	},
};
</script>

<style scoped>
.welcome {
}
.welcome .logo {
	text-align: center;
}
.welcome .logo img {
	vertical-align: bottom;
	width: 100px;
	height: 100px;
	margin-bottom: 20px;
}
.welcome .logo h2 {
	font-size: 30px;
	font-weight: normal;
	display: flex;
	align-items: center;
	justify-content: center;
}

.tips {
	margin-top: 20px;
	padding: 0 40px;
}
.tips-item {
	display: flex;
	align-items: center;
	justify-content: center;
	padding: 7.5px 0;
}
.tips-item-icon {
	width: 40px;
	height: 40px;
	display: flex;
	align-items: center;
	justify-content: center;
	border-radius: 50%;
	font-size: 18px;
	margin-right: 20px;
	color: var(--el-color-primary);
	background: rgba(180, 180, 180, 0.1);
}
.tips-item-message {
	flex: 1;
	font-size: 14px;
}

.actions {
	text-align: center;
	margin: 40px 0 20px 0;
}
</style>

