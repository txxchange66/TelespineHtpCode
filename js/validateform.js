/** \file
 This is the client-side implementation of the Protosite Form module's field
 validation functionality.  To use it, you should include it in your page and
 then call Form's makeScript() method to generate the set of rules it will use.
 Also, add the attribute 'onsubmit="validate_form(this)"' to your form tag.
 
 Version 6 (o and l are converted to 0 and 1 in credit card number checks)
 Version 7 (allow + in email name)
 
 Copyright 2003-2004 Technicode.  All Rights Reserved.  This software
 is protected by U.S. copyright law and international copyright treaty.
 This file is licensed for your use, but you may not redistribute it.
 See copyright.txt for details.
 */

validationTypes = new Object();
validationTypes['string'] = is_valid_string;
validationTypes['integer'] = is_valid_integer;
validationTypes['float'] = is_valid_float;
validationTypes['dollars'] = is_valid_dollars;
validationTypes['date'] = is_valid_date;
validationTypes['time'] = is_valid_time;
validationTypes['email'] = is_valid_email;
validationTypes['zipcode'] = is_valid_zipcode;
validationTypes['usphone'] = is_valid_us_phone;
validationTypes['ccnum'] = is_valid_cc_number;
validationTypes['ccexp'] = is_valid_cc_expiration;

// validates the given form.  use like onsubmit="validate_form(this)".
// you can use more validation in this framework with the window.moreValidation property
// (see the comments below).
function validate_form(form)
{
    // don't validate if we don't have what we need
    if (!form || !form.elements || !window.formRules)
        return true;

    var missingFields = new Array();
    var invalidFields = new Array();
    var firstBadFieldName = null; // so we can select and focus
    var ccExp = new Array();

    for (var i = 0; i < window.formRules.length; ++i)
    {
        var fieldName = window.formRules[i].fieldName;

        var field = form.elements[fieldName];
        if (!field)
            continue;

        var value = get_field_value(field);
        if (value == '')
        {
            if (window.formRules[i].required)
            {
                missingFields[missingFields.length] = window.formRules[i].description;
                if (!firstBadFieldName)
                    firstBadFieldName = fieldName;
            }

            continue;
        }

        var validationType = window.formRules[i].validationType;

        // special exception for credit card expiration date
        if (validationType.toLowerCase() == 'ccexp_month')
        {
            ccExp[0] = value;
            continue;
        }
        else if (validationType.toLowerCase() == 'ccexp_year')
        {
            ccExp[1] = value;
            continue;
        }

        var validationExtra = new Array();
        if (validationType.length >= 7 && validationType.substr(0, 7) == 'string|')
        {
            var extra = validationType.substr(7); // skip the |
            var pos = extra.indexOf(',');
            if (pos > 0 && pos != extra.length - 1)
            {
                validationExtra[0] = extra.substr(0, pos);
                validationExtra[1] = extra.substr(pos + 1);
            }

            validationType = 'string';
        }

        var validationFunction = validationTypes[validationType];
        if (!validationFunction)
            continue; // don't know how

        var valid;
        if (validationType != 'string')
            valid = validationFunction(value);
        else
            valid = validationFunction(value, validationExtra[0], validationExtra[1]);

        if (valid === false)
        {
            invalidFields[invalidFields.length] = window.formRules[i].description;
            if (!firstBadFieldName)
                firstBadFieldName = fieldName;
        }
        else
            set_field_value(field, valid);
    }

    var msg = '';
    if (missingFields.length > 0)
    {
        if (missingFields.length == 1)
            msg = 'A required field was not filled in.\n\n';
        else
            msg = 'Some required fields were not filled in.\n\n';
        msg += 'Please enter ' + list_fields(missingFields) + ' to continue.\n';
    }
    else if (invalidFields.length > 0)
    {
        if (invalidFields.length == 1)
            msg = 'A field contains invalid input.\n\n';
        else
            msg = 'Some fields contain invalid input.\n\n';
        msg += 'Please re-enter ' + list_fields(invalidFields) + ' to continue.\n';
    }
    else if (ccExp.length == 2)
    {
        var validationFunction = validationTypes['ccexp'];
        var ccExpValue = ccExp[0] + '/' + ccExp[1];
        if (validationFunction && validationFunction(ccExpValue) == false)
        {
            msg = 'The expiration date you gave for your credit card does not appear to be valid.\n\n'
            msg += 'Please re-enter a valid future date to continue.\n';
        }
    }

    if (msg.length)
    {
        if (firstBadFieldName)
        {
            if (form.elements[firstBadFieldName].select)
                form.elements[firstBadFieldName].select();
            if (form.elements[firstBadFieldName].focus)
                form.elements[firstBadFieldName].focus();
        }

        alert(msg);
        return false;
    }

    // If you have additional, custom validation to do, put a pointer to the function
    // in window.moreValidation and it will be called here.  It will be passed a reference
    // the the form; it should return either an error string to be displayed, or null to
    // indicate that things are A-OK.
    if (window.moreValidation)
    {
        var msg = window.moreValidation();
        if (msg && msg.length)
        {
            alert(msg);
            return false;
        }
    }

    return true;
}

// stores the set of requirments for a single field in the form.
function Rule(fieldName, description, required, validationType)
{
    this.fieldName = fieldName;
    this.description = description;
    this.required = required;
    this.validationType = validationType;
}

// gets the value of a form field
function get_field_value(fieldObj)
{
    if (fieldObj.type == 'text' || fieldObj.type == 'textarea' || fieldObj.type == 'password')
        return fieldObj.value;

    if (fieldObj.type == 'select-one' || fieldObj.type == 'select-multiple')
    {
        if (fieldObj.selectedIndex == -1)
            return '';
        else
            return fieldObj.options[fieldObj.selectedIndex].value;
    }

    if (fieldObj[0] && fieldObj[0].type == 'radio')
    {
        for (var i = 0; i < fieldObj.length; ++i)
            if (fieldObj[i].checked)
                return fieldObj[i].value;
        return '';
    }

    return ''; // unknown field type
}

// sets the value of a form field
function set_field_value(fieldObj, value)
{
    if (fieldObj.type == 'text' || fieldObj.type == 'textarea' || fieldObj.type == 'password')
    {
        fieldObj.value = value;
        return true;
    }

    if (fieldObj.type == 'select-one' || fieldObj.type == 'select-multiple')
    {
        for (var i = 0; i < fieldObj.options.length; ++i)
        {
            if (fieldObj.options[i].value != value)
                continue;
            fieldObj.selectedIndex = i;
            return true;
        }

        return false;
    }

    if (fieldObj[0] && fieldObj[0].type == 'radio')
    {
        for (var i = 0; i < fieldObj.length; ++i)
        {
            if (fieldObj[i].value != value)
                continue;
            fieldObj[i].checked = true;
            return true;
        }

        return false;
    }

    return false; // unknown field type
}

// makes a nice english list of fields in an array, like "foo, bar and baz".
function list_fields(array)
{
    var fields = '';
    var len = array.length - 1;
    for (var i = 0; i <= len; ++i)
    {
        if (i)
        {
            if (len > 0 && i == len)
                fields += ' and ';
            else
                fields += ', ';
        }

        fields += array[i];
    }

    return fields;
}

// is the value a valid string? optionally enforces length requirements.
function is_valid_string(value, minLength, maxLength)
{
    if (value == null)
        return false;
    else if (value.trim() == '')
        return false;

    var min = parseInt(minLength, 10);
    if (!isNaN(min) && value.length < min)
        return false;

    var max = parseInt(maxLength, 10);
    if (!isNaN(max) && value.length > max)
        return false;

    return value;
}

// is the value a valid integer? strips commas.
function is_valid_integer(value)
{
    if (value == null)
        return false;

    value = value.replace(/,/ig, '');
    if (/^\d+$/.test(value))
        return value;
    return false;
}

// is the value a valid floating-point number? strips commas.
function is_valid_float(value)
{
    if (value == null)
        return false;

    value = value.replace(/,/ig, '');
    if (is_valid_integer(value))
        return value;
    if (/^\d+\.\d+$/.test(value))
        return value;
    return false;
}

// is the value a valid dollar value? strips commas and dollar-signs.
function is_valid_dollars(value)
{
    if (value == null)
        return false;

    value = value.replace(/,/ig, '');
    value = value.replace(/\$/ig, '');
    if (is_valid_integer(value))
        return value;
    if (/^\d+\.\d{1,2}$/.test(value))
        return value;
    return false;
}

// is the value a valid date?  various formats are accepted.
function is_valid_date(value)
{
    if (value == null)
        return false;

    // on the server, only certain formats will be understood by strtotime(), and those formats
    // are different than the ones JavaScript gets, so we check for those explicitely instead of
    // using JavaScript's Date object to check.
    if (/^\d{4,4}-\d{1,2}-\d{1,2}$/.test(value))
        return value;
    if (/^\d\d-\d{1,2}-\d{1,2}$/.test(value))
        return value;
    if (/^\d{1,2}\/\d{1,2}\/\d{4,4}$/.test(value))
        return value;
    if (/^\d{1,2}\/\d{1,2}\/\d{2,2}$/.test(value))
        return value;
    if (/^\d{1,2}\/\d{1,2}$/.test(value))
        return value;

    return false;
}

// is the value a valid time?  accepts 12-hour and 24-hour times,  in formats
// "hh:mm" and "hh:mm:ss", with or without a trailing "am", "pm", "a.m." or "p.m.".
function is_valid_time(value)
{
    if (value == null)
        return false;

    var am = (value.toLowerCase().indexOf('am') != -1 || value.toLowerCase().indexOf('a.m.') != -1);
    var pm = (value.toLowerCase().indexOf('pm') != -1 || value.toLowerCase().indexOf('p.m.') != -1);
    value = value.replace(/pm/ig, '').replace(/am/ig, '');
    value = value.replace(/p\.m\./ig, '').replace(/a\.m\./ig, '');
    value = value.replace(/ /g, '');

    var hr = value;
    var min = 0;
    var sec = 0;

    var pos = hr.indexOf(':');
    if (pos > 0 && pos < hr.length - 1)
    {
        min = hr.substr(pos + 1);
        hr = hr.substr(0, pos);

        pos = min.indexOf(':');
        if (pos > 0 && pos < min.length - 1)
        {
            sec = min.substr(pos + 1);
            min = min.substr(0, pos);
        }
    }

    hr = parseInt(hr, 10);
    min = parseInt(min, 10);
    sec = parseInt(sec, 10);

    if ((am || pm) && (hr > 12 || hr == 0))
        return false;
    if (pm && (hr < 12))
        hr += 12;

    if (hr < 0 || hr > 23 || min < 0 || min > 59 || sec < 0 || sec > 59)
        return false;

    var d = new Date(0, 0, 0, hr, min, sec);
    if (isNaN(d.getTime()))
        return false;

    return value;
}

// is the value a valid email address?
function is_valid_email(value)
{
    if (value == null)
        return false;

    if (/^\w+([\./+/-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,6})+$/.test(value) ||
            /^[\w ]+<\w+([\./+/-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,6})+>$/.test(value))
    {
        var tld = RegExp.lastParen.toLowerCase(); // includes the leading dot

        // let any TLD with 4 characters or less through
        if (tld.length <= 5)
            return value;

        // validate longer TLDs specifically
        if (tld == ".museum" || tld == ".travel")
            return value;
        else
            return false;
    }

    return false;
}

// is the value a valid US zipcode? accepts "nnnnn" or "nnnnn-nnnn".
function is_valid_zipcode(value)
{
    if (value == null)
        return false;

    if (value.length == 5)
        return is_valid_integer(value);
    else if (value.length == 10)
    {
        var pos = value.indexOf('-');
        if (pos != 5)
            return false;
        if (!is_valid_integer(value.substring(0, pos)))
            return false;
        if (!is_valid_integer(value.substring(pos + 1)))
            return false;
        return value;
    }

    return false;
}

// is the value a valid US phone number? accepts any string of 7, 10 or 11 numbers,
// separated by any combo of spaces, dashes, parentheses, slashes, or periods.
function is_valid_us_phone(value)
{
    if (value == null)
        return false;

    // allow numbers, spaces, dashes, parens, slashes and dots
    var validChars = '0123456789 -()/.';

    var digits = '';
    for (var i = 0; i < value.length; ++i)
    {
        var ch = value.charAt(i);
        if (validChars.indexOf(ch) == -1)
            return false;
        if (!isNaN(parseInt(ch, 10)))
            digits += '' + ch;
    }

    // there must be 7, 10, or 11 digits for a US phone number
    if (digits.length != 10)
        return false;

    return value;
}

// is the value given a valid credit card number?
// window.cardTypes must be an array of acceptable types, with regular expressions to match.
function is_valid_cc_number(value)
{
    if (value == null)
        return false;

    // if we weren't given a list of card types to check against,
    // just give up and let the server do the validation.
    if (!window.cardTypes)
        return true;

    // first strip any non-numeric characters
    var cardNo = value.replace(/[^0-9]/ig, '');
    if (!_check_mod_10(cardNo))
    {
        // try again after replacing o with 0 and l with 1
        cardNo = value.replace(/o/ig, '0').replace(/l/ig, '1').replace(/[^0-9]/ig, '');
        if (!_check_mod_10(cardNo))
            return false;
    }

    // then see if it matches one of the list of valid card types
    for (var k in window.cardTypes)
    {
        var reg = new RegExp(window.cardTypes[k]);
        if (!reg.test(cardNo))
            continue;

        var okForSite = true; // allow any kind if window.cardRules was not given
        if (window.cardRules) // (types allowed for this site)
        {
            okForSite = false;
            for (var i = 0; i < window.cardRules.length; ++i)
            {
                if (k != window.cardRules[i])
                    continue;
                okForSite = true;
                break;
            }
        }

        if (okForSite)
            return cardNo;
    }

    return false;
}

// checks the Mod 10 for a credit card .. used by is_valid_cc_number()
function _check_mod_10(cardNo)
{
    if (!cardNo.length)
        return false;

    var rev = '';
    for (var i = cardNo.length; i > 0; --i)
        rev += cardNo.charAt(i - 1);

    var sum = 0;
    for (var i = 0; i < rev.length; ++i)
    {
        var n = parseInt(rev.charAt(i), 10);
        if (i % 2)
            n *= 2; // double every second digit
        if (n > 9)
            n -= 9; // add digits of 2-digit numbers together (e.g. 14 becomes 5)
        sum += n; // add to total
    }

    if (sum % 10)
        return false;
    return true;
}

// is the info given representative of the current month or one in the future?
// formats understood include mm/yyyy, mm/yy, mmyyyy, mmyy.
// month should be in {1-12}; if year < 100, 2000 is added.
// return value, if validation is successful, is normalized to mm/yyyy.
function is_valid_cc_expiration(value)
{
    var seps = '/- .';
    var month, year, val = null;

    for (var i = 0; i < seps.length; ++i)
    {
        var ch = seps.charAt(i);
        if (seps.indexOf(ch) == -1)
            continue;

        val = value.split(ch);
        if (val.length != 2)
            continue;

        month = val[0];
        year = val[1];
        break;
    }

    if (!month || !year)
    {
        val = value.replace(/^\d/ig, '');
        if (val.length != 4 && val.length != 6)
            return false;

        month = val.substr(0, 2);
        year = val.substr(2);
    }

    month = parseInt(month, 10);
    year = parseInt(year, 10);
    if (isNaN(month) || isNaN(year))
        return false;
    if (month < 1 || 12 < month)
        return false;

    var today = new Date();
    var currentMonth = today.getMonth() + 1;
    var currentYear = today.getFullYear();

    if (year < 100)
        year += 2000;
    if (year < currentYear)
        return false;
    if (year == currentYear && month < currentMonth)
        return false;

    if (month < 10)
        month = "0" + "" + month;
    return month + '/' + year;
}
