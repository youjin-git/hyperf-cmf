<template>
    <div style="padding:20px;">
		<formCreate class="ConfigformCreate" v-if="rules.length!==0" :option="option" :rule="rules" />
        <button @click="changeRules1">111111</button> <button @click="changeRules2">2222222</button>
    </div>
</template>

<script>
import {extend} from "@/utils/common";
export default {
	name: "system",
	data() {
		return {
			rules: [{
                "type": "input",
                "field": "ip_limit",
                "value": "",
                "title": "w1w1",
                "info": "我我我",
                "props": {
                    "type": "text",
                    "placeholder": "我我1"
                }
             }],
             	rules1: [{
                "type": "input",
                "field": "ip_limit",
                "value": "",
                "title": "w1w1112",
                "info": "我我我11",
                "props": {
                    "type": "text",
                    "placeholder": "我我2"
                }
             }],
             	rules2: [{
                "type": "input",
                "field": "ip_limit",
                "value": "",
                "title": "w1w122",
                "info": "我我我22",
                "props": {
                    "type": "text",
                    "placeholder": "我我3"
                }
             }],
            option:{

            },
        }
    },
    methods: {
        changeRules1(){
            this.rules = this.rules1;
        },
        changeRules2(){
            this.rules = this.rules2;
        }
    }
}
</script>