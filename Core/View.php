<?php

namespace Core;

/**
 * View
 *
 * PHP version 7.0
 */
class View
{

    /**
     * Render a view file
     *
     * @param string $view  The view file
     * @param array $args  Associative array of data to display in the view (optional)
     *
     * @return void
     */
    public static function render($view, $args = [])
    {
		echo static::getTemplate($template, $args);
    }

    /**
     * Render a view template using Twig
     *
     * @param string $template  The template file
     * @param array $args  Associative array of data to display in the view (optional)
     *
     * @return void
     */
    public static function getTemplate($template, $args = [])
    {
        static $twig = null;

        if ($twig === null) {
            $loader = new \Twig_Loader_Filesystem(dirname(__DIR__) . '/App/Views');
            $twig = new \Twig_Environment($loader);
            $twig->addGlobal('session', $_SESSION);
            $twig->addGlobal('current_user', \App\Auth::getUser());
            $twig->addGlobal('flash_messages', \App\Flash::getMessages());
			
			$twig->addGlobal('user_income_categories', \App\Auth::getIncomeUserCategories());

			$twig->addGlobal('user_expense_categories', \App\Auth::getExpenseUserCategories());

			$twig->addGlobal('user_payment_methods', \App\Auth::getUserPaymentMethods());
			
			$twig->addGlobal('month_name', \App\Auth::getMonthName());
			
			$twig->addGlobal('last_month_name', \App\Auth::getLastMonthName());
			
			$twig->addGlobal('current_year', \App\Auth::getCurrentYear());
			
			
			$twig->addGlobal('sumOfIncomes', \App\Auth::getSumOfIncomes());
			
			$twig->addGlobal('sumOfIncomesLastMonth', \App\Auth::getSumOfIncomesLastMonth());
			
			$twig->addGlobal('sumOfIncomesCurrentYear', \App\Auth::getSumOfIncomesCurrentYear());
			
			$twig->addGlobal('sumOfIncomesCustomPeriod', \App\Auth::getSumOfIncomesCustomPeriod());
			
			
			$twig->addGlobal('sumOfExpenses', \App\Auth::getSumOfExpenses());
			
			$twig->addGlobal('sumOfExpensesLastMonth', \App\Auth::getSumOfExpensesLastMonth());
			
			$twig->addGlobal('sumOfExpensesCurrentYear', \App\Auth::getSumOfExpensesCurrentYear());
			
			$twig->addGlobal('sumOfExpensesCustomPeriod', \App\Auth::getSumOfExpensesCustomPeriod());
			
			$twig->addGlobal('pieChartIncomeData', \App\Auth::getIncomePieChart());
			
			$twig->addGlobal('pieChartExpenseData', \App\Auth::getExpensePieChart());
			
			$twig = new \Twig_Environment($loader, [
				'debug' => true
			]);
			$twig->addExtension(new \Twig\Extension\DebugExtension());
        }

        return $twig->render($template, $args);
    }
	
	    /**
     * Render a view template using Twig
     *
     * @param string $template  The template file
     * @param array $args  Associative array of data to display in the view (optional)
     *
     * @return void
     */
    public static function renderTemplate($template, $args = [])
    {
        static $twig = null;

        if ($twig === null) {
            $loader = new \Twig_Loader_Filesystem(dirname(__DIR__) . '/App/Views');
            $twig = new \Twig_Environment($loader);
            $twig->addGlobal('session', $_SESSION);
            $twig->addGlobal('current_user', \App\Auth::getUser());
            $twig->addGlobal('flash_messages', \App\Flash::getMessages());
			$twig->addGlobal('user_income_categories', \App\Auth::getIncomeUserCategories());
			$twig->addGlobal('user_expense_categories', \App\Auth::getExpenseUserCategories());
			$twig->addGlobal('user_payment_methods', \App\Auth::getUserPaymentMethods());
			$twig->addGlobal('month_name', \App\Auth::getMonthName());
			$twig->addGlobal('last_month_name', \App\Auth::getLastMonthName());
			$twig->addGlobal('current_year', \App\Auth::getCurrentYear());
			$twig->addGlobal('sumOfIncomes', \App\Auth::getSumOfIncomes());
			$twig->addGlobal('sumOfIncomesLastMonth', \App\Auth::getSumOfIncomesLastMonth());
			$twig->addGlobal('sumOfIncomesCurrentYear', \App\Auth::getSumOfIncomesCurrentYear());
			$twig->addGlobal('sumOfIncomesCustomPeriod', \App\Auth::getSumOfIncomesCustomPeriod());
			$twig->addGlobal('sumOfExpenses', \App\Auth::getSumOfExpenses());
			$twig->addGlobal('sumOfExpensesLastMonth', \App\Auth::getSumOfExpensesLastMonth());
			$twig->addGlobal('sumOfExpensesCurrentYear', \App\Auth::getSumOfExpensesCurrentYear());
			$twig->addGlobal('sumOfExpensesCustomPeriod', \App\Auth::getSumOfExpensesCustomPeriod());
			$twig->addGlobal('pieChartIncomeData', \App\Auth::getIncomePieChart());
			$twig->addGlobal('pieChartExpenseData', \App\Auth::getExpensePieChart());

        }

        echo $twig->render($template, $args);
    }

}
