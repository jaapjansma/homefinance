<?php

namespace HomefinanceBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Translation\TranslatorInterface;

class MenuBuilder
{
    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @param FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory, TranslatorInterface $translator)
    {
        $this->factory = $factory;
        $this->translator = $translator;
    }

    public function createActionMenu(RequestStack $requestStack) {
        $menu = $this->factory->createItem('action');
        $menu->addChild('action_menu.all_transactions', array('route' => 'list_all_transactions'));
        $menu->addChild('action_menu.bank_accounts', array('route' => 'bank_accounts'));
        $menu->addChild('action_menu.categories', array('route' => 'categories'));
        $menu->addChild('action_menu.tags', array('route' => 'tags'));
        $menu->addChild('action_menu.rules', array('route' => 'rules'));
        return $menu;
    }

    public function createProfileMenu(RequestStack $requestStack) {
        $menu = $this->factory->createItem('profile');
        $menu->addChild('profile_menu.edit_profile', array('route' => 'profile'));
        $menu->addChild('profile_menu.change_password', array('route' => 'profile_change_password'));
        $menu->addChild('profile_menu.manage_administrations', array('route' => 'manage_administrations'));
        return $menu;
    }
}