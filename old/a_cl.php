<?php

class Poster {

    public $routeLarge;
    public $routeXLarge;
    public $routeMedium;
    public $routeSmall;
    public $aspectRatioDefault = "2:3";
    public $widthDefault = [
        "xl" => "2000",
        "l" => "1000",
        "m" => "600",
        "s" => "300",
    ];

}

class BG {

    public $routeLarge;
    public $routeXLarge;
    public $routeMedium;
    public $routeSmall;
    public $aspectRatioDefault = "16:9";
    public $widthDefault = [
        "xl" => "3840",
        "l" => "2560",
        "m" => "1920",
        "s" => "1280",
    ];

}






class Language {

    public $id; // int
    public $name; // string

}

class TitleData {

    public $id; // int
    public $title; // string
    public $languageId; // int
    public $languageName; // string

}

class VersionData {

    public $num; // int
    public $title; // string | ex: "Theatrical Cut"
    public $uniqueTitle; // string | ex: "theatrical-cut"

}

class CrewData {

    public $id; // int
    public $name; // string
    public $uniqueName; // string
    public $roleId; // int
    public $roleName; // array | roll på index 0 | ex: ["director"] | om skådespelare: ["actor", namn-på-karaktär]

}

class CollectionData {

    public $id; // int
    public $name; // string | name of collection
    public $uniqueName; // string
    public $numOf; // int

}

class SeriesData extends CollectionData {}
class SeasonData extends CollectionData {}
class AlbumData extends CollectionData {}






class Ent {

    public $id; // int
    public $titles; // array | contains TitleData elements | index 0 is primary title
    public $releaseDate; // DateTime
    public $uniqueTitle; // string | built from primary title and year of release
    public $desc; // string
    public $tagline; // string
    public $poster; // Poster
    public $bg; // BG
    public $length; // int
    public $versionData; // VersionData
    public $crewData; // array | contains CrewData elements
    public $collectionData; // array | contains CollectionData elements
    public $languages; // array | contains Language elements | index 0 is primary language

    public $rating; // float | avarage rating gathered from users | rounded to 1 decimal
    public $ageRating; // int | avarage age-rating gathered from users | rounded to 0 decimals | goes from 0 to 18

    public function validate() {
        if(!is_int($id) || !is_array($titles) || !is_a($releaseDate, 'DateTime') || !is_string($uniqueTitle) || !is_string($desc) || !is_string($tagline) || !is_a($poster, 'Poster') || !is_a($bg, 'BG') || !is_int($length) || !is_a($versionData, 'VersionData') || !is_array($crewData) || !is_array($collectionData) || !is_array($languageData)) {
            exit("Wrong class of property.");
        }
        if(is_a($this, 'ShortFilm') && $length > 60) {
            exit("The length is inappropriate.");
        }
    }

    public function render() {
        
    }

}

class FeatureFilm extends Ent {

    public $type = "Audio-Visual";

    public function init($titles, $releaseDate, $desc, $tagline, $length) {

    }

    // public function set_ver($num, $title, $uniqueTitle) {

    //     $versionData = new VersionData();
    //     $versionData -> init();

    // }

    public function exists_in_db($conn) {

        function compare($obj1, $obj2) {

        }

        crud();
        
        if(isset($result)) {
            compare($this, $reslut);
        }

    }

    public function create_in_db($conn) {
        crud();
    }
}

// function createUniqueTitle($titles, $releaseDate) {

//     $primaryTitle = $titles[0];
//     $primaryTitle = ltrim($primaryTitle, ["The ", "A "]);
    

//     preg_replace([" ", ".", "'", "`", "´", ""], "-", $titles[0]

//     // crud();

// }

$languages = [];
$languageEng = new Language();
$languageEng -> id = 1;
$languageEng -> name = "English";
array_push($languages, $languageEng);
$languageJap = new Language();
$languageJap -> id = 2;
$languageJap -> name = "Japanese";
array_push($languages, $languageJap);

$titles = [];
$title1 = new TitleData();
$title1 -> id = 1;
$title1 -> title = "Spirited Away";
$title1 -> languageId = $languages[0] -> id;
$title1 -> languageName = $languages[0] -> name;
array_push($titles, $title1);
$title2 = new TitleData();
$title2 -> id = 2;
$title2 -> title = "Sen to Chihiro no kamikakushi";
$title2 -> languageId = $languages[1] -> id;
$title2 -> languageName = $languages[1] -> name;
array_push($titles, $title2);

$featureFilm1 = new FeatureFilm();
$featureFilm1 -> id = 1;
$featureFilm1 -> titles = $titles;
$featureFilm1 -> uniqueTitle = "spirited-away-2001";
$featureFilm1 -> releaseDate = new DateTime('2001-7-20');
$featureFilm1 -> desc = "A family wanders into a strange world of spirits. When her parents undergo a mysterious transformation, a young girl must find great courage to free them.";
$featureFilm1 -> tagline = "I knew you were good.";
$featureFilm1 -> length = 125;

echo var_dump($featureFilm1);

$titles = [];
$title1 = new TitleData();
$title1 -> id = 1;
$title1 -> title = "Apocalypse Now";
$title1 -> languageId = $languages[0] -> id;
$title1 -> languageName = $languages[0] -> name;
array_push($titles, $title1);

$version1 = new TitleData();
$version1 -> num = 1;
$version1 -> title = "Theatrical Cut";
$version1 -> uniqueTitle = "theatrical-cut";

$featureFilm2 = new FeatureFilm();
$featureFilm2 -> id = 2;
$featureFilm2 -> titles = $titles;
$featureFilm2 -> uniqueTitle = "apocalypse-now-1979-theatrical-cut";
$featureFilm2 -> releaseDate = new DateTime('1979-12-19');
$featureFilm2 -> desc = "At the height of the Vietnam war, Captain Benjamin Willard is sent on a dangerous mission that, officially, “does not exist, nor will it ever exist.” His goal is to locate, and eliminate, a mysterious Green Beret Colonel named Walter Kurtz, who has been leading his personal army on illegal guerrilla missions into enemy territory.";
$featureFilm2 -> tagline = "The horror...";
$featureFilm2 -> length = 147;
$featureFilm2 -> versionData = $version1;

echo var_dump($featureFilm2);

class ShortFilm extends Ent {

    public $type = "Audio-Visual";

    public function validate() {
        if(!is_int($id) || !is_array($titles) || !is_a($releaseDate, 'DateTime') || !is_string($uniqueTitle) || !is_string($desc) || !is_string($tagline) || !is_a($poster, 'Poster') || !is_a($bg, 'BG') || !is_int($length) || !is_a($versionData, 'VersionData') || !is_array($crewData) || !is_array($collectionData) || !is_array($languageData)) {
            exit("Wrong class of property.");
        }
        if($length > 60) {
            exit("The length is inappropriate.");
        }
    }
}

class Series extends Ent {

    public $type = "Audio-Visual";
    public $seasons; // array | contains Season elements

}

class Seasons extends Ent {

    public $type = "Audio-Visual";
    public $seriesData; // array | contains SeriesData elements
    public $episodes; // array | contains Episode elements
    
}

class Episode extends Ent {

    public $type = "Audio-Visual";
    public $seasonData; // array | contains SeasonData elements

}

class VideoGame extends Ent {

    public $type = "Audio-Visual";
    public $difficulty; // float | avarage difficulty-rating gathered from users | rounded to 1 decimals
    public $lengthRating; // float | avarage length-rating (in hours) gathered from users | rounded to 1 decimals

}

class Song extends Ent {

    public $type = "Audio";
    public $albumData; // array | contains AlbumData elements

}

class Album extends Ent {

    public $type = "Audio";
    public $songs; // array | contains Song elements

}

class Literature extends Ent {

    public $type = "Text";
    public $graphic; // bool | whether it's a graphic novel or not

}

class Picture extends Ent {

    public $type = "Visual";

}

