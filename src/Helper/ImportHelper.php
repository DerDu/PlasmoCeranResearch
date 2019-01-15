<?php

namespace App\Helper;

//
//        FtpStream::register();
//        $finder = new Finder();
//        $finder->in('ftp://'.urlencode('media-repository').':'.urlencode('!Media#200.220').'@192.168.200.220/documents/plasmoceran/');
//        $finder->ignoreDotFiles(true);
//        $finder->ignoreUnreadableDirs(true);
//        $finder->depth(0);
//        $finder->files();
//        $finder->name('*.csv');
//
//        $iterator = $finder->getIterator();
//        foreach( $iterator as $splFileInfo ) {
//            dump($splFileInfo->getRelativePathname());
//        }
//
//        FtpStream::unregister();


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
        $fp = fopen('ftp://'.urlencode($this->ftp_user).':'.urlencode($this->ftp_pass).'@'.$this->ftp_server.$file,'r');
        $csv = [];
        while (($row = fgetcsv($fp, 1000, ";")) !== FALSE) {
            $csv[] = array_map('trim',$row);
        }
        fclose($fp);

        return $csv;
    }
}
