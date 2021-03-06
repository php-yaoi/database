<?php

namespace Yaoi\Database\Definition;

use Yaoi\BaseClass;

class Index extends BaseClass
{
    const TYPE_KEY = 'key';
    const TYPE_UNIQUE = 'unique';
    const TYPE_PRIMARY = 'primary';

    /** @var Column[]  */
    public $columns = array();
    public $type = self::TYPE_KEY;

    public function __construct($columns) {
        if (is_array($columns)) {
            $this->columns = $columns;
        }
        else {
            $this->columns = func_get_args();
        }
    }

    public function setType($type) {
        $this->type = $type;
        return $this;
    }

    private $name;
    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    public function getName() {
        if (!$this->name) {
            $this->name = $this->type;
            foreach ($this->columns as $column) {
                $this->name .= '_' . $column->schemaName;
            }
        }
        return $this->name;
    }

}