<?php
/**
 * Events class  - Events.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

namespace Congress\Service\Api;

use Congress\Service\ApiMethod;

/**
 * Events class
 *
 * @package   Congress.Service.Api
 */
class Events extends ApiMethod
{

    public function search()
    {
        $p   = $this->prepare(func_get_args());
        extract($p);
        $app = \Congress\Application::getInstance();
        $db  = $app->db;

        throw new \Exception('message');
    }

    public function get()
    {
        $p   = $this->prepare(func_get_args());
        extract($p);
        $app = \Congress\Application::getInstance();
        $db  = $app->db;
    }

    public function edit()
    {
        $p   = $this->prepare(func_get_args());
        extract($p);
        $app = \Congress\Application::getInstance();
        $db  = $app->db;
    }

    public function create()
    {
        $p   = $this->prepare(func_get_args());
        extract($p);
        $app = \Congress\Application::getInstance();
        $db  = $app->db;
    }

    public function delete()
    {
        $p   = $this->prepare(func_get_args());
        extract($p);
        $app = \Congress\Application::getInstance();
        $db  = $app->db;
    }

    /**
     * Подписаться на событие
     * --------------------------------------------
     *
     * PARAMS:
     * ------
     * user_id               (*) id
     * event_id
     *
     * --------------------------------------------
     *
     *
     * @param  array $params
     * @return mixed
     * @api
     */
    public function subscribe()
    {
        $p = $this->prepare(func_get_args());

        $event_id = (int) $p['event_id'];
        $user_id  = (int) $p['user_id'];

        if ( ! $event_id || ! $user_id) {
            $this->getApp()->applyHook('api');
            return 0;
        }


        $query = "INSERT IGNORE INTO `events_users` "
                . " SELECT events.id, users.id, NULL, {$visited}, NULL "
                . ", " . (($visited == 1) ? 'NOW()' : 'NULL') // when_visited
                . ", " . $db->quote($registered_type)      // registered_type
                . " FROM events, users "
                . " WHERE events.id = " . $db->quote($event_id)
                . " AND users.id = " . $db->quote($user_id);

        $stmt = $db->query($query);

        return (($stmt->rowCount()) ? 1 : 0);
    }

    /**
     * Отписаться от события
     * --------------------------------------------
     *
     * PARAMS:
     * ------
     * user_id               (*) id
     * event_id
     *
     * --------------------------------------------
     *
     *
     * @param  array $params
     * @return mixed
     * @api
     */
    public function unsubscribe()
    {
        $p = $this->prepare(func_get_args());

        $event_id = (int) $p['event_id'];
        $user_id  = (int) $p['user_id'];
        if ( ! $event_id || ! $user_id) {
            $this->getApp()->applyHook('api');
            return 0;
        }

        $query = "DELETE FROM `events_users` WHERE "
                . " event_id = " . $db->quote($event_id)
                . " AND user_id = " . $db->quote($user_id)
                . " LIMIT 1";
        $db->query($query);

        return 1;
    }
}