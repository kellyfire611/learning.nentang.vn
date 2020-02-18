<?php
// Include file cấu hình ban đầu của `Twig`
require_once __DIR__ . '/../../bootstrap.php';

// Truy vấn database để lấy danh sách
// 1. Include file cấu hình kết nối đến database, khởi tạo kết nối $conn
include_once(__DIR__ . '/../../dbconnect.php');

if (isset($_POST['btnImport'])) {
    // Kiểm tra file import đã được upload hay chưa?
    if (!empty($_FILES['taptindulieu']['name'])) {
        // lấy đuôi file để tạo class READER tương ứng
        $extension = pathinfo($_FILES['taptindulieu']['name'], PATHINFO_EXTENSION);

        if ($extension == 'csv') {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
        } elseif ($extension == 'xlsx') {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        } else {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
        }

        // Đọc tất cả dữ liệu từ file import
        $spreadsheet = $reader->load($_FILES['taptindulieu']['tmp_name']);
        $allDataInSheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

        // Kiểm tra các cột dữ liệu có đúng mẫu hay không?
        $arrayCount = count($allDataInSheet);
        $flag = 0;
        $createArray = array('lsp_ten', 'lsp_mota');
        $makeArray = array('lsp_ten' => 'lsp_ten', 'lsp_mota' => 'lsp_mota');
        $SheetDataKey = array();
        foreach ($allDataInSheet as $dataInSheet) {
            foreach ($dataInSheet as $key => $value) {
                if (in_array(trim($value), $createArray)) {
                    $value = preg_replace('/\s+/', '', $value);
                    $SheetDataKey[trim($value)] = $key;
                }
            }
        }
        $dataDiff = array_diff_key($makeArray, $SheetDataKey);
        if (empty($dataDiff)) {
            $flag = 1;
        }
        
        // Thực thi Import dữ liệu
        if ($flag == 1) {
            // Đọc dữ liệu từ dòng số 2 (do dòng 1 đã lấy làm tiêu đề cột)
            for ($i = 2; $i <= $arrayCount; $i++) {
                $lsp_ten = $SheetDataKey['lsp_ten'];
                $lsp_mota = $SheetDataKey['lsp_mota'];

                $lsp_ten = filter_var(trim($allDataInSheet[$i][$lsp_ten]), FILTER_SANITIZE_STRING);
                $lsp_mota = filter_var(trim($allDataInSheet[$i][$lsp_mota]), FILTER_SANITIZE_STRING);
                $fetchData[] = array('lsp_ten' => $lsp_ten, 'lsp_mota' => $lsp_mota,);
            }
            $data['dataInfo'] = $fetchData;
            
            // dd($fetchData);
            foreach($fetchData as $row) {
                // Câu lệnh INSERT
                $sql = "INSERT INTO `loaisanpham` (lsp_ten, lsp_mota) VALUES ('" . $row['lsp_ten'] . "', '" . $row['lsp_mota'] . "');";

                // Thực thi INSERT
                mysqli_query($conn, $sql);
            }
        } else {
            echo "Các cột Excel không đúng mẫu. Vui lòng download mẫu Import từ trang web và nhập liệu chính xác.";
        }
        header('location:index.php');
    }
} else {
    // Yêu cầu `Twig` vẽ giao diện được viết trong file `backend/loaisanpham/loaisanpham.html.twig`
    // với dữ liệu truyền vào file giao diện được đặt tên là `ds_loaisanpham`
    echo $twig->render('backend/loaisanpham/import.html.twig');
}
