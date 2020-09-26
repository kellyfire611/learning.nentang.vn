<?php
class Config
{
    public static $DB_CONNECTION_HOST           = 'localhost';
    public static $DB_CONNECTION_USERNAME       = 'admin_doctruyen';
    public static $DB_CONNECTION_PASSWORD       = 'Db@#123-$%^';
    public static $DB_CONNECTION_DATABASE_NAME  = 'admin_doctruyen';

    public static $LIMIT = 5;
    public static $PAGE = 1;

    public static $BOOTSTRAP_RATING_CLASS = array(
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