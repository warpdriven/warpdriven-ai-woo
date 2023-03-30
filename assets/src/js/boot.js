import { createApp } from 'vue'

import GptPage from '../../../node_modules/wd-vs-woo-gpt-vue/src/components/warp-driven/warp-driven-gpt-page.vue';
import GptSettingPage from '../../../node_modules/wd-vs-woo-gpt-vue/src/components/warp-driven/warp-driven-gpt-setting-page.vue';
import {config} from '../../../node_modules/wd-vs-woo-gpt-vue/src/api/api-setting'
config({
    GET_PRODUCT_CATEGORIES:{
        ajax_url:"/wp-admin/admin-ajax.php",
        action:"wgc_get_woo_product_categories",
        method:"POST",
        contentType: "application/x-www-form-urlencoded"
    },
    WD_QUERY_PRODUCT_PAGE:{
        ajax_url:"/wp-admin/admin-ajax.php",
        action:"wgc_query_product_page",
        method:"POST",
        contentType: "application/x-www-form-urlencoded"
    },
    WRITER_GPT:{
        ajax_url:"/wp-admin/admin-ajax.php?action=wgc_gpt",
        method:"POST",
        contentType: "application/json"
    }
})
import ElementPlus from 'element-plus';
import 'element-plus/theme-chalk/index.css';
let app = createApp({}).use(ElementPlus).component('gpt-page',GptPage).component("gpt-setting-page",GptSettingPage).mount('#gpt-app')