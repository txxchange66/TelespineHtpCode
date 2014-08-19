<?php

require_once("include/db.class.php");

class common extends db
{

    static public $config;

    function __construct()
    {

        include("config.php");

        $this->config = $txxchange_config;
    }

    static function action_parser($action, $entity = "")
    {

        $sx = simplexml_load_file("config_txxchange.xml");

        $arr = array();

        $arr_property = array("name", "path", "checkLogin");

        foreach($sx->action as $a)
        {

            if($action == $a['name'])
            {

                foreach($sx->action as $e)
                {

                    foreach($arr_property as $p)
                    {

                        if($e[$p])
                        {

                            $abc = explode("=", $e[$p]->asxml());

                            if(is_array($abc))
                            {

                                $arr['action'][$p][] = preg_replace('/^"|"$/', "", $abc[1]);
                            }
                            else
                            {

                                $arr['action'][$p][] = "";
                            }
                        }
                        else
                        {

                            $arr['action'][$p][] = "";
                        }
                    }
                }

                foreach($a->$entity as $e)
                {

                    foreach($arr_property as $p)
                    {

                        if($e[$p])
                        {

                            $abc = explode("=", $e[$p]->asxml());

                            if(is_array($abc))
                            {

                                $arr[$entity][$p][] = preg_replace('/^"|"$/', "", $abc[1]);
                            }
                            else
                            {



                                $arr[$entity][$p][] = "";
                            }
                        }
                        else
                        {

                            $arr[$entity][$p][] = "";
                        }
                    }
                }
            }
        }

        return $arr;
    }

    function get_state()
    {

        $state.='<option  value="0" SELECTED>Please Select</option>';

        $result_state = @mysql_query("select id,state_name from state");

        while($row_state = @mysql_fetch_array($result_state))
        {

            $state.="<option  value=" . $row_state['id'] . ">" . $row_state['state_name'] . "</option>";
        }

        return($state);
    }

    function get_city()
    {

        $city.='<option  value="0" SELECTED>Please Select</option>';

        $result_city = @mysql_query("select id,city_name from city");

        while($row_city = @mysql_fetch_array($result_city))
        {

            $city.='<option  value=' . $row_city['id'] . ' SELECTED>' . $row_city['city_name'] . '</option>';
        }

        return($city);
    }

    function get_year()
    {

        for($i = 1970; $i <= date('Y'); $i++)
        {

            if($i == $year)
            {

                $option_year .= "<option  value=$i SELECTED>$i</option>";
            }
            else
            {

                $option_year .= "<option  value=$i>$i</option>";
            }
        }

        return($option_year);
    }

    function get_month()
    {

        for($i = 1; $i <= 12; $i++)
        {

            if($i == $month)
            {

                $option_month .= "<option  value=$i SELECTED>$i</option>";
            }
            else
            {

                $option_month .= "<option  value=$i>$i</option>";
            }
        }

        return($option_month);
    }

    function get_day()
    {

        for($i = 1; $i <= 31; $i++)
        {

            if($i == $day)
            {

                $option_day .= "<option  value=$i SELECTED>$i</option>";
            }
            else
            {

                $option_day .= "<option  value=$i>$i</option>";
            }
        }

        return($option_day);
    }

    function formatDate($dt, $strFormat = null)
    {
        $formatedDate = null;
        if($dt == null || $dt == "")
        {
            return "";
        }
        if($strFormat != null)
        {
            $formatedDate = date($strFormat, strtotime($dt));
        }
        else
        {
            $formatedDate = date('m/d/Y h:i A', strtotime($dt));
        }

        return $formatedDate;
    }

    /**
     * Formats date according to the timezone region provided in $regionstr parameter
     * 
     * @param String $datestr The date string that is to be converted/formatted (preferably in Y-m-d H:i:S format)
     * @param String $regionstr The region to which this date needs to be formatted
     *                          (if nothing is provided then, the regioned mentioned in config.php will be used)
     * @param String $formatstr Date format string like y/m/d H:i:S
     *                          (if nothing is provided then, the format mentioned in config.php will be used)
     * @return String The formatted date
     */
    function formatDateExtra($datestr, $regionstr = null, $formatstr = null)
    {
        if($regionstr == null)
        {
            $region = $this->config['timezone']['frontend']['region'];
        }
        else
        {
            $region = $regionstr;
        }

        if($formatstr == null)
        {
            $format = $this->config['timezone']['frontend']['dateformat'];
        }
        else
        {
            $format = $formatstr;
        }

        $sql = "SELECT CONVERT_TZ('" . $datestr . "', '{$this->config['timezone']['default']['region']}', '{$region}') as converteddate";

        $resultarray = $this->fetch_array($this->execute_query($sql));

        return $this->formatDate($resultarray['converteddate'], $format);
    }

    function get_action($action)
    {

        $login_arr = self::action_parser($action, 'action');

        $pos = array_search($action, $login_arr['action']['name']);

        return $login_arr['action']['name'][$pos];
    }

    function get_checkLogin($action)
    {

        $login_arr = self::action_parser($action, 'action');

        $pos = array_search($action, $login_arr['action']['name']);

        return $login_arr['action']['checkLogin'][$pos];
    }

    function get_module_name($action)
    {

        $login_arr = self::action_parser($action, 'module');

        return $login_arr['module']['name'][0];
    }

    function get_module($action, $module = "")
    {

        $login_arr = self::action_parser($action, 'module');

        //$pos =  array_search($module, $login_arr['module']['name']); 

        return $login_arr['module']['path'][0];
    }

    function get_submodule($action)
    {

        $login_arr = self::action_parser($action, 'submodule');

        //$pos =  array_search($submodule, $login_arr['submodule']['name']); 

        return $login_arr['submodule']['path'][0];
    }

    function get_template($action, $template)
    {

        $login_arr = self::action_parser($action, 'template');

        $pos = array_search($template, $login_arr['template']['name']);
        if($pos === false)
        {
            return false;
        }

        return $login_arr['template']['path'][$pos];
    }

    function build_template($template_path, $replace = "")
    {
        $flag = true;
        if(self::value('action') != "")
        {
            $template = self::get_template(self::value('action'), 'header');
            if($template === false)
            {
                $flag = false;
            }
        }
        else
        {
            $template = "template/header.php";
        }
        if($flag === true && $template_path == $template)
        {
            return self::clinic_logo();
        }
        $content = file_get_contents($template_path);

        while(is_array($replace) && list($key, $value) = each($replace))
        {

            $patterns = '/<!' . $key . '>/';

            $value = (string) $value;

            if(empty($value) === false)
            {

                $content = preg_replace($patterns, $value, $content);
            }
            else
            {

                $content = preg_replace($patterns, $value, $content);
            }
        }

        /*

          $patterns = '/<![^-].*?>/';

          $content = preg_replace($patterns, "", $content); */

        return $content;
    }

    function value($str)
    {

        global $_REQUEST;

        if(isset($_REQUEST[$str]) && trim($_REQUEST[$str]) != "")
        {
            return htmlspecialchars(stripslashes(trim($_REQUEST[$str])), ENT_QUOTES);
        }
        else
        {
            return "";
        }
    }

    function show_error($error, $color = 'red')
    {
        if(is_array($error) && count($error) > 0)
        {
            $error = implode("<br>", $error);
            $error = "<span style='color:{$color};font-weight:bold;padding:0px;margin:0px;' >" . $error . '</span>';
            return $error;
        }
        else
        {

            return "";
        }
    }

    function show_error_notbold($error, $color = 'red')
    {
        if(is_array($error) && count($error) > 0)
        {
            $error = implode("<br>", $error);
            $error = "<span style='color:{$color};padding:0px;margin:0px;'>" . $error . '</span>';
            return $error;
        }
        else
        {

            return "";
        }
    }

    function fillForm($formArray, $populate = false)
    {

        $replace = array();

        if(is_array($formArray))
        {

            foreach($formArray as $key => $value)
            {

                if(is_array($populate))
                {

                    $replace[$key] = $populate[$key];
                }
                elseif($populate == true)
                {

                    $replace[$key] = common::value($key);
                }
                else
                {

                    $replace[$key] = $value;
                }
            }
        }

        return $replace;
    }

    function fillTableArray($tableArray)
    {

        $replace = array();

        if(is_array($tableArray))
        {

            foreach($tableArray as $key => $value)
            {

                $replace[$key] = common::value($value);
            }
        }

        return $replace;
    }

    function check_for_empty_value($valid_for_empty_value)
    {

        if(is_array($valid_for_empty_value))
        {

            $str = "";

            foreach($valid_for_empty_value as $key => $value)
            {

                if(self::value($key) == "")
                {

                    echo self::value($key);

                    $str .= $value;
                }
            }

            return $str;
        }
        else
            return false;
    }

    function check_email($address)
    {

        // check an email address is possibly valid

        if(ereg("^[^@ ]+@[^@ ]+\.[^@ ]+$", $address, $trashed))
            return true;
        else
            return false;
    }

    function build_select_option($list, $selected = "", $key = "", $value = "", $where = "")
    {



        $option = "";



        if(is_array($list))
        {

            foreach($list as $option_value => $option_field)
            {

                if(trim($option_value) == trim($selected))
                {

                    $option .= "<option value='" . $option_value . "' selected >" . $option_field . "</option>";
                }
                else
                {

                    $option .= "<option value='" . $option_value . "'>" . $option_field . "</option>";
                }
            }



            return $option;
        }
        elseif(is_string($list))
        {



            if(trim($key) != "" && trim($value) != "")
            {

                if(@mysql_num_rows(@mysql_query("SHOW TABLES LIKE '" . trim($list) . "'")) == 1)
                {

                    $result = $this->select(trim($list), "", "*", $where, "", "");

                    while($row = @mysql_fetch_array($result))
                    {

                        if(trim($selected) == trim($row[$key]))
                        {

                            $option .= "<option value='" . $row[$key] . "' selected >" . $row[$value] . "</option>";
                        }
                        else
                            $option .= "<option value='" . $row[$key] . "'>" . $row[$value] . "</option>";
                    }

                    return $option;
                }

                else
                {

                    return $option;
                }
            }
            else
            {

                return $option;
            }
        }
        else
        {

            return $option;
        }
    }

    function sortorder($table, $column)
    {

        $where = '1 = 1 ORDER BY ' . $column . ' ';

        $res = $this->select(parent::$config['table'][$table], "", "*", $where, "");

        return $res;
    }

    function lengthtcorrect($text, $length)
    {

        $str = substr($text, 0, $length - 3);

        if($length < strlen($text))
        {

            $str = $str . '...';
        }

        return $str;
    }

    function chk_login($username, $password)
    {

        if(!empty($username) && !empty($password))
        {
            /********** TXM-25 *********/
            //fetch the clinic id of the current user
            $resultsetclinicid = parent::execute_query("
                SELECT
                    u.user_id,
                    u.usertype_id,
                    u.username,
                    u.status,
                    cu.clinic_id
                FROM
                    user AS u
                INNER JOIN clinic_user AS cu
                    ON u.user_id = cu.user_id
                WHERE
                    u.username = '{$username}'
            ");
            $userclinicdata = parent::fetch_object($resultsetclinicid);
            
            //check if clinic made online payment
            $resultsetpaymentmode = parent::select("provider_subscription", "", "clinic_id, user_id", "clinic_id = '{$userclinicdata->clinic_id}'");

            if(parent::num_rows($resultsetpaymentmode) > 0)
            {
                //check if clinic has unsubscribed
                $rsunsubscribed = parent::execute_query("SELECT user_subs_id, unsubscribed FROM provider_subscription WHERE clinic_id = '{$userclinicdata->clinic_id}' AND unsubscribed = 'yes'");

                if(parent::num_rows($rsunsubscribed) > 0)
                {
                    //there are two tables in which the subscription expiry date is maintained
                    if($userclinicdata->usertype_id != '1')
                    {
                        $table = "provider_subscription";
                    }
                    else
                    {
                        $table = "patient_subscription";
                    }

                    //check if the billing cycle is over or not.
                    $rssubscriptionstatus = parent::execute_query("
                        SELECT
                            user_id,
                            user_subs_id
                        FROM
                            {$table}
                        WHERE
                            clinic_id = '{$userclinicdata->clinic_id}'
                        AND
                            subs_end_datetime < NOW()
                    ");

                    if(parent::num_rows($rssubscriptionstatus) > 0)
                    {
                        //deactivate all the associated users
                        $sql = "
                            select 
                                clinic_user.user_id  as user_id 
                            from 
                                clinic_user, user 
                            where 
                                clinic_user.user_id = user.user_id 
                                and clinic_user.clinic_id = '{$userclinicdata->clinic_id}' 
                                and user.usertype_id = 2";

                        $res = parent::execute_query($sql);

                        if(parent::num_rows($res) > 0)
                        {
                            while($row = parent::fetch_row($res))
                            {
                                $sqluser = "update user set trial_status=null, free_trial_date=null, status = '2' where user_id = " . $row['user_id'];
                                parent::execute_query($sqluser);
                            }
                        }

                        //update the 'status' column in database table
                        parent::update("clinic", array('status' => '2'), "clinic_id = {$userclinicdata->clinic_id} or parent_clinic_id = {$userclinicdata->clinic_id}");

                        //update the patient_subscription table with unsubscribe information
                        parent::update("patient_subscription", array('subs_status' => '2'), "clinic_id = {$userclinicdata->clinic_id}");

                        //update the provider_subscription table with unsubscribe information
                        parent::update("provider_subscription", array('subs_status' => '2'), "clinic_id = {$userclinicdata->clinic_id}");
                    }
                }
            }

            /********** TXM-25 changes ends *********/
            
            // check clinic status for Therapist and Patient.

            $private_key = parent::$config['private_key'];
            $where_user = " username  = '{$username}' AND AES_DECRYPT(UNHEX(password),'{$private_key}') = '{$password}'  AND ( status=1 or status=2 ) ";
            $result_user = parent::select(parent::$config['table']['user'], "", "*", $where_user, "", "");
            $row_num_user = @mysql_num_rows($result_user);

            if($row_num_user > 0)
            {

                $row_user = @mysql_fetch_array($result_user);
                // Decrypt data
                $row_user['password'] = parent::decrypt_data($row_user['password']);
                // End Decrypt
                if($row_user['usertype_id'] == 2 || $row_user['usertype_id'] == 1 || $row_user['admin_access'] == '1')
                {
                    $sql_clinic = "select clinic_id from clinic_user where user_id = '{$row_user['user_id']}' ";
                    $result_clinic = @mysql_query($sql_clinic);
                    if(@mysql_num_rows($result_clinic) > 0)
                    {
                        $row_clinic = @mysql_fetch_array($result_clinic);
                        $clinic_status = parent::get_field($row_clinic['clinic_id'], 'clinic', 'status');
                        if($clinic_status != '1')
                        {
                            header("location:index.php?action=logout");
                            exit();
                        }
                    }
                    else
                    {
                        header("location:index.php?action=logout");
                        exit();
                    }
                }
            }
            // End


            $where = " username  = '" . $username . "' AND AES_DECRYPT(UNHEX(password),'{$private_key}') = '" . $password . "'  AND (status  = 1 OR (usertype_id=1 AND (status=1 OR status = 2)))";
            //status=2 is added for login of discharge patient as UC 2 of release 2.4.1
            $result = parent::select(parent::$config['table']['user'], "", "*", $where, "", "");

            $row_num = @mysql_num_rows($result);
            if($row_num > 0)
            {
                $row = @mysql_fetch_array($result);
                // Decrypt data
                $row['password'] = parent::decrypt_data($row['password']);
                // End Decrypt
                if(trim($row["password"]) == $password)
                {


                    if($row['usertype_id'] == 4)
                    {
                        return $row['usertype_id'];
                    }
                    elseif(isset($_SESSION['tmp_username']))
                    {
                        return $row['usertype_id'];
                    }
                    else
                    {
                        if($row['session_id'] != session_id())
                        {
                            return 0;
                        }
                    }

                    return $row['usertype_id'];
                }
                else
                    return 0;
            }
            else
            {

                $returnValue = self::override_login($username, $password);
                return $returnValue;
            }
        }

        return 0;
    }

    /**
     * Override normal login if users status is in discharge status and user has subscribed for e-Rehab program.
     */
    static function override_login($username, $password)
    {

        if(!empty($username) && !empty($password))
        {
            // check existance of username and password in user table.
            $private_key = $config['private_key'];
            $userQuery = "SELECT * FROM user WHERE username  = '" . $username . "' AND AES_DECRYPT(UNHEX(password),'{$private_key}') = '" . $password . "' AND status = 2 and usertype_id = 1 ";
            $result = @mysql_query($userQuery);
            $row_num = @mysql_num_rows($result);
            if($row_num > 0)
            {
                $row = @mysql_fetch_array($result);
                // Decrypt data
                $row['password'] = parent::decrypt_data($row['password']);
                // End Decrypt
                if(trim($row["password"]) == $password)
                {
                    $sql = "select * from program_user where u_id = '{$row['user_id']}' and p_status = 1 ";
                    $resultSql = @mysql_query($sql);
                    if(@mysql_num_rows($resultSql) > 0)
                    {
                        return $row['usertype_id'];
                    }
                }
            }
        }
        return 0;
    }

    function row_highlight()
    {

        static $i = 1;

        if($i % 2 == 0)
        {

            $str = "class=brwn-bg-inside-hilite";
        }
        else
        {

            $str = "";
        }

        $i++;

        return $str;
    }

    function list_field_table($table_arr = "")
    {

        if(is_string($table_arr))
        {

            $str = $table_arr;

            $table_arr = array();

            $table_arr[] = $str;
        }

        if(sizeof($table_arr) > 0)
        {

            foreach($table_arr as $key => $value)
            {

                $result = @mysql_query("SHOW COLUMNS FROM " . $table_arr[$key]);

                while($row = @mysql_fetch_array($result))
                {

                    $field[] = $row['Field'];
                }
            }

            return $field;
        }
        else
        {

            return 0;
        }
    }

    function generateCode($length = 6)
    {

        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789";

        $code = "";

        while(strlen($code) < $length)
        {

            $code .= $chars[mt_rand(0, strlen($chars))];
        }

        return $code;
    }

    function table_heading($field_arr, $field, $query_string = "")
    {

        if($this->value('search') != "")
            $search = "&search={$this->value('search')}";

        elseif($this->value('restore_search') != "")
        {

            $search = "&search={$this->value('search')}";
        }

        if(is_array($query_string))
        {
            foreach($query_string as $key => $value)
            {
                $search .= "&{$key}={$value}";
            }
        }
        if($this->value('search') == "" && $this->value('restore_search') != "")
        {

            $_REQUEST['search'] = $this->value('restore_search');
        }

        if(is_array($field_arr))
        {

            foreach($field_arr as $key => $value)
            {

                if($this->value('sort') != "")
                {

                    $field = $this->value('sort');
                }

                if($key == $field)
                {

                    if($this->value('order') == "desc")
                    {

                        $field_arr[$key] = "<a href='index.php?action={$this->value('action')}&sort={$key}{$search}'>{$value}&nbsp;&nbsp;<img src='images/sort_desc.gif'/></a> ";
                    }
                    else
                    {

                        $field_arr[$key] = "<a href='index.php?action={$this->value('action')}&sort={$key}&order=desc{$search}'>{$value}&nbsp;&nbsp;<img src='images/sort_asc.gif'/></a> ";
                    }
                }
                else
                {

                    $field_arr[$key] = "<a href='index.php?action={$this->value('action')}&sort={$key}{$search}'>{$value}</a> ";
                }
            }
        }

        return $field_arr;
    }

    function userInfo($field = "", $id = "")
    {

        if($id != "")
        {
            $query = "select * from user where user_id = '{$id}' ";
            $result = $this->execute_query($query);
            if(is_resource($result))
            {
                if($this->num_rows($result) > 0)
                {
                    $row = $this->fetch_array($result);
                    // Encrypt data
                    $encrypt_field = array('name_title', 'name_first', 'name_last', 'password', 'address', 'address2', 'city', 'state', 'zip', 'phone1', 'phone2', 'fax');
                    $row = parent::decrypt_field($row, $encrypt_field);
                    // End Encryption
                    return $row[$field];
                }
                return "";
            }
            return "";
        }
        if(!(isset($_SESSION['username']) && $_SESSION['username'] != ""))
        {

            return "";
        }

        $sql = " SELECT * FROM user WHERE username =  '{$_SESSION['username']}' and status != 3 ";
        $res = @mysql_query($sql);
        $row = @mysql_fetch_array($res);
        // Encrypt data
        $encrypt_field = array('name_title', 'name_first', 'name_last', 'password', 'address', 'address2', 'city', 'state', 'zip', 'phone1', 'phone2', 'fax');
        $row = parent::decrypt_field($row, $encrypt_field);
        // End Encryption

        if(is_array($row))
        {

            if($field != "")
            {

                return $row[$field];
            }
            else
                return $row;
        }
        return "";
    }

    function tree($table_name, $table_name2, $id1 = 0, $style1 = 0, $question_id, $not_parent)
    {



        if($not_parent)
        {

            $query = 'select * from ' . $table_name . ' where question_id =' . $question_id . ' order by id';
        }
        else
        {

            $query = 'select * from ' . $table_name . ' where parent_id=' . $id1 . ' and question_id =' . $question_id . ' order by id';
        }

        $query;

        $result = @mysql_query($query);

        $num = @mysql_num_rows($result);

        $arr = array();

        $arr1 = array();

        $p = 0;

        $flag = 0;

        $top = -1;

        $i = 0;

        $created_time = 0;

        while($row = @mysql_fetch_array($result))
        {

            $i++;

            $id = $row["id"];

            $arr[++$top] = $id;

            while($top != -1)
            {

                $id = $arr[$top--];

                $c = 0;

                $no_of_view = 0;



                $arr3 = array();

                $query = "select * from $table_name where id = $id ";

                $result4 = @mysql_query($query);

                if($row4 = @mysql_fetch_array($result4))
                {

                    $i_d = $row4["id"];

                    /* for the accumulative count of no of views */

                    $no_of_view = $no_of_view + $row4['no_of_view'];

                    $created_time = $row4['createdtime'];



                    while($i_d != 0)
                    {

                        $query = "select * from $table_name where id = $i_d ";

                        $result3 = @mysql_query($query);

                        $row3 = mysql_fetch_array($result3);

                        $arr3[$c] = $row3["subject"];

                        $i_d = $row3["parent_id"];

                        $c++;
                    }

                    $str = "";

                    for($l = $c - 1; $l >= 0; $l--)
                    {

                        if($style1 == 0)
                        {

                            if($l > 0)
                                $str = $str . "&nbsp;&nbsp;&nbsp;";
                            else
                                $str = $str . $arr3[$l];
                        }

                        if($style1 == 1)
                        {

                            if($l > 0)
                                $str = $str . $arr3[$l] . "->";
                            else
                                $str = $str . $arr3[$l];
                        }
                    }



                    $sql = 'SELECT first_name,last_name FROM users WHERE id =' . $row4['user_id'];



                    $result5 = @mysql_query($sql);

                    $row5 = @mysql_fetch_array($result5);

                    $arr1[$p][$flag] = $id;

                    $flag = 1;

                    $arr1[$p][$flag] = $str;

                    $flag = 2;

                    if($row4['anonymous'] == 1)
                    {

                        $arr1[$p][$flag] = '';
                    }
                    else
                    {

                        $arr1[$p][$flag] = $row5['first_name'] . ' ' . $row5['last_name'];
                    }

                    $flag = 3;

                    $arr1[$p][$flag] = $row4['createdtime'];

                    $flag = 4;

                    $arr1[$p][$flag] = $row4['createdtime'];

                    $flag = 5;

                    $arr1[$p][$flag] = nl2br($row4['content']);

                    $k = $flag;

                    $flag = 0;

                    $p++;
                }

                $j = $p - 1;

                /* for the accumulative count of no of views */

                $arr1[$j][$k + 1] = $no_of_view;

                /* for the latest post in the particular thread */

                $arr1[$j][$k + 2] = $created_time;

                $sql = "select id from $table_name where parent_id = $id order by id desc";

                $result2 = mysql_query($sql);

                while($row2 = mysql_fetch_array($result2))
                {

                    $id = $row2["id"];

                    $arr[++$top] = $id;
                }
            }

            break;
        }





        return $arr1;
    }

    // This function helps to populate the form fields as well as Table fields
    function assignValueToArrayFields($arrToAssign, $arrFromAssign = '', $modeOfAssigning = '', $replaceArray = '', $slash_option = '')
    {
        $is_return = 0;
        if(is_array($replaceArray) && (count($replaceArray) > 0))
        {
            $replace = &$replaceArray;
        }
        else
        {
            $is_return = 1;
            $arr = array();
            $replace = &$arr;
        }
        $formArray = $arrToAssign;
        if(empty($arrFromAssign))
        {
            $arrFromAssign = $_POST;
        }
        switch($modeOfAssigning)
        {

            case '0':
                foreach($formArray as $key => $value)
                {
                    if(!empty($value))
                    {
                        $replace[$value] = $this->getAbsoluteValue($arrFromAssign[$value], $slash_option);
                    }
                }
                break;

            case '1':
                foreach($formArray as $key => $value)
                {
                    if(!empty($key))
                    {
                        $replace[$key] = $this->getAbsoluteValue($arrFromAssign[$value], $slash_option);
                    }
                }
                break;

            case '2':
                foreach($formArray as $key => $value)
                {
                    if(!empty($value))
                    {
                        $replace[$value] = $this->getAbsoluteValue($arrFromAssign[$key], $slash_option);
                    }
                }
                break;

            default:
                foreach($formArray as $key => $value)
                {
                    if(!empty($key))
                    {
                        $replace[$key] = $this->getAbsoluteValue($arrFromAssign[$key], $slash_option);
                    }
                }
                break;
        } // Switch Ends

        if($is_return == '1')
        {
            return $replace;
        }
    }

// Function assignValueToArrayFields Ends
    // Function to remove white spaces and slashes
    function getAbsoluteValue($str, $slash_option)
    {
        $retun_value = "";
        if((isset($str)) && (trim($str) != "") && (!empty($str)))
        {
            if(is_string($str))
            {
                switch($slash_option)
                {
                    case 'insert':
                    case 'update':
                        $retun_value = htmlspecialchars(stripslashes(trim($str)), ENT_QUOTES);
                        break;
                    case '0':
                        $retun_value = stripslashes(htmlspecialchars_decode(trim($str), ENT_QUOTES));
                        break;
                    case 'select':
                    default:
                        $retun_value = htmlspecialchars_decode(trim($str), ENT_QUOTES);
                        break;
                }
            }
        }
        return $retun_value;
    }

// Function getAbsoluteValue Ends
    // Function to add a element to an array
    // Both array must be associated Array
    function addToArray($existingArray, $newArray)
    {
        $result = array_merge($existingArray, $newArray);
        return $result;
    }

    // Function to remove a element from an array
    // Both array must be associated Array
    // e.g. $clinicData = $this->removeFromArray($newRowArr, array('password'=>''))
    function removeFromArray($existingArray, $newArray)
    {
        $arr = array();
        foreach($newArray as $newKey => $newValue)
        {
            foreach($existingArray as $key => $value)
            {
                if($key != $newKey)
                {
                    $arr[$key] = $value;
                }
            }
        }
        return $arr;
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $existingArray like array('first_name'=>'Manoj', 'last_name'=>'Verma', 'username'=>'abc', 'password'=>'abcpwd')
     * @param unknown_type $removingElementArray like array('first_name', 'password')
     * @param unknown_type ($mode != 2) if u want to remove the element from existing array and ($mode==2) if u want to select those elements from the existing array.
     * @return unknown
     */
    function operateArrayElements($existingArray, $removingElementArray, $action = 'select')
    {
        $arr = array();
        foreach($existingArray as $key => $value)
        {
            switch($action)
            {
                case 'select':
                    foreach($removingElementArray as $value2)
                    {
                        if($key == $value2)
                        {
                            $arr[$key] = $value;
                        }
                    }
                    break;

                case 'remove':
                    foreach($removingElementArray as $value2)
                    {
                        if($key == $value2)
                        {
                            unset($existingArray[$key]);
                        }
                    }
                    break;

                case 'modify':
                    foreach($removingElementArray as $key2 => $value2)
                    {
                        if($key == $key2)
                        {
                            $existingArray[$key] = $value2;
                        }
                    }
                    break;

                // Default operation is 'select'
                default:
                    foreach($removingElementArray as $value2)
                    {
                        if($key == $value2)
                        {
                            $arr[$key] = $value;
                        }
                    }
                    break;
            }
        }
        switch($action)
        {
            case 'select':
                return $arr;
                break;

            case 'remove':
                return $existingArray;
                break;

            case 'modify':
                return $existingArray;
                break;

            // Default operation is 'select'
            default:
                return $arr;
                break;
        }
    }

    /**
      Function to output a separator either through whitespace, or with an image
     */
    function putSpace($height = '1', $image, $width = '100%')
    {
        return '<img src="images/' . $image . '" border="0" alt="" width="' . $width . '" height="' . $height . '">';
    }

    /**
     * Function to 
     *
     * @param array_type $arr
     * @param array_Reference_type $replace
     * @param boolean_type $range
     */

    /**
     * 	$this->putWhiteSpaces(array('14'),&$replace);
     * 	$this->putWhiteSpaces(array('5','14'), &$replace, true, 'pixel_silver.gif', 'silverSpace');
     * 	$this->putWhiteSpaces(array('5','14', '30', '80'), &$replace, false, 'pixel_black.gif', 'blackSpace');
     *  In template -- <!whiteSpace14> or <!silverSpace10> or <!blackSpace80>
     */
    function putWhiteSpaces($arr, $replace, $range = true, $image = '', $text = 'whiteSpace')
    {
        switch($image)
        {
            case '':
                $img = 'pixel_trans.gif';
                break;
            case 'silver':
                $img = 'pixel_silver.gif';
                break;
            case 'black';
                $img = 'pixel_black.gif';
                break;
            default:
                $img = $image;
                break;
        }
        if($range == true)
        {
            $min = (count($arr) == 2) ? $arr[0] : '0';
            $max = (count($arr) == 2) ? $arr[1] : $arr[0];
            for($height = $min; $height <= $max; $height++)
            {
                $key = $text . $height;
                $replace[$key] = $this->putSpace($height, $img);
            }
        }
        else
        {
            foreach($arr as $height)
            {
                $key = $text . $height;
                $replace[$key] = $this->putSpace($height, $img);
            }
        }
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $sourceArray
     * @param unknown_type $targetArray
     */
    function convertArrayToReplace($sourceArray, $replace)
    {
        if((is_array($sourceArray)) && (count($sourceArray) > 0))
        {
            foreach($sourceArray as $key => $value)
            {
                $replace[$key] = $value;
            }
        }
    }

    /**
     * Function to make template for header
     * @desc 
     */
    function clinic_logo()
    {

        //$header_1 = '<div id="sysHeader"><img src="images/logo2.gif" width="191" height="66" alt="Tx Xchange" class="headLogo" /></div>';


        $user_id = self::userInfo('user_id');
        $user_type_id = self::userInfo('usertype_id');
        $user_agreement = self::userInfo('agreement');
        $clinic_channel = self::clinic_channel($user_id);
        if($clinic_channel == 1)
        {
            $logotx = 'txlogo';
        }
        else
        {
            $logotx = 'wxlogo';
        }

        $header_1 = "<div id='line'><div id='" . $logotx . "' style='float:left; padding-left:55px;' ></div><div id='subheader' style='float:right; padding-right:55px;' ><div id='cliniclogo'></div></div><div style='clear:both;'></div></div>";
        $query = " select clinic_id from clinic_user where user_id = '{$user_id}' ";

        $result = @mysql_query($query);
        if($row = @mysql_fetch_array($result))
        {

            $query = " select * from clinic where clinic_id = '{$row['clinic_id']}' ";
            $result_2 = @mysql_query($query);
            if($row_1 = @mysql_fetch_array($result_2))
            {
                if($row_1['clinic_logo'] == "")
                {
                    $flag = $this->referral_sent_count();
                    if($flag == 1 && $user_type_id == 1 && $user_agreement == 1)
                    {
                        $final_logo = "<div id='" . $logotx . "' style='float:left; padding-left:55px;' ></div>";
                        $temp = 1;
                        if($this->value('action') == 'patient_subscription')
                            $temp = 2;
                        if($this->value('action') == 'update_paypal_profile')
                            $temp = 2;

                        if($temp == 1)
                        {
                            $referral_link = "";
                            $referral_link = "<div style='padding-top:43px; line-height:15px;'><a href='javascript:void(0);' onclick='GB_showCenter(\"\", \"/index.php?action=sendreferral\",450,720);'><img alt='' src='images/btn-clickhere.jpg' style='float:left; padding-right:5px;' border='0'/></a>to refer family, friends, and co-workers to {$row_1['clinic_name']}.<br/> We appreciate your referrals.</div>";
                        }
                        //header 3 is for patient section header for referral link apperance
                        $header_3 = "<div id='line'>{$final_logo}<div id='subheader' style='float:right;'>{$referral_link}</div><div style='clear:both;'></div></div>";
                        return $header_3;
                        exit;
                    }
                    else
                    {
                        return $header_1;
                    }
                }
                $clinic_logo = $row_1['clinic_logo'];
                $clinic_link = "";
                if(trim($row_1['clinic_website_address']) != "")
                {
                    //echo "in first condition";exit;
                    $clinic_link = trim($row_1['clinic_website_address']);
                    $header_2 = "<div id='line'>
                                <div id='" . $logotx . "' style='float:left; padding-left:55px;' ></div>
                                <div id='subheader' style='float:right; padding-right:55px;' >
                                <a href='{$clinic_link}' target='new' style='cursor: hand;' ><div id='cliniclogo' style='background-image:url(/asset/images/clinic_logo/{$clinic_logo});' ></div></a>
                                </div>
                                <div style='clear:both;'></div>
                             </div>
                             
                             ";

                    if(!empty($clinic_logo))
                    {
                        //$final_logo="<div class='cliniclogo_patient' style='float:left; padding-left:35px;padding-top:10px;' ><a href='{$clinic_link}' target='new' style='cursor: hand;' ><img alt='' src='/asset/images/clinic_logo/{$clinic_logo}' border='0'/></a></div>";
                        // Clinic Logo Position

                        $final_logo = "<div class='cliniclogo_patient' style='float:left; padding-left:35px;' ><table><tr><td style='text-align:center; height:120px;'><a href='{$clinic_link}' target='new' style='cursor: hand;' ><img alt='' src='/asset/images/clinic_logo/{$clinic_logo}' border='0'/></a></td></tr></table></div>";
                    }
                    else
                    {
                        $final_logo = "<div id='" . $logotx . "' style='float:left; padding-left:55px;' ></div>";
                    }
                    $temp = 1;
                    if($this->value('action') == 'patient_subscription')
                        $temp = 2;
                    if($this->value('action') == 'update_paypal_profile')
                        $temp = 2;

                    if($temp == 1)
                    {
                        $referral_link = "";
                        if($this->referral_sent_count())
                            $referral_link = "<div style='padding-top:43px; line-height:15px;'><a href='javascript:void(0);' onclick='GB_showCenter(\"\", \"/index.php?action=sendreferral\",450,720);'><img alt='' src='images/btn-clickhere.jpg' style='float:left; padding-right:5px;' border='0'/></a>to refer family, friends, and co-workers to {$row_1['clinic_name']}.<br/>We appreciate your referrals.</div>";
                    }
                    //header 3 is for patient section header for referral link apperance
                    $header_3 = "<div id='line'>{$final_logo}<div id='subheader' style='float:right;'>{$referral_link}</div><div style='clear:both;'></div></div>";
                }
                else
                {
                    //echo "in second condition";exit;
                    $header_2 = "<div id='line'>
                                <div id='" . $logotx . "' style='float:left; padding-left:55px;' ></div>
                             
                             <div id='subheader' style='float:right; padding-right:55px;' >
                                <div id='cliniclogo' style='background-image:url(/asset/images/clinic_logo/{$clinic_logo});' ></div>
                             </div>
                             <div style='clear:both;'></div>
                             </div>
                             ";
                    if(!empty($clinic_logo))
                    {

                        //$final_logo="<div class='cliniclogo_patient' style='float:left; padding-left:35px;padding-top:10px;' ><img alt='' src='/asset/images/clinic_logo/{$clinic_logo}'/></div>";
                        $final_logo = "<div class='cliniclogo_patient' style='float:left; padding-left:35px;padding-top:10px;' ><table><tr><td style='text-align:center; height:120px;'><a href='{$clinic_link}' target='new' style='cursor: hand;' ><img alt='' src='/asset/images/clinic_logo/{$clinic_logo}' border='0'/></a></td></tr></table></div>";
                    }
                    else
                    {
                        $final_logo = "<div id='" . $logotx . "' style='float:left; padding-left:55px;' ></div>";
                    }

                    $temp = 1;
                    if($this->value('action') == 'patient_subscription')
                        $temp = 2;
                    if($this->value('action') == 'update_paypal_profile')
                        $temp = 2;

                    if($temp == 1)
                    {
                        $referral_link = "";
                        if($this->referral_sent_count())
                            $referral_link = "<div style='padding-top:43px; line-height:15px;'><a href='javascript:void(0);' onclick='GB_showCenter(\"\", \"/index.php?action=sendreferral\",450,720);'><img alt='' src='images/btn-clickhere.jpg' style='float:left; padding-right:5px;' border='0'/></a>to refer family, friends, and co-workers to {$row_1['clinic_name']}.<br/>We appreciate your referrals.</div>";
                    }

                    //header 3 is for patient section header for referral link apperance
                    $header_3 = "<div id='line'>{$final_logo}<div id='subheader' style='float:right;'>{$referral_link}</div><div style='clear:both;'></div></div>";
                }
            }
        }

        if($user_type_id == 1)
        {
            if($user_agreement == 1)
                return $header_3;
            else
                return $header_2;
            exit;
        }
        else if($user_type_id == 2)
        {
            return $header_2;
            exit;
        }
        else
        {
            return $header_1;
            exit;
        }
    }

    function cliniclogo($clinic_id)
    {
        $sql = "select  clinic_logo from clinic where clinic_id =" . $clinic_id;
        $query = @mysql_query($sql);
        $row = mysql_fetch_object($query);
        if($row->clinic_logo != '')
        {
            $logo = $this->config['images_url'] . '/asset/images/clinic_logo/' . $row->clinic_logo;
            return $logourl = "<img src='" . $logo . "'   />";
        }
        else
        {
            return "";
        }
    }

    function clinic_channel($user_id)
    {
        $query = " select clinic_id from clinic_user where user_id = '{$user_id}' ";
        $result = @mysql_query($query);
        if($row = @mysql_fetch_array($result))
        {
            $query = " select * from clinic where clinic_id = '{$row['clinic_id']}' ";
            $result_2 = @mysql_query($query);
            if($row_1 = @mysql_fetch_array($result_2))
            {
                return $row_1['clinic_channel'];
            }
        }
        return 1;
    }

    /**
     * prints multiple variables/arrays/objects
     *
     */
    function printR()
    {
        $arguments = func_get_args();
        foreach($arguments as $argument)
        {
            echo "<pre>";
            print_r($argument);
            echo "</pre>";
        }
    }

    /**
     * var_dumps multiple variables
     *
     */
    function varDump()
    {
        $arguments = func_get_args();
        foreach($arguments as $argument)
        {
            echo "<pre>";
            var_dump($argument);
            echo "</pre>";
        }
    }

    /**
     * Internal utility function used by getAllTimeZones
     * 
     * @param type $offset
     * @return type
     */
    /* private function formatOffset($offset)
      {
      $hours = $offset / 3600;
      $remainder = $offset % 3600;
      $sign = $hours > 0 ? '+' : '-';
      $hour = (int) abs($hours);
      $minutes = (int) abs($remainder / 60);

      if($hour == 0 AND $minutes == 0)
      {
      $sign = ' ';
      }

      return $sign . str_pad($hour, 2, '0', STR_PAD_LEFT) . ':' . str_pad($minutes, 2, '0');
      } */

    /**
     * Returns a key value pair of all timezones in PHP
     * 
     * @return associative array of timezones
     */
    /* function getAllTimezones()
      {
      $timezones = array();

      $dt = new DateTime('now', new DateTimeZone('UTC'));

      foreach(DateTimeZone::listIdentifiers() as $tz)
      {
      $current_tz = new DateTimeZone($tz);
      $offset = $current_tz->getOffset($dt);
      $transition = $current_tz->getTransitions($dt->getTimestamp(), $dt->getTimestamp());
      $abbr = $transition[0]['abbr'];

      $timezones[$tz] = $tz . " [" . $abbr . " " . $this->formatOffset($offset) . "]";
      }

      $this->printR($timezones);
      } */
    function getAllTimezones()
    {
        return array(
            "Pacific/Enderbury" => "(GMT+13:00) Phoenix Islands Time (Pacific/Enderbury)",
            "Pacific/Tongatapu" => "(GMT+13:00) Tonga Time (Pacific/Tongatapu)",
            "Pacific/Chatham" => "(GMT+12:45) Chatham Standard Time (Pacific/Chatham)",
            "Asia/Kamchatka" => "(GMT+12:00) Magadan Time (Asia/Kamchatka)",
            "Pacific/Auckland" => "(GMT+12:00) New Zealand Standard Time (Pacific/Auckland)",
            "Pacific/Fiji" => "(GMT+12:00) Fiji Time (Pacific/Fiji)",
            "Pacific/Norfolk" => "(GMT+11:30) Norfolk Islands Time (Pacific/Norfolk)",
            "Pacific/Guadalcanal" => "(GMT+11:00) Solomon Islands Time (Pacific/Guadalcanal)",
            "Australia/Lord_Howe" => "(GMT+10:30) Lord Howe Standard Time (Australia/Lord_Howe)",
            "Australia/Brisbane" => "(GMT+10:00) Australian Eastern Standard Time (Australia/Brisbane)",
            "Australia/Sydney" => "(GMT+10:00) Australian Eastern Standard Time (Australia/Sydney)",
            "Australia/Adelaide" => "(GMT+09:30) Australian Central Standard Time (Australia/Adelaide)",
            "Australia/Darwin" => "(GMT+09:30) Australian Central Standard Time (Australia/Darwin)",
            "Asia/Seoul" => "(GMT+09:00) Korean Standard Time (Asia/Seoul)",
            "Asia/Tokyo" => "(GMT+09:00) Japan Standard Time (Asia/Tokyo)",
            "Asia/Hong_Kong" => "(GMT+08:00) Hong Kong Time (Asia/Hong Kong)",
            "Asia/Kuala_Lumpur" => "(GMT+08:00) Malaysia Time (Asia/Kuala Lumpur)",
            "Asia/Manila" => "(GMT+08:00) Philippine Time (Asia/Manila)",
            "Asia/Shanghai" => "(GMT+08:00) China Standard Time (Asia/Shanghai)",
            "Asia/Singapore" => "(GMT+08:00) Singapore Standard Time (Asia/Singapore)",
            "Asia/Taipei" => "(GMT+08:00) Taipei Standard Time (Asia/Taipei)",
            "Australia/Perth" => "(GMT+08:00) Australian Western Standard Time (Australia/Perth)",
            "Asia/Bangkok" => "(GMT+07:00) Indochina Time (Asia/Bangkok)",
            "Asia/Jakarta" => "(GMT+07:00) Western Indonesia Time (Asia/Jakarta)",
            "Asia/Saigon" => "(GMT+07:00) Indochina Time (Asia/Saigon)",
            "Asia/Rangoon" => "(GMT+06:30) Myanmar Time (Asia/Rangoon)",
            "Asia/Dacca" => "(GMT+06:00) Bangladesh Time (Asia/Dacca)",
            "Asia/Yekaterinburg" => "(GMT+06:00) Yekaterinburg Time (Asia/Yekaterinburg)",
            "Asia/Kathmandu" => "(GMT+05:45) Nepal Time (Asia/Katmandu)",
            "Asia/Kolkata" => "(GMT+05:30) India Standard Time (Asia/Calcutta)",
            "Asia/Colombo" => "(GMT+05:30) India Standard Time (Asia/Colombo)",
            "Asia/Baku" => "(GMT+05:00) Azerbaijan Summer Time (Asia/Baku)",
            "Asia/Karachi" => "(GMT+05:00) Pakistan Time (Asia/Karachi)",
            "Asia/Tashkent" => "(GMT+05:00) Uzbekistan Time (Asia/Tashkent)",
            "Asia/Kabul" => "(GMT+04:30) Afghanistan Time (Asia/Kabul)",
            "Asia/Tehran" => "(GMT+04:30) Iran Daylight Time (Asia/Tehran)",
            "Asia/Dubai" => "(GMT+04:00) Gulf Standard Time (Asia/Dubai)",
            "Asia/Tbilisi" => "(GMT+04:00) Georgia Time (Asia/Tbilisi)",
            "Asia/Yerevan" => "(GMT+04:00) Armenia Time (Asia/Yerevan)",
            "Europe/Moscow" => "(GMT+04:00) Moscow Standard Time (Europe/Moscow)",
            "Africa/Nairobi" => "(GMT+03:00) East Africa Time (Africa/Nairobi)",
            "Asia/Baghdad" => "(GMT+03:00) Arabian Standard Time (Asia/Baghdad)",
            "Asia/Beirut" => "(GMT+03:00) Eastern European Summer Time (Asia/Beirut)",
            "Asia/Jerusalem" => "(GMT+03:00) Israel Daylight Time (Asia/Jerusalem)",
            "Asia/Kuwait" => "(GMT+03:00) Arabian Standard Time (Asia/Kuwait)",
            "Asia/Riyadh" => "(GMT+03:00) Arabian Standard Time (Asia/Riyadh)",
            "Europe/Athens" => "(GMT+03:00) Eastern European Summer Time (Europe/Athens)",
            "Europe/Bucharest" => "(GMT+03:00) Eastern European Summer Time (Europe/Bucharest)",
            "Europe/Helsinki" => "(GMT+03:00) Eastern European Summer Time (Europe/Helsinki)",
            "Europe/Istanbul" => "(GMT+03:00) Eastern European Summer Time (Europe/Istanbul)",
            "Europe/Minsk" => "(GMT+03:00) Further-eastern European Time (Europe/Minsk)",
            "Africa/Cairo" => "(GMT+02:00) Eastern European Time (Africa/Cairo)",
            "Africa/Johannesburg" => "(GMT+02:00) South Africa Standard Time (Africa/Johannesburg)",
            "Europe/Amsterdam" => "(GMT+02:00) Central European Summer Time (Europe/Amsterdam)",
            "Europe/Berlin" => "(GMT+02:00) Central European Summer Time (Europe/Berlin)",
            "Europe/Brussels" => "(GMT+02:00) Central European Summer Time (Europe/Brussels)",
            "Europe/Paris" => "(GMT+02:00) Central European Summer Time (Europe/Paris)",
            "Europe/Prague" => "(GMT+02:00) Central European Summer Time (Europe/Prague)",
            "Europe/Rome" => "(GMT+02:00) Central European Summer Time (Europe/Rome)",
            "Africa/Algiers" => "(GMT+01:00) Central European Time (Africa/Algiers)",
            "Africa/Casablanca" => "(GMT+01:00) Western European Summer Time (Africa/Casablanca)",
            "Europe/Dublin" => "(GMT+01:00) Irish Summer Time (Europe/Dublin)",
            "Europe/Lisbon" => "(GMT+01:00) Western European Summer Time (Europe/Lisbon)",
            "Europe/London" => "(GMT+01:00) British Summer Time (Europe/London)",
            "America/Scoresbysund" => "(GMT+00:00) East Greenland Summer Time (America/Scoresbysund)",
            "Atlantic/Azores" => "(GMT+00:00) Azores Summer Time (Atlantic/Azores)",
            "UTC" => "(GMT+00:00) Greenwich Mean Time (GMT)",
            "Atlantic/Cape_Verde" => "(GMT-01:00) Cape Verde Time (Atlantic/Cap Verde)",
            "Atlantic/South_Georgia" => "(GMT-02:00) South Georgia Time (Atlantic/South Georgia)",
            "America/St_Johns" => "(GMT-02:30) Newfoundland Daylight Time (America/St Johns)",
            "America/Argentina/Buenos_Aires" => "(GMT-03:00) Argentina Time (America/Buenos Aires)",
            "America/Halifax" => "(GMT-03:00) Atlantic Daylight Time (America/Halifax)",
            "America/Sao_Paulo" => "(GMT-03:00) Brasilia Time (America/Sao Paulo)",
            "Atlantic/Bermuda" => "(GMT-03:00) Atlantic Daylight Time (Atlantic/Bermuda)",
            "America/Indiana/Indianapolis" => "(GMT-04:00) Eastern Daylight Time (America/Indianapolis)",
            "America/New_York" => "(GMT-04:00) Eastern Daylight Time (America/New York)",
            "America/Puerto_Rico" => "(GMT-04:00) Atlantic Standard Time (America/Puerto Rico)",
            "America/Santiago" => "(GMT-04:00) Chile Time (America/Santiago)",
            "America/Caracas" => "(GMT-04:30) Venezuela Time (America/Caracas)",
            "America/Bogota" => "(GMT-05:00) Colombia Time (America/Bogota)",
            "America/Chicago" => "(GMT-05:00) Central Daylight Time (America/Chicago)",
            "America/Lima" => "(GMT-05:00) Peru Time (America/Lima)",
            "America/Mexico_City" => "(GMT-05:00) Central Daylight Time (America/Mexico_City)",
            "America/Panama" => "(GMT-05:00) Eastern Standard Time (America/Panama)",
            "America/Denver" => "(GMT-06:00) Mountain Daylight Time (America/Denver)",
            "America/El_Salvador" => "(GMT-06:00) Central Standard Time (America/El_Salvador)",
            "Mexico/BajaSur" => "(GMT-06:00) Mountain Daylight Time (Mexico/BajaSur)",
            "America/Los_Angeles" => "(GMT-07:00) Pacific Daylight Time (America/Los_Angeles)",
            "America/Phoenix" => "(GMT-07:00) Mountain Standard Time (America/Phoenix)",
            "America/Tijuana" => "(GMT-07:00) Pacific Daylight Time (America/Tijuana)",
            "America/Anchorage" => "(GMT-08:00) Alaska Daylight Time (America/Anchorage)",
            "Pacific/Pitcairn" => "(GMT-08:00) Pitcairn Time (Pacific/Pitcairn)",
            "America/Atka" => "(GMT-09:00) Hawaii-Aleutian Standard Time (America/Atka)",
            "Pacific/Gambier" => "(GMT-09:00) Gambier Time (Pacific/Gambier)",
            "Pacific/Marquesas" => "(GMT-09:30) Marquesas Time (Pacific/Marquesas)",
            "Pacific/Honolulu" => "(GMT-10:00) Hawaii-Aleutian Standard Time (Pacific/Honolulu)",
            "Pacific/Niue" => "(GMT-11:00) Niue Time (Pacific/Niue)",
            "Pacific/Pago_Pago" => "(GMT-11:00) Samoa Standard Time (Pacific/Pago_Pago)"
        );
    }
	
	
	/*
	*	Get Patient Video Assign By Day
	*
	*/
	
	function getAllDayPaitentVideo($paitent_id)
	{
	
		 $query;
		 
		  $query="SELECT pt.plan_id, pt.treatment_id, pt.instruction, pt.sets, pt.reps, pt.hold, pt.lrb, pt.benefit, t.treatment_name, t.pic1, t.pic2, t.pic3,t.video,pl.assignday as assignday,FLOOR((pl.assignday-1)/7)+1 as week1,pt.watched,pt.plan_treatment_id
		from plan pl
		inner join plan_treatment pt on (pt.plan_id = pl.plan_id) 
		inner join treatment t on(t.treatment_id = pt.treatment_id) 
		WHERE pl.patient_id='".$paitent_id."' AND pl.assignday !=  'NULL' AND t.video!='' ORDER BY pl.assignday,pt.treatment_order";
        $result = @mysql_query($query);

        $num = @mysql_num_rows($result);

        $arr = array();

       

        while($row = @mysql_fetch_array($result)){
		$arr[]=$row;
		}
	
		return  $arr;
	}
	
	
	
	
	
		
	/*
	*	Get Patient Video Assign for a Day
	*
	*/
	
	function getPaitentVideoByDay($paitent_id,$day)
	{
	
		 $query;
		$query="SELECT pt.plan_id, pt.treatment_id, pt.instruction, pt.sets, pt.reps, pt.hold, pt.lrb, pt.benefit, t.treatment_name, t.pic1, t.pic2, t.pic3,t.video,pl.assignday as assignday,FLOOR((pl.assignday-1)/7)+1 as week1,pt.watched,pt.plan_treatment_id
		from plan pl
		inner join plan_treatment pt on (pt.plan_id = pl.plan_id) 
		inner join treatment t on(t.treatment_id = pt.treatment_id) 
		WHERE pl.patient_id='".$paitent_id."' AND pl.assignday !=  'NULL' AND t.video!='' AND pl.assignday='".$day."' ORDER BY pl.assignday,pt.treatment_order";
        $result = @mysql_query($query);

        $num = @mysql_num_rows($result);

        $arr = array();

       

        while($row = @mysql_fetch_array($result)){
		$arr[]=$row;
		}
	
		return  $arr;
	}
	
	/*
	*	Get Patient Article Assign By Day
	*
	*/
	
	function getAllDayPaitentArticle($paitent_id)
	{
	
		 $query;
		
		 
		 $query="(SELECT AR.article_id AS articleID,AR.link_url AS link,AR.file_path AS path, NULL AS plan_id, AR.article_name AS article_name, AR.headline AS artcleHeadline, PAA.patient_id AS patient_id, PAA.patientArticleId AS patientArticleId, PAA.read_article AS read_article, PAA.assignday AS assignday, FLOOR( (PAA.assignday-1) /7 ) +1 AS week1
				FROM article AR
				LEFT JOIN patient_article PAA ON PAA.article_id = AR.article_id
				WHERE PAA.patient_id =  ' ".$paitent_id."'  AND AR.status='1'
				ORDER BY assignday ASC
				)
				UNION (

				SELECT RS.article_id AS articleID,RS.link_url AS link,RS.file_path AS path, P.plan_id AS plan_id, RS.article_name AS article_name, RS.headline AS artcleHeadline, P.patient_id AS patient_id, NULL AS patientArticleId, PA.read_article AS read_article, P.assignday AS assignday, FLOOR( (P.assignday-1) /7 ) +1 AS week1
				FROM article RS
				LEFT JOIN plan_article PA ON RS.article_id = PA.article_id
				LEFT JOIN plan P ON P.plan_id = PA.plan_id
				AND P.status =  '1'
				WHERE (
				P.patient_id =  ' ".$paitent_id."'  AND RS.status='1'
				)
				ORDER BY assignday ASC
				)
				ORDER BY assignday ASC";
        $result = @mysql_query($query);

        $num = @mysql_num_rows($result);

        $arr = array();

       

        while($row = @mysql_fetch_array($result)){
		$arr[]=$row;
		}
	
		return  $arr;
	}
	
	
	
	
	/*
	*	Get Patient Article Assign for a Day
	*
	*/
	
	function getPaitentArticleByDay($paitent_id,$day)
	{
	
		 $query;
		
		 
		 $query="(SELECT AR.article_id AS articleID,AR.link_url AS link,AR.file_path AS path, NULL AS plan_id, AR.article_name AS article_name, AR.headline AS artcleHeadline, PAA.patient_id AS patient_id, PAA.patientArticleId AS patientArticleId, PAA.read_article AS read_article, PAA.assignday AS assignday, FLOOR( (PAA.assignday-1) /7 ) +1 AS week1
				FROM article AR
				LEFT JOIN patient_article PAA ON PAA.article_id = AR.article_id
				WHERE PAA.patient_id =  ' ".$paitent_id."' AND PAA.assignday = '".$day."' AND AR.status='1'
				ORDER BY assignday ASC
				)
				UNION (

				SELECT RS.article_id AS articleID,RS.link_url AS link,RS.file_path AS path, P.plan_id AS plan_id, RS.article_name AS article_name, RS.headline AS artcleHeadline, P.patient_id AS patient_id, NULL AS patientArticleId, PA.read_article AS read_article, P.assignday AS assignday, FLOOR( (P.assignday-1) /7 ) +1 AS week1
				FROM article RS
				LEFT JOIN plan_article PA ON RS.article_id = PA.article_id
				LEFT JOIN plan P ON P.plan_id = PA.plan_id
				AND P.status =  '1'
				WHERE (
				P.patient_id =  ' ".$paitent_id."' AND P.assignday = '".$day."'  AND RS.status='1'
				)
				ORDER BY assignday ASC
				)
				ORDER BY assignday ASC";
        $result = @mysql_query($query);

        $num = @mysql_num_rows($result);

        $arr = array();

       

        while($row = @mysql_fetch_array($result)){
		$arr[]=$row;
		}
	
		return  $arr;
	}
	
	
	
	
	/*
	*	Get Patient Current Day
	*
	*/
	
	function getPaitentCurrentDay($paitent_id)
	{
		$query = "SELECT DATEDIFF( current_date, DATE( usr.creation_date ))+1 as cuurday FROM user usr WHERE usr.usertype_id =1 AND usr.STATUS !=3 AND usr.agreement = 1 AND usr.user_id='".$paitent_id."'";
						
			$result = @mysql_query($query) ; 
			
			$current_day=0;
			// taking every user's date data line by line
			if(@mysql_num_rows($result)){
				while ($row = @mysql_fetch_array($result)) {
						//fill in data for mail content.
						
						$current_day = $row['cuurday'];
				}
			}
			
			
			return $current_day;
	}
	
	
	/*
	*	Get Patient's Last Pain Level & Date
	*	This function return array & this contains the last lain level and date
	*
	*/
	
	function getPaitentLastPainLevel($paitent_id)
	{
		$query = "SELECT * FROM patient_pain_level WHERE patient_id='".$paitent_id."'  ORDER BY painlevel_id DESC";
						
			$result = @mysql_query($query) ; 
			$return = array();
			$return['last_pain_level'] = 0;
			$return['date'] = date("Y-m-d", mktime(0, 0, 0, date("m")  , date("d")-1, date("Y")));
			
			
			//"2014-02-01"
			
			// taking every user's date data line by line
			if(@mysql_num_rows($result)){
				$row = @mysql_fetch_array($result);
				
				$return['last_pain_level'] = $row['painlevel'];
				$return['date'] = date("Y-m-d",strtotime($row['creation_on']));
				
			}
			
			
			return $return;
	}
	
	
        
    function jsonencode($a=false)
    {
        if (is_null($a)) return 'null';
        if ($a === false) return 'false';
        if ($a === true) return 'true';
        if (is_scalar($a))
        {
            if (is_float($a))
            {
                // Always use "." for floats.
                return floatval(str_replace(",", ".", strval($a)));
            }

            if (is_string($a))
            {
                static $jsonReplaces = array(array("\\", "/", "\n", "\t", "\r", "\b", "\f", '"'), array('\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"'));
                return '"' . str_replace($jsonReplaces[0], $jsonReplaces[1], $a) . '"';
            }
            else
            return $a;
        }
        $isList = true;
        for ($i = 0, reset($a); $i < count($a); $i++, next($a))
        {
            if (key($a) !== $i)
            {
                $isList = false;
                break;
            }
        }
        $result = array();
        if ($isList)
        {
            foreach ($a as $v) $result[] = $this->jsonencode($v);
            return '[' . join(',', $result) . ']';
        }
        else
        {
            foreach ($a as $k => $v) $result[] = $this->jsonencode($k).':'.$this->jsonencode($v);
            return '{' . join(',', $result) . '}';
        }
    }
	
    /**
     * Parses a JSON string into a PHP variable.
     * 
     * @param string $json  The JSON string to be parsed.
     * @param bool $assoc   Optional flag to force all objects into associative arrays.
     * @return mixed        Parsed structure as object or array, or null on parser failure.
     */
    function jsondecode($json, $assoc = false)
    {

        /* by default we don't tolerate ' as string delimiters
          if you need this, then simply change the comments on
          the following lines: */

        // $matchString = '/(".*?(?<!\\\\)"|\'.*?(?<!\\\\)\')/';
        $matchString = '/".*?(?<!\\\\)"/';

        // safety / validity test
        $t = preg_replace($matchString, '', $json);
        $t = preg_replace('/[,:{}\[\]0-9.\-+Eaeflnr-u \n\r\t]/', '', $t);
        if($t != '')
        {
            return null;
        }

        // build to/from hashes for all strings in the structure
        $s2m = array();
        $m2s = array();
        preg_match_all($matchString, $json, $m);
        foreach($m[0] as $s)
        {
            $hash = '"' . md5($s) . '"';
            $s2m[$s] = $hash;
            $m2s[$hash] = str_replace('$', '\$', $s);  // prevent $ magic
        }

        // hide the strings
        $json = strtr($json, $s2m);

        // convert JS notation to PHP notation
        $a = ($assoc) ? '' : '(object) ';
        $json = strtr($json, array(
            ':' => '=>',
            '[' => 'array(',
            '{' => "{$a}array(",
            ']' => ')',
            '}' => ')'
                )
        );

        // remove leading zeros to prevent incorrect type casting
        $json = preg_replace('~([\s\(,>])(-?)0~', '$1$2', $json);

        // return the strings
        $json = strtr($json, $m2s);

        /* "eval" string and return results.
          As there is no try statement in PHP4, the trick here
          is to suppress any parser errors while a function is
          built and then run the function if it got made. */
        $f = @create_function('', "return {$json};");
        $r = ($f) ? $f() : null;

        // free mem (shouldn't really be needed, but it's polite)
        unset($s2m);
        unset($m2s);
        unset($f);

        return $r;
    }

	

}

//Class Ends
?>