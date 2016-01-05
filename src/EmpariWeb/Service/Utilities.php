<?php
namespace EmpariWeb\Service;

class Utilities extends Service
{
    public function execute($parameters){}
        
    /**
     * 
     * get content in a page http
     * @return http content
     * @author Adriano Santos <adrianodrix@gmail.com>
     */
    public static function getCurl($url)
    {
        $ch = curl_init();
        $timeout = 15;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $dados = curl_exec($ch);
        curl_close($ch);
        
        return $dados;
    }
    
    /**
     * 
     * This function displays in file log.txt, structured information about one or more expressions, including the type and value. Arrays and objects are explored recursively with values indented to show structure.
     * @author Adriano Santos 
     */
    public static function toLog($arr, $debug=false){
        if ($debug){
            ob_start();
            var_dump($arr);
            $str = ob_get_contents();
            ob_end_clean();
    
            $arq =  __DIR__. '\..\..\..\public_html\log.txt';
            if (file_exists($arq)) unlink($arq);    
            $fp = fopen($arq, "a");
            fwrite($fp, $str);
            fclose($fp);
        }
    }
    
    /**
     * 
     * send Email
     * 
     * @param \Silex\Application $app
     * @param string $subject
     * @param string $to
     * @param string $message
     * @return boolean 
     * @author Adriano Santos <adriano.santos@empari.com.br>
     */
    public static function sendEmail(\Silex\Application $app, $subject, $to, $message )
    {
        $from = array(                    
                    $app['config']['mailer']['email-from']//'do-not-reply@' . (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : gethostname()) 
                    => $app['config']['application']['name'],                   
                );
        
        $transport = \Swift_Message::newInstance()
            ->setFrom($from)
            ->setSubject($subject)
            ->setTo($to)
            ->setBody($message, 'text/html');
        
        return $app['mailer']->send($transport);
    }

    /**
     * Get the full Url
     * 
     * @param array $s $_SESSION
     * @param boolean $use_forwarded_host
     * @param boolan $request_uri
     * @return string URL
     * @author Adriano Santos <adriano.santos@empari.com.br>
     */
    public static function getUrlOrigin($s, $use_forwarded_host = false, $request_uri = false)
    {
        $ssl = (!empty($s['HTTPS']) && $s['HTTPS'] == 'on') ? true:false;
        $sp = strtolower($s['SERVER_PROTOCOL']);
        $protocol = substr($sp, 0, strpos($sp, '/')) . (($ssl) ? 's' : '');
        $port = $s['SERVER_PORT'];
        $port = ((!$ssl && $port=='80') || ($ssl && $port=='443')) ? '' : ':'.$port;
        $host = ($use_forwarded_host && isset($s['HTTP_X_FORWARDED_HOST'])) ? $s['HTTP_X_FORWARDED_HOST'] : (isset($s['HTTP_HOST']) ? $s['HTTP_HOST'] : null);
        $host = isset($host) ? $host : $s['SERVER_NAME'] . $port;
        $url  = $protocol . '://' . $host;
        return $request_uri ? $url.$s['REQUEST_URI'] : $url; 
    }
    
    /**
     * get DateTime from Date String
     *
     * @param unknown $date_to_format
     * @return DateTime|NULL
     * @author Adriano Santos<adriano.santos@empari.com.br>
     */
    public static function getDateUTC($date_to_format)
    {
        if (($timestamp = strtotime($date_to_format)) === false) {
            return null;
        } else {
            return \DateTime::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s', strtotime($date_to_format)));
        }
    }
}