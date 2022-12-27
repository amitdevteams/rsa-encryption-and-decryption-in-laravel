<?php

namespace App\Http\Controllers;

use App\Api_key;
use App\Encrypt;
use phpseclib3\Crypt\RSA;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use phpseclib\Crypt\RSA as Crypt_RSA;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\TokenAccess as TokenAccessResource;

class ApiKeyController extends Controller
{
    public function index()
    {
        $data = Api_key::orderBy('id', 'desc')->paginate(8);

        return view('token', $data);
    }
    public function alltoken($id)
    {
        // Get all the token from db
        log::info('id me kya aa rha hai' . $id);
        log::info('api key me insert kr gye hai');
        $getting_data = Api_key::get();
        log::info('token wala data me kya aa rha hai'.$getting_data);
        $response = [
            'success' => true,
            'data' => TokenAccessResource::collection($getting_data),
            'message' => 'all token name getting successfully.',
            'count' => count($getting_data)
        ];
        return response()->json($response, 200);
    }

    public function generate(Request $request)
    {
        $res = openssl_pkey_new();
        openssl_pkey_export($res,  $privkey, "PassPhrase number 1"); {
            $pubkey = openssl_pkey_get_details($res);
            $pubkey = $pubkey["key"];
            $rsaKey = openssl_pkey_new(array(
                'private_key_bits' => 4096,
                'private_key_type' => OPENSSL_KEYTYPE_RSA,
            ));
            $privKey = openssl_pkey_get_private($rsaKey);
            openssl_pkey_export($privKey, $pem);
            log::info('what is coming pubkey' . $pubkey);
            $getting = $pubkey;
            log::info('getting me kya aa rha hai' . $getting);
            $amit = new Encrypt();
            $amit->public_key = $getting;
            $amit->save();

            file_put_contents('publickey.pem', $pubkey);
            file_put_contents('privatekey.pem', $pem);
            log::info('private key me kya aa rha hai' . $pem);
            log::info('pub key me kya aa rha hai' . $pubkey);
            return response()->download(public_path('privatekey.pem'));
        }
    }


    public function api_store(Request $request)
    {
        $input = $request->all();
        Log::info('api store token me kya aa rha hai' . print_r($input, true));
        $validator = Validator::make($input, []);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'data' => 'Validation Error.',
                'message' => $validator->errors()
            ];
            return response()->json($response, 422);
        } else {
            $gets_apis_data = Api_key::where('name', $input['name'])->where('email', $input['email'])->where('city', $input['city'])->first();
            if ($gets_apis_data === NULL) {
                Log::info('user token added successfullly');
                // encrypt open
                $getting_name = $input['name'];
                $getting_email = $input['email'];
                $getting_city = $input['city'];

                Log::info('str password me kya aa rha hai' . $getting_name);
                $iv = $request->user_security_token;
                $f = fopen($iv, 'r');
                $publicKey = fread($f, 8192);
                openssl_public_encrypt($getting_name, $encrypted, $publicKey );
                $encrypteds = base64_encode($encrypted);
                log::info('encrypted me kya aa rha hai' . $encrypteds);
                
                $Apicreates = Api_key::create([
                    'name' => $encrypteds,
                    'email' =>$getting_email,
                    'city' => $getting_city,
                ]);
                $response = [
                    'success' => true,
                    'data' => new TokenAccessResource($Apicreates),
                    'message' => 'Token Added Successfully.'
                ];
            } else {
                $response = [
                    'success' => false,
                    'data' => '',
                    'message' => 'Token Already Exist.'
                ];
            }
            // if condition close
            return response()->json($response, 200);
        }
    }

    public function getting_token_new_token($get_token_id, $user_getting_id)
    {
        Log::info('new getting user id' . $get_token_id);
        Log::info('user getting me kya aa rha hai' . $user_getting_id);
        $decrypt_data = Api_key::where('id', $user_getting_id)->first();
        Log::info('new condition check');
        $encrypteds = $decrypt_data->name;
        $edit_find_email = $decrypt_data->email;
        $getting_city = $decrypt_data->city;
        // $edit_find_name = $decrypt_data->name;
        log::info('get token id me kya aa rha hai'.$get_token_id);
        $coming_priv_key = $get_token_id;
        log::info('what is coming in get token id'.$coming_priv_key);
        $f = fopen($coming_priv_key, 'r');
        $privKey = fread($f, 8192);
        log::info('now coming private key'.$privKey);
        openssl_private_decrypt(base64_decode($encrypteds), $decrypted, $privKey );
        log::info('decrypted value is coming'.$decrypted);
        log::info('getting value'.$decrypted);
        // echo '<h1>Decrypted String</h1>';
        // echo $decrypted;
      


        // $getting_decrypt_email_id = openssl_private_decrypt($edit_find_email, $decrypted, $privKey);
        // $getting_decrypt_city_id = openssl_private_decrypt($getting_city, $decrypted, $privKey);
        $response = [
            'success' => true,
            'data' => $decrypted,
            'getting_decrypt_data_email' => $edit_find_email,
            'getting_decrypt_name_id' => $decrypted,
            'getting_decrypt_city_id' => $getting_city,
            // 'getting_pwd_dcrypt' => $getting_decrypt_pwd,
            'message' => ' retrieved successfully encrypt data.',
        ];
        return response()->json($response, 200);
    }




    // public function generate(Request $request)
    // {

    //   $getting = new Encrypt;
    //   $getting->name = $request->name;
    //   $getting->save();
    //   $res = openssl_pkey_new();
    //   openssl_pkey_export($res, $privkey, "PassPhrase number 1"); {
    //   $pubkey = openssl_pkey_get_details($res);
    //   $pubkey = $pubkey["key"];
    //   return response()->download(public_path('privatekey.pem'));
    //   }
    // }

    // public function generate(Request $request)
    // {
    //     $res = openssl_pkey_new();
    //     openssl_pkey_export($res, $privkey, "PassPhrase number 1"); {
    //         $pubkey = openssl_pkey_get_details($res);
    //         $pubkey = $pubkey["key"];
    //         file_put_contents('privatekey.pem', $privkey);
    //         return response()->download(public_path('privatekey.pem'));
    //     }
    // }

    public function page()
    {
        return view('encrypt');
    }

    public function edit_token($id)
    {
        $getting_api = Api_key::find($id);
        Log::info("abhi hum edit token me in kr gye hai");
        if (is_null($getting_api)) {
            $response = [
                'success' => false,
                'data' => 'Empty',
                'message' => 'Token not found.'
            ];
            return response()->json($response, 404);
        } else {
            $response = [
                'success' => true,
                'data' => $getting_api,
                'message' => 'token retrieved successfully on edit function .'
            ];
            return response()->json($response, 200);
        }
    }

    public function get_ip(Request $request){
        return $user_ip_address=$request->ip();  
    }
}
