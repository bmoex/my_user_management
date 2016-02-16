# Module configuration
module.tx_myusermanagement {
    view {
        templateRootPaths {
            10 = EXT:beuser/Resources/Private/Templates
            20 = EXT:my_user_management/Resources/Private/Templates
        }

        partialRootPaths {
            10 = EXT:beuser/Resources/Private/Partials
            20 = EXT:my_user_management/Resources/Private/Partials
        }

        layoutRootPaths {
            10 = EXT:beuser/Resources/Private/Layouts
            20 = EXT:my_user_management/Resources/Private/Layouts
        }
    }

    persistence {
        storagePid = 0
        classes {
            Serfhos\MyUserManagement\Domain\Model\BackendUser < config.tx_extbase.persistence.classes.TYPO3\CMS\Beuser\Domain\Model\BackendUser

            Serfhos\MyUserManagement\Domain\Model\BackendUserGroup < config.tx_extbase.persistence.classes.TYPO3\CMS\Beuser\Domain\Model\BackendUserGroup
            Serfhos\MyUserManagement\Domain\Model\BackendUserGroup {
                mapping {
                    columns {
                        db_mountpoints.mapOnProperty = dbMountPoints
                        hidden.mapOnProperty = isDisabled
                    }
                }
            }

            Serfhos\MyUserManagement\Domain\Model\FileMount < config.tx_extbase.persistence.classes.TYPO3\CMS\Extbase\Domain\Model\FileMount
            Serfhos\MyUserManagement\Domain\Model\FileMount {
                mapping {
                    columns {
                        base.mapOnProperty = storage
                        hidden.mapOnProperty = isDisabled
                    }
                }
            }
        }
    }

    settings {
        // This is a dummy entry. It is used in  Tx_Beuser_Controller_BackendUserController
        // to test that some TypoScript configuration is set.
        // This entry can be removed if extbase setup is made frontend TS independant
        // or if there are other settings set.
        dummy = foo
    }
}

// TYPO3 throws an error if not set on extbase config.
config.tx_extbase.persistence.classes.Serfhos\MyUserManagement\Domain\Model\BackendUserGroup < module.tx_myusermanagement.persistence.classes.Serfhos\MyUserManagement\Domain\Model\BackendUserGroup