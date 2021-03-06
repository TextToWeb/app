<?php
/**
 * Created by PhpStorm.
 * User: hmtmc
 * Date: 19/11/2017
 * Time: 07:24 PM
 */

namespace App\View\Helper;

use App\Utils\FileAndDirectoryService;
use App\Utils\AppCacheManager;
use Cake\View\Helper;

class MenuHelper extends Helper
{

    public $helpers = ['Html','Url'];
    const MENU_CACHE = "MENU_CACHE";
    const HOME_PAGE_MENU_CACHE = "HOME_PAGE_MENU_CACHE";


    private function leftMenuGenerator($menuList, $parentURL, $spacing = ""){
        $html = "";
        foreach ($menuList as $menu){
            $subParentURL = "$parentURL/$menu->nameOnly";
            $url =  $menu->subMenues === null ? $this->Url->build("/$subParentURL") : "#";
            $displayName = $menu->displayName;
            if ($menu->subMenues !== null){
                $html .= "<li><a href='$url' class='bd-toc-link'><h6><strong>$spacing $displayName</strong></h6></a></li>";
                $html .= $this->leftMenuGenerator($menu->subMenues, $subParentURL, $spacing . "&nbsp;&nbsp" );
            }else{
                $html .= "<li><a href='$url'><h6>$spacing $displayName</h6></a></li>";
            }
        }
        return $html;
    }

    public function getLeftMenu($pageData){
        $parentURL = $pageData->getParentURL();
        $html = AppCacheManager::read($parentURL ? $parentURL : "empty");
        if ($html === false) {
            $html = "<div class='bd-toc-item active'>";
            $html .= "<ul class='nav bd-sidenav'>";
            $html .= $this->leftMenuGenerator($pageData->getChapter(), $parentURL);
            $html .= "</ul></div>";
            AppCacheManager::cache($parentURL, $html);
        }
        echo $html;
    }


    public function getTopNavItem(){
        $fileAndDirectoryService = new FileAndDirectoryService();
        $menuList = $fileAndDirectoryService->getMenuList();
        $html = AppCacheManager::read(self::MENU_CACHE);
        if ($html === false) {
            $url = $this->Url->build('/');
            $html = '<ul class="navbar-nav bd-navbar-nav flex-row">';
            foreach ($menuList as $menu){
                $parentName = $menu->displayName;
                $parent = $menu->nameOnly;
                $pageUrl = $this->Url->build('/' . $menu->nameOnly);
                if ($menu->subMenues != null){
                    $html .= "<li class='nav-item dropdown'>";
                    $html .= "<a class='nav-link dropdown-toggle' href='$pageUrl' data-toggle='dropdown'>";
                    $html .= "$parentName";
                    $html .= "</a>";
                    $html .= "<div class='dropdown-menu'>";
                    foreach ($menu->subMenues as $subMenu){
                        $pageUrl = $this->Url->build("/$parent/$subMenu->nameOnly");
                        $html .= "<a class='dropdown-item' href='$pageUrl'>$subMenu->displayName</a>";
                    }
                    $html .= "</div>";
                    $html .= "</li>";
                }else{
                    $html.= '<li class="nav-item">';
                    $html.= "<a class='nav-link' href='$pageUrl'>$parentName</a>";
                    $html.= '</li>';
                }
            }
            $html .= "<li class='nav-item dropdown'>";
            $html .= "<a class='nav-link dropdown-toggle' href='#' data-toggle='dropdown'>";
            $html .= "About";
            $html .= "</a>";
            $html .= "<div class='dropdown-menu'>";
            $staticURL = $this->Url->build("/about/privacy");
            $html .= "<a class='dropdown-item' href='$staticURL'>Privacy</a>";
            $staticURL = $this->Url->build("/about/copyright");
            $html .= "<a class='dropdown-item' href='$staticURL'>Copyright</a>";
            $html .= "</div>";
            $html .= "</li>";

            $html .= '</ul>';
            AppCacheManager::cache(self::MENU_CACHE, $html);
        }
        echo $html;
    }

    private function getColor($index){
        $color = [
            [
                "header-text" => "text-white",
                "bg" => "bg-success",
                "border" => "border-success",
            ],
            [
                "header-text" => "text-white",
                "bg" => "bg-primary",
                "border" => "border-primary",
            ],
            [
                "header-text" => "text-white",
                "bg" => "bg-secondary",
                "border" => "border-secondary",
            ],
            [
                "header-text" => "text-white",
                "bg" => "bg-dark",
                "border" => "border-dark",
            ],
            [
                "header-text" => "text-white",
                "bg" => "bg-danger",
                "border" => "border-danger",
            ],
            [
                "header-text" => "text-white",
                "bg" => "bg-warning",
                "border" => "border-warning",
            ]
        ];
        return $color[$index];
    }

    public function getHomeMenu(){
        $fileAndDirectoryService = new FileAndDirectoryService();
        $menuList = $fileAndDirectoryService->getMenuList();
        $html = AppCacheManager::read(self::HOME_PAGE_MENU_CACHE);
        if ($html === false) {
            $html = '';
            $colorIndex = 0;
            foreach ($menuList as $menu){
                if ($menu->subMenues != null){
                    $parentName = $menu->displayName;
                    $parent = $menu->nameOnly;
                    $color = $this->getColor($colorIndex);
                    $html .= "<div class='card " . $color['bg'] . " " . $color['border'] . " text-center'>";
                    $html .= "<h4 class='card-header " . $color['header-text'] . "'>" . $parentName . "</h4>";
                    $html .= "<div class='list-group list-group-flush'>";
                    foreach ($menu->subMenues as $subMenu){
                        $pageUrl = $this->Url->build("/$parent/$subMenu->nameOnly");
                        $name = $subMenu->displayName;
                        $html .= "<a href='$pageUrl' class=' list-group-item'><h5>$name</h5></a>";
                    }
                    $html .= "</div></div>";
                    $colorIndex = $colorIndex > 4 ? 0 : $colorIndex + 1;
                }
            }
            AppCacheManager::cache(self::HOME_PAGE_MENU_CACHE, $html);
        }
        echo $html;
    }
}