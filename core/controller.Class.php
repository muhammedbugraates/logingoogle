<?php

class Connect extends PDO
{
    public function __construct()
    {
        parent::__construct("mysql:host=localhost;dbname=google_login2", "root", "123456", array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
    }
}

class Controller{
    
    // Print data
    function printData($id){
        $db = new Connect;
        $user = $db->prepare('SELECT * FROM users ORDER BY id');
        $user->execute();
        $content = '
                    <table class="table">
                        <thead class="thead-light">
                            <th scope="col">First Name</th>
                            <th scope="col">Last Name</th>
                            <th scope="col">Avatar</th>
                            <th scope="col">Email</th>
                        </thead>
                    
                        <tbody>';

        while($userInfo = $user->fetch(PDO::FETCH_ASSOC)){
            $content .= '
                <tr>
                    <td>'. $userInfo['f_name'] .'</td>
                    <td>'. $userInfo['l_name'] .'</td>
                    <td><img style="max-width: 50px;" src="'. $userInfo['avatar'] .'" alt="avatar"</td>
                    <td>'. $userInfo['email'] .'</td>
                </tr>
            ';
        }

        $content .=     '</tbody>
                    </table>';
        return $content;
    }


    // Check if user is logged in
    function checkUserStatus($id, $sess){
        $db = new Connect();
        $user = $db->prepare("SELECT id FROM users WHERE id = :id AND session = :session");
        $user->execute(array(
            ':id' => intval($id),
            ':session' => $sess
        ));
        $userInfo = $user->fetch(PDO::FETCH_ASSOC);

        if(!isset($userInfo['id'])){
            return false;
        }else{
            return true;
        }
    }

    // generate char
    function generateCode($length)
    {
        $chars = "vwyABC01256";
        $code = "";
        $clean = strlen($chars) - 1;
        while (strlen($code) < $length) {
            $code .= $chars[mt_rand(0, $clean)];
        }
        return $code;
    }
    // Insert data
    function insertData($data)
    {
        $db = new Connect();
        $checkUser = $db->prepare('SELECT * FROM users WHERE email = :email');
        $checkUser->execute(array('email' => $data['email']));
        $info = $checkUser->fetch(PDO::FETCH_ASSOC);

        // If user is not in db
        if (!isset($info['id'])) {

            $session = $this->generateCode(10);

            $insertUser = $db->prepare('INSERT INTO users (f_name, l_name, avatar, email, password, session) 
            VALUES (:f_name, :l_name, :avatar, :email, :password, :session)');
            
            $insertUser->execute(array(
                ':f_name' => $data['givenName'],
                ':l_name' => $data['familyName'],
                ':avatar' => $data['avatar'],
                ':email' => $data['email'],
                ':password' => $this->generateCode(5),
                ':session' => $session
            ));

            if ($insertUser) {
                setcookie('id', $db->lastInsertId(), time() + 60 * 60 * 24 * 30, "/", NULL);
                setcookie('sess', $session, time() + 60 * 60 * 24 * 30, "/", NULL);

                header('Location: index.php');
                exit();
            } else {
                return 'Error inserting user';
            }
        } else {
            setcookie('id', $info['id'], time() + 60 * 60 * 24 * 30, "/", NULL);
            setcookie('sess', $info['session'], time() + 60 * 60 * 24 * 30, "/", NULL);
            header('Location: index.php');
            exit();
        }
    }
}
