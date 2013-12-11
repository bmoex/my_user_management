<?php
namespace Serfhos\MyUserManagement\ViewHelpers\Widget\Controller;

/***************************************************************
 * Copyright notice
 *
 * (c) 2013 Sebastiaan de Jonge <office@sebastiaandejonge.com>, SebastiaanDeJonge.com
 *  
 * All rights reserved
 *
 * This script is part of the TYPO3 project. The TYPO3 project is
 * free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * User from group controller
 *
 * @package my_user_management
 * @author Sebastiaan de Jonge <office@sebastiaandejonge.com>, SebastiaanDeJonge.com
 */
class UsersFromGroupController extends \TYPO3\CMS\Fluid\Core\Widget\AbstractWidgetController {

	/**
	 * The backend user repository
	 *
	 * @var \Serfhos\MyUserManagement\Domain\Repository\BackendUserRepository
	 * @inject
	 */
	protected $backendUserRepository;

	/**
	 * Displays all users from the group
	 *
	 * @return void
	 */
	public function indexAction() {
		/* @var $backendUserGroup \Serfhos\MyUserManagement\Domain\Model\BackendUserGroup */
		$backendUserGroup = $this->widgetConfiguration['backendUserGroup'];

		/* @var $demand \TYPO3\CMS\Beuser\Domain\Model\Demand */
		$demand = GeneralUtility::makeInstance('TYPO3\\CMS\\Beuser\\Domain\\Model\\Demand');
		$demand->setBackendUserGroup($backendUserGroup);
		$backendUsers = $this->backendUserRepository->findDemanded($demand);

		$this->view->assign('backendUsers', $backendUsers);
	}

}