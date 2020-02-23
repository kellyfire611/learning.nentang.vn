<?php
class Config
{
    public static $limit = 5;
    public static $page = 1;

    public static $bootstrapRatingClass = array(
        5 => array(
            'class' => 'bg-success',    // 5 sao, class quy định màu mặc định (Bootstrap)
        ),
        4 => array(
            'class' => 'bg-primary',    // 4 sao, class quy định màu mặc định (Bootstrap)
        ),
        3 => array(
            'class' => 'bg-info',       // 3 sao, class quy định màu mặc định (Bootstrap)
        ),
        2 => array(
            'class' => 'bg-warning',    // 2 sao, class quy định màu mặc định (Bootstrap)
        ),
        1 => array(
            'class' => 'bg-danger',     // 1 sao, class quy định màu mặc định (Bootstrap)
        ),
    );
}
