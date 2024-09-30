<form action="<?php echo esc_url(site_url('/'))?>" method="get"><!-- action grabs the url and it shoud be sanitized all urls should be -->
    <label for="main-search">Search:</label>
    <input type="search" name="s" id="main-search">
    <input type="submit" value="Search">
</form>