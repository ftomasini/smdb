<?php
/**
 * Created by PhpStorm.
 * User: ftomasini
 * Date: 11/09/15
 * Time: 19:39
 */


class serverColetor
{


    public function __construct()
    {
        //chdir('../');
        //require_once 'core/Core.php';
    }


    /**
     *
     *
     */
    public function wsTeste($data)
    {
        try {
            $f = fopen('/tmp/resultWs.log', 'a+');

            foreach ($data as $key1 => $line1)
            {
                foreach($line1 as $key=>$line) {
                    fwrite($f, "{$key}: {$line} \n");
                }
            }

            $ok = true;
        } catch (Exception $e) {
            $ok = false;
        }

        return $ok;
    }
}

?>
