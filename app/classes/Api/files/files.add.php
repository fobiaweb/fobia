<?php

/**
 * This file is part of API.
 *
 * files.add.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */
use Fobia\Api\Method as Method;

/**
 * Метода 'files.add'
 * --------------------------------------------
 *
 * PARAMS:
 * -------
 *
 *  file     (*) файл из масива $_FILES.
 *  type     (*) тип файла
 *  data_id  (*) id записи по типу type
 *  title    (*) название
 *
 * --------------------------------------------
 *
 * RESULT:
 * -------
 * Возвращает результат успеха
 * --------------------------------------------
 *
 * @api        files.add
 */
class Api_Files_Add extends Method
{

    protected $filesDirectory;

    protected function configure()
    {
        $this->setName('files.add');
        $this->filesDirectory = HTML_DIR . "/files";

        $this->setDefinition(array(
            'name' => 'file',
            'mode' => Method::VALUE_REQUIRED,
            'parse' => null,
            'assert' => null
        ));
        $this->setDefinition(array(
            'name'  => 'type',
            'parse' => 'trim'
        ));
        $this->setDefinition(array(
            'name'  => 'data_id',
            'parse' => 'parsePositive'
        ));
        $this->setDefinition(array(
            'name'  => 'title',
            'parse' => 'trim'
        ));
    }

    protected function execute()
    {
        $p   = $this->getDefinitionParams();
        $app = \App::instance();
        $db  = $app->db;

        // Undefined | Multiple Files | $_FILES Corruption Attack
        // If this request falls under any of them, treat it invalid.
        if (
                ! isset($p['file']['error']) ||
                is_array($p['file']['error'])
        ) {
            throw new \RuntimeException('Invalid parameters.');
        }

        // Check $_FILES['upfile']['error'] value.
        switch ($p['file']['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                throw new \RuntimeException('No file sent.');
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                throw new \RuntimeException('Exceeded filesize limit.');
            default:
                throw new \RuntimeException('Unknown errors.');
        }

        // You should also check file size here.
        preg_match('/(\d+)(M)/i', ini_get("upload_max_filesize"), $m);
        $max_filesize = $m[1];
        if ($m[2] == "M") {
            $max_filesize = $max_filesize * 1024 * 1024;
        }
        if ($p['file']['size'] > $max_filesize) {
            throw new \RuntimeException('Exceeded filesize limit.');
        }


        // DO NOT TRUST $_FILES['upfile']['mime'] VALUE !!
        // Check MIME Type by yourself.
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $arr   = array(
            'jpg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
        );
        if (false === $ext   = array_search($finfo->file($p['file']['tmp_name']),
                                                         $arr, true)) {
            throw new \RuntimeException('Invalid file format.');
        }

        // Copy
        if ( ! move_uploaded_file($p['file']['tmp_name'],
                                  $this->filesDirectory . '/' . basename($p['file']['tmp_name']))) {
            throw new \RuntimeException('Failed to move uploaded file.');
        }
        //        if ( ! copy($p['file']['tmp_name'],
        //                    $this->filesDirectory . '/' . basename($p['file']['tmp_name']))) {
        //            $this->response = 0;
        //            return;
        //        }
        //array (size=1)
        //  'file' =>
        //    array (size=5)
        //      'name' => string 'webcam-toy-photo11.jpg' (length=22)
        //      'type' => string 'image/jpeg' (length=10)
        //      'tmp_name' => string '/tmp/php4cDxxW' (length=14)
        //      'error' => int 0
        //      'size' => int 43014



        $q = $db->createInsertQuery();
        $q->insertInto('files')->set('id', 'NULL');

        if ($p['type']) {
            $q->set('type', $db->quote($p['type']));
        }
        if ($p['data_id']) {
            $q->set('data_id', $db->quote($p['data_id']));
        }
        if ($p['title']) {
            $q->set('title', $db->quote($p['title']));
        }

        $stmt = $q->prepare();
        $stmt->execute();
        if ($stmt->rowCount()) {
            $this->response = $db->lastInsertId();
        } else {
            $this->response = 0;
        }
        /* */
    }
}