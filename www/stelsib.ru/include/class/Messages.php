<?php
/**
 * Created by PhpStorm.
 * User: Валентина
 * Date: 23.03.14
 * Time: 16:34
 */

class Messages {
    public $Content;

    public function __construct($type, $message, $queryString = ''){
        if($type == 'info'){
            $this->Content = $this->showInfoMessage($message, $queryString);
        }
        elseif($type == 'error'){
            $this->Content = $this->showErrorMessage($message, $queryString);
        }
        else return;
    }

    private function showInfoMessage( $message, $queryString )
    {
        if(isset($_SESSION['prevPage']))
        {

            if ( !empty( $queryString ) ){
                $queryString = '/'.$queryString;
                header( 'Refresh: '.REDIRECT_DELAY_INFO.'; url='.ADMIN_PANEL.$queryString );
            }
            else{
                header( 'Refresh: '.REDIRECT_DELAY_INFO.'; url='.$_SESSION['prevPage'] );
            }
        }
        else{
            if ( !empty( $queryString ) )
            {
                $queryString = '/'.$queryString;
                header( 'Refresh: '.REDIRECT_DELAY_INFO.'; url='.ADMIN_PANEL.$queryString );
            }
            else
            {
                header( 'Refresh: '.REDIRECT_DELAY_INFO.'; url='.ADMIN_PANEL );
            }
        }
        $html = file_get_contents( DIR_PATH.'./templates/infoMessage.tpl' );
        $html = str_replace( '{content}', $message, $html );
        return $html;
    }
    private function showErrorMessage( $message, $queryString )
    {
        if(isset($_SESSION['prevPage']))
        {

            if ( !empty( $queryString ) )
            {
                $queryString = '/'.$queryString;
                header( 'Refresh: '.REDIRECT_DELAY_INFO.'; url='.ADMIN_PANEL.$queryString );
            }
            else
            {
                header( 'Refresh: '.REDIRECT_DELAY_INFO.'; url='.$_SESSION['prevPage'] );
            }
        }
        else
        {
            if ( !empty( $queryString ) )
            {
                $queryString = '/'.$queryString;
                header( 'Refresh: '.REDIRECT_DELAY_INFO.'; url='.ADMIN_PANEL.$queryString );
            }
            else
            {
                header( 'Refresh: '.REDIRECT_DELAY_INFO.'; url='.ADMIN_PANEL );
            }
        }
        $html = file_get_contents( DIR_PATH.'./templates/errorMessage.tpl' );
        $html = str_replace( '{content}', $message, $html );
        return $html;
    }

} 