<?php
// Lightweight PSR-3 LoggerInterface compatibility shim.
// Declares the interface only if it does not already exist (i.e., psr/log is not installed).
namespace Psr\Log {
    if (!interface_exists('Psr\\Log\\LoggerInterface')) {
        interface LoggerInterface
        {
            public function emergency($message, array $context = array());
            public function alert($message, array $context = array());
            public function critical($message, array $context = array());
            public function error($message, array $context = array());
            public function warning($message, array $context = array());
            public function notice($message, array $context = array());
            public function info($message, array $context = array());
            public function debug($message, array $context = array());
            public function log($level, $message, array $context = array());
        }
    }
}
