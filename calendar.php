<!DOCTYPE html>
<html>

    <head>
        <style>
            * {
                box-sizing: border-box;
            }

            body {
                margin: 0;
                font-family: sans-serif;
            }
        </style>
    </head>

    <body>
        <?php
        function e($el, $id, $x, $y, $width, $height, $fontsize, $borderthickness, $borderstyle, $text, $verticalallign, $onclick, $oninput) {
            $str_aa = '';
            $str_ab = '';
            switch ($el) {
                case 'l':
                case 'label':
                    $str_aa = '<div style="' .
                            'box-sizing: border-box; ' .
                            'position: absolute; ' .
                            'overflow: hidden; ' .
                            'left: ' . $x . 'px; ' .
                            'top: ' . $y . 'px; ' .
                            'width: ' . $width . 'px; ' .
                            'height: ' . $height . 'px; ' .
                            'font-size: ' . $fontsize . 'px; ' .
                            'border: ' . $borderthickness . 'px ' . $borderstyle . ';">';
                    $str_ab = '</div>';
                    break;
                case 'b':
                case 'button':
                    $str_aa = '<div style="' .
                            'box-sizing: border-box; ' .
                            'position: absolute; ' .
                            'overflow: hidden; ' .
                            'left: ' . $x . 'px; ' .
                            'top: ' . $y . 'px; ' .
                            'width: ' . $width . 'px; ' .
                            'height: ' . $height . 'px; ' .
                            'font-size: ' . $fontsize . 'px; ' .
                            'border: ' . $borderthickness . 'px ' . $borderstyle . '; ' .
                            'cursor: pointer;"' .
                            'onclick="' . $onclick . '">';
                    $str_ab = '</div>';
                    break;
                case 'i':
                case 'input':
                    $str_aa = '<input type="text" id="' . $id .
                    '" oninput="' . $oninput .
                    '" onclick="' . $onclick . '" ' .
                    'style="position: absolute; ' .
                    'left: ' . $x . 'px; ' .
                    'top: ' . $y . 'px; ' .
                    'width: ' . $width . 'px; ' .
                    'height: ' . $height . 'px; ' .
                    'font-size: ' . $fontsize . 'px; ' .
                    'border: ' . $borderthickness . 'px ' . $borderstyle . '; ' .
                    'cursor: pointer;" ' . 
                        'value="' . $text . '">';
                    $str_ab = '';
                    break;
            }

            switch ($verticalallign) {
                case 'c':
                case 'center':
                    echo $str_aa .
                    '<span style="' .
                    'margin: 0; ' .
                    'position: absolute; ' .
                    'top: 50%; ' .
                    'left: 50%; ' .
                    'margin-right: -50%;' .
                    'transform: translate(-50%, -50%); ' .
                    'text-align: center; ">' .
                    $text .
                    '</span>' .
                    $str_ab;
                    break;
                case '%':
                    echo $str_aa . $str_ab;
                    break;
                default:
                    echo $str_aa . $text . $str_ab;
                    break;
            }
        }

        $y = $_GET['year'];
        if (($y < 2008) || ($y > 2050)){
            $y = intval(date('Y'));
        }

        $m = $_GET['month'];
        if (($m < 1) || ($m > 12)) {
            $m = intval(date('m'));
        }

        $kd = array("mon", "tue", "wed", "thu", "fri", "sat", "sun");
        $kd2 = array("monday", "tuesday", "wedensday", "thursday", "friday", "saturday", "sunday");

        e('l', '',2, 5, 220, 32, 29, 0, '', $y . ' ' . date('F', mktime(0, 0, 0, $m, 1, $y)), '', '', '');
        e('b', '', 224, 5, 32, 32, 29, 1, 'solid black', '+', 'c', '', '');
        e('b', '', 257, 5, 32, 32, 29, 1, 'solid black', '-', 'c', '', '');

        // rendering daynames 
        $hw = 60;
        for ($dd = 1; $dd < 8; $dd++) {
            $ypos = 50;
            $xpos = 26 + $dd * 33;
            e('l', '',$xpos, $ypos, 32, 32, 10, 1, 'solid black', $kd[$dd - 1], 'c', '', '');

            $ypos = 5;
            $xpos = 388 + ($dd - 1) * ($hw + 1) * 3;
            e('l', '',$xpos, $ypos, $hw * 3, 32, 24, 0, '', $kd2[$dd - 1], 'c', '', '');
        }

        // rendering small and big calendar
        $ypos = 96;
        $ypos2 = 50;
        $weekchange = true;
        for ($dd = 1; $dd < 32; $dd++) {
            $sd = intval(date('N', mktime(0, 0, 0, $m, $dd, $y)));
            if ($weekchange) {
                e('l', '',12, $ypos, 32, 32, 18, 1, 'solid black', intval(date('W', mktime(0, 0, 0, $m, $dd, $y))), 'c', '', '');
            }
            $xpos = 26 + $sd * 33;
            e('b', '', $xpos, $ypos, 32, 32, 10, 1, 'solid black', $dd, 'c', '', '');

            $xpos = 388 + (($sd - 1) % 7) * ($hw + 1) * 3;
            e('l', '',$xpos, $ypos2, $hw, $hw, 36, 1, 'solid gray', $dd, 'c', '', '', '');
            for ($se = 1; $se < 9; $se++) {
                e('b', '', $xpos + ($hw - 1) * ($se % 3), $ypos2 + ($hw - 1) * floor($se / 3), $hw, $hw, 10, 1, 'solid gray', '', 'c', '', '');
            }

            if ($sd == 7) {
                $ypos += 33;
                $ypos2 += ($hw + 1) * 3;
            }
        }

        // input field
        e('i', 'thomas', 12, 308, 277, 32, 18, 1, 'solid black', '', '%', '', '');

        // render charset
        $arr = array(
            33, 126,
            161, 172,
            8265, 8265,
            8505, 8505,
            8598, 8601,
            8617, 8618,
            9000, 9000,
            8986, 8987,
            9167, 9167,
            9193, 9203,
            9208, 9210,
            9312, 9331,
            9728, 9984,
            9986, 10062,
            10067, 10071,
            10083, 10085,
            10102, 10135,
            10145, 10145,
            10175, 10175,
            10548, 10549,
            11013, 11015,
            11088, 11088,
            11093, 11093,
            128512, 128592,
            57352, 57353,
            119808, 119859,
            119552, 119638,
            119860, 119892,
            119894, 119911,
            119912, 119937,
            119938, 119963,
            120146, 120171,
            120782, 120831,
            127248, 127337,
            127344, 127386,
            127744, 128722,
            128736, 128761,
            129296, 129342,
            129344, 129349,
            129351, 129392,
            129395, 129398,
            129402, 129402,
            129404, 129442,
            129456, 129465,
            129472, 129474,
            129488, 129535
        );

        $x1 = 3;
        $x = 3;
        $y1 = 5;
        $y = 5;
        $t = 1;
        $lc = 0;
        $tx = 862;

// emojis
        echo '<div style="position: absolute; overflow-y: scroll; left: 1px; top: 350px; width: 294px; height: 650px">';

        echo '<div id="g1x">';
        for ($m = 0; $m < count($arr); $m += 2) {
            for ($n = $arr[$m]; $n < ($arr[$m + 1] + 1); $n++) {
                e('b', '', $x, $y, 32, 32, 20, 1, 'solid black', '&#' . $n, 'c', '', '');
                $x += 31;
                if ($x > 33 * 8) {
                    $x = $x1;
                    $y += 31;
                    $lc++;
                    if ($lc == 28) {
                        $t++;
                        echo '</div>';
                        echo '<div id="g' . $t . 'x">';
                        $tx += 144;
                        $lc = 0;
                    }
                }
            }
        }
        echo '</div>';
        echo '</div>';

// fonsize
        echo '<div id="fss">';
        $y = 5;
        for ($n = 10; $n < 39; $n += 2) {
            e('b', '', 310, $y, 60, 60, $n, 1, 'solid black', 'Size', 'c', '', '');
            $y += 65;
        }
        echo '</div>';
        ?>


    </body>

</html>