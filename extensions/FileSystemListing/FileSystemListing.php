<?php
#
# Author: Javier Castro (jac) - javier.alejandro.castro@gmail.com
# Modifications to the use of jQuery expansible Tree: Max Friedrich (Maxgalileiconsult) - mfriedri@gc-ev.de
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License along
# with this program; if not, write to the Free Software Foundation, Inc.,
# 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
# http://www.gnu.org/copyleft/gpl.html

//Avoid unstubbing $wgParser on setHook() too early on modern (1.12+) MW versions, as per r35980
if ( defined( 'MW_SUPPORTS_PARSERFIRSTCALLINIT' ) ) {
        $wgHooks['ParserFirstCallInit'][] = 'wfListDirectory';
} else {
        $wgExtensionFunctions[] = 'wfListDirectory';
}

$wgExtensionCredits['parserhook'][] = array(
        'name' => 'FileSystemListing',
        'version' => '1.0.0',
        'author' => 'Javier Castro',
        'description' => 'Provides an easy way to list filesystem contents on your webserver with <tt>&lt;dirlist&gt;</tt> tag',
        'url' => 'http://www.mediawiki.org/wiki/Extension:FileSystemListing'
);

$wgResourceModules['ext.FileSystemListing'] = array(
    'scripts' => 'ext.FileSystemListing.js',
    'styles' => 'ext.FileSystemListing.css',
    'messages' => array(),
    'dependencies' => array(),
    'position' => 'bottom',
    'localBasePath' => dirname( __FILE__ ) . '/modules',
    'remoteExtPath' => 'FileSystemListing/modules'
);

function wfListDirectory() {
        global $wgParser;
        # register the extension with the WikiText parser
        # the first parameter is the name of the new tag.
        # In this case it defines the tag <dirlist> ... </dirlist>
        # the second parameter is the callback function for
        # processing the text between the tags
        $wgParser->setHook( 'dirlist', 'renderDirList' );
        $wgParser->setHook( 'FsDateTime', 'fsDT' );
        return true;
}

# The callback function for converting the input text to HTML output
function renderDirList( $input, $argv, $parser ) {
        $parser->disableCache();
        $dir = $argv['name'];
        if (array_key_exists('sort',$argv)) {
            $sort = $argv['sort'];
        }else{
            $sort = true;
        }
        $filePrefix = $argv['fileprefix'];
        if (array_key_exists('encoded',$argv)) {
            $encoded = $argv['encoded'];
        }else{
            $encoded = false;
        }
        # add jquery module for collapsing directory tree
	global  $wgOut;
        $wgOut->addModules( 'ext.FileSystemListing' );
        if ($dir !== "") {
                $result = readDirContents($dir,$sort,$encoded);
                $output = "";
                if($encoded) {
                    $dir = rawurldecode($dir);
                }
                $output .= renderDirContents($result, $dir, $filePrefix);
                return $output;
        }
        return "";
}

# The callback function for converting the input text to HTML output and return the date time of an file
function fsDT( $input, $argv, $parser ) {
        $parser->disableCache();
        $dir = $argv['name'];
        $filePrefix = $argv['fileprefix'];
        $fsdate = date ("d/m/Y H:i",filemtime($dir.$fileName));
        if ($dir !== "") {
                return $fsdate;
        }
        return "";
}

function renderDirContents($dirArray, $dirName, $prefix = null) {
        $output = "<ul class='mw-ext-FileSystemListing'>";
        foreach ($dirArray as $value) {
            if ($value['content'] !== null) {
                $output .= "<li>".utf8_encode($value['name'])."";
                $output .= renderDirContents($value['content'], $dirName, $prefix);
                $output .= "</li>";
            } else {
                        if ($prefix) {
                                $pathToFile = substr($value['path'], strlen($dirName));
                                $href = $prefix . str_replace('%2F','/',rawurlencode($pathToFile));
                                $output .= "<li><a href='$href'>".utf8_encode($value['name'])."</a></li>";
                        } else {
                                $output .= "<li>".utf8_encode($value['name'])."</li>";
                        }
            }
        }
        $output .= "</ul>";
        return $output;
}

function readDirContents($dir, $sort = true, $encoded=false) {
        if ($dir{strlen($dir)-1} !== '/')
                $dir .= '/';
        $a = array();
        if($encoded) {
            $dir = rawurldecode($dir);
        }
        $gd = opendir($dir);
        $i = 0;
/*        while (($fileName = readdir($gd)) !== false) {
        if ($fileName == "." || $fileName == "..")
                continue;
        if (is_dir($dir.$fileName))
                $a[$i++] = array("path" => $dir.$fileName, "name" => $fileName, "content" => readDirContents($dir.$fileName));
        else
                $a[$i++] = array("path" => $dir.$fileName, "name" => $fileName, "content" => null);
        }
        closedir($gd);
        if ($sort) {
                sort($a);
	}*/
        return $a;
}

