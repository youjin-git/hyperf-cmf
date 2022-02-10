<template>
	<div class="login_bg">
		<div
			class="login_adv"
			style="background-image: url(img/auth_banner.jpg)"
		>
			<div class="login_adv__title">
				<h2>后台管理系统</h2>
				<h4>{{ $t("login.slogan") }}</h4>
				<p>{{ $t("login.describe") }}</p>
				<div>
					<span><i class="sc-icon-vuejs-fill"></i></span>
					<span><i class="add el-icon-plus"></i></span>
					<span><i class="el-icon-platform-eleme"></i></span>
				</div>
			</div>
			<div class="login_adv__bottom">
				© {{ $CONFIG.APP_NAME }} {{ $CONFIG.APP_VER }}
			</div>
		</div>
		<div class="login_main">
			<div class="login_config">
				<!-- <el-button
					:icon="
						config.theme == 'dark'
							? 'el-icon-sunny'
							: 'el-icon-moon'
					"
					circle
					type="info"
					@click="configTheme"
				></el-button> -->
		
			</div>
			<div class="login-form">
				<div class="login-header">
					<div class="logo">
						<img :alt="$CONFIG.APP_NAME" src="img/logo.png" />
						<label>{{ $CONFIG.APP_NAME }}</label>
					</div>
					<h2>{{ $t("login.signInTitle") }}</h2>
				</div>
				<el-form
					ref="loginForm"
					:model="FormParams"
					:rules="rules"
					label-width="0"
					size="large"
				>
					<el-form-item prop="username">
						<el-input
							v-model="FormParams.username"
							prefix-icon="el-icon-user"
							clearable
							:placeholder="$t('login.userPlaceholder')"
						>
							<template #append>
								<el-select
									v-model="userType"
									style="width: 130px"
								>
									<el-option
										:label="$t('login.admin')"
										value="admin"
									></el-option>
									<el-option
										:label="$t('login.user')"
										value="user"
									></el-option>
								</el-select>
							</template>
						</el-input>
					</el-form-item>

					<el-form-item prop="password">
						<el-input
							v-model="FormParams.password"
							prefix-icon="el-icon-lock"
							clearable
							show-password
							:placeholder="$t('login.PWPlaceholder')"
						></el-input>
					</el-form-item>
					<el-form-item prop="code" class="captcha">
						<div class="captcha">
							<el-input
								v-model="FormParams.code"
								prefix-icon="el-icon-message"
								placeholder="验证码"
							>
								<template #append>
									<el-image
										class="captchaImg"
										:src="captchatImg"
										@click="getCaptcha()"
									></el-image>
								</template>
							</el-input>
						</div>
					</el-form-item>
					<el-form-item style="margin-bottom: 10px">
						<el-row>
							<el-col :span="12">
								<el-checkbox
									:label="$t('login.rememberMe')"
									v-model="FormParams.autologin"
								></el-checkbox>
							</el-col>
							<el-col :span="12" style="text-align: right">
								<el-button type="text"
									>{{
										$t("login.forgetPassword")
									}}？</el-button
								>
							</el-col>
						</el-row>
					</el-form-item>
					<el-form-item>
						<el-button
							type="primary"
							style="width: 100%"
							:loading="islogin"
							round
							@click="login"
							>{{ $t("login.signIn") }}</el-button
						>
					</el-form-item>
				</el-form>

				<!-- <el-divider>{{ $t("login.signInOther") }}</el-divider> -->

				<!-- <div class="login-oauth">
					<el-button
						size="small"
						type="success"
						icon="sc-icon-wechat-fill"
						circle
					></el-button>
					<el-button
						size="small"
						type="primary"
						icon="el-icon-platform-eleme"
						circle
					></el-button>
					<el-button
						size="small"
						type="success"
						icon="el-icon-s-goods"
						circle
					></el-button>
					<el-button
						size="small"
						type="info"
						icon="el-icon-s-promotion"
						circle
					></el-button>
					<el-button
						size="small"
						type="warning"
						icon="el-icon-menu"
						circle
					></el-button>
				</div> -->
			</div>
		</div>
	</div>
</template>

<script>
export default {
	data() {
		return {
			userType: "admin",
			FormParams: {
				user: "admin",
				password: "admin",
				autologin: false,
			},

			config: {
				lang: this.$TOOL.data.get("APP_LANG") || this.$CONFIG.LANG,
				theme: this.$TOOL.data.get("APP_THEME") || "default",
			},
			lang: [
				{
					name: "简体中文",
					value: "zh-cn",
				},
				{
					name: "English",
					value: "en",
				},
				{
					name: "日本語",
					value: "ja",
				},
			],
		};
	},
	watch: {
		userType(val) {
			if (val == "admin") {
				this.FormParams.user = "admin";
				this.FormParams.password = "admin";
			} else if (val == "user") {
				this.FormParams.user = "user";
				this.FormParams.password = "user";
			}
		},
		"config.theme"(val) {
			document.body.setAttribute("data-theme", val);
			this.$TOOL.data.set("APP_THEME", val);
		},
		"config.lang"(val) {
			this.$i18n.locale = val;
			this.$TOOL.data.set("APP_LANG", val);
		},
	},
	created: function () {
		console.log(this);
		this.$TOOL.data.remove("TOKEN");
		this.$TOOL.data.remove("USER_INFO");
		this.$TOOL.data.remove("MENU");
		this.$TOOL.data.remove("PERMISSIONS");
		this.$TOOL.data.remove("grid");
		this.$store.commit("clearViewTags");
		this.$store.commit("clearKeepLive");
		this.$store.commit("clearIframeList");
		console.log(
			"%c SCUI %c Gitee: https://gitee.com/lolicode/scui",
			"background:#666;color:#fff;border-radius:3px;",
			""
		);
	},
	methods: {
		async login1() {
			var validate = await this.$refs.loginForm
				.validate()
				.catch(() => {});
			if (!validate) {
				return false;
			}

			this.islogin = true;
			var data = {
				username: this.FormParams.user,
				password: this.$TOOL.crypto.MD5(this.FormParams.password),
			};
			//获取token
			var user = await this.$API.auth.token.post(data);
			if (user.code == 200) {
				this.$TOOL.data.set("TOKEN", user.data.token);
				this.$TOOL.data.set("USER_INFO", user.data.userInfo);
			} else {
				this.islogin = false;
				this.$message.warning(user.message);
				return false;
			}
			//获取菜单
			var menu = null;
			if (this.FormParams.user == "admin") {
				menu = await this.$API.system.menu.myMenus.get();
			} else {
				menu = await this.$API.demo.menu.get();
			}
			if (menu.code == 200) {
				if (menu.data.menu.length == 0) {
					this.islogin = false;
					this.$alert(
						"当前用户无任何菜单权限，请联系系统管理员",
						"无权限访问",
						{
							type: "error",
							center: true,
						}
					);
					return false;
				}
				this.$TOOL.data.set("MENU", menu.data.menu);
				this.$TOOL.data.set("PERMISSIONS", menu.data.permissions);
			} else {
				this.islogin = false;
				this.$message.warning(menu.message);
				return false;
			}

			this.$router.replace({
				path: "/",
			});
			this.$message.success("Login Success 登录成功");
			this.islogin = false;
		},
		configTheme() {
			this.config.theme =
				this.config.theme == "default" ? "dark" : "default";
		},
		configLang(command) {
			this.config.lang = command.value;
		},
	},
};
</script>
<script setup>
import { ref, getCurrentInstance, onMounted, reactive } from "vue";
import { useRouter } from "vue-router";
const router = useRouter();

import { captchatImg, getCaptcha } from "./loginCaptcha";
import request from "@/utils/request";
const { proxy, ctx } = getCurrentInstance();
const FormParams = reactive({
	username: "admin",
	password: "123456",
	key: "",
	code: "",
});
const rules = reactive({
	username: [
		{
			required: true,
			message: "请输入用户名",
			trigger: "blur",
		},
	],
	password: [
		{
			required: true,
			message: "请输入密码",
			trigger: "blur",
		},
	],
});

const islogin = ref(false);



const login = async () => {
	islogin.value = true;
	var validate = await proxy.$refs.loginForm.validate().catch(() => {});
	if (!validate) {
		return false;
	}
	//获取token
	var user = await request().params(FormParams).post('/admin/login/login')
		.then(loginSuccess)
		.finally(() => {
			islogin.value = false;
		});
};
const loginSuccess = (data) => {
	proxy.$TOOL.data.set("TOKEN", data.token);
	router.replace({
		path: "/",
	});
};

onMounted(() => {
	getCaptcha().then((data) => {
		FormParams.key = data.key;
		FormParams.code = data.code;
	});
});
</script>

<style scoped>
.login_bg {
	width: 100%;
	height: 100%;
	background: #fff;
	display: flex;
}
.login_adv {
	width: 33.33333%;
	background-color: #555;
	background-size: cover;
	background-position: center center;
	background-repeat: no-repeat;
	position: relative;
}
.login_adv__title {
	color: #fff;
	padding: 40px;
}
.login_adv__title h2 {
	font-size: 40px;
}
.login_adv__title h4 {
	font-size: 18px;
	margin-top: 10px;
	font-weight: normal;
}
.login_adv__title p {
	font-size: 14px;
	margin-top: 10px;
	line-height: 1.8;
	color: rgba(255, 255, 255, 0.6);
}
.login_adv__title div {
	margin-top: 10px;
	display: flex;
	align-items: center;
}
.login_adv__title div span {
	margin-right: 15px;
}
.login_adv__title div i {
	font-size: 40px;
}
.login_adv__title div i.add {
	font-size: 20px;
	color: rgba(255, 255, 255, 0.6);
}
.login_adv__bottom {
	position: absolute;
	left: 0px;
	right: 0px;
	bottom: 0px;
	color: #fff;
	padding: 40px;
	background-image: linear-gradient(transparent, #000);
}

.login_main {
	flex: 1;
	overflow: auto;
	display: flex;
}
.login-form {
	width: 400px;
	margin: auto;
	padding: 20px 0;
}
.login-header {
	margin-bottom: 20px;
}
.login-header .logo {
	display: flex;
	align-items: center;
}
.login-header .logo img {
	width: 35px;
	height: 35px;
	vertical-align: bottom;
	margin-right: 10px;
}
.login-header .logo label {
	font-size: 24px;
}
.login-header h2 {
	font-size: 24px;
	font-weight: bold;
	margin-top: 50px;
}
.login-oauth {
	display: flex;
	justify-content: space-around;
}
.login-form .el-divider {
	margin-top: 40px;
}

.login_config {
	position: absolute;
	top: 20px;
	right: 20px;
}
.el-dropdown-menu__item.selected {
	color: var(--el-color-primary);
}

@media (max-width: 1200px) {
	.login-form {
		width: 340px;
	}
}
@media (max-width: 1000px) {
	.login_main {
		display: block;
	}
	.login-form {
		width: 100%;
		padding: 20px 40px;
	}
	.login_adv {
		display: none;
	}
}
:deep(.el-input-group__append) {
	padding: 0;
}
.captchaImg {
	width: 130px;
	height: 39px;
	vertical-align: top;
	margin-top: -1px;
}
</style>
