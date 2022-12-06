<?php namespace App\Helpers;

class General {
    public function get_random_string()
    {
        $lenght = 64;
        $chars_array = array_merge(range('a', 'z'),range('A', 'Z'), range('0', '9'), array('$', '.', '+', '-')); // 26 + 26 + 10 + 4 => 66
        $to_return = '';

        for ($i = 0; $i < $lenght; $i++) {
            $chars_array_length = count($chars_array);
            $index = rand(0, ($chars_array_length - 1));
            $to_return .= $chars_array[$index];
            unset($chars_array[$index]);
            sort($chars_array);
        }

        return $to_return;
    }
}