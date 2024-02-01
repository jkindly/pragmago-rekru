<?php

declare(strict_types=1);

include 'vendor/autoload.php';

use PragmaGoTech\Interview\Service\FeeCalculator;
use PragmaGoTech\Interview\Model\LoanProposal;

$calculator = new FeeCalculator();

$proposal1 = new LoanProposal(24, 11500);
$fee1 = $calculator->calculate($proposal1);
echo "Fee for 11500 PLN loan for 24 months: " . $fee1 . " PLN\n";

$proposal2 = new LoanProposal(12, 19250);
$fee2 = $calculator->calculate($proposal2);
echo "Fee for 19250 PLN loan for 12 months: " . $fee2 . " PLN\n";
