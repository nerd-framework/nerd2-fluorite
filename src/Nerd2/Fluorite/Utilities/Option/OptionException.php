<?php

namespace Nerd2\Fluorite\Utilities\option;


class OptionException extends \Exception {
    public function __construct($message = "", $code = 0, \Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}
