<template>
	<el-main>
		<el-card shadow="never">
			<el-tabs v-model="currentId" tab-position="top">

				<el-tab-pane
					v-for="(item,index) in headerList"
					:name="item.id.toString()"
					:key="index"
					:label="item.name"
					>
						<formCreate class="ConfigformCreate" v-if="rules.length!==0" :option="option" :rule="rules" />
				</el-tab-pane>

			</el-tabs>
		</el-card>
	</el-main>
</template>

<script>
import {extend} from "@/utils/common";

export default {
	name: "system",
	data() {
		return {
			sys: {
				name: "SCUI",
				logoUrl: "",
				login: true,
				passwordRules: "^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{6,20}$",
				copyright: "@SCUI",
			},
			msg: {
				open: true,
				appKey: "",
				secretKey: "",
			},
			rules:[

			],
			option:[],
			currentId:0,
			headerList:[],
			setting: [
				{
					key: "file_serve",
					value: "https://file.scui.com",
					category: "url",
					title: "文件服务器地址",
				},
				{
					key: "cloud_url",
					value: "-",
					category: "url",
					title: "客户端地址",
				},
				{
					key: "crm_url",
					value: "-",
					category: "url",
					title: "CRM地址",
				},
				{
					key: "autoSwitch",
					value: true,
					category: "user",
					title: "自动判断boolean类型",
				},
			],
		};
	},
	watch:{
		currentId(val){

			this.$nextTick(res=>{
				this.changeTab();
			})

		}
	},
	mounted() {
		this.getConfig();
	},
	methods: {
		getConfig(){
			this.$HTTP().post('/admin/config_classify/lists').then(res=>{
				this.headerList = res;
				this.currentId = this.collect(res).first().id.toString();
			});
		},
		changeTab(){
			this.$HTTP().post('/admin/config_classify/create_form',{tab_id:this.currentId}).then(res=>{
				this.option = extend({'size':'large'},res.config)
				this.rules = res.rule
				this.title = res.title
				this.FromData = res
			});
		},
		table_add() {
			var newRow = {
				key: "",
				value: "",
				title: "",
				isSet: true,
			};
			this.setting.push(newRow);
		},
		table_edit(row) {
			if (row.isSet) {
				row.isSet = false;
			} else {
				row.isSet = true;
			}
		},
		table_del(row, index) {
			this.setting.splice(index, 1);
		},
	},
};
</script>

<style scoped>
.ConfigformCreate{
	margin-top: 20px;
}
</style>
