<?php

namespace Gitbox\Bundle\CoreBundle\TwigExtension;

    class Gravatar extends \Twig_Extension {

        // the magic function that makes this easy
        public function getFilters()
        {
            return array(
                'getGravatarImage' => new \Twig_Filter_Method($this, 'getGravatarImage'),
            );
        }

        // get gravatar image
        public function getGravatarImage($email, $size = 80, $defaultImage = null, $rating = 'G')
        {
            if (!isset($defaultImage)) {
                $defaultImage = 'http://lh5.googleusercontent.com/-b0-k99FZlyE/AAAAAAAAAAI/AAAAAAAAAAA/twDq00QDud4/s' . $size . '-c/photo.jpg';
            }
            
            return $grav_url = "http://www.gravatar.com/avatar/" . md5(strtolower(trim($email))) . "?d=" . urlencode($defaultImage) . "&s=" . $size . '&r=' . $rating;
        }

        // for a service we need a name
        public function getName()
        {
            return 'gravatar';
        }

    }
