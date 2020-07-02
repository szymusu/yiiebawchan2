<?php

namespace frontend\controllers;

use yii\web\Controller;

class TestController extends Controller
{
    /**
     * @param $xd string
     * @return mixed
     */
    public function actionLol($xd = 'eeeeeee')
    {
        return $this->render('lol',
        [
            'xd' => $xd
        ]);
    }
}