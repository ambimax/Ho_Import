<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Catalog
 * @copyright   Copyright (c) 2014 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


/**
 * Catalog product group price backend attribute model
 *
 * @category    Mage
 * @package     Mage_Catalog
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Ho_Import_Model_Resource_Attribute_Backend_Profile
    extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * Initialize connection and define main table
     *
     */
    protected function _construct()
    {
        $this->_init('ho_import/entity', 'entity_id');
    }

    /**
     * @param int $productId
     * @return array
     */
    public function loadProfileData($productId, $typeId)
    {
        $adapter = $this->_getReadAdapter();

        $columns = array(
            'profile'        => 'profile',
            'created_at'     => 'created_at',
            'updated_at'     => 'updated_at',
        );

        $select  = $adapter->select()->reset('columns')
            ->from($this->getMainTable(), $columns)
            ->where('entity_id=?', $productId)
            ->where('entity_type_id=?', $typeId);

        return $adapter->fetchAll($select);
    }


    /**
     * @param int $productId
     * @return int The number of affected rows
     */
    public function deleteProfileData($productId)
    {
        $adapter = $this->_getWriteAdapter();

        $conds   = array(
            $adapter->quoteInto('entity_id = ?', $productId)
        );

//        if (!is_null($websiteId)) {
//            $conds[] = $adapter->quoteInto('website_id = ?', $websiteId);
//        }
//
//        if (!is_null($priceId)) {
//            $conds[] = $adapter->quoteInto($this->getIdFieldName() . ' = ?', $priceId);
//        }

        $where = implode(' AND ', $conds);

        return $adapter->delete($this->getMainTable(), $where);
    }

    /**
     * Save tier price object
     *
     * @param Varien_Object $profileObject
     * @return $this
     */
    public function saveProfileData(Varien_Object $profileObject)
    {
        $adapter = $this->_getWriteAdapter();
        $data    = $this->_prepareDataForTable($profileObject, $this->getMainTable());

        if (!empty($data[$this->getIdFieldName()])) {
            $where = $adapter->quoteInto($this->getIdFieldName() . ' = ?', $data[$this->getIdFieldName()]);
            unset($data[$this->getIdFieldName()]);
            $adapter->update($this->getMainTable(), $data, $where);
        } else {
            $adapter->insert($this->getMainTable(), $data);
        }
        return $this;
    }
}
