<?php
/**
 * Site Language Injection
 * @author GANJAR (Bohdan Rykhal)
 * @link   http://sli.su/
 */

namespace SLI\Buffer;

/**
 * Class Buffer
 * @package SLI
 */
class Buffer
{
    /**
     * @var array
     */
    protected $buffers = [];

    /**
     * Buffering content in callback function
     * @param \Closure $callback
     */
    public function buffering(\Closure $callback)
    {
        $this->start();
        $callback();
        $this->end();
    }

    /**
     * Start buffering
     */
    public function start()
    {
        ob_start(function ($buffer) {
            return $this->add($buffer);
        });
    }

    /**
     * Add buffer and get string buffer key
     * (after translate we replace this key to content)
     * @param $buffer
     * @return string
     */
    public function add($buffer)
    {
        $bufferId = count($this->buffers);
        $this->buffers[$bufferId] = $buffer;

        return $this->getBufferKey($bufferId);
    }

    /**
     * @param $id
     * @return string
     */
    public function getBufferKey($id)
    {
        return '<!--SLI:buffer:' . $id . '-->';
    }

    /**
     * Stop buffering and get stub content
     */
    public function end()
    {
        ob_end_flush();
    }

    /**
     * @param $bufferId
     * @return string|bool
     */
    public function getBuffer($bufferId)
    {
        return !empty($this->buffers[$bufferId]) ? $this->buffers[$bufferId] : false;
    }

    /**
     * @return array
     */
    public function getBuffers()
    {
        return $this->buffers;
    }

    /**
     * @param $bufferId
     */
    public function remove($bufferId)
    {
        if (isset($this->buffers[$bufferId])) {
            unset($this->buffers[$bufferId]);
        }
    }

    public function clear()
    {
        $this->buffers = [];
    }
}