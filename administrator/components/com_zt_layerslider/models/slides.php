<?php
/**
 * @package ZT LayerSlider
 * @author    ZooTemplate.com
 * @copyright(C) 2015 - ZooTemplate.com
 * @license PHP files are GNU/GPL
 **/
// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

class ZT_LayersliderModelSlides extends JModelList
{
    protected $context = 'com_zt_layerslider';
    /**
     * Constructor.
     *
     * @param	array	An optional associative array of configuration settings.
     * @see		JController
     * @since	1.6
     */
    public function __construct($config = array())
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'id', 'a.id',
                'state', 'a.state',
                'access', 'a.access',
                'language', 'a.language',
            );
        }

        parent::__construct($config);
    }

    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     *
     * @return	void
     * @since	1.6
     */
    protected function populateState($ordering = null, $direction = null)
    {
        // Initialise variables.
        $app = JFactory::getApplication();

        // Adjust the context to support modal layouts.
        if ($layout = $app->input->get('layout')) {
            $this->context .= '.'.$layout;
        }

        $this->setState('filter.slider', $app->input->getInt('slider_id', 0));

        $search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
        $this->setState('filter.search', $search);

        $access = $this->getUserStateFromRequest($this->context.'.filter.access', 'filter_access', 0, 'int');
        $this->setState('filter.access', $access);

        $published = $this->getUserStateFromRequest($this->context.'.filter.published', 'filter_published', '');
        $this->setState('filter.published', $published);

        $language = $this->getUserStateFromRequest($this->context.'.filter.language', 'filter_language', '');
        $this->setState('filter.language', $language);

        // List state information.
        parent::populateState('a.ordering', 'asc');
    }

    /**
     * Method to get a store id based on model configuration state.
     *
     * This is necessary because the model is used by the component and
     * different modules that might need different sets of data or different
     * ordering requirements.
     *
     * @param	string		$id	A prefix for the store id.
     *
     * @return	string		A store id.
     * @since	1.6
     */
    protected function getStoreId($id = '')
    {
        // Compile the store id.
        $id	.= ':'.$this->getState('filter.search');
        $id	.= ':'.$this->getState('filter.access');
        $id	.= ':'.$this->getState('filter.published');
        $id	.= ':'.$this->getState('filter.language');

        return parent::getStoreId($id);
    }

    /**
     * Build an SQL query to load the list data.
     *
     * @return	JDatabaseQuery
     * @since	1.6
     */
    protected function getListQuery()
    {
        // Create a new query object.
        $db		= $this->getDbo();
        $query	= $db->getQuery(true);
        $user	= JFactory::getUser();

        // Select the required fields from the table.
        $query->select(
            $this->getState(
                'list.select',
                'a.*'
            )
        );
        $query->from('#__zt_layerslider_slide AS a');

        // Join over the language
        $query->select('l.title AS language_title');
        $query->join('LEFT', $db->quoteName('#__languages').' AS l ON l.lang_code = a.language');


        // Join over the asset groups.
        $query->select('ag.title AS access_level');
        $query->join('LEFT', '#__viewlevels AS ag ON ag.id = a.access');


        // Filter by access level.
        if ($access = $this->getState('filter.access')) {
            $query->where('a.access = ' . (int) $access);
        }

        // Implement View Level Access
        if (!$user->authorise('core.admin'))
        {
            $groups	= implode(',', $user->getAuthorisedViewLevels());
            $query->where('a.access IN ('.$groups.')');
        }

        // Filter by published state
        $published = $this->getState('filter.published');
        if (is_numeric($published)) {
            $query->where('a.state = ' . (int) $published);
        }
        elseif ($published === '') {
            $query->where('(a.state = 0 OR a.state = 1)');
        }

        // Filter by search in title.
        $search = $this->getState('filter.search');
        if (!empty($search)) {
            if (stripos($search, 'id:') === 0) {
                $query->where('a.id = '.(int) substr($search, 3));
            }
        }

        // Filter on the language.
        if ($language = $this->getState('filter.language')) {
            $query->where('a.language = '.$db->quote($language));
        }

        // Filter on slider
        if ($this->getState('filter.slider')) {
            $query->where('slider_id = ' . (int) $this->getState('filter.slider'));
        }

        // Add the list ordering clause.
        $orderCol	= $this->state->get('list.ordering', 'a.ordering');
        $orderDirn	= $this->state->get('list.direction', 'asc');

        //sqlsrv change
        if($orderCol == 'language')
            $orderCol = 'l.title';
        if($orderCol == 'access_level')
            $orderCol = 'ag.title';
        $query->order($db->escape($orderCol.' '.$orderDirn));

        return $query;
    }
}