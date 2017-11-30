<?php

namespace AppBundle\Twig;
/**
 * Created by PhpStorm.
 * User: abdelhak
 * Date: 23/06/2017
 * Time: 11:47
 */
class AppExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('toLetter', array($this, 'toLetter')),
        );
    }

    public function toLetter($number)
    {
        $convert = explode('.', $number);
        $num[17] = array('zero', 'un', 'deux', 'trois', 'quatre', 'cinq', 'six', 'sept', 'huit',
            'neuf', 'dix', 'onze', 'douze', 'treize', 'quatorze', 'quinze', 'seize','01'=>'zero un',
            '02'=>'zero deux','03'=>'zero trois','04'=>'zero quatre','05'=>'zero cinq','06'=>'zero six',
            '07'=>'zero sept', '08'=>'zero huit','09'=>'zero neuf');

        $num[100] = array(20 => 'vingt', 30 => 'trente', 40 => 'quarante', 50 => 'cinquante',
            60 => 'soixante', 70 => 'soixante-dix', 80 => 'quatre-vingt', 90 => 'quatre-vingt-dix');

        if (isset($convert[1]) && $convert[1] != '') {
            return $this->toLetter($convert[0]) . ' virgule ' . $this->toLetter($convert[1]);
        }
        if ($number < 0)
            return 'moins ' . $this->toLetter(-$number);
        if ($number < 17) {
            return $num[17][$number];
        } elseif ($number < 20) {
            return 'dix-' . $this->toLetter($number - 10);
        } elseif ($number < 100) {
            if ($number % 10 == 0) {
                return $num[100][$number];
            } elseif (substr($number, -1) == 1) {
                if (((int) ($number / 10) * 10) < 70) {
                    return $this->toLetter((int) ($number / 10) * 10) . '-et-un';
                } elseif ($number == 71) {
                    return 'soixante-et-onze';
                } elseif ($number == 81) {
                    return 'quatre-vingt-un';
                } elseif ($number == 91) {
                    return 'quatre-vingt-onze';
                }
            } elseif ($number < 70) {
                return $this->toLetter($number - $number % 10) . '-' . $this->toLetter($number % 10);
            } elseif ($number < 80) {
                return $this->toLetter(60) . '-' . $this->toLetter($number % 20);
            } else {
                return $this->toLetter(80) . '-' . $this->toLetter($number % 20);
            }
        } elseif ($number == 100) {
            return 'cent';
        } elseif ($number < 200) {
            return $this->toLetter(100) . ' ' . $this->toLetter($number % 100);
        } elseif ($number < 1000) {
            return $this->toLetter((int) ($number / 100)) . ' ' . $this->toLetter(100) . ($number % 100 > 0 ? ' ' . $this->toLetter($number % 100) : '');
        } elseif ($number == 1000) {
            return 'mille';
        } elseif ($number < 2000) {
            return $this->toLetter(1000) . ' ' . $this->toLetter($number % 1000) . ' ';
        } elseif ($number < 1000000) {
            return $this->toLetter((int) ($number / 1000)) . ' ' . $this->toLetter(1000) . ($number % 1000 > 0 ? ' ' . $this->toLetter($number % 1000) : '');
        } elseif ($number == 1000000) {
            return 'millions';
        } elseif ($number < 2000000) {
            return $this->toLetter(1000000) . ' ' . $this->toLetter($number % 1000000);
        } elseif ($number < 1000000000) {
            return $this->toLetter((int) ($number / 1000000)) . ' ' . $this->toLetter(1000000) . ($number % 1000000 > 0 ? ' ' . $this->toLetter($number % 1000000) : '');
        }
    }
}