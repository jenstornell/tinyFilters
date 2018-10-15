<?php
class TinyFilter {
  #public $validators;
  public $settings;

  function __construct() {
    $this->validators[] = new TinyValidators();
  }
  function validate($array) {
    if(isset($this->settings)) {
      foreach($this->settings as $key => $items) {
        foreach($items as $args) {
          $validator_name = $this->validator($args);
          $positive = $this->positive($validator_name, $args);
          
          foreach($this->validators as $validator) {
            if(!method_exists($validator, $validator_name)) continue;

            $result = $validator->{$validator_name}($array, $key, $args['args']);
            $result = $positive ? $result : !$result;
          }

          if(!$result) return false;
        }
      }
      return true;
    }
    return false;
  }

  function addValidators($object) {
    $this->validators[] = $object;
    /*print_r($this->validators);
    print_r($object);

    echo $object->test(1,2);

    $merged = (object)array_merge((array) $this->Validators, (array)$object);

    print_r($merged->test());*/
  }

  function validator($args) {
    return strtok($args['validator'], '!');
  }

  function positive($validator, $args) {
    return ($validator == $args['validator']);
  }

  function add($key, $validator = null, $args = null) {
    if(is_string($key)) $this->addSingle($key, $validator, $args);
  }

  function reset() {
    unset($this->settings);
  }

  function addSingle($key, $validator, $args) {
    $this->settings[$key][] = [
      'key' => $key,
      'validator' => $validator,
      'args' => $args,
    ];
  }
}