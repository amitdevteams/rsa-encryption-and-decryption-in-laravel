<?php

namespace App\Http\Controllers;


use RsaCrypt;
use phpseclib3\Net\SFTP;
use phpseclib3\Crypt\RSA;
use Illuminate\Http\Request;
use Spatie\Crypto\Rsa\KeyPair;
use Illuminate\Support\Facades\Log;
use phpseclib3\Crypt\PublicKeyLoader;




class TestingController extends Controller
{
    public function generate()
    {
        return view('generate');
    }

    public function get_token(Request $request)
    {
        // $encrypt_method = "AES-256-CBC";
        // $string = "Hey this my Testing";
        // $publicKey = file_get_contents('publickey.pem');
        // openssl_public_encrypt( $string, $encrypted, $publicKey );
        // $encrypteds = base64_encode($encrypted);
        // echo"encrypted ke upar hai".$encrypteds;
        // echo '<h1>Encrypted String</h1>';
        // echo $encrypteds;
        // $privKey = file_get_contents('privatekey.pem');
        
        // echo"ab niche wala me hai".$encrypteds;
        
        // openssl_private_decrypt(base64_decode($encrypteds), $decrypted, $privKey);
        // echo '<h1>Decrypted String</h1>';
        // print_r($decrypted);
        // $crypt = new RsaCrypt;
        // $crypt->genKeys(512);
        // $crypt->setPublicKey('publickey.pem');
        // $crypt->setPrivateKey('privatekey.pem');
        // $data = $crypt->encrypt("Test Crypt");

        // log::info('data me kya aa rha hai' . $data);
        // $crypt->decrypt($data);
        // echo $crypt->decrypt($data);
        // $crypt = new RsaCrypt;

        // $crypt->genKeys(512);
        // $crypt->setPublicKey('publickey.pem');
        // $crypt->setPrivateKey('privatekey.pem');
        // $data = $crypt->encrypt("Test Crypt");

        // echo $data;

        // $getting = $crypt->decrypt($data);
        // log::info('getting me kya aa rhahai' . $getting);

        $crypt = new RsaCrypt;
        $crypt->genKeys(2048);
        $crypt->setPublicKey('publickey.pem');
        $crypt->setPrivateKey('privatekey.pem');
        $data = $crypt->encrypt("Test Crypt");
        log::info('what is coming in crypted'.$data);
        echo $crypt->decrypt($data);

        
        
    }

    
}
