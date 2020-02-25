<?php
// Include cấu hình
include_once __DIR__ . '/../config.php';

// Hàm lấy full đường dẫn trang web
if (!function_exists('siteURL')) {
    function siteURL()
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $domainName = $_SERVER['HTTP_HOST'];
        return $protocol . $domainName;
    }
}

/**
 * Hàm kiểm tra file có được người dùng upload lên serer chưa?
 * 
 * @return boolean
 */
function files_uploaded()
{

    // bail if there were no upload forms
    if (empty($_FILES))
        return false;

    // check for uploaded files
    $files = $_FILES['files']['tmp_name'];
    foreach ($files as $field_title => $temp_name) {
        if (!empty($temp_name) && is_uploaded_file($temp_name)) {
            // found one!
            return true;
        }
    }
    // return false if no files were found
    return false;
}

if (!function_exists('group_by')) {
    /**
     * Function that groups an array of associative arrays by some key.
     * 
     * @param {String} $key Property to sort by.
     * @param {Array} $data Array that stores multiple associative arrays.
     */
    function group_by($key, $data)
    {
        $result = array();

        foreach ($data as $val) {
            if (array_key_exists($key, $val)) {
                $result[$val[$key]][] = $val;
            } else {
                $result[""][] = $val;
            }
        }

        return $result;
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


// Hàm lấy full đường dẫn trang web

function backend_check_email_has_permission($email, $permissionKey)
{
    // Tạo kết nối
    $conn = mysqli_connect(Config::$DB_CONNECTION_HOST, Config::$DB_CONNECTION_USERNAME, Config::$DB_CONNECTION_PASSWORD, Config::$DB_CONNECTION_DATABASE_NAME) or die('Xin lỗi, database không kết nối được.');
    $conn->query("SET NAMES 'utf8mb4'"); 
    $conn->query("SET CHARACTER SET UTF8MB4");  
    $conn->query("SET SESSION collation_connection = 'utf8mb4_unicode_ci'"); 

    // Gọi Store Procedure `get_permissions_by_email` trong MYSQL
    $sqlPermissions = "CALL `get_permissons_by_email`('$email');";

    // 3. Thực thi câu truy vấn SQL để lấy về dữ liệu
    $resultPermissions = mysqli_query($conn, $sqlPermissions) or die("<b>Có lỗi khi thực thi câu lệnh SQL: </b>" . mysqli_error($conn) . "<br /><b>Câu lệnh vừa thực thi:</b></br>$sqlPermissions");

    // 4. Khi thực thi các truy vấn dạng SELECT, dữ liệu lấy về cần phải phân tách để sử dụng
    // Thông thường, chúng ta sẽ sử dụng vòng lặp while để duyệt danh sách các dòng dữ liệu được SELECT
    // Ta sẽ tạo 1 mảng array để chứa các dữ liệu được trả về
    // $dataPermissions = [];
    while ($row = mysqli_fetch_array($resultPermissions, MYSQLI_ASSOC)) {
        $dataPermissions[] = array(
            "id" => $row["id"],
            'name' => $row['name'],
            'display_name' => $row['display_name'],
            'guard_name' => $row['guard_name'],
            'created_at' => $row['created_at'],
            'updated_at' => $row['updated_at'],
        );
    }

    $allow = false;
    foreach ($dataPermissions as $permission) {
        if ($permission['name'] == $permissionKey) {
            $allow = true;
            break;
        }
    }

    return $allow;
}
