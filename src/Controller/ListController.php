<?php
namespace Budget\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Budget\Entity\Budget;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;

class ListController extends AbstractActionController 
{
  /**
     * Budget manager.
     * @var Budget\Service\BudgetManager 
     */
    private $budgetManager;
  
 // Constructor is used for injecting dependencies into the controller.
    public function __construct($entityManager, $budgetManager) 
    {
        $this->entityManager = $entityManager;
        $this->budgetManager = $budgetManager;
    }
    
    public function indexAction() 
    {
        $page = $this->params()->fromQuery('page', 1);
        $monthFilter = $this->params()->fromQuery('month', null);
        $unitFilter = $this->params()->fromQuery('unit', null);
        
        if ($monthFilter) {
         
            // Filter budgets by month
            $query = $this->entityManager->getRepository(Budget::class)
                    ->findBudgetsByMonth($monthFilter);
            
        } 
        elseif ($unitFilter) {
         
            // Filter budgets by month
            $query = $this->entityManager->getRepository(Budget::class)
                    ->findBudgetsByUnit($unitFilter);
            
        }
        else {
            // Get recent budgets
            $query = $this->entityManager->getRepository(Budget::class)
                    ->findPublishedBudgets();
        }
        
        $adapter = new DoctrineAdapter(new ORMPaginator($query, false));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(10);        
        $paginator->setCurrentPageNumber($page);
                       
        // Get months.
        $monthCloud = $this->budgetManager->getMonthCloud();
                       
        // Get unit.
        $unitCloud = $this->budgetManager->getUnitCloud();
        
        // Render the view template.
        return new ViewModel([
            'budgets' => $paginator,
            'budgetManager' => $this->budgetManager,
            'monthCloud' => $monthCloud,
            'unitCloud' => $unitCloud
        ]);
    }
}
