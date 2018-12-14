<?php
/**
 * Created by PhpStorm.
 * User: baiyan
 * Date: 2018-12-13
 * Time: 10:24
 */

namespace Des;

class Des
{
    
    public function strEnc($data, $firstKey, $secondKey, $thirdKey)
    {
        $leng = strlen($data);
        $encData = "";
        $firstKeyBt = null;
        $secondKeyBt = null;
        $thirdKeyBt = null;
        $firstLength = null;
        $secondLength = null;
        $thirdLength = null;
        if ($firstKey != null && $firstKey != "") {
            $firstKeyBt = $this->getKeyBytes($firstKey);
            $firstLength = count($firstKeyBt);
        }
        if ($secondKey != null && $secondKey != "") {
            $secondKeyBt = $this->getKeyBytes($secondKey);
            $secondLength = count($secondKeyBt);
        }
        if ($thirdKey != null && $thirdKey != "") {
            $thirdKeyBt = $this->getKeyBytes($thirdKey);
            $thirdLength = count($thirdKeyBt);
        }
        if ($leng > 0) {
            if ($leng < 4) {
                $bt = $this->strToBt($data);
                $encByte = null;
                if ($firstKey != null && $firstKey != "" && $secondKey != null && $secondKey != "" && $thirdKey != null && $thirdKey != "") {
                    $tempBt = null;
                    $x = null;
                    $y = null;
                    $z = null;
                    $tempBt = $bt;
                    for ($x = 0; $x < $firstLength; $x++) {
                        $tempBt = $this->enc($tempBt, $firstKeyBt[$x]);
                    }
                    for ($y = 0; $y < $secondLength; $y++) {
                        $tempBt = $this->enc($tempBt, $secondKeyBt[$y]);
                    }
                    for ($z = 0; $z < $thirdLength; $z++) {
                        $tempBt = $this->enc($tempBt, $thirdKeyBt[$z]);
                    }
                    $encByte = $tempBt;
                } else {
                    if ($firstKey != null && $firstKey != "" && $secondKey != null && $secondKey != "") {
                        $tempBt = null;
                        $x = null;
                        $y = null;
                        $tempBt = $bt;
                        for ($x = 0; $x < $firstLength; $x++) {
                            $tempBt = $this->enc($tempBt, $firstKeyBt[$x]);
                        }
                        for ($y = 0; $y < $secondLength; $y++) {
                            $tempBt = $this->enc($tempBt, $secondKeyBt[$y]);
                        }
                        $encByte = $tempBt;
                    } else {
                        if ($firstKey != null && $firstKey != "") {
                            $tempBt = null;
                            $tempBt = $bt;
                            for ($x = 0; $x < $firstLength; $x++) {
                                $tempBt = $this->enc($tempBt, $firstKeyBt[$x]);
                            }
                            $encByte = $tempBt;
                        }
                    }
                }
                $encData = $this->bt64ToHex($encByte);
            } else {
                $iterator = intval($leng / 4);
                $remainder = $leng % 4;
                for ($i = 0; $i < $iterator; $i++) {
                    $tempData = $this->substring($data, $i * 4 + 0, $i * 4 + 4);
                    $tempByte = $this->strToBt($tempData);
                    $encByte = null;
                    if ($firstKey != null && $firstKey != "" && $secondKey != null && $secondKey != "" && $thirdKey != null && $thirdKey != "") {
                        $tempBt = null;
                        $x = null;
                        $y = null;
                        $z = null;
                        $tempBt = $tempByte;
                        for ($x = 0; $x < $firstLength; $x++) {
                            $tempBt = $this->enc($tempBt, $firstKeyBt[$x]);
                        }
                        for ($y = 0; $y < $secondLength; $y++) {
                            $tempBt = $this->enc($tempBt, $secondKeyBt[$y]);
                        }
                        for ($z = 0; $z < $thirdLength; $z++) {
                            $tempBt = $this->enc($tempBt, $thirdKeyBt[$z]);
                        }
                        $encByte = $tempBt;
                    } else {
                        if ($firstKey != null && $firstKey != "" && $secondKey != null && $secondKey != "") {
                            $tempBt = null;
                            $x = null;
                            $y = null;
                            $tempBt = $tempByte;
                            for ($x = 0; $x < $firstLength; $x++) {
                                $tempBt = $this->enc($tempBt, $firstKeyBt[$x]);
                            }
                            for ($y = 0; $y < $secondLength; $y++) {
                                $tempBt = $this->enc($tempBt, $secondKeyBt[$y]);
                            }
                            $encByte = $tempBt;
                        } else {
                            if ($firstKey != null && $firstKey != "") {
                                $tempBt = null;
                                $x = null;
                                $tempBt = $tempByte;
                                for ($x = 0; $x < $firstLength; $x++) {
                                    $tempBt = $this->enc($tempBt, $firstKeyBt[$x]);
                                }
                                $encByte = $tempBt;
                            }
                        }
                    }
                    $encData .= $this->bt64ToHex($encByte);
                }
                if ($remainder > 0) {
                    $remainderData = $this->substring($data, $iterator * 4 + 0, $leng);
                    $tempByte = $this->strToBt($remainderData);
                    $encByte = null;
                    if ($firstKey != null && $firstKey != "" && $secondKey != null && $secondKey != "" && $thirdKey != null && $thirdKey != "") {
                        $tempBt = null;
                        $x = null;
                        $y = null;
                        $z = null;
                        $tempBt = $tempByte;
                        for ($x = 0; $x < $firstLength; $x++) {
                            $tempBt = $this->enc($tempBt, $firstKeyBt[$x]);
                        }
                        for ($y = 0; $y < $secondLength; $y++) {
                            $tempBt = $this->enc($tempBt, $secondKeyBt[$y]);
                        }
                        for ($z = 0; $z < $thirdLength; $z++) {
                            $tempBt = $this->enc($tempBt, $thirdKeyBt[$z]);
                        }
                        $encByte = $tempBt;
                    } else {
                        if ($firstKey != null && $firstKey != "" && $secondKey != null && $secondKey != "") {
                            $tempBt = null;
                            $x = null;
                            $y = null;
                            $tempBt = $tempByte;
                            for ($x = 0; $x < $firstLength; $x++) {
                                $tempBt = $this->enc($tempBt, $firstKeyBt[$x]);
                            }
                            for ($y = 0; $y < $secondLength; $y++) {
                                $tempBt = $this->enc($tempBt, $secondKeyBt[$y]);
                            }
                            $encByte = $tempBt;
                        } else {
                            if ($firstKey != null && $firstKey != "") {
                                $tempBt = null;
                                $x = null;
                                $tempBt = $tempByte;
                                for ($x = 0; $x < $firstLength; $x++) {
                                    $tempBt = $this->enc($tempBt, $firstKeyBt[$x]);
                                }
                                $encByte = $tempBt;
                            }
                        }
                    }
                    $encData .= $this->bt64ToHex($encByte);
                }
            }
        }
        return $encData;
    }


    private function getKeyBytes($key)
    {
        $keyBytes = array();
        $leng = strlen($key);
        $iterator = intval($leng / 4);
        $remainder = $leng % 4;
        for ($i = 0; $i < $iterator; $i++) {
            $keyBytes[$i] = $this->strToBt($this->substring($key, $i * 4 + 0, $i * 4 + 4));
        }
        if ($remainder > 0) {
            $keyBytes[$i] = $this->strToBt($this->substring($key, $i * 4 + 0, $leng));
        }
        return $keyBytes;
    }

    private function strToBt($str)
    {
        $leng = strlen($str);
        $bt = array();
        if ($leng < 4) {
            for ($i = 0; $i < $leng; $i++) {
                $k = $this->charCodeAt($str, $i);
                for ($j = 0; $j < 16; $j++) {
                    $pow = 1;
                    for ($m = 15; $m > $j; $m--) {
                        $pow *= 2;
                    }
                    $bt[16 * $i + $j] = intval($k / $pow) % 2;
                }
            }
            for ($p = $leng; $p < 4; $p++) {
                $k = 0;
                for ($q = 0; $q < 16; $q++) {
                    $pow = 1;
                    for ($m = 15; $m > $q; $m--) {
                        $pow *= 2;
                    }
                    $bt[16 * $p + $q] = intval($k / $pow) % 2;
                }
            }
        } else {
            for ($i = 0; $i < 4; $i++) {
                $k = $this->charCodeAt($str, $i);
                for ($j = 0; $j < 16; $j++) {
                    $pow = 1;
                    for ($m = 15; $m > $j; $m--) {
                        $pow *= 2;
                    }
                    $bt[16 * $i + $j] = intval($k / $pow) % 2;
                }
            }
        }
        return $bt;
    }

    private function bt4ToHex($binary)
    {
        $hex = null;
        switch ($binary) {
            case "0000":
                $hex = "0";
                break;
            case "0001":
                $hex = "1";
                break;
            case "0010":
                $hex = "2";
                break;
            case "0011":
                $hex = "3";
                break;
            case "0100":
                $hex = "4";
                break;
            case "0101":
                $hex = "5";
                break;
            case "0110":
                $hex = "6";
                break;
            case "0111":
                $hex = "7";
                break;
            case "1000":
                $hex = "8";
                break;
            case "1001":
                $hex = "9";
                break;
            case "1010":
                $hex = "A";
                break;
            case "1011":
                $hex = "B";
                break;
            case "1100":
                $hex = "C";
                break;
            case "1101":
                $hex = "D";
                break;
            case "1110":
                $hex = "E";
                break;
            case "1111":
                $hex = "F";
                break;
        }
        return $hex;
    }


    private function bt64ToHex($byteData)
    {
        $hex = "";
        for ($i = 0; $i < 16; $i++) {
            $bt = "";
            for ($j = 0; $j < 4; $j++) {
                $bt .= $byteData[$i * 4 + $j];
            }
            $hex .= $this->bt4ToHex($bt);
        }
        return $hex;
    }


    private function enc($dataByte, $keyByte)
    {
        $keys = $this->generateKeys($keyByte);
        $ipByte = $this->initPermute($dataByte);
        $ipLeft = array();
        $ipRight = array();
        $tempLeft = array();
        for ($k = 0; $k < 32; $k++) {
            $ipLeft[$k] = $ipByte[$k];
            $ipRight[$k] = $ipByte[32 + $k];
        }
        for ($i = 0; $i < 16; $i++) {
            for ($j = 0; $j < 32; $j++) {
                $tempLeft[$j] = $ipLeft[$j];
                $ipLeft[$j] = $ipRight[$j];
            }
            $key = array();
            for ($m = 0; $m < 48; $m++) {
                $key[$m] = $keys[$i][$m];
            }
            $tempRight = $this->yiHuo($this->pPermute($this->sBoxPermute($this->yiHuo($this->expandPermute($ipRight), $key))), $tempLeft);
            for ($n = 0; $n < 32; $n++) {
                $ipRight[$n] = $tempRight[$n];
            }
        }
        $finalData = array();
        for ($i = 0; $i < 32; $i++) {
            $finalData[$i] = $ipRight[$i];
            $finalData[32 + $i] = $ipLeft[$i];
        }
        return $this->finallyPermute($finalData);
    }

    private function initPermute($originalData)
    {
        $ipByte = array();
        for ($i = 0, $m = 1, $n = 0; $i < 4; $i++, $m += 2, $n += 2) {
            for ($j = 7, $k = 0; $j >= 0; $j--, $k++) {
                $ipByte[$i * 8 + $k] = $originalData[$j * 8 + $m];
                $ipByte[$i * 8 + $k + 32] = $originalData[$j * 8 + $n];
            }
        }
        return $ipByte;
    }

    private function expandPermute($rightData)
    {
        $epByte = array();
        for ($i = 0; $i < 8; $i++) {
            if ($i == 0) {
                $epByte[$i * 6 + 0] = $rightData[31];
            } else {
                $epByte[$i * 6 + 0] = $rightData[$i * 4 - 1];
            }
            $epByte[$i * 6 + 1] = $rightData[$i * 4 + 0];
            $epByte[$i * 6 + 2] = $rightData[$i * 4 + 1];
            $epByte[$i * 6 + 3] = $rightData[$i * 4 + 2];
            $epByte[$i * 6 + 4] = $rightData[$i * 4 + 3];
            if ($i == 7) {
                $epByte[$i * 6 + 5] = $rightData[0];
            } else {
                $epByte[$i * 6 + 5] = $rightData[$i * 4 + 4];
            }
        }
        return $epByte;
    }

    private function yiHuo($byteOne, $byteTwo)
    {
        $yiHuoByte = array();
        for ($i = 0; $i < count($byteOne); $i++) {
            $yiHuoByte[$i] = $byteOne[$i] ^ $byteTwo[$i];
        }
        return $yiHuoByte;
    }

    private function sBoxPermute($expandByte)
    {
        $sBoxByte = array();
        $binary = "";
        $s1 = array(array(14, 4, 13, 1, 2, 15, 11, 8, 3, 10, 6, 12, 5, 9, 0, 7), array(0, 15, 7, 4, 14, 2, 13, 1, 10, 6, 12, 11, 9, 5, 3, 8), array(4, 1, 14, 8, 13, 6, 2, 11, 15, 12, 9, 7, 3, 10, 5, 0), array(15, 12, 8, 2, 4, 9, 1, 7, 5, 11, 3, 14, 10, 0, 6, 13));
        $s2 = array(array(15, 1, 8, 14, 6, 11, 3, 4, 9, 7, 2, 13, 12, 0, 5, 10), array(3, 13, 4, 7, 15, 2, 8, 14, 12, 0, 1, 10, 6, 9, 11, 5), array(0, 14, 7, 11, 10, 4, 13, 1, 5, 8, 12, 6, 9, 3, 2, 15), array(13, 8, 10, 1, 3, 15, 4, 2, 11, 6, 7, 12, 0, 5, 14, 9));
        $s3 = array(array(10, 0, 9, 14, 6, 3, 15, 5, 1, 13, 12, 7, 11, 4, 2, 8), array(13, 7, 0, 9, 3, 4, 6, 10, 2, 8, 5, 14, 12, 11, 15, 1), array(13, 6, 4, 9, 8, 15, 3, 0, 11, 1, 2, 12, 5, 10, 14, 7), array(1, 10, 13, 0, 6, 9, 8, 7, 4, 15, 14, 3, 11, 5, 2, 12));
        $s4 = array(array(7, 13, 14, 3, 0, 6, 9, 10, 1, 2, 8, 5, 11, 12, 4, 15), array(13, 8, 11, 5, 6, 15, 0, 3, 4, 7, 2, 12, 1, 10, 14, 9), array(10, 6, 9, 0, 12, 11, 7, 13, 15, 1, 3, 14, 5, 2, 8, 4), array(3, 15, 0, 6, 10, 1, 13, 8, 9, 4, 5, 11, 12, 7, 2, 14));
        $s5 = array(array(2, 12, 4, 1, 7, 10, 11, 6, 8, 5, 3, 15, 13, 0, 14, 9), array(14, 11, 2, 12, 4, 7, 13, 1, 5, 0, 15, 10, 3, 9, 8, 6), array(4, 2, 1, 11, 10, 13, 7, 8, 15, 9, 12, 5, 6, 3, 0, 14), array(11, 8, 12, 7, 1, 14, 2, 13, 6, 15, 0, 9, 10, 4, 5, 3));
        $s6 = array(array(12, 1, 10, 15, 9, 2, 6, 8, 0, 13, 3, 4, 14, 7, 5, 11), array(10, 15, 4, 2, 7, 12, 9, 5, 6, 1, 13, 14, 0, 11, 3, 8), array(9, 14, 15, 5, 2, 8, 12, 3, 7, 0, 4, 10, 1, 13, 11, 6), array(4, 3, 2, 12, 9, 5, 15, 10, 11, 14, 1, 7, 6, 0, 8, 13));
        $s7 = array(array(4, 11, 2, 14, 15, 0, 8, 13, 3, 12, 9, 7, 5, 10, 6, 1), array(13, 0, 11, 7, 4, 9, 1, 10, 14, 3, 5, 12, 2, 15, 8, 6), array(1, 4, 11, 13, 12, 3, 7, 14, 10, 15, 6, 8, 0, 5, 9, 2), array(6, 11, 13, 8, 1, 4, 10, 7, 9, 5, 0, 15, 14, 2, 3, 12));
        $s8 = array(array(13, 2, 8, 4, 6, 15, 11, 1, 10, 9, 3, 14, 5, 0, 12, 7), array(1, 15, 13, 8, 10, 3, 7, 4, 12, 5, 6, 11, 0, 14, 9, 2), array(7, 11, 4, 1, 9, 12, 14, 2, 0, 6, 10, 13, 15, 3, 5, 8), array(2, 1, 14, 7, 4, 10, 8, 13, 15, 12, 9, 0, 3, 5, 6, 11));
        for ($m = 0; $m < 8; $m++) {
            $i = $expandByte[$m * 6 + 0] * 2 + $expandByte[$m * 6 + 5];
            $j = $expandByte[$m * 6 + 1] * 2 * 2 * 2 + $expandByte[$m * 6 + 2] * 2 * 2 + $expandByte[$m * 6 + 3] * 2 + $expandByte[$m * 6 + 4];
            switch ($m) {
                case 0:
                    $binary = $this->getBoxBinary($s1[$i][$j]);
                    break;
                case 1:
                    $binary = $this->getBoxBinary($s2[$i][$j]);
                    break;
                case 2:
                    $binary = $this->getBoxBinary($s3[$i][$j]);
                    break;
                case 3:
                    $binary = $this->getBoxBinary($s4[$i][$j]);
                    break;
                case 4:
                    $binary = $this->getBoxBinary($s5[$i][$j]);
                    break;
                case 5:
                    $binary = $this->getBoxBinary($s6[$i][$j]);
                    break;
                case 6:
                    $binary = $this->getBoxBinary($s7[$i][$j]);
                    break;
                case 7:
                    $binary = $this->getBoxBinary($s8[$i][$j]);
                    break;
            }
            $sBoxByte[$m * 4 + 0] = intval($this->substring($binary, 0, 1));
            $sBoxByte[$m * 4 + 1] = intval($this->substring($binary, 1, 2));
            $sBoxByte[$m * 4 + 2] = intval($this->substring($binary, 2, 3));
            $sBoxByte[$m * 4 + 3] = intval($this->substring($binary, 3, 4));
        }
        return $sBoxByte;
    }

    private function pPermute($sBoxByte)
    {
        $pBoxPermute = array();
        $pBoxPermute[0] = $sBoxByte[15];
        $pBoxPermute[1] = $sBoxByte[6];
        $pBoxPermute[2] = $sBoxByte[19];
        $pBoxPermute[3] = $sBoxByte[20];
        $pBoxPermute[4] = $sBoxByte[28];
        $pBoxPermute[5] = $sBoxByte[11];
        $pBoxPermute[6] = $sBoxByte[27];
        $pBoxPermute[7] = $sBoxByte[16];
        $pBoxPermute[8] = $sBoxByte[0];
        $pBoxPermute[9] = $sBoxByte[14];
        $pBoxPermute[10] = $sBoxByte[22];
        $pBoxPermute[11] = $sBoxByte[25];
        $pBoxPermute[12] = $sBoxByte[4];
        $pBoxPermute[13] = $sBoxByte[17];
        $pBoxPermute[14] = $sBoxByte[30];
        $pBoxPermute[15] = $sBoxByte[9];
        $pBoxPermute[16] = $sBoxByte[1];
        $pBoxPermute[17] = $sBoxByte[7];
        $pBoxPermute[18] = $sBoxByte[23];
        $pBoxPermute[19] = $sBoxByte[13];
        $pBoxPermute[20] = $sBoxByte[31];
        $pBoxPermute[21] = $sBoxByte[26];
        $pBoxPermute[22] = $sBoxByte[2];
        $pBoxPermute[23] = $sBoxByte[8];
        $pBoxPermute[24] = $sBoxByte[18];
        $pBoxPermute[25] = $sBoxByte[12];
        $pBoxPermute[26] = $sBoxByte[29];
        $pBoxPermute[27] = $sBoxByte[5];
        $pBoxPermute[28] = $sBoxByte[21];
        $pBoxPermute[29] = $sBoxByte[10];
        $pBoxPermute[30] = $sBoxByte[3];
        $pBoxPermute[31] = $sBoxByte[24];
        return $pBoxPermute;
    }

    private function finallyPermute($endByte)
    {
        $fpByte = array();
        $fpByte[0] = $endByte[39];
        $fpByte[1] = $endByte[7];
        $fpByte[2] = $endByte[47];
        $fpByte[3] = $endByte[15];
        $fpByte[4] = $endByte[55];
        $fpByte[5] = $endByte[23];
        $fpByte[6] = $endByte[63];
        $fpByte[7] = $endByte[31];
        $fpByte[8] = $endByte[38];
        $fpByte[9] = $endByte[6];
        $fpByte[10] = $endByte[46];
        $fpByte[11] = $endByte[14];
        $fpByte[12] = $endByte[54];
        $fpByte[13] = $endByte[22];
        $fpByte[14] = $endByte[62];
        $fpByte[15] = $endByte[30];
        $fpByte[16] = $endByte[37];
        $fpByte[17] = $endByte[5];
        $fpByte[18] = $endByte[45];
        $fpByte[19] = $endByte[13];
        $fpByte[20] = $endByte[53];
        $fpByte[21] = $endByte[21];
        $fpByte[22] = $endByte[61];
        $fpByte[23] = $endByte[29];
        $fpByte[24] = $endByte[36];
        $fpByte[25] = $endByte[4];
        $fpByte[26] = $endByte[44];
        $fpByte[27] = $endByte[12];
        $fpByte[28] = $endByte[52];
        $fpByte[29] = $endByte[20];
        $fpByte[30] = $endByte[60];
        $fpByte[31] = $endByte[28];
        $fpByte[32] = $endByte[35];
        $fpByte[33] = $endByte[3];
        $fpByte[34] = $endByte[43];
        $fpByte[35] = $endByte[11];
        $fpByte[36] = $endByte[51];
        $fpByte[37] = $endByte[19];
        $fpByte[38] = $endByte[59];
        $fpByte[39] = $endByte[27];
        $fpByte[40] = $endByte[34];
        $fpByte[41] = $endByte[2];
        $fpByte[42] = $endByte[42];
        $fpByte[43] = $endByte[10];
        $fpByte[44] = $endByte[50];
        $fpByte[45] = $endByte[18];
        $fpByte[46] = $endByte[58];
        $fpByte[47] = $endByte[26];
        $fpByte[48] = $endByte[33];
        $fpByte[49] = $endByte[1];
        $fpByte[50] = $endByte[41];
        $fpByte[51] = $endByte[9];
        $fpByte[52] = $endByte[49];
        $fpByte[53] = $endByte[17];
        $fpByte[54] = $endByte[57];
        $fpByte[55] = $endByte[25];
        $fpByte[56] = $endByte[32];
        $fpByte[57] = $endByte[0];
        $fpByte[58] = $endByte[40];
        $fpByte[59] = $endByte[8];
        $fpByte[60] = $endByte[48];
        $fpByte[61] = $endByte[16];
        $fpByte[62] = $endByte[56];
        $fpByte[63] = $endByte[24];
        return $fpByte;
    }

    private function getBoxBinary($i)
    {
        $binary = "";
        switch ($i) {
            case 0:
                $binary = "0000";
                break;
            case 1:
                $binary = "0001";
                break;
            case 2:
                $binary = "0010";
                break;
            case 3:
                $binary = "0011";
                break;
            case 4:
                $binary = "0100";
                break;
            case 5:
                $binary = "0101";
                break;
            case 6:
                $binary = "0110";
                break;
            case 7:
                $binary = "0111";
                break;
            case 8:
                $binary = "1000";
                break;
            case 9:
                $binary = "1001";
                break;
            case 10:
                $binary = "1010";
                break;
            case 11:
                $binary = "1011";
                break;
            case 12:
                $binary = "1100";
                break;
            case 13:
                $binary = "1101";
                break;
            case 14:
                $binary = "1110";
                break;
            case 15:
                $binary = "1111";
                break;
        }
        return $binary;
    }

    private function generateKeys($keyByte)
    {
        $key = array();
        $keys = array();
        $keys[0] = array();
        $keys[1] = array();
        $keys[2] = array();
        $keys[3] = array();
        $keys[4] = array();
        $keys[5] = array();
        $keys[6] = array();
        $keys[7] = array();
        $keys[8] = array();
        $keys[9] = array();
        $keys[10] = array();
        $keys[11] = array();
        $keys[12] = array();
        $keys[13] = array();
        $keys[14] = array();
        $keys[15] = array();
        $loop = array(1, 1, 2, 2, 2, 2, 2, 2, 1, 2, 2, 2, 2, 2, 2, 1);
        for ($i = 0; $i < 7; $i++) {
            for ($j = 0, $k = 7; $j < 8; $j++, $k--) {
                $key[$i * 8 + $j] = $keyByte[8 * $k + $i];
            }
        }
        for ($i = 0; $i < 16; $i++) {
            for ($j = 0; $j < $loop[$i]; $j++) {
                $tempLeft = $key[0];
                $tempRight = $key[28];
                for ($k = 0; $k < 27; $k++) {
                    $key[$k] = $key[$k + 1];
                    $key[28 + $k] = $key[29 + $k];
                }
                $key[27] = $tempLeft;
                $key[55] = $tempRight;
            }
            $tempKey = array();
            $tempKey[0] = $key[13];
            $tempKey[1] = $key[16];
            $tempKey[2] = $key[10];
            $tempKey[3] = $key[23];
            $tempKey[4] = $key[0];
            $tempKey[5] = $key[4];
            $tempKey[6] = $key[2];
            $tempKey[7] = $key[27];
            $tempKey[8] = $key[14];
            $tempKey[9] = $key[5];
            $tempKey[10] = $key[20];
            $tempKey[11] = $key[9];
            $tempKey[12] = $key[22];
            $tempKey[13] = $key[18];
            $tempKey[14] = $key[11];
            $tempKey[15] = $key[3];
            $tempKey[16] = $key[25];
            $tempKey[17] = $key[7];
            $tempKey[18] = $key[15];
            $tempKey[19] = $key[6];
            $tempKey[20] = $key[26];
            $tempKey[21] = $key[19];
            $tempKey[22] = $key[12];
            $tempKey[23] = $key[1];
            $tempKey[24] = $key[40];
            $tempKey[25] = $key[51];
            $tempKey[26] = $key[30];
            $tempKey[27] = $key[36];
            $tempKey[28] = $key[46];
            $tempKey[29] = $key[54];
            $tempKey[30] = $key[29];
            $tempKey[31] = $key[39];
            $tempKey[32] = $key[50];
            $tempKey[33] = $key[44];
            $tempKey[34] = $key[32];
            $tempKey[35] = $key[47];
            $tempKey[36] = $key[43];
            $tempKey[37] = $key[48];
            $tempKey[38] = $key[38];
            $tempKey[39] = $key[55];
            $tempKey[40] = $key[33];
            $tempKey[41] = $key[52];
            $tempKey[42] = $key[45];
            $tempKey[43] = $key[41];
            $tempKey[44] = $key[49];
            $tempKey[45] = $key[35];
            $tempKey[46] = $key[28];
            $tempKey[47] = $key[31];
            switch ($i) {
                case 0:
                    for ($m = 0; $m < 48; $m++) {
                        $keys[0][$m] = $tempKey[$m];
                    }
                    break;
                case 1:
                    for ($m = 0; $m < 48; $m++) {
                        $keys[1][$m] = $tempKey[$m];
                    }
                    break;
                case 2:
                    for ($m = 0; $m < 48; $m++) {
                        $keys[2][$m] = $tempKey[$m];
                    }
                    break;
                case 3:
                    for ($m = 0; $m < 48; $m++) {
                        $keys[3][$m] = $tempKey[$m];
                    }
                    break;
                case 4:
                    for ($m = 0; $m < 48; $m++) {
                        $keys[4][$m] = $tempKey[$m];
                    }
                    break;
                case 5:
                    for ($m = 0; $m < 48; $m++) {
                        $keys[5][$m] = $tempKey[$m];
                    }
                    break;
                case 6:
                    for ($m = 0; $m < 48; $m++) {
                        $keys[6][$m] = $tempKey[$m];
                    }
                    break;
                case 7:
                    for ($m = 0; $m < 48; $m++) {
                        $keys[7][$m] = $tempKey[$m];
                    }
                    break;
                case 8:
                    for ($m = 0; $m < 48; $m++) {
                        $keys[8][$m] = $tempKey[$m];
                    }
                    break;
                case 9:
                    for ($m = 0; $m < 48; $m++) {
                        $keys[9][$m] = $tempKey[$m];
                    }
                    break;
                case 10:
                    for ($m = 0; $m < 48; $m++) {
                        $keys[10][$m] = $tempKey[$m];
                    }
                    break;
                case 11:
                    for ($m = 0; $m < 48; $m++) {
                        $keys[11][$m] = $tempKey[$m];
                    }
                    break;
                case 12:
                    for ($m = 0; $m < 48; $m++) {
                        $keys[12][$m] = $tempKey[$m];
                    }
                    break;
                case 13:
                    for ($m = 0; $m < 48; $m++) {
                        $keys[13][$m] = $tempKey[$m];
                    }
                    break;
                case 14:
                    for ($m = 0; $m < 48; $m++) {
                        $keys[14][$m] = $tempKey[$m];
                    }
                    break;
                case 15:
                    for ($m = 0; $m < 48; $m++) {
                        $keys[15][$m] = $tempKey[$m];
                    }
                    break;
            }
        }
        return $keys;
    }

    private function substring($str, $start, $end)
    {
        $len = strlen($str);
        if (func_num_args() === 1) {
            $end = $len;
        }
        $start = (int)$start;
        $end = (int)$end;
        if ($start < 0) $start = 0;
        if ($start > $len) $start = $len;
        if ($end < 0) $end = 0;
        if ($end > $len) $end = $len;
        if ($start === $end) {
            return '';
        }
        if ($end < $start) {
            list($start, $end) = array($end, $start);
        }
        return mb_substr($str, $start, $end - $start);
    }


    private function charCodeAt($str, $index)
    {
        $char = mb_substr($str, $index, 1, 'UTF-8');
        if (mb_check_encoding($char, 'UTF-8')) {
            $ret = mb_convert_encoding($char, "UCS-4BE");
            $ret = unpack("N", $ret);
            $ret = dechex($ret[1]);
            return $ret;
        } else {
            return null;
        }
    }
}
