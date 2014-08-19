<?php

/**
 *
 * Copyright (c) 2008 Tx Xchange
 *
 * Class for notepad module
 * 
 * // necessary classes 
 * require_once("module/application.class.php");
 * 
 * // pagination class
 * require_once("include/paging/my_pagina_class.php");
 * 
 * 
 */
// including files
require_once("module/application.class.php");

// class declaration
class notepad extends application
{
    // class variable declaration section

    /**
     * Get action from header and calling function
     * The variable defines the action request
     *
     * @var string
     * @access private
     */
    private $action;

    /**
     * Populate html form elements as an array
     * The variable defines all the fields present in the form
     *
     * @var array
     * @access private
     */
    private $form_array;

    /**
     * The variable defines the error message(if any) against the action request
     * It could be an array if more than one error messages are there else a simple variable
     *
     * @var string
     * @access private
     */
    private $error;

    /**
     * Pass as parameter in display() function which shows output in browser.
     * 
     * The variable is used for getting final output template or string message to be displayed to the user
     * This function of statement(s) are to handle all the actions supported by this Login class
     * that is it could be the case that more then one action are handled by login
     * for example at first the action is "login" then after submit say action is submit
     * so if login is explicitly called we have the login action set (which is also our default action)
     * else whatever action is it is set in $str.
     *
     * @var string
     * @access private
     */
    private $output;

    ### Constructor #####

    /**
     *  constructor
     *  set action variable from url string, if not found action in url, call default action from config.php
     * 
     */
    function __construct()
    {
        parent::__construct();

        if($this->value('action'))
        {

            /*

              This block of statement(s) are to handle all the actions supported by this Login class

              that is it could be the case that more then one action are handled by login

              for example at first the action is "login" then after submit say action is submit

              so if login is explicitly called we have the login action set (which is also our default action)

              else whatever action is it is set in $str.

             */

            $actionstr = $this->value('action');
        }
        else
        {

            $actionstr = "notepad"; //default if no action is specified
        }

        $this->action = $actionstr;

        if($this->get_checkLogin($this->action) == "true")
        {

            if(isset($_SESSION['username']) && isset($_SESSION['password']))
            {

                if(!$this->chk_login($_SESSION['username'], $_SESSION['password']))
                {

                    header("location:index.php");
                }
            }
            else
            {

                header("location:index.php");
            }
        }

        if($this->userAccess($actionstr))
        {
            $actionstr = $actionstr . "()";
            eval("\$this->$actionstr;");
        }
        else
        {
            $this->output = $this->config['error_message'];
        }

        $this->display();
    }

    /**
     * 
     * @param type $patientId
     * @return string
     */
    function notesList($patientId)
    {
        $replace = array();
        $c = 1;

        //Get the notes list
        $privateKey = $this->config['private_key'];
        $sqlNotes = "SELECT 
                            notepad.provider_id AS providerid,
                            AES_DECRYPT(UNHEX(user.name_first), '{$this->config['private_key']}') as firstname,
                            AES_DECRYPT(UNHEX(user.name_last), '{$this->config['private_key']}') as lastname,
                            notepad.patient_id AS patientid,
                            AES_DECRYPT(UNHEX(notepad.note), '{$this->config['private_key']}') as note,
                            notepad.created AS createdon
                        FROM 
                            notepad
                        LEFT JOIN user 
                            ON (user.user_id = notepad.provider_id)
                        WHERE 
                            notepad.status = 'enabled' 
                            AND notepad.patient_id = '" . $patientId . "'
                        ORDER BY created DESC";

        $result = $this->execute_query($sqlNotes);
        if($this->num_rows($result) != 0)
        {
            while($row = $this->fetch_array($result))
            {
                $row1['provider'] = trim($row['firstname']) . "&nbsp;" . trim($row['lastname']);
                $row1['note'] = nl2br($row['note']);
                $row1['patient_id'] = $row['patientid'];
                $row1['createdon'] = $this->formatDateExtra($row['createdon'], $this->userInfo('timezone'));

                $replace['noteTblRecord'] .= $this->build_template($this->get_template("noteTblRecord"), $row1);
            }
        }
        else
        {
            $replace['noteTblRecord'] = "<br/>No notes yet for this patient. Enter your notes above and click 'Save'.";
        }

        return $replace;
    }

    function patientNotepad()
    {
        $patientId = $this->value('patient_id');

        $privateKey = $this->config['private_key'];

        $replace = $this->notesList($patientId);

        // patient details
        $query = "SELECT 
                      AES_DECRYPT(UNHEX(name_first),'{$privateKey}') as name_first,
                      AES_DECRYPT(UNHEX(name_last),'{$privateKey}') as name_last 
                  FROM 
                      user WHERE user_id = " . $patientId;
        $result = $this->execute_query($query);

        $row = $this->fetch_array($result);

        $patientName = trim($row['name_first']) . "&nbsp;" . trim($row['name_last']);
        $replace['patientName'] = $patientName;

        //it is needed in addNote template as well.
        $replace['patient_id'] = $patientId;

        $this->output = $this->build_template($this->get_template("addNote"), $replace);
    }

    /**
     * Add a new note
     *
     * @access public
     */
    function addNote()
    {
        $replace = array();

        //current logged in user details
        $userInfo = $this->userInfo();

        $patientId = $this->value('patient_id');

        $msg = "";

        //save button clicked!
        if("Save" == $this->value('submitted'))
        {
            $newNote = $this->value('note');

            if(strlen(trim($newNote)) == 0)
            {
                $msg = '<div style="padding-left:5px;color:red;">Please enter a note for this patient.</div>';
            }
            else
            {
                $insertArr = array(
                    'patient_id' => $patientId,
                    'provider_id' => $userInfo['user_id'],
                    'note' => $this->encrypt_data($this->value('note')),
                    'created' => date('Y-m-d H:i:s', time())
                );

                $result = $this->insert('notepad', $insertArr);

                /* if(!$result)
                  {
                  $msg = '<div style="padding-left:5px;">Failed adding a note.</div>';
                  } */
            }
            $privateKey = $this->config['private_key'];

            // patient details
            $query = "SELECT 
                          AES_DECRYPT(UNHEX(name_first),'{$privateKey}') as name_first,
                          AES_DECRYPT(UNHEX(name_last),'{$privateKey}') as name_last 
                      FROM 
                          user WHERE user_id = " . $patientId;
            $result = $this->execute_query($query);

            $row = $this->fetch_array($result);

            $replace = $this->notesList($patientId);
            $patientName = $row['name_first'] . "&nbsp;&nbsp;" . $row['name_last'];
            $replace['patient_id'] = $patientId;
            $replace['patientName'] = $patientName;
            $replace['statusMessage'] = $msg;
        }
        else
        {
            $replace = $this->notesList($patientId);
        }

        $replace['patient_id'] = $patientId;

        $this->output = $this->build_template($this->get_template("addNote"), $replace);
    }

    /**
     * This function gets the template path from xml file.
     *
     * @param string $template - pass template file name as defined in xml file for that template file.
     * @return string - template file
     * @access private
     */
    function get_template($template)
    {

        $login_arr = $this->action_parser($this->action, 'template');

        $pos = array_search($template, $login_arr['template']['name']);

        return $login_arr['template']['path'][$pos];
    }

    /**
     * This function sends the output to browser.
     * 
     * @access public
     */
    function display()
    {

        view::$output = $this->output;
    }

}

/**
 * Initialize the object of this class
 */
$obj = new notepad();
?>

