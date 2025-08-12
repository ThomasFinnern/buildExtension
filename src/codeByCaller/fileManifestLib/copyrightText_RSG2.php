<?php

namespace Finnern\BuildExtension\src\codeByCaller\fileManifestLib;

/**
 * container for inner copyright line like "(c) 2005-2024 RSGallery2 Team"
 * used in
 * manifest file:
 *    <copyright>(c) 2005-2024 RSGallery2 Team</copyright>
 * *.php
 *    @copyright   (c) 2003-2024 RSGallery2 Team
 */
class copyrightText_RSG2 extends copyrightTextBase
    implements copyrightTextInterface
{

//    public function scan4CopyrightInLine(string $line) : array
//    {
//        // ToDo: try, catch
//
//        // fall back, preset result
//
//        $this->init();
//
//        //   * @copyright (c)  2020-2022 RSGallery2 Team
//        $idx = stripos($line, '(c)');
//        if ($idx !== false) {
//            //$valuePart = trim(substr($line, $idx));
//            // preg_match_all('/\d+/', $valuePart, $matches);
//            preg_match_all('/\d+/', $line, $matches);
//
//            $finds = $matches [0];
//            if (count ($finds) > 1)
//            {
//                $this->sinceCopyrightDate = $finds[0];
//                $this->actCopyrightDate = $finds[1];
//
//                // author from line is last part
//                $pieces = explode($this->actCopyrightDate, $line);
//                $count = count($pieces);
//                if ($count > 0) {
//                    $this->postCopyrightAuthor = trim($pieces[$count-1]);
//                }
//
//            }
//        } else {
//
//            print ('!!! Unexpected copyright line: "' . $line . '" !!!');
//            parent::scan4CopyrightInLine($line);
//        }
//
//        return [$this->sinceCopyrightDate, $this->actCopyrightDate];
//        // return [$this->actCopyrightDate, $this->sinceCopyrightDate];
//    }

}