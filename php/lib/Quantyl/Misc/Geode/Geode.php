<?php

namespace Quantyl\Misc\Geode ;

use Quantyl\Misc\Matrix4D;
use Quantyl\Misc\Vertex3D;

class Geode {
    
    private $_size ;
    
    public function __construct($size) {
        $this->_size = $size ;
    }
    
    public function getSize() {
        return $this->_size ;
    }
    
    public function getNeighbour(GeodeNode $gn) {
        if ($gn->getI() == 0 && $gn->getJ() == 0) {
            return $this->getNeighbourVertex($gn) ;
        } else if ($gn->getJ() == 0) {
            return $this->getNeighbourEdge($gn) ;
        } else {
            return $this->getNeighbourFace($gn) ;
        }
    }
    
    public static function getMainPoint($i) {
        $coord = self::$mainpoints[$i] ;
        $v1    = Vertex3D::XYZ($coord[0], $coord[1], $coord[2]) ;
        $rot   = Matrix4D::rotationZ(0.5 * pi() - atan(1.618)) ;
        $v2    = $rot->transform3D($v1) ;
        $v2->normalize() ;
        return $v2 ;
    }
    
    // phi = 1,618 = (1 + sqrt(5)) / 2
    public static $mainpoints = array (
        array ( 1.0  ,  1.618,  0.0  ),    //  0
        array (-1.0  ,  1.618,  0.0  ),    //  1
        array ( 0.0  ,  1.0  ,  1.618),    //  2
        array ( 1.618,  0.0  ,  1.0  ),    //  3
        array ( 1.618,  0.0  , -1.0  ),    //  4
        array ( 0.0  ,  1.0  , -1.618),    //  5
        array (-1.618,  0.0  , -1.0  ),    //  6
        array (-1.618,  0.0  ,  1.0  ),    //  7
        array ( 0.0  , -1.0  ,  1.618),    //  8
        array ( 1.0  , -1.618,  0.0  ),    //  9
        array ( 0.0  , -1.0  , -1.618),    //  10
        array (-1.0  , -1.618,  0.0  ),    //  11
        ) ;
    
    // sommets des 20 triangles
    public static $_faces = array(
        // Top
        array(2, 0, 1),
        array(3, 0, 2),
        array(4, 0, 3),
        array(5, 0, 4),
        array(1, 0, 5),
        // middle up
        array(1, 7, 2),
        array(2, 8, 3),
        array(3, 9, 4),
        array(4, 10, 5),
        array(5, 6, 1),
        // middle down
        array(7, 1, 6),
        array(8, 2, 7),
        array(9, 3, 8),
        array(10, 4, 9),
        array(6, 5, 10),
        // bottom
        array(6, 11, 7),
        array(7, 11, 8),
        array(8, 11, 9),
        array(9, 11, 10),
        array(10, 11, 6)
    );

    /**
     * Les arrêtes de la géode
     * Pour chaqune, on stocke :
     * - les extrêmités
     * - les sommets des triangles adgacents
     *
     * @var array la liste des arrêtes
     */
    public static $_edges = array(
        array(0, 1, 5, 2),
        array(0, 2, 1, 3),
        array(0, 3, 2, 4),
        array(0, 4, 3, 5),
        array(0, 5, 4, 1),
        array(1, 2, 0, 7),
        array(2, 3, 0, 8),
        array(3, 4, 0, 9),
        array(4, 5, 0, 10),
        array(1, 5, 0, 6),
        array(1, 6, 5, 7),
        array(2, 7, 1, 8),
        array(3, 8, 2, 9),
        array(4, 9, 3, 10),
        array(5, 10, 4, 6),
        array(1, 7, 2, 6),
        array(2, 8, 3, 7),
        array(3, 9, 4, 8),
        array(4, 10, 5, 9),
        array(5, 6, 1, 10),
        array(6, 7, 1, 11),
        array(7, 8, 2, 11),
        array(8, 9, 3, 11),
        array(9, 10, 4, 11),
        array(6, 10, 5, 11),
        array(6, 11, 10, 7),
        array(7, 11, 6, 8),
        array(8, 11, 7, 9),
        array(9, 11, 8, 10),
        array(10, 11, 9, 6)
    );

    /**
     * Les sommets
     * Pour chaque sommet, on stocke
     * - son numéro
     * - les 5 sommets des arrêtes adgacentes (sens horaire)
     */
    public static $_vertexes = array(
        array(0, 5, 4, 3, 2, 1),
        array(1, 0, 2, 7, 6, 5),
        array(2, 0, 3, 8, 7, 1),
        array(3, 0, 4, 9, 8, 2),
        array(4, 0, 5, 10, 9, 3),
        array(5, 0, 1, 6, 10, 4),
        array(6, 1, 7, 11, 10, 5),
        array(7, 2, 8, 11, 6, 1),
        array(8, 3, 9, 11, 7, 2),
        array(9, 4, 10, 11, 8, 3),
        array(10, 5, 6, 11, 9, 4),
        array(11, 6, 7, 8, 9, 10)
    );
    
    
}
