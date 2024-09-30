<?php
function print_base_menu($args = array()){
    // Parsing arguments over the default values
    $default = array(
        'menu'          =>  '',
        'navClass'      =>  '',
        'customClass'   =>  '',
        'subMenu'       =>  TRUE,//-------------------------not implemented
        'begItem'       =>  '',
        'endItem'       =>  '',
        'begLink'       =>  '',
        'endLink'       =>  '',
        'beg'           =>  '',
        'end'           =>  '',
 
    );
    $args = wp_parse_args($args, $default);

    // Check if there are menus
    $location = get_nav_menu_locations();
    if (empty($location)) {
        echo 'No menus found';
        return;
    }

    // Add default menu case no menu found and assign ID
    if (!isset($location[$args['menu']])) {
        $allMenus = wp_get_nav_menus();
        foreach ($allMenus as $repMenu) {
            $repMenuItems = wp_get_nav_menu_items($repMenu->term_id);
            if ($repMenuItems) {
                $menuID = $repMenu->term_id;
                break;
            }
        }
    }else {
        $menuID = $location[$args['menu']];
    }

    // Get menu items
    $menuItems = wp_get_nav_menu_items($menuID);
    $allItems = base_menu_items($menuItems, 0, $menuID, $args);

    // Print full menu
    $fullMenu = '<nav class="'.$args['navClass'].'">'.$args['beg'].$allItems.$args['end'].'</nav>';

    echo $fullMenu;
    
}

function base_menu_items($menuItems, $curentParent, $menuID, $args = array()) {
    //adding a class of sub-menu if applicable
    $menuClass = 'menu';
    if ($curentParent) {
        $menuClass = 'sub-menu';
    }else{
        $menuClass .= $args['customClass'];
    }

    //adding opening tag
    $menuItemsList = '<menu id="menu-'.$menuID.'" class="'.$menuClass.'">';

    //selecting curent page
    $pageID = get_the_ID();
    
    //the list element
    foreach ($menuItems as $menuItem) {
        $menuItemID = $menuItem->ID;
        $menuItemParent = $menuItem->menu_item_parent;

        //checking if right parent otherwise skip this instence
        if ($menuItemParent != $curentParent) {
            continue;
        }

        //checking if has child
        $hasChild = FALSE;
        foreach ($menuItems as $childItem) {
            $childItemParent = $childItem->menu_item_parent;
            if ($childItemParent == $menuItemID) {
                $hasChild = TRUE;
                break;
            }
        }
        
        //make the id
        $printID = 'menu-item-'.$menuItemID;

        //make list of classes
        $printClass = 'menu-item ';
        $isCurrent = $pageID == $menuItem->object_id;
        $parentOf = is_parent_of($menuItems, $menuItem, $pageID);
        $ancestorOf = ($parentOf && !$menuItemParent) ? TRUE : FALSE;

        if ($isCurrent) {
            $printClass .= 'current-menu-item ';
        }
        if ($parentOf && !$isCurrent) {
            $printClass .= 'curent-menu-parent ';
        }
        if ($ancestorOf && !$isCurrent) {
            $printClass .= 'curent-menu-ancestor ';
        }
        if ($hasChild) {
            $printClass .= 'menu-item-has_children ';
        }

        

        //printing the items
        if ($hasChild ) {
           $menuItemsList .= '<li id="'.$printID.'" class="'.$printClass.'">'.$args['begItem'].'<a href="'.$menuItem->url.'">'.$args['begLink'].$menuItem->title.$args['endLink'].'</a>'.$args['endItem'].base_menu_items($menuItems, $menuItemID, $menuID, $args).'</li>';
           
        }elseif (!$hasChild ) {
            $menuItemsList .= '<li id="'.$printID.'" class="'.$printClass.'">'.$args['begItem'].'<a href="'.$menuItem->url.'">'.$args['begLink'].$menuItem->title.$args['endLink'].'</a>'.$args['endItem'].'</li>';
        }
        
    }
    $menuItemsList .= '</menu>';

    return $menuItemsList;
    
}
function is_parent_of($menuItems, $currentMenuItem, $pageID){
    if($currentMenuItem->object_id == $pageID){
        return TRUE;
    }else{
        foreach ($menuItems as $item) {
            if ($item->menu_item_parent == $currentMenuItem->ID) {
                if (is_parent_of($menuItems, $item, $pageID)) {
                    return TRUE;
                }
            }
        }
    }
}


/*
menu id = name, id,
class = menu // submenu = sub-menu
li id = menu-item-id
class = menu-item// curent = current-menu-item // parent = curent-menu-parent // root = curent-menu-ancestor // has child = menu-item-has_children

*/