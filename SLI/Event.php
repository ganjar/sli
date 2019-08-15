<?php
/**
 * Site Language Injection
 * @author GANJAR (Bohdan Rykhal)
 * @link   http://sli.su/
 */

namespace SLI;


/**
 * Class Event
 * @package SLI
 */
class Event
{
    const EVENT_MISSING_TRANSLATION = 'missing_translation';

    /**
     * @var array
     */
    protected $callbackStack = [];

    /**
     * @param          $event
     * @param \Closure $callback
     */
    public function on($event, \Closure $callback)
    {
        if (!isset($this->callbackStack[$event])) {
            $this->callbackStack[$event] = [];
        }
        $this->callbackStack[$event][] = $callback;
    }

    /**
     * @param       $event
     * @param array $data
     */
    public function trigger($event, ...$data)
    {
        if (!empty($this->callbackStack[$event])) {
            foreach ($this->callbackStack[$event] as $callback) {
                $callback(...$data);
            }
        }
    }
}