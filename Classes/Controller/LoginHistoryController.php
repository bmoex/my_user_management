<?php
namespace KoninklijkeCollective\MyUserManagement\Controller;

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Messaging\AbstractMessage;
use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

/**
 * Controller: LoginHistory
 *
 * @package KoninklijkeCollective\MyUserManagement\Controller
 */
class LoginHistoryController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * @var \KoninklijkeCollective\MyUserManagement\Service\LogService
     */
    protected $logService;

    /**
     * @var \KoninklijkeCollective\MyUserManagement\Service\AccessService
     */
    protected $accessService;

    /**
     * Initialize generic variables
     *
     * @param ViewInterface $view
     * @return void
     */
    public function initializeView(ViewInterface $view)
    {
        parent::initializeView($view);
        $listUrl = BackendUtility::getModuleUrl('myusermanagement_MyUserManagementLoginhistory');
        $view->assignMultiple(array(
            'returnUrl' => rawurlencode($listUrl),
            'dateFormat' => $GLOBALS['TYPO3_CONF_VARS']['SYS']['ddmmyy'],
            'timeFormat' => $GLOBALS['TYPO3_CONF_VARS']['SYS']['hhmm'],
        ));
    }

    /**
     * Action: List all users
     *
     * @param integer $page
     * @return void
     */
    public function indexAction($page = 1)
    {
        $parameters = array(
            'page' => $page,
            'itemsPerPage' => 20,
            'hide-admin' => ($this->getBackendUserAuthentication()->isAdmin() === false),
        );
        $logs = $this->getLogService()->findUserLoginActions($parameters);
        if (empty($logs['items'])) {
            $this->addFlashMessage(
                $this->translate('empty_description'),
                $this->translate('empty_title'),
                AbstractMessage::INFO
            );
        }

        $this->view->assignMultiple(array(
            'backendUsers' => $this->getAccessService()->findAllBackendUsers(),
            'inactiveUsers' => $this->getAccessService()->findAllInactiveBackendUsers(),
            'userModuleAccess' => $this->beUserHasRightToSeeModule('myusermanagement_MyUserManagementUseradmin'),
            'logs' => $logs,
        ));
    }

    /**
     * Action: Get login overview
     *
     * @param integer $user
     * @return void
     */
    public function detailAction($user = null)
    {
        if ($user === null) {
            $this->redirect('index');
        }

        $user = $this->getAccessService()->findBackendUser($user);
        if ($user instanceof \KoninklijkeCollective\MyUserManagement\Domain\Model\BackendUser) {
            $parameters = array(
                'user' => $user->getUid(),
            );
            $logs = $this->getLogService()->findUserLoginActions($parameters);

            $this->view->assignMultiple(array(
                'user' => $user,
                'logs' => $logs,
            ));
        } else {
            $this->redirect('index');
        }
    }

    /**
     * Translate label for module
     *
     * @param string $key
     * @param array $arguments
     * @return string
     */
    protected function translate($key, $arguments = array())
    {
        $label = null;
        if (!empty($key)) {
            $label = LocalizationUtility::translate(
                'backendLoginHistory_' . $key,
                'my_user_management',
                $arguments
            );
        }
        return ($label) ? $label : $key;
    }

    /**
     * @return \KoninklijkeCollective\MyUserManagement\Service\LogService
     */
    protected function getLogService()
    {
        if ($this->logService === null) {
            $this->logService = $this->objectManager->get('KoninklijkeCollective\MyUserManagement\Service\LogService');
        }
        return $this->logService;
    }

    /**
     * @return \KoninklijkeCollective\MyUserManagement\Service\AccessService
     */
    protected function getAccessService()
    {
        if ($this->accessService === null) {
            $this->accessService = $this->objectManager->get('KoninklijkeCollective\MyUserManagement\Service\AccessService');
        }
        return $this->accessService;
    }

    /**
     * @return \TYPO3\CMS\Core\Authentication\BackendUserAuthentication
     */
    protected function getBackendUserAuthentication()
    {
        return $GLOBALS['BE_USER'];
    }

    /**
     * Check if user has access to module
     *
     * @param string $moduleName
     * @return boolean
     */
    protected function beUserHasRightToSeeModule($moduleName = 'myusermanagement_module')
    {
        $hasAccess = false;
        if (BackendUtility::isModuleSetInTBE_MODULES($moduleName)) {
            $hasAccess = $this->getBackendUserAuthentication()->check('modules', $moduleName) && $this->getBackendUserAuthentication()->check('tables_modify', \KoninklijkeCollective\MyUserManagement\Domain\Repository\BackendUserRepository::TABLE);
        }

        return $hasAccess;
    }

}
