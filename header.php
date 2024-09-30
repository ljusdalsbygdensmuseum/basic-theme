<?php 
// HEADER
?>
<!DOCTYPE html>
<html>
    <head>
        <?php wp_head(); ?> 
        <title><?php bloginfo( 'name' ); wp_title( $sep = '&bull;'); ?></title>   
    </head>
    <body>
        <header>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-3">
                        <h1><a href="<?php echo esc_url(site_url())?>"><strong>LOGO</strong>text</a></h1>
                    </div>
                    <div class="col-sm-9">
                        <ul class="main-menu button-menu">
                            <?php if (is_user_logged_in()) {
                                ?>
                                    <li><a href="<?php echo esc_url(site_url( '/notes')) ?>"><button>Notes</button></a></li>
                                    <li><a href="<?php echo esc_url(wp_logout_url()) ?>"><button>Log out</button></a></li>
                                <?php
                            } else{
                                ?>
                                    <li><a href="<?php echo esc_url(wp_login_url()) ?>"><button>Log in</button></a></li>
                                    <li><a href="<?php echo esc_url(wp_registration_url()) ?>"><button>Sign up</button></a></li>
                                <?php
                            } ?>
                            <li id="search_btn_container"><a href="<?php echo esc_url(site_url('/search'))?>"><button id="search-open">Search</button></a></li>
                        </ul>
                        <?php

                        wp_nav_menu(array(
                            'theme_location' => 'mainMenu',
                            'menu_class'    => 'main-menu',
                            'container' => false,
                        ));

                        /* Depricated
                            $endbuttons = '<menu>
                                    <li><button>login</button></li>
                                    <li><button>sign up</button></li>
                                    <li><button>search</button></li>
                                </menu>';
                            print_base_menu(array(
                                'end' => $endbuttons,
                                'navClass' => 'justify-content-end',
                                'menu'=> 'mainMenu', 
                            ));
                        */
                        ?>
                    </div>
                </div>
            </div>
        </header>
        <?php
        
        ?>
