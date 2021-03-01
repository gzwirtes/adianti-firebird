<?php
/**
 * ClienteList Listing
 * @author  <your name here>
 */
class ClienteList extends TPage
{
    protected $form;     // registration form
    protected $datagrid; // listing
    protected $pageNavigation;
    protected $formgrid;
    protected $deleteButton;
    
    use Adianti\base\AdiantiStandardListTrait;
    
    /**
     * Page constructor
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->setDatabase('firebird');            // defines the database
        $this->setActiveRecord('Cliente');   // defines the active record
        $this->setDefaultOrder('ID', 'asc');         // defines the default order
        $this->setLimit(10);
        // $this->setCriteria($criteria) // define a standard filter

        $this->addFilterField('ID', 'like', 'ID'); // filterField, operator, formField
        $this->addFilterField('RAZAO_SOCIAL', 'like', 'RAZAO_SOCIAL'); // filterField, operator, formField
        $this->addFilterField('CPF', 'like', 'CPF'); // filterField, operator, formField
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_search_Cliente');
        $this->form->setFormTitle('Cliente');
        

        // create the form fields
        $ID = new TEntry('ID');
        $RAZAO_SOCIAL = new TEntry('RAZAO_SOCIAL');
        $CPF = new TEntry('CPF');


        // add the fields
        $this->form->addFields( [ new TLabel('ID') ], [ $ID ] );
        $this->form->addFields( [ new TLabel('Razão Social') ], [ $RAZAO_SOCIAL ] );
        $this->form->addFields( [ new TLabel('CPF') ], [ $CPF ] );


        // set sizes
        $ID->setSize('100%');
        $RAZAO_SOCIAL->setSize('100%');
        $CPF->setSize('100%');

        
        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data') );
        
        // add the search form actions
        $btn = $this->form->addAction(_t('Find'), new TAction([$this, 'onSearch']), 'fa:search');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addActionLink(_t('New'), new TAction(['ClienteForm', 'onEdit']), 'fa:plus green');
        
        // creates a Datagrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->datatable = 'true';
        // $this->datagrid->enablePopover('Popover', 'Hi <b> {name} </b>');
        

        // creates the datagrid columns
        $column_ID = new TDataGridColumn('ID', 'ID', 'center');
        $column_RAZAO_SOCIAL = new TDataGridColumn('RAZAO_SOCIAL', 'Razão Social', 'left');
        $column_CPF = new TDataGridColumn('CPF', 'CPF', 'center');


        // add the columns to the DataGrid
        $this->datagrid->addColumn($column_ID);
        $this->datagrid->addColumn($column_RAZAO_SOCIAL);
        $this->datagrid->addColumn($column_CPF);


        // creates the datagrid column actions
        $column_ID->setAction(new TAction([$this, 'onReload']), ['order' => 'ID']);
        $column_RAZAO_SOCIAL->setAction(new TAction([$this, 'onReload']), ['order' => 'RAZAO_SOCIAL']);
        $column_CPF->setAction(new TAction([$this, 'onReload']), ['order' => 'CPF']);

        
        $action1 = new TDataGridAction(['ClienteForm', 'onEdit'], ['ID'=>'{ID}']);
        $action2 = new TDataGridAction([$this, 'onDelete'], ['ID'=>'{ID}']);
        
        $this->datagrid->addAction($action1, _t('Edit'),   'far:edit blue');
        $this->datagrid->addAction($action2 ,_t('Delete'), 'far:trash-alt red');
        
        // create the datagrid model
        $this->datagrid->createModel();
        
        // creates the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction(new TAction([$this, 'onReload']));
        
        $panel = new TPanelGroup('', 'white');
        $panel->add($this->datagrid);
        $panel->addFooter($this->pageNavigation);
        
        // header actions
        $dropdown = new TDropDown(_t('Export'), 'fa:list');
        $dropdown->setPullSide('right');
        $dropdown->setButtonClass('btn btn-default waves-effect dropdown-toggle');
        $dropdown->addAction( _t('Save as CSV'), new TAction([$this, 'onExportCSV'], ['register_state' => 'false', 'static'=>'1']), 'fa:table blue' );
        $dropdown->addAction( _t('Save as PDF'), new TAction([$this, 'onExportPDF'], ['register_state' => 'false', 'static'=>'1']), 'far:file-pdf red' );
        $panel->addHeaderWidget( $dropdown );
        
        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($this->form);
        $container->add($panel);
        
        parent::add($container);
    }
}
