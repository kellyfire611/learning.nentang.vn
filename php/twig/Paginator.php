<?php
class Paginator
{
    /**
     * Biến kết nối đến database
     */
    private $conn;

    /**
     * Số mẫu tin TỐI ĐA cần hiển thị trong 1 trang
     * Mặc định hiển thị 10 mẫu tin trong 1 trang
     */
    private $limit;

    /**
     * Số trang muốn hiển thị dữ liệu
     */
    private $page;

    /**
     * Câu lệnh truy vấn database
     * SELECT ... FROM ...
     */
    private $query;

    /**
     * Tổng số mẫu tin lấy được sau khi thực thi câu lệnh SQL
     */
    private $total;

    /**
     * Biến quản lý TWIG template engine
     */
    private $twig;

    /**
     * Hàm khởi tạo
     */
    public function __construct($twig, $conn, $query)
    {
        $this->twig = $twig;
        $this->conn = $conn;
        $this->query = $query;

        $rs = $this->conn->query($this->query);
        $this->total = $rs->num_rows;
    }

    /**
     * Lấy dữ liệu
     */
    public function getData($limit = 10, $page = 1)
    {
        $this->limit   = $limit;
        $this->page    = $page;

        if ($this->limit == 'all') {
            $query      = $this->query;
        } else {
            $offset = (($this->page - 1) * $this->limit);
            $query      = $this->query . " LIMIT " . $offset . ", $this->limit";
        }
        $rs             = $this->conn->query($query);

        while ($row = $rs->fetch_assoc()) {
            $results[]  = $row;
        }

        $result         = new stdClass();
        $result->page   = $this->page;
        $result->limit  = $this->limit;
        $result->total  = $this->total;
        $result->data   = $results;

        return $result;
    }

    /**
     * Tạo HTML phân trang
     */
    public function createLinks()
    {
        if ($this->limit == 'all') {
            return '';
        }

        $first      = 1;
        $last        = ceil($this->total / $this->limit);

        $html = $this->twig->render('shared/pagination/index.html.twig', [
            'page' => $this->page,
            'limit' => $this->limit,
            'total' => $this->total,
            'first' => $first,
            'last' => $last
        ]);
        return $html;
    }
}
