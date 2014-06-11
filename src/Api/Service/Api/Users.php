<?php
/**
 * Users class  - Users.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

namespace Congress\Service\Api;

use Congress\Service\ApiMethod;

/**
 * Users class
 *
 * @package   Congress.Service.Api
 */
class Users extends ApiMethod
{

    public function login()
    {
        $p   = $this->prepare(func_get_args());
        extract($p);
        $app = \Congress\Application::getInstance();
        $db  = $app->db;

        $q    = $db->createSelectQuery();
        $q->select('*')->from('users')
                ->limit(1)
                ->where($q->expr->eq('email', $db->quote($login)))
                ->where($q->expr->eq('password', $db->quote($password)))
        ;
        $stmt = $q->prepare();
        $stmt->execute();
//         $user = $
    }

    /**
     * Создает нового пользователя
     * --------------------------------------------
     *
     * PARAMS:
     * ------
     * email               (*) email
     * name                (*) Имя
     * surname             (*) Фамилия
     * patronymic          (*) Отчество
     * mobile              Мобильный телефон
     * birth               Дата рождения
     * specialization_id
     * academic_title_id
     * scholastic_degree_id
     * position_id          Должность
     * job                  Место работы
     * country_id          (*) страна
     * region_id           (*) регион
     *
     * -------------------------------------------
     *
     * @param  array $p
     * @return int
     * @api
     */
    public function create()
    {
        $p   = $this->prepare(func_get_args());
        extract($p);
        $app = \Congress\Application::getInstance();
        $db  = $app->db;

        $elist = ($elist) ? 1 : 0;

        $specialization_id = (int) $specialization_id;
        $academic_id       = (int) $academic_id;
        $scholastic_id     = (int) $scholastic_id;
        $position_id       = (int) $position_id;
        $country_id        = (int) $country_id;
        $region_id         = (int) $region_id;

        if ( ! $name || ! $surname || ! $email) {
            $app->applyHook('api', array('msg' => 'Invalid args'));
            return 0;
        }

        // --------------------------
        // Создаем пользователя
        $q = $db->createInsertQuery();
        $q->insertInto('users')
                ->set('email', $db->quote($email))
                ->set('name', $db->quote($name))
                ->set('surname', $db->quote($surname))
                ->set('patronymic', $db->quote($patronymic))
                ->set('created_type',
                      (in_array($created_type, array('REG', 'QUICK', 'IMPORT'))) ? $db->quote($created_type) : "'REG'")
        ;
        $q->prepare()->execute();

        $id = $db->lastInsertId();
        if ( ! $id) {
            return 0;
        }

        // Создаем пользователя
        $q = $app->db->createInsertQuery();
        $q->insertInto('users_participants')
                ->set('id', $db->quote($id))
                ->set('country_id', $db->quote($country_id))
                ->set('region_id', $db->quote($region_id))
                ->set('mobile', ($mobile) ? $db->quote($mobile) : 'NULL')
                ->set('birth', ($birth) ? $db->quote($birth) : 'NULL' )
                ->set('specialization_id',
                      ($specialization_id) ? $db->quote($specialization_id) : '0')
                ->set('academic_id',
                      ($academic_id) ? $db->quote($academic_id) : 'NULL')
                ->set('degree_id',
                      ($degree_id) ? $db->quote($degree_id) : 'NULL')
                ->set('position_id',
                      ($position_id) ? $db->quote($position_id) : 'NULL')
                ->set('job', ($job) ? $db->quote($job) : 'NULL')
                ->set('elist', ($elist) ? $db->quote($elist) : '1')
        ;

        if ($data) {
            $data = \CJSON::encode($data); // json_encode($data);
            $q->set('data', $db->quote($data));
        }

        $q->prepare()->execute();

        return $id;
    }

    /**
     * Создает нового пользователя
     * --------------------------------------------
     *
     * PARAMS:
     * ------
     * email               (*) email
     * name                (*) Имя
     * surname             (*) Фамилия
     * patronymic          (*) Отчество
     * mobile              Мобильный телефон
     * birth               Дата рождения
     * specialization_id
     * academic_title_id
     * scholastic_degree_id
     * position_id          Должность
     * job                  Место работы
     * country_id          (*) страна
     * region_id           (*) регион
     *
     * -------------------------------------------
     *
     * @param  array $p
     * @return int
     * @api
     */
    public function edit()
    {
        $p   = $this->prepare(func_get_args());
        $app = \Congress\Application::getInstance();
        $db  = $app->db;

        if ( ! $p['id']) {
            $app->applyHook('api', array('msg' => 'Invalid args'));
            return 0;
        }

        $setField = function($q, $key) use ($p, $db) {
            if (array_key_exists($key, $p)) {
                $q->set($key, $db->quote($p[$key]));
            }
        };

        $q = $db->createUpdateQuery();
        $q->update('users')->where($q->expr->eq('id', $db->quote($p['id'])));

        $setField($q, 'email');
        $setField($q, 'name');
        $setField($q, 'surname');
        $setField($q, 'patronymic');

        $stmt = $q->prepare();
        $stmt->execute();
        // -----------------------

        $q = $db->createUpdateQuery();
        $q->update('users_participants')
                ->where($q->expr->eq('id', $db->quote($p['id'])));
        $q->set('id', $db->quote($p['id']));

        $setField($qp, 'mobile');
        $setField($qp, 'birth');
        $setField($qp, 'specialization_id');
        $setField($qp, 'academic_title_id');
        $setField($qp, 'scholastic_degree_id');
        $setField($qp, 'position_id');
        $setField($qp, 'job');
        $setField($qp, 'country_id');
        $setField($qp, 'region_id');
        // -----------------------

        $stmt = $q->prepare();
        $stmt->execute();

        return $stmt->queryString;
    }

    /**
     * Удаляет пользователя
     * --------------------------------------------
     *
     * PARAMS:
     * ------
     * id               (*) id
     *
     * --------------------------------------------
     *
     * @param  array $p
     * @return int
     * @api
     */
    public function delete()
    {
        $db = $this->getDb();
        $p  = $this->prepare(func_get_args());

        $id = (int) $p['user_id'];

        $arr = array(
            "DELETE FROM `users` WHERE id = {$db->quote($id)}",
            "DELETE FROM `users_participants` WHERE id = {$db->quote($id)}",
            "DELETE FROM `events_users` WHERE user_id = {$db->quote($id)}",
        );

        foreach ($arr as $query) {
            if ( ! $db->query($query)) {
                return 0;
            }
        }

        return 1;
    }

    /**
     * Удаляет пользователя
     * --------------------------------------------
     *
     * PARAMS:
     * ------
     * id               (*) id
     *
     * --------------------------------------------
     *
     * @param  array $p
     * @return array
     * @api
     */
    public function get()
    {
        $p  = $this->prepare(func_get_args());
        $db = $this->getDb();

        $ids = parseNumbers($p['ids']);
        if ( ! count($ids)) {
            $ids = array(-1);
        }

        $q    = $db->createSelectQuery();
        $q->select('*, users.id AS id');
        $q->from('users')
                ->leftJoin('users_participants', 'users.id',
                           'users_participants.id')
                ->where($q->expr->in('users.id', $ids));
        $stmt = $q->prepare();
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Ищет пользователей по фильтрам
     * --------------------------------------------
     *
     * PARAMS:
     * ------
     * name                (*) Имя
     *
     * -------------------------------------------
     *
     * @param  array $p
     * @return array
     * @api
     */
    public function search()
    {
        $p  = $this->prepare(func_get_args());
        $db = $this->getDb();

        $q = $db->createSelectQuery();

        $q->where($q->expr->like('name', $db->quote("%{$p['name']}%")));

        $q = new \Congress\Service\DbSelectCount($q);

        $result = $q->execute();




        return $result;
    }

    /**
     * Метод
     * --------------------------------------------
     *
     * PARAMS:
     * ------
     * id               (*) id
     *
     * --------------------------------------------
     *
     * @param  array $params
     * @return mixed
     * @api
     */
    public function resetPassword()
    {
        $params = $this->prepare(func_get_args());
        $app    = \Congress\Application::getInstance();
        $db     = $this->getDb();

        $email = $params['email'];

        $q    = $db->createSelectQuery();
        $q->select('*')->from('users')->where($q->expr->eq('email',
                                                           $db->quote($email)));
        $q->limit(1);
        $stmt = $q->prepare();
        $stmt->execute();
        if ( ! $stmt->rowCount()) {
            return;
        }

        $user = $stmt->fetchObject(\Congress\Model\User::CLASS_NAME);


        /* @var $user \Congress\Model\User */

        // $hash = hash_hmac($this->_config['hash_method'], $password, $this->_config['hash_key']);
        $password = \Fobia\Utils::randString(8);
        $hash     = hash_hmac($app->config('crypt.method'), $password,
                                           $app->config('crypt.key'));

        $q = $db->createUpdateQuery();
        $q->update('users');
        $q->set('password', $db->quote($hash));
        $q->where($q->expr->eq('id', $user->id));
        $q->prepare()->execute();





        $hostname     = '172.16.0.65'; ///.almazovcentre.ru';// 'smtp.yandex.ru';
        $sender_email = 'noreplay@almazovcentre.ru'; // 'nobody@ufac.ru';
        $password     = 'gkl21CRB4xi6'; // 'nobodypass';
        $port         = '465';
        $encryption   = 'ssl';
        $signature    = 'Центр им. Алмазова';

        // --- Smtp Transport
        $transport = Swift_SmtpTransport::newInstance($hostname, $port)
                ->setUsername($sender_email)
                ->setPassword($password)
                ->setEncryption($encryption);

        // --- Mailer
        $mailer = Swift_Mailer::newInstance($transport);

        // --- Message
        $message = Swift_Message::newInstance('Доступ к личному кабинету')
                ->setFrom(array($sender_email => $signature))
                ->setTo(array($email))
                ->setBody($view, 'text/html');

        /** @noinspection PhpParamsInspection */
        $result = $mailer->send($message);

        if ( ! $result) {
            throw new \Exception('sending failed');
        }
    }
}