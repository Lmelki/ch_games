<?php 
namespace Config;

class CaptchaValidation{

    public function verifyrecaptcha(string $str, ?string &$error = null): bool
    {
          $secretkey = getenv('GOOGLE_RECAPTCHA_SECRETKEY');

          if(($str) && !empty($str)) {

                $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secretkey."&response=".$str."&remoteip=".$_SERVER['REMOTE_ADDR']);

                $responseData = json_decode($response);
                if($responseData->success) { // Verified
                      return true;
                }
          }

          $error = "Invalid captacha";

          return false;
    }

}