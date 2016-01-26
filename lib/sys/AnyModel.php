<?php

namespace sys;

class AnyModel extends \mongo\Model {

    public function fieldtype($field = '_id') {
        return strtolower(gettype($this->$field ?? null));
    }

    public function fieldClass($field = '_id') {
        if (($type = $this->fieldtype($field)) !== 'object') return $type;
        return str_replace('\\', '-', get_class($this->$field));
    }

    public function toString($field) {
        $val = $this->$field ?? null;
        if (!array_key_exists($field, $this->_data)) return '';
        if ($val === null) return var_export($val, true);
        if (is_bool($val)) return var_export($val, true);
        if (is_string($val)) return $val;
        if (is_numeric($val)) return $val;
        if (is_array($val)) return json_encode($val, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        if ($val instanceof \MongoDB\BSON\ObjectId) return (string)$val;
        if ($val instanceof \MongoDB\BSON\UTCDateTime) return $val->toDateTime()->format('Y/n/j H:i');
        return get_class($val);
    }

    public function save() {
        if (isset($this->_id)) {
            return $this->db()->update($this->_ns, ['_id' => $this->_id], $this->_data);
        }
        $this->_id = $this->db()->insert($this->_ns, $this->_data);
        return $this->_id;
    }
}
