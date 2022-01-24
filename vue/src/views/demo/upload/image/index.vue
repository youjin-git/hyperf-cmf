<template>
	<el-main>
		<el-card shadow="never" header="上传文件">
			<yj-upload
				v-model="imgurl3"
				:apiObj="uploadApi"
				accept=".xls,.xlsx"
				:on-success="handleSuccess"
			>
				<el-button
					icon="el-icon-plus"
					type="primary"
				>导入学校数据
				</el-button>
			</yj-upload>
			<div v-if="successData">
				<el-alert :title="successData" type="success" />
			</div>

		</el-card>
	</el-main>

</template>

<script>
	export default {
		name:'upload:image',
		data () {
			return {
				successData:"",
			}
		},
		methods: {
			handleSuccess(data) {
				console.log(data);
				this.successData = data
			}
		}
	}
</script>
