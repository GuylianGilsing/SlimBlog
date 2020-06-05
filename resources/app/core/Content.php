<?php
namespace App\Core;

class ContentTools
{
    /**
     * A stricter 'empty()' function.
     * 
     * @param mixed $data The data you wish to check.
     * @return bool Returns true when the content is empty, and false when
     * the content is not empty.
     */
    public static function Empty($data)
    {
        $regex = "~[\\n\\r\s\\t]+~";
        $replacedData = preg_replace($regex, '', $data);

        return empty($replacedData);
    }

    /**
     * Uses a regex to remove all types of whitespaces from a string.
     * 
     * @param string $data The string you want to remove the whitespaces from.
     * @return string Returns the input string with all whitespaces removed.
    */
    public static function RemoveWhitespace(string $data)
    {
        $output = "";

        if(!empty($data))
        {
            $regex = "~[\\n\\r\s\\t]+~";
            $output = preg_replace($regex, '', $data);
        }

        return $output;
    }

    /**
     * Encodes a given string, or an array of strings, for database insertions.
     * 
     * @param array|string Either an array of strings, or just a string.
     * @return array|string Returns the encoded version of the input data.
     */
    public static function EncodeData($data)
    {
        $cleanedData = $data;

        if(!is_array($data))
        {
            $cleanedData = self::Encode($data);
        }
        else if(is_array($data) && count($data) == 1)
        {
            $cleanedData = self::Encode($data[0]);
        }
        else
        {
            foreach($data as $piece)
            {
                $cleanedData[] = self::Encode($piece);
            }
        }

        return $cleanedData;
    }

    /**
     * Decodes a given string, or an array of strings, that has been returned
     * from a database record.
     * 
     * @param array|string Either an array of strings, or just a string.
     * @return array|string Returns the decoded version of the input data.
     */
    public static function DecodeData($data)
    {
        $cleanedData = $data;
        
        if(!is_array($data))
        {
            $cleanedData = self::Decode($data);
        }
        else if(is_array($data) && count($data) == 1)
        {
            $cleanedData = self::Decode($data[0]);
        }
        else
        {
            foreach($data as $piece)
            {
                $cleanedData[] = self::Decode($piece);
            }
        }

        return $cleanedData;
    }

    private static function Encode($data)
    {
        // Remove spaces in the front and back of the string.
        $cleanedData = trim($data);
        $cleanedData = rawurldecode($cleanedData);

        // Encode the data in UTF-8
        $cleanedData = utf8_encode($cleanedData);

        // Convert all dangerous characters to htmlentities.
        $cleanedData = htmlentities($cleanedData);

        // Convert all special chars to htmlentities.
        $cleanedData = htmlspecialchars($cleanedData);

        return $cleanedData;
    }

    private static function Decode($data)
    {
        // Remove spaces in the front and back of the string.
        $cleanedData = trim($data);
        
        // Convert all htmlentities back to special characters.
        $cleanedData = htmlspecialchars_decode($cleanedData);
        
        // Convert all htmlentities to normal characters.
        $cleanedData = html_entity_decode($cleanedData);
        
        // Decode UTF-8.
        $cleanedData = utf8_decode($cleanedData);

        return $cleanedData;
    }
}