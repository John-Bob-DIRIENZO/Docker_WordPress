<?php


class BannerMessage
{
    const OPTION_GROUP = "wphetic_group";
    const SETTING_SECTION = "wphetic_section";
    const MENU_PAGE_NAME = 'wphetic_header_banner';
    const BANNER_MESSAGE = 'custom_header_banner';
    const BANNER_ACTIVE = 'wphetic_banner_active';
    const BANNER_DATE = 'wphetic_banner_date';

    public static function init()
    {
        add_action('admin_menu', [self::class, 'addMenu']);
        add_action('admin_init', [self::class, 'registerSetting']);
        add_action('admin_enqueue_scripts', [self::class, 'flatpickr']);
    }

    public static function flatpickr($suffix)
    {
        if ($suffix === 'toplevel_page_wphetic_header_banner') {
            wp_enqueue_style(
                'distant_flatpickr_css',
                'https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css'
            );

            wp_enqueue_script(
                'distant_flatpickr_js',
                'https://cdn.jsdelivr.net/npm/flatpickr',
                [],
                false,
                true
            );

            wp_enqueue_script(
                'wphetic_admin_js',
                get_template_directory_uri() . '/assets/js/admin.js',
                ['distant_flatpickr_js'],
                false,
                true
            );
        }
    }

    public static function registerSetting()
    {
        /**
         * Textarea
         */
        register_setting(
            self::OPTION_GROUP,
            self::BANNER_MESSAGE,
            ['default' => 'Il ne se passe rien de spécial ici']
        );

        add_settings_section(
            self::SETTING_SECTION,
            'Paramètres',
            function () {
                echo 'Modifiez la bannière d\'information dans le header';
            },
            self::MENU_PAGE_NAME
        );

        add_settings_field(
            'wphetic_banner_message',
            'Message de la bannière',
            function () {
                ?>
                <textarea
                        name=<?= self::BANNER_MESSAGE; ?>><?= esc_html(get_option(self::BANNER_MESSAGE)); ?></textarea>
                <?php
            },
            self::MENU_PAGE_NAME,
            self::SETTING_SECTION
        );

        /**
         * Checkbox : Active
         */
        register_setting(
            self::OPTION_GROUP,
            self::BANNER_ACTIVE,
            ['default' => false]
        );

        add_settings_field(
            'wphetic_activate_header_banner',
            'Afficher la bannière ?',
            function () {
                ?>
                <input type="checkbox" name="<?= self::BANNER_ACTIVE; ?>"
                       value="true" <?php checked(get_option(self::BANNER_ACTIVE), 'true'); ?>>
                <?php
            },
            self::MENU_PAGE_NAME,
            self::SETTING_SECTION
        );

        /**
         * Date
         */
        register_setting(
            self::OPTION_GROUP,
            self::BANNER_DATE
        );

        add_settings_field(
            'wphetic_date_banner',
            'Une belle date ?',
            function () {
                ?>
                <input type="date" name="<?= self::BANNER_DATE; ?>"
                       value=" <?= get_option(self::BANNER_DATE); ?>" class="wphetic_datepickr">
                <?php
            },
            self::MENU_PAGE_NAME,
            self::SETTING_SECTION
        );
    }

    public static function addMenu()
    {
        add_menu_page(
            'Ajouter une bannière dans le header',
            'Header Banner',
            'manage_options',
            self::MENU_PAGE_NAME,
            [self::class, 'wphetic_render_header_banner'],
            'dashicons-info',
            80
        );
    }

    public static function wphetic_render_header_banner()
    {
        ?>
        <div class="wrap">
            <h1><?= get_admin_page_title(); ?></h1>
            <form action="options.php" method="post">
                <?php settings_fields(self::OPTION_GROUP); ?>
                <?php do_settings_sections(self::MENU_PAGE_NAME); ?>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }
}