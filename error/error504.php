<?php

$go_url = "https://wespeakenglish.chat";
header("Location: $go_url");
 
	require("/www/wwwroot/school.wespeakenglish.chat/vendor/phpmailer/phpmailer/src/PHPMailer.php");
	require("/www/wwwroot/school.wespeakenglish.chat/vendor/phpmailer/phpmailer/src/SMTP.php");

    $mail = new \PHPMailer\PHPMailer\PHPMailer();
    $mail->isSMTP();                             // 使用SMTP
    $mail->Host = 'mail.wespeakenglish.net';                // SMTP服务器
    $mail->SMTPAuth = true;                      // 允许 SMTP 认证
    $mail->Username = 'noreply@wespeakenglish.net';                // SMTP 用户名  即邮箱的用户名
    $mail->Password = 'Email@2021@06';             
    $mail->SMTPSecure = "starttls";                    
    $mail->Port = 587;                            
	
    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );
    $mail->setFrom('noreply@wespeakenglish.net', 'Nginx 504');  //发件人
    $mail->addAddress('liyan.zhao@outlook.com', 'Leo');  // 收件人

    $mail->addReplyTo('noreply@wespeakenglish.net', 'info'); //回复的时候回复给哪个邮箱 建议和发件人一致

    //Content
    $mail->isHTML(true);                         
    $mail->Subject = '504 error: Server is unavailable';
    $mail->Body    = 'https://www.namecheap.com/support/knowledgebase/article.aspx/9366/2194/errors-500-502-503-504-and-508-reasons-and-ways-of-fixing/';
    $mail->AltBody = 'No Html';

    $mail->send();
//    exit(); 
?>