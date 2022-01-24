<template>
	<el-container>
		<el-header>
			<div class="left-panel">
				<el-button
					icon="el-icon-plus"
					type="primary"
					@click="onAdd()"
				>
					新增任务
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
					label="ID"
					prop="id"
					width="150"
				></el-table-column>
				<el-table-column
					label="准考证"
					prop="ticket"
					width="150"
				></el-table-column>
				<el-table-column
					label="省份"
					prop="tables_name"
					width="150"
				></el-table-column>
				<el-table-column
					label="姓名"
					prop="username"
					width="150"
				></el-table-column>
				<el-table-column
					label="手机号"
					prop="mobile"
					width="150"
				></el-table-column>
				<el-table-column
					label="成绩"
					prop="score"
					width="150"
				></el-table-column>
				<el-table-column
					label="来源"
					prop="tables_name"
					width="150"
				></el-table-column>
				<el-table-column
					label="状态"
					prop="status"
					width="150"
				></el-table-column>
				<el-table-column
					label="操作"
					fixed="right"
					align="right"
					width="100"
				>
					<template #default="scope">
						<el-button type="text" @click="onEdit(scope.row)">
							编辑
						</el-button>
					</template>
				</el-table-column>
			</yjTable>
		</el-main>
	</el-container>

</template>
<script>

	export default {
		name: "setting:code",
		components: {
		},
		data() {
			return {
				formParams: {},
				apiObj: this.$HTTP().url("/admin/task/lists"),
				tableData: [],
				queryParams: {
					table_name: undefined,
				},
				selection: [],
			};
		},
		async created() {},
		methods: {
			onAdd(){
				this.$modalForm(
					this.$HTTP().post("/admin/task/form")
				).then((res) => {});
			},
			onEdit({id}){
				this.$modalForm(
					this.$HTTP().params({id}).post("/admin/task/form")
				).then((res) => {});
			},
			success(res){
				console.log(res);
				this.$HTTP().post('/import/school',res).then(res=>{

				});
			},
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
