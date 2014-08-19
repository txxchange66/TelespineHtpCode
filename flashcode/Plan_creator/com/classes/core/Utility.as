class com.classes.core.Utility
{
    function Utility()
    {
    } // End of the function
    static function replaceAll(str, from, to)
    {
        return (str.split(from).join(to));
    } // End of the function
    static function firstUC(str, onlyEditFirst)
    {
        onlyEditFirst = onlyEditFirst == undefined ? (false) : (onlyEditFirst);
        if (onlyEditFirst)
        {
            return (str.charAt(0).toUpperCase() + str.substr(1));
        }
        else
        {
            return (str.charAt(0).toUpperCase() + str.substr(1).toLowerCase());
        } // end else if
    } // End of the function
    static function wordUC(str, onlyEditFirst)
    {
        onlyEditFirst = onlyEditFirst == undefined ? (false) : (onlyEditFirst);
        var _loc2 = str.split(" ");
        for (var _loc1 = 0; _loc1 < _loc2.length; ++_loc1)
        {
            _loc2[_loc1] = com.classes.core.Utility.firstUC(_loc2[_loc1], onlyEditFirst);
        } // end of for
        return (_loc2.join(" "));
    } // End of the function
    static function trim(str)
    {
        return (com.classes.core.Utility.leftTrim(com.classes.core.Utility.rightTrim(str)));
    } // End of the function
    static function leftTrim(string)
    {
        return (com.classes.core.Utility.leftTrimForChars(string, "\n\t\n "));
    } // End of the function
    static function rightTrim(string)
    {
        return (com.classes.core.Utility.rightTrimForChars(string, "\n\t\n "));
    } // End of the function
    static function rightTrimForChars(string, chars)
    {
        var _loc3 = 0;
        for (var _loc1 = string.length - 1; _loc3 < _loc1 && chars.indexOf(string.charAt(_loc1)) >= 0; --_loc1)
        {
        } // end of for
        return (_loc1 >= 0 ? (string.substr(_loc3, _loc1 + 1)) : (string));
    } // End of the function
    static function leftTrimForChars(string, chars)
    {
        var _loc1 = 0;
        var _loc3 = string.length;
        while (_loc1 < _loc3 && chars.indexOf(string.charAt(_loc1)) >= 0)
        {
            ++_loc1;
        } // end while
        return (_loc1 > 0 ? (string.substr(_loc1, _loc3)) : (string));
    } // End of the function
    static function howMany(str, strPattern, isCaseSensitive)
    {
        isCaseSensitive = isCaseSensitive == undefined ? (false) : (isCaseSensitive);
        if (isCaseSensitive)
        {
            return (str.split(strPattern).length - 1);
        }
        else
        {
            return (str.toUpperCase().split(strPattern.toUpperCase()).length - 1);
        } // end else if
    } // End of the function
    static function wave(str, isFirstUpper)
    {
        isFirstUpper = isFirstUpper == undefined ? (true) : (isFirstUpper);
        var _loc2 = str.split("");
        var _loc3 = 1;
        for (var _loc1 = 0; _loc1 < _loc2.length; ++_loc1)
        {
            if (_loc2[_loc1] != " ")
            {
                if (isFirstUpper)
                {
                    if (_loc3 % 2 == 0)
                    {
                        _loc2[_loc1] = _loc2[_loc1].toLowerCase();
                    }
                    else
                    {
                        _loc2[_loc1] = _loc2[_loc1].toUpperCase();
                    } // end else if
                }
                else if (_loc3 % 2 == 0)
                {
                    _loc2[_loc1] = _loc2[_loc1].toUpperCase();
                }
                else
                {
                    _loc2[_loc1] = _loc2[_loc1].toLowerCase();
                } // end else if
                ++_loc3;
            } // end if
        } // end of for
        return (_loc2.join(""));
    } // End of the function
    static function replaceUnwantedChars(str)
    {
        str = com.classes.core.Utility.replaceAll(str, "&lt;", "<");
        str = com.classes.core.Utility.replaceAll(str, "&gt;", ">");
        str = com.classes.core.Utility.replaceAll(str, "&quot;", "\"");
        str = com.classes.core.Utility.replaceAll(str, "&amp;", "&");
        str = com.classes.core.Utility.replaceAll(str, "&apos;", "\'");
        return (str);
    } // End of the function
    function toString(Void)
    {
        return ("Utility class");
    } // End of the function
} // End of Class
