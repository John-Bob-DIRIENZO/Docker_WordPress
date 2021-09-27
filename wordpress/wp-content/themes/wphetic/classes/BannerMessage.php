<?php


class BannerMessage
{
    const OPTION_GROUP = "wphetic_group";
    const SETTING_SECTION = "wphetic_section";
    const MENU_PAGE_NAME = 'wphetic_header_banner';
    const BANNER_MESSAGE = 'custom_header_banner';
    const BANNER_ACTIVE = 'wphetic_banner_active';

    public static function init()
    {
        add_action('admin_menu', [self::class, 'addMenu']);
        add_action('admin_init', [self::class, 'registerSetting']);
    }

    public static function registerSetting()
    {
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
                <textarea name=<?= self::BANNER_MESSAGE; ?>><?= get_option(self::BANNER_MESSAGE); ?></textarea>
                <?php
            },
            self::MENU_PAGE_NAME,
            self::SETTING_SECTION
        );

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