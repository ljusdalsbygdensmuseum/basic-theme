<?php 
// FOOTER
?>
        <footer class="footer">
            <div class="container">
                <div class="row justify-content-between">
                    <div class="col">
                        <h1><strong>LOGO</strong>text</h1>
                        <p>0651-24192</p>
                    </div>
                    <div class="col">
                        <h2>Explore</h2>
                        <?php
                            wp_nav_menu(array(
                                'theme_location' => 'footerMenu',
                                'container' => false,
                            ));
                        ?>
                    </div>
                    <div class="col">
                        <h2>connect with us</h2>
                        <nav>
                            <ul>
                                <li>IG</li>
                                <li>YT</li>
                                <li>FB</li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </footer>
        <?php wp_footer(); ?>
    </body>
</html>