<?php

namespace WarpDrivenWGCCore;
class  WGCSettingPage
{


    public $fields;

    public function __construct()
    {
        add_action('admin_menu', array($this, 'add_page'));
        add_action('admin_init', array($this, 'setting_init'));
        $this->fields = array(
            array("name" => "wgc_api_key", "label" => __("API Key", 'wd-wgc-woo'))
        );
    }

    public function setting_init()
    {

        register_setting(
            'wgc',
            'wgc_api_key'
        );

        $this->add_settings_fields($this->fields);

    }

    public function add_settings_fields($fields)
    {
        foreach ($fields as $field) {
            register_setting(
                'wgc',
                $field['name']
            );
        }
    }

    public function section_callback()
    {
        echo '<p>' . '</p>';
    }

    public function add_page()
    {
        $hookname = add_submenu_page(
            'warpdriven-gpt-copywriting',
            __('WarpDriven GPT Copywriting Setting', 'warpdriven-gpt-copywriting'),
            __('Setting', 'warpdriven-gpt-copywriting'),
            'manage_options',
            'warpdriven-gpt-copywriting-setting',
            array($this, 'page_html'),
            80
        );

        add_action('load-' . $hookname, array($this, 'submit'));
    }

    public function page_html()
    {
        if (!current_user_can('manage_options')) {
            return;
        }
        if (isset($_GET['settings-updated'])) {
            add_settings_error('warpdriven-gpt-copywriting-setting', 'warpdriven-gpt-copywriting-setting', __('Settings Saved', 'wd-wgc-woo'), 'updated');
        }
        settings_errors('warpdriven-gpt-copywriting-setting');
        ?>
        <div id="gpt-app" class="wrap">
            <?php
            $data = array();
            foreach ($this->fields as $field) {
                $value = get_option($field['name']);
                $data[$field['name']] = isset($value) ? esc_attr($value) : '';
            }
          
            ?>
            <!-- <gpt-setting-page  :action="'options.php'" :data="{'wgc_api_key':'<?php echo esc_attr($data['wgc_api_key']); ?>'}">
                <template #footer>
                    <?php
                        // settings_fields('wgc');
                        // do_settings_sections('warpdriven-gpt-copywriting-setting');
                        // submit_button(__('Save Settings', 'wd-wgc-woo'));
                    ?>
                </template>
            </gpt-setting-page> -->
            <gpt-setting-page :erp-user-email="'<?php echo esc_html(get_option('admin_email')); ?>'" />
        </div>
        <?php
    }

    public function submit()
    {

    }
}