<?php

session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once './Database.php';
require_once './operatii_db.php';

if(! isset($_SESSION['username'])){
    header('Location: ./login-user.php');
    exit();
}
else
    if(! isset($_SESSION['admin'])){
        if(isset($_SESSION['editor'])){
            header('Location: ./homepage-editor.php'); //modify for editor and author!
            exit();
        }
        else
            if(isset($_SESSION['author'])){
                header('Location: ./homepage-author.php'); //modify for editor and author!
                exit();
            }
        else
            header('Location: ./homepage.php'); //modify for editor and author!
        exit();
    }
else
    //var_dump($_POST);
    if(isset($_POST['action']))
    {
        if($_POST['action'] == "delete_user") {
            try {
                $id_to_delete = intval($_POST['id']);

                $userData = OperatiiDB::read('users', '*', "WHERE user_id = $id_to_delete");

                if (!empty($userData)) {
                    $user = $userData[0];

                    OperatiiDB::delete('users', 'user_id = :id', ['id' => $id_to_delete]);

                    if (!empty($user['author_id'])) {
                        OperatiiDB::delete('authors', 'author_id = :aid', ['aid' => $user['author_id']]);
                    }
                    if (!empty($user['editor_id'])) {
                        OperatiiDB::delete('editors', 'editor_id = :eid', ['eid' => $user['editor_id']]);
                    }
                    if (!empty($user['administrator_id'])) {
                        OperatiiDB::delete('administrators', 'administrator_id = :adminid', ['adminid' => $user['administrator_id']]);
                    }

                }
                } catch (PDOException $e) {
                    die("Deletion failed: " . $e->getMessage());
                }

        }

        elseif($_POST['action'] == "create_user")
        {
            //var_dump($_POST);
         try {
             $param = [
                'username' => $_POST['username'],
                'email' => $_POST['email'],
                'password' => password_hash($_POST['password'], PASSWORD_BCRYPT)
             ];
            //var_dump($param);
            OperatiiDB::create('users', $param); //creare de utilizator
            } catch (PDOException $e) {
                die(" Connection failed: " . $e->getMessage());
            }
        }

        elseif($_POST['action'] == "create_editor")
        {
            //var_dump($_POST);
         try {
             $param = [
                'username' => $_POST['username'],
                'email' => $_POST['email'],
                'password' => password_hash($_POST['password'], PASSWORD_BCRYPT),
             ];
             $editor_param = [
                 'salary' => $_POST['salary'],
                 'manager_id' => $_POST['admin_id']
             ];
             //trebuie sa fac select din tabelul adim si sa leg cheia de id
            //var_dump($param);
            $last_id = OperatiiDB::create('editors', $editor_param); //creare de utilizator
            $param['editor_id'] = $last_id;
            OperatiiDB::create('users', $param); //creare de utilizator
            } catch (PDOException $e) {
                die(" Connection failed: " . $e->getMessage());
            }
        }

        elseif($_POST['action'] == "create_author")
        {
            //var_dump($_POST);
         try {
             $param = [
                'username' => $_POST['username'],
                'email' => $_POST['email'],
                'password' => password_hash($_POST['password'], PASSWORD_BCRYPT),
             ];
             $author_param = [
                 'commission' => $_POST['commission']
             ];
             //trebuie sa fac select din tabelul adim si sa leg cheia de id
            //var_dump($param);
            $last_id = OperatiiDB::create('authors', $author_param); //creare de utilizator
            $param['author_id'] = $last_id;
            OperatiiDB::create('users', $param); //creare de utilizator
            } catch (PDOException $e) {
                die(" Connection failed: " . $e->getMessage());
            }
        }

        elseif($_POST['action'] == "create_admin")
        {
            //var_dump($_POST);
         try {
             $param = [
                'username' => $_POST['username'],
                'email' => $_POST['email'],
                'password' => password_hash($_POST['password'], PASSWORD_BCRYPT),
             ];
             $admin_param = [
                 'salary' => $_POST['salary']
             ];
             //trebuie sa fac select din tabelul adim si sa leg cheia de id
            //var_dump($param);
            $last_id = OperatiiDB::create('administrators', $admin_param); //creare de utilizator
            $param['administrator_id'] = $last_id;
            OperatiiDB::create('users', $param); //creare de utilizator
            } catch (PDOException $e) {
                die(" Connection failed: " . $e->getMessage());
            }
        }

        elseif($_POST['action'] == "alter_user") {
            try {
                $user_id = intval($_POST['user_id']);
                $conditie = 'user_id = :user_id';
                $paramCond = ['user_id' => $user_id];

                $param = [];
                if(!empty($_POST['username'])) $param['username'] = $_POST['username'];
                if(!empty($_POST['email']))    $param['email'] = $_POST['email'];
                if(!empty($_POST['password'])) $param['password'] = password_hash($_POST['password'], PASSWORD_BCRYPT);

                if(!empty($param)) {
                    OperatiiDB::update('users', $param, $conditie, $paramCond);
                }

                if(!empty($_POST['role_type'])) {
                    $role = $_POST['role_type'];
                    $new_role_id = null;

                    if($role == 'editor') {
                        $new_role_id = OperatiiDB::create('editors', [
                            'salary' => $_POST['salary'],
                            'manager_id' => $_POST['admin_id']
                        ]);
                        OperatiiDB::update('users', ['editor_id' => $new_role_id], $conditie, $paramCond);
                    }
                    elseif($role == 'author') {
                        $new_role_id = OperatiiDB::create('authors', [
                            'commission' => $_POST['commission']
                        ]);
                        OperatiiDB::update('users', ['author_id' => $new_role_id], $conditie, $paramCond);
                    }
                    elseif($role == 'admin') {
                        $new_role_id = OperatiiDB::create('administrators', [
                            'salary' => $_POST['salary']
                        ]);
                        OperatiiDB::update('users', ['administrator_id' => $new_role_id], $conditie, $paramCond);
                    }
                }

                header("Location: admin-pannel.php");
                exit();
            } catch (PDOException $e) {
        die("Update failed: " . $e->getMessage());
    }
}
        header("Location: admin-pannel.php?");
        exit(); // ca sa apara in tabel
    }

try {
  // get all the users
    $record = OperatiiDB::read('users', '*', 'WHERE 1 = 1'); //success, asa apelezi functia
    $record_editor = OperatiiDB::read('editors','*', 'WHERE 1 = 1'); 
    $record_admin = OperatiiDB::read('administrators','*', 'WHERE 1 = 1'); 
    $record_author = OperatiiDB::read('authors','*', 'WHERE 1 = 1'); 
   
    
    //var_dump($record);


} catch (PDOException $e) {
    die(" Connection failed: " . $e->getMessage());
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>homepage</title>
</head>

<body>
<header>
<nav>
<a href="homepage.php">Home</a> |
<a href="login-user.php">Login</a> |
<!--modify nav bar based on which user uses it-->
</nav>
</header>

    <h1>Admin pannel</h1>
    <h2>Tabele</h2>
    <h3>Utilizatori</h3>
    <table border='1'>
        <thead>
        <tr>
            <th>User ID</th>
            <th>Username</th>
            <th>Author ID</th>
            <th>Admin ID</th>
            <th>Editor ID</th>
            <th>email</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($record as $record): ?>
            <tr>
                <td><?php echo htmlspecialchars($record['user_id']) ?></td>
                <td><?php echo htmlspecialchars($record['username']??' ') ?></td>
                <td><?php echo htmlspecialchars($record['author_id']??' ') ?></td> <!--??' ' daca e null il transf in ' '-->
                <td><?php echo htmlspecialchars($record['administrator_id']??' ') ?></td>
                <td><?php echo htmlspecialchars($record['editor_id']??' ') ?></td>
                <td><?php echo htmlspecialchars($record['email']??' ') ?></td>
            </tr>
        <?php endforeach;?>
        <!--merge sa fac asa un tabel cu toate info-->
        </tbody>
        </table>

    <h3>Editori</h3>
    <table border='1'>
        <thead>
        <tr>
            <th>Editor ID</th>
            <th>Data Angajarii</th>
            <th>Admin ID</th>
            <th>Salariu</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($record_editor as $record_editor): ?>
            <tr>
                <td><?php echo htmlspecialchars($record_editor['editor_id']) ?></td>
                <td><?php echo htmlspecialchars($record_editor['hire_date']??' ') ?></td>
                <td><?php echo htmlspecialchars($record_editor['manager_id']??' ') ?></td> <!--??' ' daca e null il transf in ' '-->
                <td><?php echo htmlspecialchars($record_editor['salary']??' ') ?></td>
            </tr>
        <?php endforeach;?>
        <!--merge sa fac asa un tabel cu toate info-->
        </tbody>
        </table>

    <h3>Administratori</h3>
    <table border='1'>
        <thead>
        <tr>
            <th>Administrator ID</th>
            <th>Data Angajarii</th>
            <th>Salariu</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($record_admin as $record_admin): ?>
            <tr>
                <td><?php echo htmlspecialchars($record_admin['administrator_id']) ?></td>
                <td><?php echo htmlspecialchars($record_admin['hire_date']??' ') ?></td>
                <td><?php echo htmlspecialchars($record_admin['salary']??' ') ?></td>
            </tr>
        <?php endforeach;?>
        <!--merge sa fac asa un tabel cu toate info-->
        </tbody>
        </table>

    <h3>Autori</h3>
    <table border='1'>
        <thead>
        <tr>
            <th>Autor ID</th>
            <th>Comision</th>
            <th>Numar de articole</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($record_author as $record_author): ?>
        <tr>
                <td><?php echo htmlspecialchars($record_author['author_id']) ?></td>
                <td><?php echo htmlspecialchars($record_author['commission']??' ') ?></td>
                <td>
                    <?php
                        $no_articles = OperatiiDB::read(
                        'articles',
                        'COUNT(*) AS total',
                        'WHERE author_id = ' . intval($record_author['author_id'])
                        );

                        echo htmlspecialchars($no_articles[0]['total'] ?? 0);
                    ?>
                </td>

            </tr>
        <?php endforeach;?>
        <!--merge sa fac asa un tabel cu toate info-->
        </tbody>
        </table>
    <h2>Operatii utilizatori</h2>

    <h4>Stergere</h4>
        <form action="admin-pannel.php" method="post">
        <input type="hidden" name="action" value="delete_user"> <!-- fac switch in php in functie de $_POST[action]-->
        <label for="id">ID utilizator</label><br>
        <input type="text" name="id" id="id">
        <input type="submit" value="Submit">
        </form>

    <h4>Modificare / Promovare</h4>
    <p>Blank pentru valoarea default, ID este necesar pentru orice operatie.</p>
    <form action="admin-pannel.php" method="post">
        <input type="hidden" name="action" value="alter_user">

        <label for="user_id"><strong>ID-ul utilizatorului (Necesar)</strong></label><br>
        <input type="text" name="user_id" id="user_id" required><br><br>

        <label for="username">Username nou</label><br>
        <input type="text" name="username" id="username"><br>

        <label for="email">E-mail nou</label><br>
        <input type="text" name="email" id="email"><br>

        <label for="password">Parola noua</label><br>
        <input type="text" name="password" id="password"><br><br>

        <fieldset>
            <legend>Promovare (Completeaza doar daca vrei sa schimbi rolul)</legend>
            <label for="role_type">Transforma in:</label>
            <select name="role_type">
                <option value="">-- Fara schimbare rol --</option>
                <option value="editor">Editor</option>
                <option value="author">Autor</option>
                <option value="admin">Administrator</option>
            </select><br><br>

            <label for="salary">Salariu (pt Editor/Admin)</label><br>
            <input type="text" name="salary" id="salary"><br>

            <label for="admin_id">ID Manager (pt Editor)</label><br>
            <input type="text" name="admin_id" id="admin_id"><br>

            <label for="commission">Comision (pt Autor)</label><br>
            <input type="text" name="commission" id="commission">
        </fieldset>

        <br><input type="submit" value="Salveaza Modificarile">
    </form>

    <h4>Adaugare utilizator</h4>
        <p>Blank pentru valoarea default</p>
        <form action="admin-pannel.php" method="post">
        <input type="hidden" name="action" value="create_user"> <!-- fac switch in php in functie de $_POST[action]-->
        <label for="email">e-mail</label><br>
        <input type="text" name="email" id="email"><br>

        <label for="username">Username</label><br>
        <input type="text" name="username" id="username"><br>

        <label for="password">Password</label><br>
        <input type="text" name="password" id="password">

        <input type="submit" value="Submit">
        </form>
   
    <h4>Adaugare editor</h4>
        <p>Blank pentru valoarea default</p>
        <form action="admin-pannel.php" method="post">
        <input type="hidden" name="action" value="create_editor"> <!-- fac switch in php in functie de $_POST[action]-->
        <label for="email">e-mail</label><br>
        <input type="text" name="email" id="email"><br>

        <label for="username">Username</label><br>
        <input type="text" name="username" id="username"><br>

        <label for="password">parola</label><br>
        <input type="text" name="password" id="password"><br>

        <label for="salary">salariu</label><br>
        <input type="text" name="salary" id="salary"><br>

        <label for="admin_id">Id administrator</label><br>
        <input type="text" name="admin_id" id="admin_id">

        <input type="submit" value="Submit">
        </form>

    <h4>Adaugare autor</h4>
        <p>Blank pentru valoarea default</p>
        <form action="admin-pannel.php" method="post">
        <input type="hidden" name="action" value="create_author"> <!-- fac switch in php in functie de $_POST[action]-->
        <label for="email">e-mail</label><br>
        <input type="text" name="email" id="email"><br>

        <label for="username">Username</label><br>
        <input type="text" name="username" id="username"><br>

        <label for="password">parola</label><br>
        <input type="text" name="password" id="password"><br>

        <label for="commission">comision</label><br>
        <input type="text" name="commission" id="commission"><br>

        <input type="submit" value="Submit">
        </form>

    <h4>Adaugare administrator</h4>
        <p>Blank pentru valoarea default</p>
        <form action="admin-pannel.php" method="post">
        <input type="hidden" name="action" value="create_admin"> <!-- fac switch in php in functie de $_POST[action]-->
        <label for="email">e-mail</label><br>
        <input type="text" name="email" id="email"><br>

        <label for="username">Username</label><br>
        <input type="text" name="username" id="username"><br>

        <label for="password">parola</label><br>
        <input type="text" name="password" id="password"><br>

        <label for="salary">salariu</label><br>
        <input type="text" name="salary" id="salary"><br>

        <input type="submit" value="Submit">
        </form>
</body>
</html>
