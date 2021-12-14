<template>
	<Codemirror
		v-model:value="code"
		:options="cmOptions"
		border
		placeholder="test placeholder"
		:height="200"
		@change="change"
	/>

	<!--  <el-dialog-->
	<!--    title="预览代码"-->
	<!--    v-model="dialogVisible"-->
	<!--    width="1220px"-->
	<!--    :before-close="handleDialogClose"-->
	<!--  >-->
	<!--    <el-tabs v-model="activeName">-->

	<!--      <el-tab-pane-->
	<!--        v-for="(item, index) in previewCode"-->
	<!--        :key="index"-->
	<!--        :label="item.tab_name"-->
	<!--        :name="item.name"-->
	<!--      >-->
	<!--        <el-alert-->
	<!--          title="说明"-->
	<!--          type="info"-->
	<!--          :closable="false"-->
	<!--          v-if="item.name === 'model'"-->
	<!--          description="模型类预览的代码与实际生成的代码会稍微不同"-->
	<!--          style="margin-bottom: 10px;"-->
	<!--        ></el-alert>-->
	<!--        <ma-highlight :code="item.code" :lang="item.lang" />-->

	<!--      </el-tab-pane>-->

	<!--    </el-tabs>-->
	<!--  </el-dialog>-->
</template>
<script>
import maHighlight from "@/components/maHighlight";
export default {
	components: {
		maHighlight,
	},

	data() {
		return {
			code: "const a = 10",
			cmOptions: {
				// codemirror options
				tabSize: 4,
				mode: "text/javascript",
				theme: "base16-dark",
				lineNumbers: true,
				line: true,
				// more codemirror options, 更多 codemirror 的高级配置...
			},
			// modal
			dialogVisible: false,
			// 激活tab
			activeName: "controller",
			// 要预览的代码
			previewCode: [],
		};
	},

	methods: {
		// 显示modal
		async show(id) {
			this.activeName = "controller";
			await this.$API.generate.preview({ id }).then((res) => {
				if (res.success) {
					this.previewCode = res.data;
					this.dialogVisible = true;
				}
			});
		},

		// 表字段modal关闭
		handleDialogClose() {
			this.dialogVisible = false;
		},
	},
};
</script>
