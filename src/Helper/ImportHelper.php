<?php

namespace App\Helper;

use Wrappers\FtpStream;

class ImportHelper
{

    private $ftp_server = "192.168.200.220";
    private $ftp_port = "21";
    private $ftp_user = "media-repository";
    private $ftp_pass = "!Media#200.220";

    private $connection;


    public function openFtp()
    {
        $this->connection = ftp_connect( $this->ftp_server, $this->ftp_port, 5 );
    }
    
    public function authFtp() {
        ftp_login($this->connection, $this->ftp_user, $this->ftp_pass);
    }

    public function closeFtp()
    {
        ftp_close($this->connection);
    }

    private function listFtp($directory = '/')
    {
        set_time_limit(ini_get('max_execution_time'));

        ftp_chdir($this->connection, $directory);
        $nlist = ftp_nlist($this->connection, ftp_pwd($this->connection));
//        if( is_array($nlist)) {
//            $nlist = array_map(function ($v) use ($directory) {
//                return rtrim($directory, '/') . '/' . $v;
//            }, $nlist);
//        }
        $rawlist = ftp_rawlist($this->connection, ftp_pwd($this->connection));
        if( is_array($rawlist)) {
//            $rawlist = array_slice($rawlist, 2);
            foreach ($rawlist as $raw) {
                if (preg_match('!^d.*\s(.*?)$!', $raw, $match)) {
                    $nlist = array_merge($nlist, $this->listFtp(rtrim($directory,'/').'/'.$match[1]));
                }
            }
        }
        if( is_array($nlist)) {
            return $nlist;
        }
        return [];
    }

    public function fetchList($directory = '/', $match = '!csv$!')
    {
        $list = $this->listFtp($directory);
        $list = array_filter(array_map(function($v)use($match){return preg_match($match,$v) ? $v : false;},$list));
        return $list;
    }

    public function readCsv($file)
    {
//        fopen()

        $temp = fopen('php://temp', 'r+');
        ftp_fget($this->connection, $temp, $file, FTP_BINARY, 0 );
        $statistic = fstat($temp);
        fseek($temp, 0);
        $content = fread($temp, $statistic['size']);
        fclose($temp);

        $fp = fopen('ftp://'.$this->ftp_user.':'.$this->ftp_pass.'@'.$this->ftp_server.$file,'r');
        $csv = [];
        while (($row = fgetcsv($fp, 1000, ";")) !== FALSE) {
            $csv[] = array_map('trim',$row);
        }
        fclose($fp);

        return $csv;
    }

/*
    private function connectFtp1()
    {

        $this->connection = ftp_connect($ftp_server);
        ftp_login($this->connection, $ftp_user, $ftp_pass);
        function ftp_list($this->connection, $dir)
        {
            ftp_chdir($this->connection, $dir);
            $nlist = ftp_nlist($this->connection, ftp_pwd($this->connection));
            if( is_array($nlist)) {
                $nlist = array_map(function ($v) use ($dir) {
                    return rtrim($dir, '/') . '/' . $v;
                }, $nlist);
            }
            $rawlist = ftp_rawlist($this->connection, ftp_pwd($this->connection));
            if( is_array($rawlist)) {
                $rawlist = array_slice($rawlist, 2);
                foreach ($rawlist as $raw) {
                    if (preg_match('!^d.*\s(.*?)$!', $raw, $match)) {
                        $nlist = array_merge($nlist, ftp_list($this->connection,rtrim($dir,'/').'/'.$match[1]));
                    }
                }
            }
            if( is_array($nlist)) {
                return $nlist;
            }
            return [];
        }

        $list = ftp_list($this->connection,'/');
        ftp_close($this->connection);

        $list = array_filter(array_map(function($v){return preg_match('!csv$!',$v) ? $v : false;},$list));
        var_dump($list);
        $file = current($list);
        var_dump($file);

        $fp = fopen('ftp://'.$ftp_user.':'.$ftp_pass.'@'.$ftp_server.$file,'r');

        $csv = [];
        while (($row = fgetcsv($fp, 1000, ";")) !== FALSE) {
            $csv[] = array_map('trim',$row);
        }
        fclose($fp);

        var_dump($csv);
    }
*/
}