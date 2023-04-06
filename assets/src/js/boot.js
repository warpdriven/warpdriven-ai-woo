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
    GET_USER_EXSITED:{
        ajax_url:"/wp-admin/admin-ajax.php?action=wgc_get_user_exsited",
        method:"GET"
    },
    CREATE_USER:{
        ajax_url:"/wp-admin/admin-ajax.php?action=wgc_create_erp_user",
        method:"POST",
        contentType: "application/json"
    },
    CREATE_MY_WEBSITE: {
        ajax_url: "/wp-admin/admin-ajax.php?action=wgc_create_my_website",
        method: "POST",
        contentType: "application/json"
    },
    WRITER_GPT:{
        ajax_url:"/wp-admin/admin-ajax.php?action=wgc_gpt",
        method:"POST",
        contentType: "application/json"
    },
    SAVE_PRODUCT:{
        ajax_url:"/wp-admin/admin-ajax.php?action=wgc_save_product",
        method:"POST",
        contentType: "application/json"
    },
    SAVE_POST:{
        ajax_url:"/wp-admin/admin-ajax.php?action=wgc_save_post",
        method:"POST",
        contentType: "application/json"
    }
})
import ElementPlus from 'element-plus';
import 'element-plus/theme-chalk/index.css';
let app = createApp({}).use(ElementPlus).component('gpt-page',GptPage).component("gpt-setting-page",GptSettingPage).mount('#gpt-app')