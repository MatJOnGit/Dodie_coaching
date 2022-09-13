<?php

require ('./../src/controllers/MemberPanelsController.php');

class NutritionProgramController extends MemberPanelsController {
    private $subMenuPage = 'nutritionProgram';

    public function renderMemberNutritionProgram($twig) {
        echo $twig->render('components/head.html.twig', ['stylePaths' => $this->getMemberPanelsStyles()]);
        echo $twig->render('components/header.html.twig', ['memberPanels' => $this->getMemberPanels(), 'subPanel' => $this->getMemberPanelsSubpanels($this->subMenuPage)]);
        echo $twig->render('member_panels/nutrition-program.html.twig', []);
        echo $twig->render('components/footer.html.twig');
    }
}