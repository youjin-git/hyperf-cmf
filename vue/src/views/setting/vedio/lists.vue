<template>
	<el-container>
		<el-header>
			<div class="left-panel">
				<el-button
					type="primary"
					@click="onAdd()"
				>
					添加
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
				
				<el-table-column label="图片" width="60">
				<template #default="scope">
					<el-avatar :src="scope.row.image_path.full_path" size="small" @error="() => true">
					<i class="el-icon-s-custom" />
					</el-avatar>
				</template>
				</el-table-column>

				<el-table-column
					label="名称"
					prop="title"
					width="150"
				></el-table-column>

				<el-table-column
					label="链接"
					prop="link"
					width="150"
				></el-table-column>

				<el-table-column
					label="操作"
					fixed="right"
					align="right"
					width="100"
				>
					<template #default="scope">
						<el-button type="text" @click="onEdit(scope.row, scope.$index)">
							编辑
						</el-button>
						<el-button type="text" @click="handleDelete(scope.row, scope.$index)">
							删除
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
				apiObj: this.$HTTP().url("/admin/vedio/lists"),
				tableData: [],
				selection: [],
			};
		},
		async created() {},
		methods: {
			delete(){

			},
			onEdit({id}){
				this.$modalForm(
					this.$HTTP().params({id}).post("/admin/vedio/create")
				).then((res) => {
					 this.$refs.yjTable.upData(this.formParams)
				});
			},
			onAdd(){
				this.$modalForm(
					this.$HTTP().post("/admin/vedio/create")
				).then((res) => {
					 this.$refs.yjTable.upData(this.formParams)
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
			handleDelete({id}) {
				console.log();
				this.$confirm("此操作会将数据物理删除？", "提示", {
					confirmButtonText: "确定",
					cancelButtonText: "取消",
					type: "warning",
				}).then(() => {
					this.$HTTP().params({id}).post('admin/banner/delete').then((res) => {
						 this.$refs.table.upData(this.queryParams)
						this.$message.success("操作成功")
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
