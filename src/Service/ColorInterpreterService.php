<?php


namespace App\Service;

use ourcodeworld\NameThatColor\ColorInterpreter;
use stdClass;

class ColorInterpreterService
{
    /**
     * @var ColorInterpreter
     * https://ourcodeworld.com/articles/read/784/how-to-retrieve-the-human-name-of-a-color-by-its-hex-code-in-php
     */
    private ColorInterpreter $interpreter;

    public function __construct(ColorInterpreter $interpreter)
    {
        $this->interpreter = $interpreter;
    }

    public function colorConverter($color)
    {
        $result = $this->interpreter->name($color);

        // 1. Print the human name color  :  $result["name"] ;
        // 2. Print the hex code of the closest color name : $result["hex"]
        // 3. Print wheter the given hex code is exact as a color with a name
        //    or if it has been derived

        return $result["name"];
    }


    /**
     * @param $hex
     * @return float|int
     */
    public function get_brightness($hex)
    {
        // returns brightness value from 0 to 255
        // strip off any leading #
        $hex = str_replace('#', '', $hex);

        $c_r = hexdec(substr($hex, 0, 2));
        $c_g = hexdec(substr($hex, 2, 2));
        $c_b = hexdec(substr($hex, 4, 2));

        return (($c_r * 299) + ($c_g * 587) + ($c_b * 114)) / 1000;
    }


    /**
     * @param $hexadecimal
     * @param $percent
     * @return string
     * 0%   =  black
     * 50%  = no change
     * 100% = white
     */
    function brightness($hexadecimal, $percent): string
    {
        if ( strlen( $hexadecimal ) < 6 ) {
            $hexadecimal = $hexadecimal[0] . $hexadecimal[0] . $hexadecimal[1] . $hexadecimal[1] . $hexadecimal[2] . $hexadecimal[2];
        }
        $hexadecimal = array_map('hexdec', str_split( str_pad( str_replace('#', '', $hexadecimal), 6, '0' ), 2 ) );

        foreach ($hexadecimal as $i => $color) {
            $from = $percent < 0 ? 0 : $color;
            $to = $percent < 0 ? $color : 255;
            $ceilValue = ceil( ($to - $from) * $percent );
            $hexadecimal[$i] = str_pad( dechex($color + $ceilValue), 2, '0', STR_PAD_LEFT);
        }

        return '#' . implode($hexadecimal);
    }


    public function detectColors($image, $num, $level = 5)
    {

        $level   = (int) $level;
        $palette = array();
        $size    = getimagesize($image);
        if (!$size) {
            exit;
        }
//        dd($level,$palette,$size);
        switch ($size['mime']) {
            case 'image/jpeg':
                $img = imagecreatefromjpeg($image);
                break;
            case 'image/png':
                $img = imagecreatefrompng($image);
                break;
            case 'image/gif':
                $img = imagecreatefromgif($image);
                break;
            default:
                return FALSE;
        }
        if (!$img) {
            return FALSE;
        }
        for ($i = 0; $i < $size[0]; $i += $level) {
            for ($j = 0; $j < $size[1]; $j += $level) {
                $thisColor       = imagecolorat($img, $i, $j);
                $rgb             = imagecolorsforindex($img, $thisColor);
                $color           = sprintf('%02X%02X%02X', (round(round(($rgb['red'] / 0x33)) * 0x33)), round(round(($rgb['green'] / 0x33)) * 0x33), round(round(($rgb['blue'] / 0x33)) * 0x33));
                $palette[$color] = isset($palette[$color]) ? ++$palette[$color] : 1;
            }
        }
        arsort($palette);
        return array_slice(array_keys($palette), 0, $num);
    }


//    public function detectColors1($image, $num, $level = 5)
//    {
//         $level   = (int)$level;
//            $palette = array();
//            $details = array();# store the count of non black or white colours here ( see $exclusions )
//
//            list( $width, $height, $type, $attr )=getimagesize( $image );
//            if( !$type ) return exit;
//
//            switch ( image_type_to_mime_type( $type ) ) {
//                case 'image/jpeg':
//                    $img = imagecreatefromjpeg( $image );
//                    break;
//                case 'image/png':
//                    $img = imagecreatefrompng( $image );
//                    break;
//                case 'image/gif':
//                    $img = imagecreatefromgif( $image );
//                    break;
//                default: return FALSE;
//            }
//            if( !$img ) return FALSE;
//
//            /* Colours to not factor into dominance statistics */
//            $exclusions=['000000','FFFFFF'];
//
//            for( $i = 0; $i < $width; $i += $level ) {
//                for( $j = 0; $j < $height; $j += $level ) {
//                    $colour             = imagecolorat( $img, $i, $j );
//                    $rgb                = imagecolorsforindex( $img, $colour );
//                    $key                = sprintf('%02X%02X%02X', ( round( round( ( $rgb['red'] / 0x33 ) ) * 0x33 ) ), round(round(($rgb['green'] / 0x33)) * 0x33), round(round(($rgb['blue'] / 0x33)) * 0x33));
//                    $palette[ $key ]    = isset( $palette[ $key ] ) ? ++$palette[ $key ] : 1;
//
//                    if( !in_array( $key, $exclusions ) ){
//                        /* add count of any non excluded colours */
//                        $details[ $key ] = isset( $details[ $key ] ) ? ++$details[ $key ] : 1;
//                    }
//                }
//            }
//            arsort( $palette );
//
//            /* prepare statistics for output */
//            $output=new stdclass;
//            $output->data=array_slice( array_keys( $palette ), 0, $num );
//            $output->highest=max( $details );
//            $output->lowest=min( $details );
//            $output->dominant=array_search( $output->highest, $details );
//            $output->recessive=array_search( $output->lowest, $details );
//
//
//            return $output;
//
//    }





}