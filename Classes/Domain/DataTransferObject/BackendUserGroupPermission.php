<?php
namespace KoninklijkeCollective\MyUserManagement\Domain\DataTransferObject;

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * DTO: Permission access Backend User Groups
 *
 * @package KoninklijkeCollective\MyUserManagement\Domain\Model\DataTransferObject
 */
class BackendUserGroupPermission implements \ArrayAccess, \Countable
{

    /**
     * @var string
     */
    const KEY = 'my_user_management_group_permissions';

    /**
     * @var array
     */
    protected $data;

    /**
     * Constructor: BackendUserGroupPermission
     */
    public function __construct()
    {
        $this->initializeData();
    }

    /**
     * @return array
     */
    protected function initializeData()
    {
        if ($this->data === null) {
            $this->data = array(
                'header' => 'LLL:EXT:my_user_management/Resources/Private/Language/locallang.xlf:backendAccessGroupsPerGroup',
                'items' => array(),
            );
            $groups = \TYPO3\CMS\Backend\Utility\BackendUtility::getRecordsByField('be_groups', 'hide_in_lists', 0);
            foreach ((array) $groups as $group) {
                $this->data['items'][$group['uid']] = array(
                    $group['title'],
                    'EXT:my_user_management/Resources/Public/Icons/table-user-group-backend.svg',
                    $group['description'],
                );
            }
        }
        return $this->data;
    }

    /**
     * Get configured options based on current backend user
     *
     * @return array
     */
    public static function userAllowed()
    {
        $configured = array();
        $backendUser = $GLOBALS['BE_USER'];
        // Only return allowed users for non-admin
        if ($backendUser instanceof \TYPO3\CMS\Core\Authentication\BackendUserAuthentication && $backendUser->isAdmin() === false) {
            $options = $backendUser->groupData['custom_options'];
            foreach (GeneralUtility::trimExplode(',', $options, true) as $value) {
                if (strpos($value, static::KEY) === 0) {
                    $configured[] = (int) substr($value, strlen(static::KEY) + 1);
                }
            }
        }
        return $configured;
    }

    /**
     * Whether a offset exists
     *
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset
     * @return boolean
     */
    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

    /**
     * Offset to retrieve
     *
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return isset($this->data[$offset]) ? $this->data[$offset] : null;
    }

    /**
     * Offset to set
     *
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->data[] = $value;
        } else {
            $this->data[$offset] = $value;
        }
    }

    /**
     * Offset to unset
     *
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }

    /**
     * Count elements of an object
     *
     * @link http://php.net/manual/en/countable.count.php
     * @return integer
     */
    public function count()
    {
        return count($this->data);
    }
}
