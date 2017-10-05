<?php

//You can use the below php code for the credit card payment :

//open connection
$ch = curl_init();

$live_client = 'AYab711SkWtKrlHxXOjSJOHnGhBGCIqL0SGppiOocN0teX5Ux2EiJbJASKzFtlkSkHt9Ren0DgJ5TbNd';
$live_secret = 'EAXt1xgyknxqBvRVXGeb-kBmhu0J7_GS8mMxO2-M6k76p6S8kMSx8wCvZV338TE8NqdE4Xb0giICb1aM';

$client = 'AelQxLMKBcLjwu9lvpKBGgIwyr2xIXIfuHSAHiQFa8mLuzr66s9mAa2bLH4XnyE-wuJmVAfMzmlYPWhN';
$secret = 'EIGQ0tPCRtlaPh5eCjjmFbzRccphfYqrlp0hhwwocDF4lhoSh8oXb5HcrxEQacUzd7l6BiXuu9iVVC5f';

//curl_setopt($ch, CURLOPT_URL, "https://api.paypal.com/v1/oauth2/token");
curl_setopt($ch, CURLOPT_URL, "https://api.sandbox.paypal.com/v1/oauth2/token");
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
curl_setopt($ch, CURLOPT_USERPWD, $client.":".$secret);
curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");

$result = curl_exec($ch);

if(empty($result))die("Error: No response.");
else
{
    $json = json_decode($result);
    print_r($json->access_token);
}



//$access_token = '';
//"A21AAGYiyzShGSvPyxoo6gtjNy5nfRrvSUGvYv-9QVy5cLx089_iy3O7vlIuaARjebXqwVwYS-dGH8EZtDbriCsa2CR24ZylA"
$access_token = $json->access_token;

// Now doing txn after getting the token 
$ch = curl_init();

$data = '{
  "intent":"sale",
  "redirect_urls":{
    "return_url":"http://<return URL here>",
    "cancel_url":"http://<cancel URL here>"
  },
  "payer": {
    "payment_method": "credit_card",
    "funding_instruments": [
      {
        "credit_card": {
          "number": "5500005555555559",
          "type": "mastercard",
          "expire_month": 12,
          "expire_year": 2018,
          "cvv2": 111,
          "first_name": "Joe",
          "last_name": "Shopper"
        }
      }
    ]
  },
  "transactions":[
    {
      "amount":{
        "total":"7.47",
        "currency":"USD"
      },
      "description":"This is the payment transaction description."
    }
  ]
}
';

//curl_setopt($ch, CURLOPT_URL, "https://api.paypal.com/v1/payments/payment");
curl_setopt($ch, CURLOPT_URL, "https://api.sandbox.paypal.com/v1/payments/payment");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data); 
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json","Authorization: Bearer ".$access_token));

$result = curl_exec($ch);


if(empty($result))die("Error: No response.");
else
{
    $json = json_decode($result);
    print_r($json);
}
