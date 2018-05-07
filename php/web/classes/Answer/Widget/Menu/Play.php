<?php

namespace Answer\Widget\Menu ;

class Play extends \Quantyl\Answer\Widget {
    
    public function getContent() {
        $res  = '<form method="post" action="/User/Login" >' ;
        $res .= '<div class="login_input">' ;
        //$res .= '<p><label for="nickname">' . \I18n::NICKNAME() . '</label>' ;
        $res .= '<p>   <input type="text"      name="nickname"   placeholder="'.\I18n::NICKNAME().'"     value=""/></p>' ;
        //$res .= '<p><label for="nickname">' . \I18n::PASSWORD() . '</label>' ;
        $res .= '<p>   <input type="password"  name="password"  placeholder="'.\I18n::PASSWORD().'"      value=""/></p>' ;
        $res .= '</div>' ;

        $res .= '<div class="login_submit">' ;
        $res .= '<p>   <input type="checkbox"  value="1" name="remember"></input>' . \I18n::REMEMBER() ;
        $res .= '   <input type="submit"    name="_submits[login]" value="' . \I18n::LOGIN()    . '" /></p>' ;
        $res .= '</div>' ;

        $res .= '<div class="login_links">' ;
        $res .= '   <p><a href="/User/Authentication/Forgotten">' . \I18n::FORGOTTEN_PASSWORD() . '</a></p>' ;
        //$res .= '   <a href="/User/Create">' . \I18n::REGISTER() . '</a></p>' ;
        $res .= '</div>' ;
        $res .= "</form>" ;
        return $res ;
        
    }
    
}
