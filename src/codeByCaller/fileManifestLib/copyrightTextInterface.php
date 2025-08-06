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
interface copyrightTextInterface {

    /*--------------------------------------------------------------------
    construction
    --------------------------------------------------------------------*/


    public function init() : void;

//    function useActual4SinceDate ();

    public function setActCopyright2Today ()  : void ;
    public function setSinceCopyright2Today ()  : void;


    public function setActCopyright (string $year)  : void;

    public function setSinceCopyright (string $year) : void;
    public function scan4CopyrightInLine(string $line) : array;

    public function formatCopyrightPhp($middlePadCount, $endPadCount, $sinceCopyrightDate='', $actCopyrightDate=''): string ;

    // ToDo: just since may not exist
    public function formatCopyrightManifest($sinceCopyrightDate='', $actCopyrightDate=''): string;
    public function text(): string;

}
