<?php
if (!function_exists('formatDateTime')) {
    function formatDateTime($value, $str = 'd-m-Y')
    {
        return \Carbon\Carbon::parse($value)->format($str);
    }
}

if (!function_exists('breadcrumb')) {
    function breadcrumb($params)
    {

    }
}
if (!function_exists('renderToAnswers')) {
    function renderToAnswers($json, $correct = null, $isChoice = false)
    {
        $answers = json_decode($json);
        $res = '<ul style="padding: 0; list-style-type: none;">';
        foreach ($answers as $key => $value) {
            if ($correct !== null && strtolower($correct) === strtolower($key)) {
                $res .= "<li class='mb-1 text-success font-weight-bold'>
                        <span class='mr-1 ml-2 pl-1 pr-1 border border-success rounded-circle'>" . $key . ".</span>$value
                        </li>";
            } else {
                $res .= "<li class='mb-1 text-muted'><span class='mr-1 ml-2'>" . $key . ".</span>$value</li>";
            }
        }
        return $res . '</ul>';
    }
}

if (!function_exists('showDropdown')) {
    function showDropdown($data, $select = 0)
    {
        foreach ($data as $key => $value) {
            $id = $value->id;
            $name = $value->name;
            if ($select != 0 && $id == $select) {
                echo "<option value='" . $id . "' selected>$name</option>";
            } else {
                echo "<option value='" . $id . "'>$name</option>";
            }
        }

    }
}
