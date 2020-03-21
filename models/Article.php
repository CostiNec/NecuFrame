<?php
namespace models;

use core\Model;

/**
 * protected $primaryKey = 'ID';
 * const TABLE = 'table_name';(default is strtolower(model_name).'s' ex Article => articles
 */

class Article extends Model
{
    protected $columns = ['ID','content','description'];
}