<?php

namespace components\Home\Controllers;

use components\Projects\Models\ProjectModel;
use components\Skills\Models\SkillModel;
use components\Cv\Models\CvModel;

class HomeController
{
    public function index()
    {
        $projects = (new ProjectModel())->getAll();
        $skills = (new SkillModel())->getAll();
        $cv = (new CvModel())->get();

        include __DIR__ . '/../Views/home.php';
    }
}
