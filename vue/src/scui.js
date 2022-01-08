import config from "./config";
import api from "./api";
import tool from "./utils/tool";
import http from "@/utils/request";
import { permission, rolePermission } from "./utils/permission";
import scTable from "./components/scTable";
import yjTable from "./components/yjTable";
import yjUpload from "./components/yjUpload";
import scFilterBar from "./components/scFilterBar";
import scUpload from "./components/scUpload";
import scUploadMultiple from "./components/scUpload/multiple";
import scFormTable from "./components/scFormTable";
import scTableSelect from "./components/scTableSelect";
import scPageHeader from "./components/scPageHeader";
import scSelect from "./components/scSelect";
import scDialog from "./components/scDialog";
import scForm from "./components/scForm";
import scTitle from "./components/scTitle";
import auth from "./directives/auth";
import role from "./directives/role";
import time from "./directives/time";
import copy from "./directives/copy";
import errorHandler from "./utils/errorHandler";
import modalForm from "@/libs/modal-form";

export default {
	install(app) {
		//挂载全局对象
		app.config.globalProperties.$CONFIG = config;
		app.config.globalProperties.$TOOL = tool;
		app.config.globalProperties.$HTTP = function(){
			return http.reflush();
		}
		app.config.globalProperties.$modalForm = modalForm(app);

		app.config.globalProperties.$API = api;
		app.config.globalProperties.$AUTH = permission;
		app.config.globalProperties.$ROLE = rolePermission;

		//注册全局组件

		app.component("scTable", scTable);

		app.component("yjTable", yjTable);
		app.component("scFilterBar", scFilterBar);
		app.component("scUpload", scUpload);
		app.component("yjUpload", yjUpload);
		app.component("scUploadMultiple", scUploadMultiple);
		app.component("scFormTable", scFormTable);
		app.component("scTableSelect", scTableSelect);
		app.component("scPageHeader", scPageHeader);
		app.component("scSelect", scSelect);
		app.component("scDialog", scDialog);
		app.component("scForm", scForm);
		app.component("scTitle", scTitle);

		//注册全局指令
		app.directive("auth", auth);
		app.directive("role", role);
		app.directive("time", time);
		app.directive("copy", copy);

		//全局代码错误捕捉
		// app.config.errorHandler = errorHandler;
	},
};
