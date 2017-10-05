<?php
/*
 *
 */

/**
 * Description of OutputCollector
 *
 * @author Ahmad
 */
class OutputCollector {

    public $errors   = null;
    public $warnings = null;
    public $messages = null;

    public $objects  = null;

    public $status = 0;

    public function OutputCollector(){

        $this->errors   = array();
        $this->warnings = array();
        $this->messages = array();

        $this->objects  = array();

        $this->status = 0;
    }

    public function addOutputCollector(OutputCollector $output){

        foreach ($output->errors as $error) {
            $this->errors[] = $error;
        }

        foreach ($output->warnings as $warning) {
            $this->warnings[] = $warning;
        }

        foreach ($output->messages as $message) {
            $this->messages[] = $message;
        }

        foreach ($output->objects as $object) {
            $this->objects[] = $object;
        }

    }

}
?>
