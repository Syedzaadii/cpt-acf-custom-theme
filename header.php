<!DOCTYPE html>
<html <?php language_attributes(); ?>>

    <head>
        <meta charset="<?php echo bloginfo('charset'); ?>" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <?php wp_head(); ?>
    </head>

    <body <?php echo body_class(); ?>>
        <header class="site-header">
            <div class="container">
                <h1 class="school-logo-text float-left">
                    <a href="<?php echo esc_url(site_url('/')); ?>"><strong>Fictional</strong> University</a>
                </h1>
                <a class="js-search-trigger site-header__search-trigger" href="<?php echo site_url('/search'); ?>"><i
                        class="fa fa-search" aria-hidden="true"></i></a>
                <i class="site-header__menu-trigger fa fa-bars" aria-hidden="true"></i>
                <div class="site-header__menu group">
                    <nav class="main-navigation">
                        <?php
                    wp_nav_menu([
                        'theme_location' => 'primary'
                    ]);
                    ?>
                    </nav>
                    <div class="site-header__util">
                        <?php
                    if (is_user_logged_in()) { ?>
                        <a href="<?php echo site_url('/my-notes'); ?>"
                            class="btn btn--small btn--orange float-left push-right">My Notes</a>
                        <a href="<?php echo wp_logout_url();; ?>" class="btn btn--small btn--dark-orange float-left
                            btn--with-photo">
                            <span
                                class="site-header__avatar"><?php echo get_avatar(get_current_user_id(), 60); ?></span>
                            <span class="btn__text">Logout</span>
                        </a>
                        <?php } else { ?>
                        <a href="<?php echo wp_login_url(); ?>"
                            class="btn btn--small btn--orange float-left push-right">Login</a>
                        <a href="<?php echo wp_registration_url();; ?>"
                            class="btn btn--small btn--dark-orange float-left">Sign
                            Up</a>
                        <?php }
                    ?>

                        <a class="search-trigger js-search-trigger" href="<?php echo site_url('/search'); ?>"><i
                                class="fa fa-search" aria-hidden="true"></i></a>
                    </div>
                </div>
            </div>
        </header>