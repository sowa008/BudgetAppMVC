<?php

namespace App\Models;

use \App\Models\ShowBalance;
use \App\Models\DateModel;
use \Core\View;
use \App\Auth;
use \App\Controllers\Balance;
use PDO;


class PieChartModel extends \Core\Model
{	
	public static function takeExpensePieChart($userID, $begin, $end)
	{
        $expensePieChart = "SELECT ec.name AS name, SUM(e.amount) AS sum
											FROM expenses AS e, expenses_category_assigned_to_users AS ec
											WHERE e.user_id='$userID' AND ec.user_id='$userID'
											AND e.user_id=ec.user_id
											AND ec.id=e.expense_category_assigned_to_user_id
											AND e.date_of_expense BETWEEN '$begin' AND '$end'
											GROUP BY expense_category_assigned_to_user_id
											ORDER BY SUM(e.amount) DESC";

        $db = static::getDB();
        $stmt = $db->prepare($expensePieChart);
        $stmt->execute();

        $expensePie = array();
        $expenseResult = $stmt->fetchAll(\PDO::FETCH_OBJ);

        $expensesArray = json_decode(json_encode($expenseResult), True);

        foreach ($expensesArray as $expenseChart) {
            array_push($expensePie, array("label"=>$expenseChart['name'], "y"=>$expenseChart['sum']));
        }

        json_encode($expensePie, JSON_NUMERIC_CHECK);

        return $expensesArray;
	}

	public static function takeIncomePieChart($userID, $begin, $end)
	{
        $incomePieChart = "SELECT ic.name AS name, SUM(i.amount) AS sum
											FROM incomes AS i, incomes_category_assigned_to_users AS ic
											WHERE i.user_id='$userID' AND ic.user_id='$userID'
											AND i.user_id=ic.user_id
											AND ic.id=i.income_category_assigned_to_user_id
											AND i.date_of_income BETWEEN '$begin' AND '$end'
											GROUP BY income_category_assigned_to_user_id
											ORDER BY SUM(i.amount) DESC";

        $db = static::getDB();
        $stmt_1 = $db->prepare($incomePieChart);
        $stmt_1->execute();

        $incomePie = array();
        $incomeResult = $stmt_1->fetchAll(\PDO::FETCH_OBJ);

        $incomesArray = json_decode(json_encode($incomeResult), True);

        foreach ($incomesArray as $incomeChart) {
            array_push($incomePie, array("label"=>$incomeChart['name'], "y"=>$incomeChart['sum']));
        }

        json_encode($incomePie, JSON_NUMERIC_CHECK);

      return $incomesArray;
      //return $incomePie;
	}
  
 }