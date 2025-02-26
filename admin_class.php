<?php
session_start();
ini_set('display_errors', 1);

class Action
{
    private $db;

    public function __construct()
    {
        ob_start();
        include 'db_connect.php';

        $this->db = $conn;
    }

    function __destruct()
    {
        $this->db->close();
        ob_end_flush();
    }

    function login()
    {
        extract($_POST);
        $qry = $this->db->query("SELECT * FROM users WHERE username = '$username' AND password = '" . md5($password) . "'");
        if ($qry->num_rows > 0) {
            $user = $qry->fetch_array();
            $_SESSION['login_name'] = $user['username'];
            $_SESSION['login_id'] = $user['id']; 
            foreach ($user as $key => $value) {
                if (!is_numeric($key)) {
                    $_SESSION['login_' . $key] = $value;
                }
            }
            return 1; 
        } else {
            return 3; 
        }
    }

    function login2()
    {
        extract($_POST);
        if (isset($email))
            $username = $email;
        $qry = $this->db->query("SELECT * FROM users where username = '" . $username . "' and password = '" . md5($password) . "' ");
        if ($qry->num_rows > 0) {
            foreach ($qry->fetch_array() as $key => $value) {
                if ($key != 'passwors' && !is_numeric($key))
                    $_SESSION['login_' . $key] = $value;
            }
            if ($_SESSION['login_alumnus_id'] > 0) {
                $bio = $this->db->query("SELECT * FROM alumnus_bio where id = " . $_SESSION['login_alumnus_id']);
                if ($bio->num_rows > 0) {
                    foreach ($bio->fetch_array() as $key => $value) {
                        if ($key != 'passwors' && !is_numeric($key))
                            $_SESSION['bio'][$key] = $value;
                    }
                }
            }
            if ($_SESSION['bio']['status'] != 1) {
                foreach ($_SESSION as $key => $value) {
                    unset($_SESSION[$key]);
                }
                return 2;
                exit;
            }
            return 1;
        } else {
            return 3;
        }
    }

    function logout()
    {
        session_destroy();
        foreach ($_SESSION as $key => $value) {
            unset($_SESSION[$key]);
        }
        header("location:homepage.php");
    }

    function logout2()
    {
        session_destroy();
        foreach ($_SESSION as $key => $value) {
            unset($_SESSION[$key]);
        }
        header("location:../index.php");
    }

    function save_user()
    {
        extract($_POST);
        $data = " name = '$name' ";
        $data .= ", username = '$username' ";
        if (!empty($password))
            $data .= ", password = '" . md5($password) . "' ";
        $data .= ", type = '$type' ";
        if ($type == 1)
            $establishment_id = 0;
        $data .= ", establishment_id = '$establishment_id' ";
        $chk = $this->db->query("Select * from users where username = '$username' and id !='$id' ")->num_rows;
        if ($chk > 0) {
            return 2;
            exit;
        }
        if (empty($id)) {
            $save = $this->db->query("INSERT INTO users set " . $data);
        } else {
            $save = $this->db->query("UPDATE users set " . $data . " where id = " . $id);
        }
        if ($save) {
            return 1;
        }
    }

    function delete_user()
    {
        extract($_POST);
        $delete = $this->db->query("DELETE FROM users where id = " . $id);
        if ($delete)
            return 1;
    }

    function signup()
    {
        extract($_POST);
        $data = " name = '" . $firstname . ' ' . $lastname . "' ";
        $data .= ", username = '$email' ";
        $data .= ", password = '" . md5($password) . "' ";
        $chk = $this->db->query("SELECT * FROM users where username = '$email' ")->num_rows;
        if ($chk > 0) {
            return 2;
            exit;
        }
        $save = $this->db->query("INSERT INTO users set " . $data);
        if ($save) {
            $uid = $this->db->insert_id;
            $data = '';
            foreach ($_POST as $k => $v) {
                if ($k == 'password')
                    continue;
                if (empty($data) && !is_numeric($k))
                    $data = " $k = '$v' ";
                else
                    $data .= ", $k = '$v' ";
            }
            if ($_FILES['img']['tmp_name'] != '') {
                $fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['img']['name'];
                $move = move_uploaded_file($_FILES['img']['tmp_name'], 'assets/uploads/' . $fname);
                $data .= ", avatar = '$fname' ";
            }
            $save_alumni = $this->db->query("INSERT INTO alumnus_bio set $data ");
            if ($data) {
                $aid = $this->db->insert_id;
                $this->db->query("UPDATE users set alumnus_id = $aid where id = $uid ");
                $login = $this->login2();
                if ($login)
                    return 1;
            }
        }
    }

    function update_account()
    {
        extract($_POST);
        $data = " name = '" . $firstname . ' ' . $lastname . "' ";
        $data .= ", username = '$email' ";
        if (!empty($password))
            $data .= ", password = '" . md5($password) . "' ";
        $chk = $this->db->query("SELECT * FROM users where username = '$email' and id != '{$_SESSION['login_id']}' ")->num_rows;
        if ($chk > 0) {
            return 2;
            exit;
        }
        $save = $this->db->query("UPDATE users set $data where id = '{$_SESSION['login_id']}' ");
        if ($save) {
            $data = '';
            foreach ($_POST as $k => $v) {
                if ($k == 'password')
                    continue;
                if (empty($data) && !is_numeric($k))
                    $data = " $k = '$v' ";
                else
                    $data .= ", $k = '$v' ";
            }
            if ($_FILES['img']['tmp_name'] != '') {
                $fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['img']['name'];
                $move = move_uploaded_file($_FILES['img']['tmp_name'], 'assets/uploads/' . $fname);
                $data .= ", avatar = '$fname' ";
            }
            $save_alumni = $this->db->query("UPDATE alumnus_bio set $data where id = '{$_SESSION['bio']['id']}' ");
            if ($data) {
                foreach ($_SESSION as $key => $value) {
                    unset($_SESSION[$key]);
                }
                $login = $this->login2();
                if ($login)
                    return 1;
            }
        }
    }

    function save_settings()
    {
        extract($_POST);
        $data = " name = '" . str_replace("'", "&#x2019;", $name) . "' ";
        $data .= ", email = '$email' ";
        $data .= ", contact = '$contact' ";
        $data .= ", about_content = '" . htmlentities(str_replace("'", "&#x2019;", $about)) . "' ";
        if ($_FILES['img']['tmp_name'] != '') {
            $fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['img']['name'];
            $move = move_uploaded_file($_FILES['img']['tmp_name'], 'assets/uploads/' . $fname);
            $data .= ", cover_img = '$fname' ";
        }

        // echo "INSERT INTO system_settings set ".$data;
        $chk = $this->db->query("SELECT * FROM system_settings");
        if ($chk->num_rows > 0) {
            $save = $this->db->query("UPDATE system_settings set " . $data);
        } else {
            $save = $this->db->query("INSERT INTO system_settings set " . $data);
        }
        if ($save) {
            $query = $this->db->query("SELECT * FROM system_settings limit 1")->fetch_array();
            foreach ($query as $key => $value) {
                if (!is_numeric($key))
                    $_SESSION['system'][$key] = $value;
            }

            return 1;
        }
    }

    function save_category()
    {
        extract($_POST);
        $user_id = $_SESSION['login_id'];
        $data = "name = '$name' ";
        $data .= ", user_id = '$user_id' ";
        if (empty($id)) {
            $save = $this->db->query("INSERT INTO categories set $data");
        } else {
            $save = $this->db->query("UPDATE categories set $data where id = $id");
        }
        if ($save)
            return 1;
    }

    function delete_category()
    {
        extract($_POST);
        $user_id = $_SESSION['login_id']; 
        $delete = $this->db->query("DELETE FROM categories where id = " . $id . " AND user_id = '$user_id'"); 
        if ($delete) {
            return 1;
        }
    }

    function save_house()
    {
        extract($_POST);
        $user_id = $_SESSION['login_id']; 
        $data = " house_no = '$house_no' ";
        $data .= ", description = '$description' ";
        $data .= ", category_id = '$category_id' ";
        $data .= ", price = '$price' ";
        $data .= ", capacity = '$capacity' ";
        $data .= ", occupancy_status = '$occupancy_status' ";
        $data .= ", address = '$address' ";
        $data .= ", user_id = '$user_id' "; 
        if (empty($id)) {
            $chk = $this->db->query("SELECT * FROM houses WHERE house_no = '$house_no' AND user_id = '$user_id'")->num_rows;
            if ($chk > 0) {
                return 2;
            }
            $save = $this->db->query("INSERT INTO houses set $data");
        } else {
            $chk = $this->db->query("SELECT * FROM houses WHERE house_no = '$house_no' AND id != '$id' AND user_id = '$user_id'")->num_rows;
            if ($chk > 0) {
                return 2;
            }
            $save = $this->db->query("UPDATE houses set $data where id = $id");
        }

        if ($save) {
            return 1;
        }
        return 0;
    }

    function delete_house()
    {
        extract($_POST);
        $user_id = $_SESSION['login_id'];
        $delete = $this->db->query("DELETE FROM houses where id = " . $id . " AND user_id = '$user_id'");
        if ($delete) {
            return 1;
        }
    }

    function save_tenant() {
        extract($_POST);
        $user_id = $_SESSION['login_id']; 
        $status = isset($house_id) && !empty($house_id) ? 1 : 0; 
        $data = " firstname = '$firstname' ";
        $data .= ", lastname = '$lastname' ";
        $data .= ", middlename = '$middlename' ";
        $data .= ", email = '$email' ";
        $data .= ", contact = '$contact' ";
        $data .= ", house_id = '$house_id' ";
        $data .= ", date_in = '$date_in' ";
        $data .= ", contract_start = '$contract_start' ";
        $data .= ", contract_end = '$contract_end' ";
        $data .= ", user_id = '$user_id' "; 
        $data .= ", status = '$status' "; 

        if (empty($id)) {
            $save = $this->db->query("INSERT INTO tenants set $data");
        } else {
            $save = $this->db->query("UPDATE tenants set $data where id = $id AND user_id = '$user_id'");
        }

        if ($save) {
            return 1;
        }
        return 0;
    }

    function get_houses() {
        $user_id = $_SESSION['login_id']; 
        $qry = $this->db->query("SELECT * FROM houses WHERE user_id = '$user_id'");
        $data = array();
        while ($row = $qry->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    function delete_tenant()
    {
        extract($_POST);
        $delete = $this->db->query("UPDATE tenants SET status = 0 WHERE id = " . $id);
        if ($delete) {
            $tenant = $this->db->query("SELECT * FROM tenants WHERE id = " . $id)->fetch_assoc();
            $house_id = $tenant['house_id'];
            $this->db->query("UPDATE houses SET occupancy_status = 0 WHERE id = $house_id");
            return 1;
        }
        return 0;
    }

    function get_tdetails() {
        extract($_POST);
        $data = array();
        $user_id = $_SESSION['login_id'];
        
        $qry = $this->db->query("SELECT t.*, CONCAT(t.lastname, ', ', t.firstname, ' ', t.middlename) AS name, h.house_no, h.price 
                                FROM tenants t 
                                INNER JOIN houses h ON h.id = t.house_id 
                                WHERE t.id = $id AND t.user_id = $user_id");
        
        if ($qry->num_rows > 0) {
            $tenant = $qry->fetch_assoc();
            foreach ($tenant as $k => $v) {
                if (!is_numeric($k)) {
                    $$k = $v;
                    $data[$k] = $v;
                }
            }
            
            $months = abs(strtotime(date('Y-m-d') . " 23:59:59") - strtotime($date_in . " 23:59:59"));
            $months = floor(($months) / (30 * 60 * 60 * 24));
            $data['months'] = $months;
            
            $payable = abs($price * $months);
            $data['payable'] = number_format($payable, 2);
            
            $paid = $this->db->query("SELECT SUM(amount) as paid FROM payments WHERE tenant_id = $id");
            $last_payment = $this->db->query("SELECT * FROM payments WHERE tenant_id = $id ORDER BY unix_timestamp(date_created) DESC LIMIT 1");
            
            $paid = $paid->num_rows > 0 ? $paid->fetch_array()['paid'] : 0;
            $data['paid'] = number_format($paid, 2);
            
            $data['last_payment'] = $last_payment->num_rows > 0 ? date("M d, Y", strtotime($last_payment->fetch_array()['date_created'])) : 'N/A';
            $data['outstanding'] = number_format($payable - $paid, 2);
            $data['price'] = number_format($price, 2);
            $data['name'] = ucwords($name);
            $data['rent_started'] = date('M d, Y', strtotime($date_in));
            
            return json_encode($data);
        } else {
            return json_encode(['error' => 'No tenant found with the given ID']);
        }
    }

    function save_payment() {
        extract($_POST);
        $data = "";
        foreach ($_POST as $k => $v) {
            if (!in_array($k, array('id', 'ref_code')) && !is_numeric($k)) {
                $data .= "$k=?, ";
            }
        }
        $data = rtrim($data, ", "); 
        $user_id = $_SESSION['login_id'];
        $params = array_values(array_filter($_POST, function($k) {
            return !in_array($k, array('id', 'ref_code')) && !is_numeric($k);
        }, ARRAY_FILTER_USE_KEY));
        $params[] = $user_id;
    
        $stmt = null;
        if (empty($id)) {
            $query = "INSERT INTO payments SET $data, user_id=?";
            $stmt = $this->db->prepare($query);
        } else {
            $query = "UPDATE payments SET $data WHERE id=? AND user_id=?";
            $params[] = $id;
            $stmt = $this->db->prepare($query);
        }
    
        if ($stmt) {
            $stmt->bind_param(str_repeat('s', count($params)), ...$params);
            if ($stmt->execute()) {
                return 1;
            }
        }
        return 0;
    }
    
    

    function delete_payment() {
        extract($_POST);
        $user_id = $_SESSION['login_id']; 
        $delete = $this->db->query("DELETE FROM payments where id = " . $id . " AND user_id = '$user_id'");
        if ($delete) {
                return 1;
        }
    }
}

?>