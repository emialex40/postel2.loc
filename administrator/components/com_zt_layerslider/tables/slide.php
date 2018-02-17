<?php
/**
 * @package ZT LayerSlider
 * @author    ZooTemplate.com
 * @copyright(C) 2015 - ZooTemplate.com
 * @license PHP files are GNU/GPL
 **/

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.database.table');

/**
 * Content table
 *
 * @package     Joomla.Platform
 * @subpackage  Table
 * @since       11.1
 */
class ZT_LayersliderTableSlide extends JTable
{
    /**
     * Constructor
     *
     * @param   JDatabase &$db  A database connector object
     *
     * @since   11.1
     */
    public function __construct ( &$db )
    {
        parent::__construct('#__zt_layerslider_slide', 'id', $db);
    }


    protected function _getAssetName ()
    {
        $k = $this->_tbl_key;

        return 'com_zt_layerslider.slide.' . (int)$this->$k;
    }

    public function bind ( $array, $ignore = '' )
    {

        if ( isset($array['attribs']) && is_array($array['attribs']) ) {
            $registry = new JRegistry;
            $registry->loadArray($array['attribs']);
            $array['attribs'] = (string)$registry;
        }


        return parent::bind($array, $ignore);
    }

    /**
     * Method to set the publishing state for a row or list of rows in the database
     * table. The method respects checked out rows by other users and will attempt
     * to checkin rows that it can after adjustments are made.
     *
     * @param   mixed $pks     An optional array of primary key values to update.  If not set the instance property value is used.
     * @param   integer $state   The publishing state. eg. [0 = unpublished, 1 = published]
     * @param   integer $userId  The user id of the user performing the operation.
     *
     * @return  boolean  True on success.
     *
     * @since   11.1
     */
    public function publish ( $pks = null, $state = 1, $userId = 0 )
    {
        // Initialise variables.
        $k = $this->_tbl_key;

        // Sanitize input.
        JArrayHelper::toInteger($pks);
        $userId = (int)$userId;
        $state  = (int)$state;

        // If there are no primary keys set check to see if the instance key is set.
        if ( empty($pks) ) {
            if ( $this->$k ) {
                $pks = array($this->$k);
            } // Nothing to set publishing state on, return false.
            else {
                $this->setError(JText::_('JLIB_DATABASE_ERROR_NO_ROWS_SELECTED'));

                return false;
            }
        }

        // Build the WHERE clause for the primary keys.
        $where = $k . '=' . implode(' OR ' . $k . '=', $pks);


        // Get the JDatabaseQuery object
        $query = $this->_db->getQuery(true);

        // Update the publishing state for rows with the given primary keys.
        $query->update($this->_db->quoteName($this->_tbl));
        $query->set($this->_db->quoteName('state') . ' = ' . (int)$state);
        $query->where('(' . $where . ')');
        $this->_db->setQuery($query);
        $this->_db->execute();

        // Check for a database error.
        if ( $this->_db->getErrorNum() ) {
            $this->setError($this->_db->getErrorMsg());

            return false;
        }


        // If the JTable instance value is in the list of primary keys that were set, set the instance.
        if ( in_array($this->$k, $pks) ) {
            $this->state = $state;
        }

        $this->setError('');

        return true;
    }
}
