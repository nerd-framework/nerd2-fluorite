<?php
/**
 * Created by PhpStorm.
 * User: Roman
 * Date: 21.03.16
 * Time: 17:34
 */

namespace Nerd2\Fluorite\ORM;


abstract class EventModel extends BaseModel
{
    const CREATE_EVENT = "CREATE";
    const UPDATE_EVENT = "UPDATE";
    const DELETE_EVENT = "DELETE";

    /**
     * Event listeners array.
     *
     * @var array
     */
    protected static $listeners = [];

    /**
     * Bind listener of CREATE event.
     *
     * @param callable $callback
     */
    public static function onCreate($callback)
    {
        static::bindEvent(static::CREATE_EVENT, $callback);
    }

    /**
     * Bind listener of UPDATE event.
     *
     * @param callable $callback
     */
    public static function onUpdate($callback)
    {
        static::bindEvent(static::UPDATE_EVENT, $callback);
    }

    /**
     * Bind listener of DELETE event.
     *
     * @param callable $callback
     */
    public static function onDelete($callback)
    {
        static::bindEvent(static::DELETE_EVENT, $callback);
    }

    /**
     * Bind listener of $event event.
     *
     * @param $event
     * @param $callback
     */
    private static function bindEvent($event, $callback)
    {
        if (!isset(static::$listeners[$event])) {
            static::$listeners[$event] = [];
        }
        static::$listeners[$event][] = $callback;
    }
}