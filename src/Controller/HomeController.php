<?php

namespace App\Controller;

use App\application\Application\Service\Calculation\AbstractCalculationService;
use App\application\Application\Service\Calculation\Exceptions\UnknowInstallmentType;
use application\Domain\Calculation\Calculation;
use application\Domain\Calculation\CalculationResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("home", name="home")
     */
    public function index(): Response
    {
        return $this->render('home.html.twig');
    }

    /**
     * @Route ("home/calculate", name="calculate")
     */
    public function calculate()
    {
        $postData = json_decode(file_get_contents("php://input"));
        $calculation = new Calculation($postData->value, $postData->years, $postData->percent, $postData->paymentsInYear);
        $page = $postData->page;
        $limit = 12;
        try {
            $calculateService = AbstractCalculationService::getService($postData->installmentType);
        }
        catch (UnknowInstallmentType $e) {
            return new Response(json_encode(['error' => 'ERR-CP1. Unknown Installment Type.']));
        }

        $calculationResponse = $calculateService->execute($calculation);
    //    dd($calculationResponse);
       $calculationResponse->setPaymentSchedule(array_filter($calculationResponse->getPaymentSchedule(),
            function ($installment) use ($page, $limit) {
            return $installment['index'] <= $page * $limit && $installment['index'] > (($page - 1) * $limit);
        }));

        if (!$calculationResponse instanceof CalculationResponse) {
            return new Response (json_encode(['error' => 'Brak wyliczenia kalkulacji']));
        }
        return new Response (json_encode($calculationResponse->toArray()));
    }

}