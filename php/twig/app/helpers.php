<?php
if (!function_exists('show_route')) {
    function siteURL()
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $domainName = $_SERVER['HTTP_HOST'] . '/';
        return $protocol . $domainName;
    }
}

if (!function_exists('print_mysqli_error')) {
    function print_mysqli_error()
    {
        if (!$resultSelectSanPham) {
            printf("Error: %s\n", mysqli_error($conn));
            exit();
        }
    }
}

if (!function_exists('is_active')) {
    function is_active($currect_page)
    {
        $url_array =  explode('/', $_SERVER['REQUEST_URI']);
        $url = end($url_array);
        if ($currect_page == $url) {
            echo 'active'; //class name in css 
        }
    }
}
