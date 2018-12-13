<?php
$config = array (	
		//应用ID,您的APPID。
		'app_id' => "2016091300503801",

		//商户私钥
		'merchant_private_key' => "MIIEowIBAAKCAQEAtAX9mo5dy8EpkPoncPNllJcV3d1qojGae9fDbH3joCT5rLRgCDEQ65ndTj2tpuZeURTm++kPvwDL4icv6KtjOSGlR3S/S55i87SBEcU3/kqFPkjFay2hf8kRs2vEudKTMSSeuFSxbaB8oBHXrnwkXQrn7edUMq6D6DDkaduUwuQgJF5rN+TSnevg129L+yVlcdhcO16/pdoMToq3NTka4aMWv1TTy6fP9ZMOGSIbea+Fb/tjxKOgYr9mFwdcLh8Jy5iFJmFulf91N1vdrDvNmyogn/bVvysj+B0Ucd2zsL3Z1ML8d+/5CuC9EurDwwt0I6W3su/LIuPfD92uud9CLQIDAQABAoIBAC7YeDvl3CaPZ1+gmO7dh4t5HWmUmpEGU2rypJnw0HD17jqw3WSEUCDe8yXPCwcpX1W+GLVo2n4bmPKKu/7RyootZTMJAFaPKrS8PfH/3ihAABhLG4ReZW45Rm/oFVM9pqvdGahh8aHTZ1rICdYP2+6STfQshE6D9zNOtcGOMFHeV0sfGunZDcXdjpJuTImw9qW5W0n6XCp+F7IAPuDZ5VQyAxt2oT7rqS2y/sWhIGuAFfX3d8YVLQ6xfdEoU4unhpJH4lXjJjuwVXj45tZYSrweH+naWL9HW7MyvwcJVXApORBVGkrXq3lkBltcA8UMN7UHGFhTEmI1E59KC9UhB4kCgYEA462+dBS5p0wSvgmrLX/Mta/JHUCMSiQCSxQKRMZY3zJZVRf4WEKZgMMLHinfh0yK713ITtH1omPta5B7EUUqQJpodiC+I/RhrnkuWgM4mI5u1MEqpBJVAxQScPNA5sGdUNZmVIBeRjzODzz51OdNB93ORJJygU547jY/Y9XkuQcCgYEAymqzNYoV45zambjjNSYXM++Swig4l7hE8Wmymd2tMUuvH0VnCi2QfAe/JCJMqmInmLZXBbvBMHb9vlI3jyaiyqqH7LXBnLrL5zUXJoraa7bTMZI09noRMph4AaXQ8S22sC3qK/nSRYm4QXbqQEr6AX6xFzUX7CvKagtmdtsE4isCgYBOdTkEV4ACnrx2sG5Ep52rnn/3jJ7Ljc0cgjNnr9JAV+Fg6XmzMIAlVxElu9hDj6TxB6hXQRMcesL+JjPCp8r/qDYHPxFG4pgmv6uxaRq/t5WJy2ceUxLolKomMi1TV7UWfkuVIwdZvOplZeXR8EMjStQh3TWISbpynE8L4FGJUQKBgQCon+LTQ0mg6FajoQaGjEjgF0CnRmJ+4h9tDaSf0bAqVOZ6opKIRBUNflaZf4lml4ZtDdnJCPSTr00Lc+qc8o7DTvUVGQVFz8d1wSCcFyiGezPNJAPDIUWaZLH0jXJWuMJsWpOuNtwY2fYJc0sKbGuBO03EC5L2SDTDyuN9OFAFKQKBgCszn+i3oS1QaH9LHJo9uffUZx8Uo+g7Z2fufZ/W0j2HrKys53OK2yxfZcyWd5LcWheRaedchI0tLvm3428m/oREW5PDQDXfAZPwyOoQNfYAQZ6vMuDRAc+m4E9l7T1qW74MuUhHMhEN7oLGFofueMExvMHDZh14UFgeIbgGIQgJ",
		
		//异步通知地址
		'notify_url' => "http://外网可访问网关地址/alipay.trade.page.pay-PHP-UTF-8/notify_url.php",
		
		//同步跳转
		'return_url' => "http://外网可访问网关地址/alipay.trade.page.pay-PHP-UTF-8/return_url.php",

		//编码格式
		'charset' => "UTF-8",

		//签名方式
		'sign_type'=>"RSA2",

		//支付宝网关
		'gatewayUrl' => "https://openapi.alipaydev.com/gateway.do",

		//支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
		'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAwEdYSOZ40a6opuG5F8pX7LJhe9tY+VrH6NPq1IY240zlGkejCCo9EX1WP1z00wV8wB+iWwPMA9nspqzDJtufSIFLwrOZqhIKX2Ml4wpQTFgH5NYpFGAYeHeouH1LQhtZtMF4D6RUnm79oWvvtxeBnpo6LqUvio3aTH2hv/HfqLKRSmFmn7sSeQOUoV/sRcqFbb+rWeDuUt0svxKWZWus+kA1Ud2zg5loQR1nAsoAYJwnBPdtl76MG1rKbW22fLsyEY2oBZZWmUV1piqp+gqoVAB35cmodPUodJBjDrMti5W36y2R7teDqqlly172zw57uJ/aK9VJTQm1VapKqZP86wIDAQAB",
);