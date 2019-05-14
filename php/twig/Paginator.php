<?php
class Paginator
{
    private $conn;
    private $limit;
    private $page;
    private $query;
    private $total;
    private $twig;

    public function __construct($twig, $conn, $query)
    {
        $this->twig = $twig;
        $this->conn = $conn;
        $this->query = $query;

        $rs = $this->conn->query($this->query);
        $this->total = $rs->num_rows;
    }

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
