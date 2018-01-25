<?php

class UITools
{
    public static function echoPaging($currentPage, $totalCount, $pageSize)
    {
        //If only one page, no show

        $pageCount = ceil($totalCount / $pageSize);
        if($pageCount <= 1) return;

        $isFirstPage = ($currentPage <= 0);
        $isLastPage = ($currentPage >= $pageCount - 1);

        echo '<div class="pages">';
        echo '<a class="op" href="javascript:reloadUriWithParam(\'page\','.($isFirstPage ? 0 : ($currentPage - 1)).')">«</a>';

        $pageEntries = array($currentPage - 1, $currentPage, $currentPage + 1);

        if($pageEntries[0] <= 3)
        {
            for($j = min(3, $pageEntries[0] - 1); $j >= 0 ; $j--) array_unshift($pageEntries, $j);
        }
        else
        {
            array_unshift($pageEntries, -999);
            for($j = 3; $j >= 0 ; $j--) array_unshift($pageEntries, $j);
        }

        $lastElement = $pageEntries[count($pageEntries) - 1];
        if($lastElement > $pageCount - 3)
        {
            for($j = max($lastElement + 1, $pageCount - 3); $j < $pageCount ; $j++) array_push($pageEntries, $j);
        }
        else
        {
            array_push($pageEntries, -999);
            for($j = $pageCount - 3; $j < $pageCount ; $j++) array_push($pageEntries, $j);
        }

        foreach($pageEntries as $p)
        {
            $p = intval($p);

            if($p == $currentPage)
            {
                echo '<span>'.($p + 1).'</span>';
            }
            else if($p == -999)
            {
                echo '<em>…</em>';
            }
            else if($p >= 0 && $p < $pageCount)
            {
                echo '<a href="javascript:reloadUriWithParam(\'page\','.$p.')">'.($p + 1).'</a>';
            }
        }

        echo '<a class="op" href="javascript:reloadUriWithParam(\'page\','.($isLastPage ? ($pageCount - 1) : ($currentPage + 1)).')">»</a>';
        echo '</div>';
    }

    public static function echoDatePicker($id, $name, $placeHolder = "",
                                          $classes = array(), $enableOldDateSelect = false,
                                          $value = "", $editable = true)
    {
        $cs = Yii::app()->getClientScript();
        $cs->registerScriptFile(UserConfig::$staticFileRoot.'/js/datepicker.js');
        $cs->registerCssFile(UserConfig::$staticFileRoot.'/css/datepicker.css');

        $classes[] = 'disable-drag';
        if(!$enableOldDateSelect)
        {
            $classes[] = 'disable-19700101-'.date("Ymd");
        }

        echo '<input maxlength="12" name="'.$name.'" value="'.$value.'" placeholder="'.$placeHolder.'" type="text" id="'.$id.'" class="w16em dateformat-Y-ds-m-ds-d';

        foreach($classes as $c) { echo ' '.$c; }
        if(!$editable) echo '" readonly="readonly';

        echo '">';
    }

    public static function echoDynamicPage($key)
    {
        if(isset($_POST['preview_'.$key]))
        {
            echo $_POST['preview_'.$key];
        }
        else
        {
            $currentDomain = Tools::getLangObjectFromContext()->currentDomain;
            $model = DynamicPage::model()->findByPk($key.'.'.$currentDomain);
            if($model)
            {
                echo $model->content;
            }
            elseif ($currentDomain != UserConfig::$defaultDomain)
            {
                $fallbackModel = DynamicPage::model()->findByPk($key.'.'.UserConfig::$defaultDomain);
                if($fallbackModel)
                {
                    echo $fallbackModel->content;
                }
            }

        }
    }

    public static function echoStat()
    {
        if(Tools::mode() == 2)
        {
            echo "<script>(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),".
                "m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');".
                "ga('create', '".UserConfig::$googleAnalyticsId."', 'auto');ga('send', 'pageview');</script>";
        }
    }

} 