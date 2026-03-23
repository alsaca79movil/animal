<?php

use Zend_View_Helper_Abstract as ViewHelperAbstract;

class App_View_Helper_FindFirstNonNullValue extends ViewHelperAbstract {
  public function findFirstNonNullValue(...$values) : string {
    foreach ($values as $value) {
      if ($value != null && (is_string($value) || is_numeric($value))) {
        return strval($value);  
      }
    }
  }
}
