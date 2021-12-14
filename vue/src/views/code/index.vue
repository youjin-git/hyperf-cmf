<template>
		<el-container>
			<el-header>
				<div class="left-panel">
					<el-button
						type="primary"
						icon="el-icon-download"
						@click="handleGenCodes"
						>生成代码
					</el-button>
				</div>
			</el-header>
			<el-main class="nopadding">
				<yjTable
					ref="yjTable"
					:data="tableData"
					:apiObj="apiObj"
					:params="formParams"
				>
					<el-table-column type="selection" width="50"></el-table-column>
					<el-table-column
						label="表名"
						prop="tables_name"
						width="150"
					></el-table-column>
					<el-table-column
						label="操作"
						fixed="right"
						align="right"
						width="130"
					>
						<template #default>
							<el-button type="text" @click="preview">
								预览
							</el-button>
						</template>
					</el-table-column>
				</yjTable>
			</el-main>
		</el-container>

	<preview ref="preview" />
</template>
<script>
// import editForm from './edit'
// import tableList from './table'
import preview from "./preview";

export default {
	name: "setting:code",
	components: {
		// tableList,
		// editForm,
		preview,
	},
	data() {
		return {
			formParams: { id: 1 },
			apiObj: this.$HTTP.url("/admin/code/code/lists"),
			tableData: [],
			queryParams: {
				table_name: undefined,
			},
			selection: [],
		};
	},
	async created() {},
	methods: {

		//表格选择后回调事件
		selectionChange(selection) {
			this.selection = selection;
		},

		// 装载数据表后处理方法
		confirm() {
			this.handleSuccess();
		},

		// 批量生成
		async handleGenCodes() {
			console.log(this.$refs.preview);
			// let ids = this.selection.map((item) => item.id);
			// this.$message.info("代码生成下载中，请稍后");
			// this.generateCode(ids);
		},

		// 生成代码
		async generateCode(id) {
			this.$message.info("代码生成下载中，请稍后");
			await this.$API.generate.generate(id).then((res) => {
				if (res.message && !res.success) {
					this.$message.error(res.message);
				} else {
					this.$TOOL.download(res);
					this.$message.success("代码生成成功");
				}
			});
		},

		// 批量删除
		async handleDeleteBatch() {
			if (this.selection.length > 0) {
				let ids = this.selection.map((item) => item.id);
				await this.handleDelete(ids.join(","));
			} else {
				this.$message.error("请选择要删除的项");
			}
		},

		// 删除
		handleDelete(id) {
			this.$confirm("此操作会将数据物理删除？", "提示", {
				confirmButtonText: "确定",
				cancelButtonText: "取消",
				type: "warning",
			}).then(() => {
				this.$API.generate.deletes(id).then((res) => {
					this.$message.success(res.message);
					this.handleSuccess();
				});
			});
		},

		// 同步数据表
		handleSync(id) {
			this.$confirm(
				"此操作会导致字段设置信息丢失，确定同步吗？",
				"提示",
				{
					confirmButtonText: "确定",
					cancelButtonText: "取消",
					type: "warning",
				}
			).then(() => {
				this.$API.generate.sync(id).then((res) => {
					res.success && this.$message.success(res.message);
				});
			});
		},

		//搜索
		handlerSearch() {
			this.handleSuccess();
		},

		resetSearch() {
			this.queryParams = {
				table_name: undefined,
			};
			this.handleSuccess();
		},

		//本地更新数据
		handleSuccess() {
			this.$refs.table.upData(this.queryParams);
		},
	},
};
</script>

<style></style>
