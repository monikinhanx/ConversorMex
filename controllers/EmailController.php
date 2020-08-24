<?php
    session_start();

    header('Content-Type: text/html; charset=UTF-8');

    include_once "models/Email.php";

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    require 'vendor/autoload.php';

    class EmailController{
        public function acao($rotas){
            switch($rotas){
                case "email":
                    $this->enviaEmail();
                break;
            }
        }

        //Método para envio de email
        private function enviaEmail(){
            $toemail = (isset($_POST['toemail'])) ? $_POST['toemail']: "";
            $from = (isset($_POST['from'])) ? $_POST['from']: "";
            $subject = (isset($_POST['subject'])) ? $_POST['subject']: "";
            $nome = (isset($_POST['nome'])) ? $_POST['nome'] : "";
            $email = (isset($_POST['email'])) ? $_POST['email']: "";
            $telefone = (isset($_POST['telefone'])) ? $_POST['telefone']: "";
            $empresa = (isset($_POST['empresa'])) ? $_POST['empresa']: "";
            $mensagem = (isset($_POST['message'])) ? $_POST['message']: "";

            $db = new Email();

            switch ($from) {
                case "contato@mexconsulting.com.br":
                    $nameFrom = 'Contato | Mex Consulting';
                    $origem = "Contato";
                    $message = "<img src='cid:topo-email' style=' width: 200px;height: auto;' alt='Topo Email'><br><br><h1 style='color: #415a78;'>Recebemos uma mensagem no site!</h1><br><br><strong>Nome:</strong> $nome<br/><strong>E-mail:</strong> $email<br/><strong>Telefone:</strong> $telefone<br/><strong>Empresa:</strong> $empresa<br/><strong>Mensagem:</strong> $mensagem<br><br><div style='background-color: #415a78;width: 100%;height: 3rem;'></div>";
                    $messagePlain = "==> Recebemos uma mensagem no site! Nome: $nome ======== E-mail:  $email ======== Telefone: $telefone ======== Empresa: $empresa ======== Mensagem:$mensagem";
                break;
            }

            $mail = new PHPMailer(true);
            
            try {
                $mail->SMTPDebug = SMTP::DEBUG_OFF;
                $mail->IsSMTP();
                $mail->Host = 'relay-hosting.secureserver.net';
                $mail->SMTPAuth = false;

                $mail->setFrom($from, $nameFrom);
                $mail->addAddress($toemail);
                $mail->AddEmbeddedImage('views/img/Logo_Mex.png',"topo-email","topo-email");

                $mail->isHTML(true);
                $mail->CharSet = 'UTF-8';
                $mail->Subject = $subject;
                $mail->Body    = $message;
                $mail->AltBody = $messagePlain;

                $mailresult = $mail->Send();
                
                switch ($from) {
                    case "contato@mexconsulting.com.br":
                        $_SESSION['mailresult'] = "Em breve nossos especialistas irão entrar em contato para dar mais informações.";
                        $db->contatos($nome,$email,$telefone,$empresa,$mensagem,true,$mailresult);
                    break;
                }
            }catch (Exception $e) {
                switch ($from) {
                    case "contato@mexconsulting.com.br":
                        $_SESSION['ErrorInfo'] = "Houve um erro enviando o email. Tente de novo mais tarde!";
                    break;
                }
                $db->falhas($origem,$nome,$email,$telefone,$empresa,$mensagem,false,$_SESSION['ErrorInfo']);
            }

            switch ($from) {
                case "contato@mexconsulting.com.br":
                    echo "<script>window.location.href = '/';</script>";
                break;
            }
        }
    }
?>