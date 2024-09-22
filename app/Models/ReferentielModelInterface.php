<?php

namespace App\Models;

interface ReferentielModelInterface{
    public static function query($params);
    public static function findByuidAndFilter($uid);
}