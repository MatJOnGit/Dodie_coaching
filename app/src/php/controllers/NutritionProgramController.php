<?php

require ('app/src/php/controllers/MemberPanelsController.php');

class NutritionProgramController extends MemberPanelsController {
    public function renderMemberNutritionProgram($twig) {
        $subMenuPage = 'nutritionProgram';

        echo $twig->render('components/head.html.twig', ['stylePaths' => $this->memberPanelPagesStyles]);
        echo $twig->render('components/header.html.twig', ['memberPanels' => $this->getmemberPanels(), 'subPanel' => $this->getMemberPanelsSubpanels($subMenuPage)]);
        echo $twig->render('member_panels/nutrition-program.html.twig', []);
        echo $twig->render('components/footer.html.twig');
    }
}