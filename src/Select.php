<?php

/**
 * THIS IS DEPRECATED!!!!!
 */

namespace LongTailVentures\Html;

class Select
{
    public static function render($name, $options, $selected = '', $attributes = array())
    {
        $selectParams = array('name=' . $name);

        foreach ($attributes as $label => $value)
            $selectParams[] = $label . '="' . $value . '"';

        $html = '<select ' . implode(' ', $selectParams) . '>';

        if (self::_isOptGroup($options))
        {
            foreach ($options as $optGroupLabel => $optGroup)
            {
                $html .= '<optgroup label="' . $optGroupLabel . '">';
                foreach ($optGroup as $label => $value)
                {
                    if (strcasecmp($value, $selected) == 0)
                        $html .= '<option value="' . $value . '" selected="selected">' . $label . '</option>';
                    else
                        $html .= '<option value="' . $value . '">' . $label . '</option>';
                }
                $html .= '</optgroup>';
            }
        }
        else
        {
            foreach ($options as $label => $value)
            {
                if (strcasecmp($value, $selected) == 0)
                    $html .= '<option value="' . $value . '" selected="selected">' . $label . '</option>';
                else
                    $html .= '<option value="' . $value . '">' . $label . '</option>';
            }
        }

        $html .= '</select>';

        return $html;
    }


    public static function formatOptions(array $format, array $options)
    {
        $formattedOptions = array();

        $keyField = $format['key'];
        $valueFields = is_array($format['values'])
            ? $format['values']
            : array($format['values']);

        foreach ($options as $option)
        {
            if (isset($option[$keyField]))
            {
                $values = array();
                foreach ($valueFields as $valueKey)
                {
                    if (isset($option[$valueKey]))
                        $values[$valueKey] = $option[$valueKey];
                }

                $formattedOptions[$option[$keyField]] = count($values) == 1
                    ? array_pop($values)
                    : $values;
            }
            else
                $formattedOptions[] = $option;
        }

        return $formattedOptions;
    }


    private static function _isOptGroup($options)
    {
        if (!is_array($options))
            return false;

        // re: http://stackoverflow.com/questions/262891/is-there-a-way-to-find-how-how-deep-a-php-array-is
        $max_indentation = 1;

        // PHP_EOL in case we're running on Windows
        $lines = explode(PHP_EOL, print_r($options, true));

        foreach ($lines as $line)
        {
            $indentation = (strlen($line) - strlen(ltrim($line))) / 4;
            $max_indentation = max($max_indentation, $indentation);
        }

        $arrayDepth = ceil(($max_indentation - 1) / 2) + 1;

        return $arrayDepth > 1;
    }
}